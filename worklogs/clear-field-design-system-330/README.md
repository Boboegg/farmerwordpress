# 清朗田野 3.3.0 LocalWP 瀏覽器驗證

- 唯一規格：`/Users/boboegg/bobohome-agent-claude/300_Areas/尊嚴農業/網站現行設計規範-清朗田野-2026-07-13.md`
- 執行：`node worklogs/clear-field-design-system-330/audit-design-system-330.mjs`
- 結果：`results.json`
- 實拍：`output/playwright/clear-field-design-system-330/audit/`

每個案例各自啟動全新 Chrome 暫存 profile，忽略 LocalWP 自簽憑證，但不讀寫使用者既有 Chrome cookies、localStorage 或偏好。測試只讀公開 LocalWP 頁面；唯一瀏覽器寫入是各暫存 profile 的 `fwdignity-reader-size`，用來驗證本機偏好與重新互動。

門檻涵蓋 HTTP 200、3.3.0 資產、水平溢出、壞圖、runtime/console error、頁面與頁尾色、H1、最小可見字級、14/4/2px 幾何、B 角色式陰影、照片處理、Aa/浮層、三段字級、Escape/外點關閉，以及手機固定底列避讓。

`beginner-farmers`、`young-farmers`、`experienced-farmers` 與 `public` 目前在 LocalWP 都是草稿，公開 URL 為 404，因此固定底列沒有可用的公開實頁。本驗證在真實 390px 最新消息頁的瀏覽器 DOM 暫時加入 79px `.bf-bottom-nav` 並觸發 resize，只做「條件預演」；沒有發布草稿、修改 WordPress 內容或寫入資料庫。
