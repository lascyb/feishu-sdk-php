# 发版示例

## 示例 1：minor 发版（新增 Request 类）

**背景**：新增 contact v3 两个 Request，README 已补示例。

| 项 | 值 |
|----|-----|
| 上一 tag | `v1.1.2`（composer `1.1.2`） |
| 新版本 | `1.2.0` / tag `v1.2.0` |
| 级别 | minor（新公开 API） |

```bash
# 1. bump composer.json → "version": "1.2.0"

git add composer.json README.md src/open_apis/contact/v3/ ...
git commit -m "$(cat <<'EOF'
release: v1.2.0 — contact v3 部门与用户请求类

新增 ChildrenListRequest、FindByDepartmentGetRequest；补充 README 示例。
EOF
)"

git tag -a v1.2.0 -m "Release v1.2.0"

# 用户确认后再推送
git push && git push --tags
```

---

## 示例 2：patch 发版（小修复）

**背景**：修复某 Request 的 query 参数拼写，无新类。

| 项 | 值 |
|----|-----|
| 上一 tag | `v1.2.0` |
| 新版本 | `1.2.1` / tag `v1.2.1` |
| 级别 | patch |

```bash
git commit -m "$(cat <<'EOF'
release: v1.2.1 — 修复 MessagesCreateRequest query 参数

修正 receive_id_type 默认值与文档不一致的问题。
EOF
)"

git tag -a v1.2.1 -m "Release v1.2.1"
```

---

## 示例 3：release 已完成，仅待推送

**背景**：subagent 或本地已 commit + tag，branch ahead of origin。

```bash
git status          # ahead by 1 commit, working tree clean
git log -1 --oneline # release: v1.2.0 — ...
git tag -l 'v1.2*'   # v1.2.0

# 向用户汇报：本地 v1.2.0 已就绪，是否 push？
# 用户同意后：
git push && git push --tags
```

---

## 示例 4：发版后验证

```bash
# 本地
git rev-parse v1.2.0
git show v1.2.0:composer.json | grep '"version"'

# 远程（push 后）
git ls-remote --tags origin v1.2.0
```

Packagist：https://packagist.org/packages/lascyb/feishu-sdk-php — 确认最新版本号与 tag 一致。
