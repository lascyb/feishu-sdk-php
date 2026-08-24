---
name: feishu-sdk-release
description: >-
  feishu-sdk-php 发版流程：版本号 bump、release commit、annotated tag、推送与 Packagist 验证。
  在用户说发版、release、bump version、publish、打 tag、推送 Packagist 时使用。
---

# feishu-sdk-php 发版

## 目标

将 `lascyb/feishu-sdk-php` 的新变更发布为 Git 标签；Packagist 通过 GitHub Webhook **自动**同步新 tag，无需手动上传。

**仓库根目录**：本 skill 所在仓库（`composer.json` 含 `"name": "lascyb/feishu-sdk-php"`）。

## 何时使用

- 用户明确要求 **发版 / release /  bump version / publish**
- 已合并或完成一批 Ready 变更（新 Request 类、README、bugfix），需要打 tag 对外发布

未获用户确认前，**不要**执行 `git push` 或 `git push --tags`。

## 发版前检查

复制 checklist 逐项确认：

```
发版检查：
- [ ] 待发布代码已在 main（或用户指定分支），working tree clean
- [ ] 新增 `src/open_apis/**` Request 类符合 [feishu-openapi-request](../feishu-openapi-request/SKILL.md) 规范
- [ ] `README.md` 已补充新接口调用示例（若有对外新能力）
- [ ] 已确定 semver 级别（见下表）
- [ ] `composer.json` 的 `"version"` 已 bump 且与 tag 一致（不含 `v` 前缀）
- [ ] 未混入无关文件或密钥
```

### Semver 指引（本 SDK）

| 级别 | 何时 bump | 示例 |
|------|-----------|------|
| **patch** `x.y.Z` | 小修复、文档/注释、仅调版本号、无新公开 API | 1.1.1 → 1.1.2 |
| **minor** `x.Y.0` | 新增 Request 类、Webhook/OAuth/Client 新能力，向后兼容 | 1.1.2 → 1.2.0 |
| **major** `X.0.0` | 破坏性变更：PHP 最低版本、namespace、删除/重命名公开类或方法 | 1.x → 2.0.0 |

默认：**新增 OpenAPI Request 类 → minor**；纯 bugfix → patch。

## 发版步骤

### 1. 确认当前状态

```bash
git status
git log -3 --oneline
git tag -l 'v*'
```

读取 `composer.json` 中当前 `"version"`，对照最新 tag 决定新版本号。

若 **commit 与 tag 已存在**（例如已有 `release: v1.2.0` 与 `v1.2.0`），跳过步骤 2–3，直接进入步骤 4（推送需用户确认）。

### 2. Bump 版本号

编辑 `composer.json`：

```json
"version": "1.2.0"
```

版本字符串 **不带 `v`**；Git tag **带 `v` 前缀**（`v1.2.0`）。

### 3. Commit 与 annotated tag

暂存本次发版相关文件（至少含 `composer.json`；通常含新 Request、README、skill 等）：

```bash
git add composer.json README.md src/open_apis/ ...
git commit -m "$(cat <<'EOF'
release: v1.2.0 — 简短中文摘要

- 变更点 1
- 变更点 2
EOF
)"
```

**Commit 规范**

- 首行：`release: v{x.y.z} — {一句话摘要}`
- 正文：bullet 列出主要变更
- **禁止** `Co-authored-by: Cursor` 或任何 Agent trailer

创建 **annotated tag**（与本仓库历史一致）：

```bash
git tag -a v1.2.0 -m "Release v1.2.0"
```

验证：

```bash
git show v1.2.0 --no-patch
git cat-file -t v1.2.0   # 应为 tag（annotated）
```

### 4. 推送（须用户确认）

**仅在用户明确同意推送后**执行：

```bash
git push
git push --tags
```

或合并为：`git push && git push --tags`

### 5. Packagist

- 包名：`lascyb/feishu-sdk-php`
- 页面：https://packagist.org/packages/lascyb/feishu-sdk-php
- 已配置 **Auto-update**：推送 GitHub tag 后 Packagist 通常在数分钟内出现新版本
- 若长时间未更新：在 Packagist 包页点击 **Update**，或检查 GitHub → Packagist Webhook

### 6. 发版后验证

- [ ] GitHub Releases / Tags 页可见 `v{x.y.z}`，指向正确 commit
- [ ] Packagist 出现 `{x.y.z}` 且 `composer require lascyb/feishu-sdk-php` 可解析

## 常见情况

| 情况 | 处理 |
|------|------|
| 已有 release commit，缺 tag | 仅 `git tag -a v{x.y.z} -m "Release v{x.y.z}"`，再 push tags |
| 已有 tag，未 push | 告知用户本地状态，等确认后 `git push && git push --tags` |
| 发版含多个 feature commit | 可 squash 或单 release commit；保持 `composer.json` version 与 tag 一致 |
| 误 bump 版本 | 未 push 前可 amend/re-tag；**已 push 勿 force**，改 bump 新版本 |

## 参考

- 最近一次发版 commit 示例：`release: v1.2.0 — contact v3 部门与用户请求类`
- Tag 示例：`v1.2.0`，message `Release v1.2.0`
- 更多命令与场景见 [examples.md](examples.md)
