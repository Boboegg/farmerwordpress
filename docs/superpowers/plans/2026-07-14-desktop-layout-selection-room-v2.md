# 尊嚴農業電腦版最終版型選擇室 v2 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan.

**Goal:** 用 LocalWP 現行真實內容製作一份可在 1440px 瀏覽器直接檢視的九類頁面最終版型預覽，讓 Bobo 先驗收電腦版的資訊層級、留白、區塊與幾何，不改動網站現行模板。

**Architecture:** 預覽是獨立的靜態選擇室；一個建置器匯入三個互不重疊的頁面模組，套用共用的清朗田野 3.3.0 token 與桌機元件，再輸出單一 HTML。驗證器先檢查九類頁面、真實內容標記與禁止模式，最後以 1440px 真實 Chrome 檢查水平溢位、固定高度與主要區塊尺寸。

**Tech Stack:** Node.js ESM、語意化 HTML、原生 CSS、Playwright CLI／Chrome、LocalWP 現行內容與媒體。

## Global Constraints

- 只做電腦版 1440px；本輪不加手機斷點、不驗收手機版。
- 不修改 LocalWP 資料庫、正式頁面模板或現行 `site-v3-layout-final.css`。
- 不 reset、不 clean、不 commit、不 push；所有產出限於本 worklog、plan 與驗證輸出。
- 色彩、字體、字級、幾何、陰影與頁尾沿用唯一現行規格 `網站現行設計規範-清朗田野-2026-07-13.md`，本輪只判斷版面骨架。
- 使用真實標題、摘要、日期與現有媒體；不得以 lorem ipsum、假姓名或假數字補位。
- 留白採「平衡緊湊」：頁首至內容 32px、主章節 48px、標題至內容 24px、卡片與欄距 20–24px；父子層級不可重複疊 margin。
- 大章節以留白或單一底色面區分；細線只用於清單、時間流、表格，不把所有內容框成卡片。

---

### Task 1: 建立可失敗的版型契約

**Files:**
- Create: `worklogs/layout-final-v2-desktop-20260714/tests/verify-desktop-room.mjs`
- Test: `worklogs/layout-final-v2-desktop-20260714/tests/verify-desktop-room.mjs`

**Step 1: Write the failing test**

驗證輸出 HTML 必須包含 `data-page-family="01"` 至 `"09"`、九個唯一 `h2`、真實內容標記、Canonical token，以及以下禁止模式：手機 media query、主題頁 `01 / 05`、主題五選項列、全篇文章外框、以 `nth-child` 支配消息版型。

**Step 2: Run test to verify it fails**

Run: `node worklogs/layout-final-v2-desktop-20260714/tests/verify-desktop-room.mjs`
Expected: FAIL，原因是 `desktop-layout-room-v2.html` 尚不存在。

**Step 3: Add no production code yet**

保留第一次失敗輸出，確認驗證器不是永遠通過。

### Task 2: 建立共用桌機殼層與視覺規則

**Files:**
- Create: `worklogs/layout-final-v2-desktop-20260714/src/styles.css`
- Create: `worklogs/layout-final-v2-desktop-20260714/build-desktop-room.mjs`
- Modify: `worklogs/layout-final-v2-desktop-20260714/progress.md`

**Step 1: Implement the minimal shared shell**

建立 1320px 內容寬度、網站頁首、頁面跳轉列、九段預覽容器、共用頁尾與 Aa 閱讀控制視覺。所有章節使用自然高度，圖片預留 `aspect-ratio`，卡片只用在獨立可操作內容。

**Step 2: Add canonical desktop tokens**

套用清朗田野 3.3.0：紙張底、白色內容面、深綠主色、黑色長文、金色輔色、14／4／2px 幾何與既定 serif／sans 字體組合。

**Step 3: Run the contract test**

Run: `node worklogs/layout-final-v2-desktop-20260714/build-desktop-room.mjs && node worklogs/layout-final-v2-desktop-20260714/tests/verify-desktop-room.mjs`
Expected: 仍 FAIL，清楚列出尚未完成的頁面家族。

### Task 3: 製作 01–03 核心入口頁

**Files:**
- Create: `worklogs/layout-final-v2-desktop-20260714/src/pages/core.mjs`
- Test: `worklogs/layout-final-v2-desktop-20260714/tests/verify-desktop-room.mjs`

