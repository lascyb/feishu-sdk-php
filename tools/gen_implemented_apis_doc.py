#!/usr/bin/env python3
"""Generate docs/implemented-apis.md from src/open_apis/**/*Request.php."""

import re
from collections import defaultdict
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
OPEN_APIS = ROOT / "src" / "open_apis"
OUT = ROOT / "docs" / "implemented-apis.md"

MODULE_NAMES = {
    "approval": "审批 (approval)",
    "authen": "身份验证 (authen)",
    "contact": "通讯录 (contact)",
    "drive": "云文档 (drive)",
    "im": "即时消息 (im)",
    "sheets": "电子表格 (sheets)",
}

MODULE_ORDER = ["authen", "contact", "drive", "im", "approval", "sheets"]


def parse_request_file(path: Path) -> dict:
    text = path.read_text(encoding="utf-8")
    rel = path.relative_to(OPEN_APIS)
    module = rel.parts[0] if rel.parts else "other"

    ns_match = re.search(r"namespace\s+([\w\\]+);", text)
    class_match = re.search(r"class\s+(\w+Request)\s", text)
    class_name = class_match.group(1) if class_match else path.stem
    fqcn = f"{ns_match.group(1)}\\{class_name}" if ns_match and class_match else class_name

    title = doc_url = api_line = None
    doc_match = re.search(r"/\*\*(.*?)\*/", text, re.DOTALL)
    if doc_match:
        for line in doc_match.group(1).split("\n"):
            line = line.strip().lstrip("*").strip()
            if not line:
                continue
            if line.startswith("文档：") or line.startswith("文档:"):
                doc_url = re.split(r"[:：]", line, maxsplit=1)[1].strip()
            elif line.startswith("API：") or line.startswith("API:"):
                api_line = re.split(r"[:：]", line, maxsplit=1)[1].strip()
            elif title is None and not line.startswith("@"):
                m = re.match(r"\w+Request\s+(.+)", line)
                if m:
                    title = m.group(1).strip()

    method = None
    m = re.search(
        r"function\s+getMethod\s*\(\)\s*\{[^}]*return\s+['\"](\w+)['\"]",
        text,
        re.DOTALL,
    )
    if m:
        method = m.group(1).upper()

    path_value = None
    m = re.search(r"function\s+getPath\s*\(\)\s*\{(.*?)\n\s*\}", text, re.DOTALL)
    if m:
        body = m.group(1)
        ret = re.search(r"return\s+(.+?);", body, re.DOTALL)
        if ret:
            expr = ret.group(1).strip()
            sm = re.match(r"['\"]([^'\"]+)['\"]", expr)
            if sm:
                path_value = sm.group(1)
            else:
                sm = re.search(r"sprintf\s*\(\s*['\"]([^'\"]+)['\"]", expr)
                if sm:
                    path_value = sm.group(1)

    if api_line:
        if not method:
            method = api_line.split()[0].upper()
        # docblock API 行含完整路径模板，优先于 getPath 拼接结果
        api_path = " ".join(api_line.split()[1:])
        api_path = re.sub(r"^/open-apis", "", api_path)
        path_value = api_path

    return {
        "module": module,
        "class": class_name,
        "fqcn": fqcn,
        "title": title or "-",
        "method": method or "-",
        "path": path_value or "-",
        "doc_url": doc_url,
        "file": str(rel).replace("\\", "/"),
    }


def main() -> int:
    files = sorted(OPEN_APIS.rglob("*Request.php"))
    apis = [parse_request_file(f) for f in files]

    by_module: dict[str, list] = defaultdict(list)
    for api in apis:
        by_module[api["module"]].append(api)

    lines = [
        "# 已实现的 OpenAPI 接口",
        "",
        "本文档汇总 `src/open_apis` 下已封装的 Request 类，便于查阅与选用。",
        "",
        f"**共计 {len(apis)} 个接口**",
        "",
        "## 使用方式",
        "",
        "```php",
        "use feishu\\Client;",
        "use feishu\\open_apis\\im\\v1\\messages\\MessagesCreateRequest;",
        "",
        "$client = new Client($appId, $appSecret);",
        "$response = $client->request(new MessagesCreateRequest(/* ... */));",
        "```",
        "",
        "未列出的接口可使用 `AnyRequest` 直接调用，参见 [README](../README.md)。",
        "",
        "## 目录",
        "",
    ]

    for mod in MODULE_ORDER:
        if mod in by_module:
            label = MODULE_NAMES.get(mod, mod)
            anchor = mod
            lines.append(f"- [{label}](#{anchor})")

    for mod in sorted(by_module.keys()):
        if mod not in MODULE_ORDER:
            lines.append(f"- [{mod}](#{mod})")

    lines.append("")

    ordered_modules = [m for m in MODULE_ORDER if m in by_module]
    ordered_modules += sorted(m for m in by_module if m not in MODULE_ORDER)

    for mod in ordered_modules:
        items = sorted(by_module[mod], key=lambda x: (x["path"], x["class"]))
        label = MODULE_NAMES.get(mod, mod)
        lines.extend([f'<a id="{mod}"></a>', "", f"## {label}", ""])
        lines.append("| 类名 | 说明 | 方法 | 路径 | 文档 |")
        lines.append("|------|------|------|------|------|")
        for api in items:
            doc_cell = f"[链接]({api['doc_url']})" if api["doc_url"] else "-"
            lines.append(
                f"| `{api['class']}` | {api['title']} | {api['method']} "
                f"| `{api['path']}` | {doc_cell} |"
            )
        lines.append("")
        lines.append(f"命名空间前缀：`feishu\\open_apis\\{mod}`")
        lines.append("")
        lines.append("<details>")
        lines.append("<summary>完整类名与源文件</summary>")
        lines.append("")
        lines.append("| 完整类名 | 源文件 |")
        lines.append("|----------|--------|")
        for api in items:
            lines.append(f"| `{api['fqcn']}` | `{api['file']}` |")
        lines.append("")
        lines.append("</details>")
        lines.append("")

    OUT.parent.mkdir(exist_ok=True)
    OUT.write_text("\n".join(lines).rstrip() + "\n", encoding="utf-8")
    print(f"Written {len(apis)} APIs to {OUT}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
