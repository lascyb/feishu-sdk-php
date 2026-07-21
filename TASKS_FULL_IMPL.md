# 全量迁移计划 — 一次性实现剩余功能

目标：将 lascyb/oapi-sdk-go 的所有功能（认证/令牌管理、Channel 长连接、事件归一化、卡片与流式更新、scene/registration、一键注册流程、以及 service/* 下的所有 OpenAPI 接口封装）完整移植并实现到 `lascyb/feishu-sdk-php` 仓库，交付为单个 PR（分多个有意义的提交）。

注意：此为工作执行计划与任务清单，提交分支：`port/oapi-sdk-go-to-php`。

范围（来自 go 仓库的模块映射）

- core: Config, TokenManager（已实现），扩展支持 client_assertion、app_ticket、multi-tenant 模式
- channel: 完整的 Channel 功能
  - WebSocket 长连接（自动鉴权、心跳、重连、指数退避）
  - 事件归一化（message/card/interaction/system/stream 等）
  - 发送 API 封装（消息发送/更新/撤回、卡片流式更新）
  - 策略/过滤/拦截（policy）
- scene/registration: 设备授权 / 一键注册流程（二维码、轮询、回调）
- service/*: 按 go 列表全部实现（优先常用：im, contact, drive, sheets, calendar, mail, tenant, passport, v c, wiki, approval, attendance, meeting_room, event, translation 等）
  - 每个 service 封装 Request/Response 类（PSR-4 命名空间： feishu\open_apis\<service>\v1\... ）
  - AnyRequest 已存在，用于未封装接口
- event: 事件相关类型、签名校验、Webhook 处理（WebhookBot 已存在）
- card: 卡片构建/序列化辅助
- sample 与 tests: 按功能提供示例与 PHPUnit 测试
- docs: 中文文档（USAGE, CHANNEL, SERVICE 使用示例, MIGRATION）

交付形式

- 单一 PR（分多个 commit），pr 标题："feat: full port of oapi-sdk-go — complete feature set"
- 每个子模块独立的 commit：core、channel、scene/registration、service/*、docs、tests

时间估算（单人，粗略）

- 分析与详细设计：0.5 天
- Core 深化（client_assertion 等）：0.5 天
- Channel 完整实现：6–10 天
- scene/registration：1–2 天
- service/*（按数量与复杂度并行实现，但整体估计）：10–20 天
- 测试、文档、修正：3–5 天

总计估算：21–38 天（视 API 数量与细节而定）。

交付里程碑（在同分支连续提交）

1. commit: core 完成（client_assertion、app_ticket）
2. commit: channel 基础 + 心跳/重连/简单归一化
3. commit: channel 流式卡片与交互
4. commit: scene/registration 完整实现
5. commits: 每个 service 的实现（分批提交）
6. commit: tests & samples
7. commit: docs 中文化
8. Open PR，附迁移指南与 breaking-change 列表

风险与注意事项

- 这是大规模移植，会产生 breaking change（已在前面阶段移除了 Auth）。
- Channel 的稳定性需要线下长期跑验（长连接、重连策略）。
- Redis clear() 的 KEYS 使用需在 docs 强烈警告并提供替代策略。

现在开始执行：
- 我将按上面计划一次性实现全部功能，结果提交到 `port/oapi-sdk-go-to-php` 分支并创建 PR（包含详细变更说明与迁移文档）。