**Step 1: Implement 01 首頁**

把即時高溫警示放在導覽正下方，第一屏採真正 8:4 編輯頭版；頭條 440–480px、右欄放計畫導讀與兩則入口，消除 Hero 與最新消息間的 120px 空洞。

**Step 2: Implement 02 最新消息**

建立最新頭條、兩則次要消息、左側年份／月份索引與右側單欄時間流；不使用 `nth-child` 或跨列硬撐高度，並在版面備註後台可輪替頭條。

**Step 3: Implement 03 田間知識與資源**

直接呈現 3×2 六張任務卡，不重複建立雙軸入口，也不加第二組快捷導覽。

### Task 4: 製作 04–06 田野、主題與影音頁

**Files:**
- Create: `worklogs/layout-final-v2-desktop-20260714/src/pages/field-media.mjs`
- Test: `worklogs/layout-final-v2-desktop-20260714/tests/verify-desktop-room.mjs`

**Step 1: Implement 04 田野入口與集合**

田野誌使用 7:5 主稿＋短訊；做農的人使用 7+5／4+4+4 節奏；農學堂章節距離 48px，影音區改為白色內容面，不用灰色大面積底。

**Step 2: Implement 05 五個田野主題**

問句與說明改為全寬單欄，副標 20px 且可在 1100px 內自然成行；移除 `01 / 05` 與完整五題選項列，再接 7:5 主稿與證據內容。

**Step 3: Implement 06 聽見田裡的聲音**

第一屏採主打單集＋右側三集近期內容，後續只保留系列與典藏；移除重複的最新 Podcast／最新影片區塊。

### Task 5: 製作 07–09 研究、認識與單篇

**Files:**
- Create: `worklogs/layout-final-v2-desktop-20260714/src/pages/research-single.mjs`
- Test: `worklogs/layout-final-v2-desktop-20260714/tests/verify-desktop-room.mjs`

**Step 1: Implement 07 研究、資源與倡議**

建立問題→研究→共作→公開成果四步路徑與最新成果；三個行動入口採完整三欄或三列，不留下二欄網格孤兒。

**Step 2: Implement 08 認識尊嚴農業與研究團隊**

計畫頁第一屏採真實 7:5 文字／場域照片；團隊主責人物用水平敘事，其餘成員 auto-fit，有文字無照片者改為文字列。

**Step 3: Implement 09 文章單篇**

移除全篇白框、綠色上框與過大封面；第一屏採標題 7／媒體 5、媒體高不超過 480px，正文回到 780px 黑字。Podcast 以播放器／封面為媒體角色。

### Task 6: 建置、瀏覽器驗證與交付

**Files:**
- Create: `worklogs/layout-final-v2-desktop-20260714/desktop-layout-room-v2.html`
- Create: `output/playwright/layout-final-v2-desktop-20260714/results.json`
- Create: `output/playwright/layout-final-v2-desktop-20260714/screenshots/desktop-layout-room-v2.png`
- Modify: `worklogs/layout-final-v2-desktop-20260714/progress.md`

**Step 1: Run static verification**

Run: `node worklogs/layout-final-v2-desktop-20260714/build-desktop-room.mjs && node worklogs/layout-final-v2-desktop-20260714/tests/verify-desktop-room.mjs`
Expected: PASS，九類頁面與禁止模式全數通過。

**Step 2: Run browser verification at 1440px**

用 Playwright CLI 開啟輸出 HTML，檢查 `scrollWidth === clientWidth`、九類頁面均可見、主題頁副標寬度、主題選項列不存在、文章媒體高度與首頁／最新消息主要區塊尺寸。

**Step 3: Capture visual evidence**

輸出完整頁面截圖與結構化驗證結果；若失敗，先修正再重新驗證，不以口頭宣稱替代。

**Step 4: Open the preview for Bobo**

在既有 headed Chrome 工作階段開啟 `desktop-layout-room-v2.html`，停在總覽首段，讓 Bobo 直接逐頁檢視。

**Step 5: Record completion without Git operations**

更新 worklog progress；不 commit、不 push，等待 Bobo 視覺拍板後才討論 LocalWP 實作。
