# 清朗田野設計系統 3.3.0 實作計畫

> **執行方式：** 依本計畫逐步實作與驗證；使用 test-driven-development 與 verification-before-completion。使用者明確禁止 `reset`、`clean`、`commit`、`push`，本計畫不包含任何提交或上傳步驟。

**目標：** 把 2026-07-13 已核准的色彩、字體、三段讀者字級、C 外 B 內幾何與 B 角色式層次，正式套入 LocalWP 主題並完成真實瀏覽器驗收。

**唯一規格：** `/Users/boboegg/bobohome-agent-claude/300_Areas/尊嚴農業/網站現行設計規範-清朗田野-2026-07-13.md`

**架構：** `site-v2-tokens.css` 只存 canonical tokens；`site-v2-page-foundation.css` 作最後載入的公共頁裁決層；`reader-size-control.js` 管理無追蹤的本機字級偏好與無障礙互動；舊頁 CSS 只保留版面結構，不得再決定現役色票、字級、圓角與效果。

**技術：** WordPress/PHP、CSS custom properties、原生 JavaScript、shell 靜態測試、PHP lint、LocalWP、Chrome/CDP 瀏覽器驗證。

---

## Task 1：建立會先失敗的 3.3.0 設計契約

**Files**

- Create: `scripts/test-clear-field-design-system-330.sh`
- Inspect: `themes/fwdignity/assets/css/site-v2-tokens.css`
- Inspect: `themes/fwdignity/assets/css/site-v2-page-foundation.css`
- Inspect: `themes/fwdignity/functions.php`

**步驟**

1. 寫入色彩、字級、字重、行高、14/4/2px 幾何、三種陰影、160ms motion、讀者控制與 3.3.0 enqueue 的靜態斷言。
2. 先在現行 3.2.1 執行，確認因舊 token 與缺少閱讀器控制而失敗。
3. 保留失敗輸出，證明測試能抓到本輪目標缺口。

## Task 2：替換 canonical tokens 與全站字體角色

**Files**

- Modify: `themes/fwdignity/assets/css/site-v2-tokens.css`
- Modify: `themes/fwdignity/functions.php`
- Modify: `themes/fwdignity/assets/css/site-v2-page-foundation.css`

**步驟**

1. 將四個淺色表面、連結、邊線、雙層 focus、狀態色、字體、三段字級、行高、幾何、陰影與 motion 寫成 canonical tokens。
2. 移除 Lora enqueue；Google Fonts 只載 Noto Sans TC 400/500/700/900 與 Noto Serif TC 700/900。
3. 在裁決層固定 H1–H3/卡片標題與 H4/正文角色，長文改用 `#1A1A1A` 與 20px/1.85，一般正文使用 18px/1.80。
4. 執行 Task 1 測試，確認 token 與字體斷言通過，尚未完成項仍保持失敗。

## Task 3：實作全站讀者字級控制

**Files**

- Create: `themes/fwdignity/assets/js/reader-size-control.js`
- Modify: `themes/fwdignity/functions.php`
- Modify: `themes/fwdignity/assets/css/site-v2-page-foundation.css`

**步驟**

1. 原生 JS 建立 56×56px `Aa`、錨定浮層與三個真實按鈕；加入 `aria-expanded`、`aria-controls`、`aria-pressed`。
2. 使用 `localStorage` 保存 `standard/large/xlarge`，失敗時安全回到 standard；不攔截瀏覽器縮放。
3. 實作再次按 Aa、點外部與 Escape 關閉，Escape 後焦點回入口；選字級時面板保持開啟。
4. 量測可見 `.bf-bottom-nav` 高度，以 CSS 變數讓入口與浮層避開固定底列。
5. CSS 實作桌機與手機同一錨定浮層、safe area、無 JS 隱藏、三段逐角色字級。

## Task 4：收斂幾何、分類狀態、陰影與動態

**Files**

- Modify: `themes/fwdignity/assets/css/site-v2-page-foundation.css`
- Modify: `themes/fwdignity/assets/css/layout.css`
- Modify: `themes/fwdignity/assets/css/site-parts.css`

**步驟**

1. 公共內容外框收斂到 14px、控制 4px、分類/狀態 2px；真正圓形功能保留 50%，移除頁尾與公共標籤的膠囊。
2. 分類標籤改成中性底與 4px 左角色線；功能狀態實作 A/C/A 混合規則。
3. 內容卡與文章取消常駐陰影；工具、浮層、Aa 分別使用核准的三種 B 方案陰影。
4. 共用頁首改實色表面、移除一般毛玻璃；照片保持原色且 hover 不縮放或旋轉。
5. hover 只在精準指標裝置啟用且不位移；transition 只保留核准屬性、160ms ease-out；reduced-motion 關閉非必要動畫與平滑捲動。
6. 首頁影音帶上下 28px 紙白緩衝、內距 46px，深綠帶與深綠卡色不變；頁尾維持 `#25331E`。

## Task 5：LocalWP 自動驗證

**Files**

- Modify: `scripts/test-clear-field-design-system-330.sh`
- Create: `worklogs/clear-field-design-system-330/audit-design-system-330.mjs`
- Create: `worklogs/clear-field-design-system-330/README.md`

**步驟**

1. 執行設計契約、`php -l`、CSS 括號檢查與 JS 語法檢查。
2. 將資產版本更新為 3.3.0，再確認 LocalWP HTML 實際載入 `ver=3.3.0`。
3. 以隔離 Chrome profile 驗證標準/大字/特大字、localStorage、Escape/外點關閉、focus 回復、手機 fixed bottom nav 避讓。
4. 量測 1440、1025、390px 的 HTTP、水平溢出、壞圖、console/page error、H1/正文/控制尺寸與關鍵計算色。

## Task 6：逐頁目視驗收與規格回填

**Files**

- Modify: `/Users/boboegg/bobohome-agent-claude/300_Areas/尊嚴農業/progress.md`
- Modify: `/Users/boboegg/bobohome-agent-claude/300_Areas/尊嚴農業/網站現行設計規範-清朗田野-2026-07-13.md`

**步驟**

1. 目視首頁首屏/警示/影音帶/頁尾、最新消息、Podcast、做農的人、做農指南、田野誌、研究頁與真實長文。
2. 同時檢查桌機與手機的字級浮層、圖像裁切、圓角、明度節奏、頁尾銜接與無重疊。
3. 只有瀏覽器證據通過後，才把規格狀態由「待實作」改成「LocalWP 3.3.0 已實作並待 Bobo 最終目視」；若有缺口，保留未完成狀態並記錄實際失敗。

