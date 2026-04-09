# AGENTS.md — 尊嚴農業網站 AI 協作 context

> 本檔案同時被 **OpenAI Codex CLI**、**Google Gemini CLI**、以及 **Claude Code** 讀取作為專案 context。修改前請了解三方影響。
>
> Gemini CLI 透過 `~/.gemini/settings.json` 的 `context.fileName` 設定讀取此檔。Codex CLI 預設讀取此檔。

---

## 專案身份

- **計畫全名**：農業尊嚴：共建雲嘉農業工作者職業健康社會安全網
- **執行團隊**：國立中正大學 × 臺大醫院雲林分院
- **實踐場域**：嘉義縣民雄鄉
- **核心使用者**：雲嘉地區農業工作者（橫跨新手務農、青農、資深農友、一般民眾四大分眾）
- **主要研究人員**：曾柏儒（Bobo），國立中正大學成人及繼續教育學系博士生
- **指導教授**：陳毓璟

## Repo 用途

存放 WordPress 網站的所有自訂前端程式碼：頁面 HTML 區塊、全站 CSS、Shortcode 程式、自動化部署腳本。所有 HTML 設計為直接貼入 WordPress 頁面編輯器（程式碼模式）或透過 `scripts/deploy.sh` 自動部署。

**完整架構藍圖**：先讀 `docs/architecture.md`，再開始任何頁面任務。

---

## 必要遵守規範

### 語言
- 所有 commit message、文件、註解、UI 文案：**繁體中文**
- 變數名稱、檔名、CSS class：英文
- 不要簡體中文、不要中國用語

### 學術引用
- 數據與文獻一律使用 **APA 第 7 版**格式
- **完全排除中國大陸來源**：不引用、不比較、不佐證中國大陸的期刊、研究、作者（港澳臺或海外華人學者除外）
- 優先使用台灣本土研究、日本、韓國等具相似高齡農業背景之來源
- 不造假 DOI / URL / 作者，不確定就標註「待確認」

### 視覺與設計
- **品牌主色**（尊嚴綠）：`#5C8607`
- **背景色**：`#E3E9D8`
- **輔色**（稻金黃）：`#D4A017`
- **字型**：Noto Sans TC（中文）、Lato（英文標題）、Lora（英文襯線）
- **圖示庫**：Font Awesome 6.4.x
- **設計風格**：稻穗熟成（Rice Ripening）配色系統 — 飽和但溫暖，避開純白與冷藍
- 視覺爆改原則：巨型字 + Editorial 風格 + 動畫，但保持中高齡使用者可讀性
- **學術網站可以美麗**：可信度來自引用嚴謹，不是視覺無聊

### 響應式
- 三層斷點：手機 / 平板 / 桌機
- **觸控裝置必須隔離 hover 效果**（避免 mobile 上 hover 殘留卡死）

---

## 程式碼規範（前端）

### HTML
- 所有頁面 HTML 必須用 `<!-- wp:html -->` / `<!-- /wp:html -->` 包裹
- 避免內聯 `<style>` 中使用 `body{}`、`html{}`、`*{}` 全域選擇器
- 用 CSS 前綴隔離各模組：`prev-`（職安）、`hp-`（健促）、`money-`（經保）、`start-`（新手）、`young-`（青農）、`senior-`（資深）、`public-`（公眾）

### CSS
- 全站樣式統一在 `css/global.css`
- WordPress 主題：Astra（雙背景模式已統一）
- 不要破壞現有 prefix 隔離

### Shortcode
- PHP shortcodes 在 `shortcodes/` 是參考用，**實際以 WordPress Code Snippets 插件管理**
- 部署腳本會跳過 shortcodes 階段

---

## 目錄結構

```
farmerwordpress/
├── docs/                              ← 架構藍圖（必讀）
│   ├── architecture.md                ← 完整網站規劃藍圖（最重要）
│   ├── audience-landing-pages.md      ← 四分眾入口設計
│   ├── article-category-slugs.md      ← 文章分類對照
│   ├── import-map.md                  ← 外部匯入對照
│   └── occupational-safety-data-enhancement.md
│
├── css/global.css                     ← 全站自訂 CSS
│
├── pages/                             ← 各頁面 HTML 區塊
│   ├── home/                          ← 首頁 + 側邊欄 (8 檔)
│   ├── About/                         ← 01 關於計畫 (4)
│   ├── occupational-safety/           ← 02 職業安全 (12，prev-)
│   ├── healthgood/                    ← 03 健康促進 (13，hp-)
│   ├── economic-insurance/            ← 04 經濟保險 (12，money-)
│   ├── farmer-study/                  ← 05 培力（農學堂未開發）
│   ├── dignity-farming-initiative/    ← 06 尊嚴農業倡議 (3)
│   ├── research-result/               ← 07 研究成果 (7)
│   ├── beginner-farmers/              ← 橫軸：新手 (start-)
│   ├── young-farmers/                 ← 橫軸：青農 (young-)
│   ├── experienced-farmers/           ← 橫軸：資深 (senior-)
│   ├── Public/                        ← 橫軸：公眾 (public-)
│   └── thenews/                       ← 最新消息
│
├── shortcodes/                        ← PHP shortcodes（參考用）
└── scripts/
    ├── deploy.sh                      ← 主部署腳本（三階段）
    ├── import-wp-pages.sh             ← WP-CLI 頁面匯入
    └── page-map.json                  ← HTML → WP 頁面 ID 對應
```

---

## 開發進度

| Phase | 狀態 | 內容 |
|---|---|---|
| 1 | ✅ | 首頁 + 側邊欄 Widget |
| 2 | ✅ | 職業安全（主頁 + 3 子頁 + 國際數據） |
| 3 | ✅ | 健康促進（主頁 + 4 子頁） |
| 4 | ✅ | 經濟與保險（主頁 + 5 子頁 + 試算器） |
| 5 | ✅ | 尊嚴農業倡議 |
| 6 | ✅ | 研究成果（5 子頁） |
| 7 | ✅ | 四大分眾入口（差異化設計） |
| 8 | 🔄 | 農學堂 LMS、部署自動化 |

---

## AI 工具分工（重要）

本專案採用 **4 層 AI 編排架構**。每個工具的職責：

### Claude Code（總指揮）
- 拆任務、verify、commit、Notion 同步
- 跨檔重構、架構決策
- 寫 issue / PR 描述、寫 commit message
- 管理 Codex / Gemini 的派任流程

### Codex CLI（透過 `openai/codex-plugin-cc`）
- 寫複雜元件邏輯、refactor
- Adversarial review（質疑設計決策）
- 一般 code review（`/codex:review`）
- 派任語法：在 Claude Code 內 `/codex:rescue <任務>`

### Gemini CLI + 官方 extensions
- **設計新頁面、刻 UI、design-to-code** → Stitch（透過 stitch.withgoogle.com）
- **生圖、製作品牌素材** → nanobanana（Gemini 3.1 Flash Image）
- **跨檔審計、找風格不一致** → 1M context 直接吃整個 repo
- **WCAG 無障礙檢查** → web-accessibility extension
- **功能規劃** → conductor extension
- **程式碼審查** → code-review extension（CI 也用這個）
- 派任語法：Claude Code 透過 `gemini-delegate` skill 自動呼叫

### GitHub Actions（CI 層）
- 自動 PR review（`.github/workflows/gemini-review.yml`）
- 自動 issue triage（`.github/workflows/gemini-triage.yml`）
- `@gemini-cli` 互動助手
- **強制使用 Gemini 2.5 Flash**（透過 repo variable `GEMINI_MODEL`），確保零成本

### 任務分流決策表

| 任務類型 | 派給誰 |
|---|---|
| 設計新頁面、刻 UI、design-to-code | Gemini + Stitch |
| 從 Figma 設計稿生程式碼 | Gemini + Figma extension |
| 生農業情境插圖、品牌素材 | Gemini + nanobanana |
| 寫複雜元件邏輯、refactor | Codex |
| 跨檔審計、找風格不一致 | Gemini（1M context） |
| WCAG 無障礙檢查 | Gemini + web-accessibility |
| Adversarial review、挑戰設計決策 | Codex `/codex:adversarial-review` |
| Code review 一般 | Codex `/codex:review` 或 GitHub Action |
| commit / push / PR / Notion 更新 | Claude 自己做 |
| 跨檔重構、架構決策 | Claude 自己做 |
| PR 自動 review（CI） | run-gemini-cli |
| Issue triage（CI） | run-gemini-cli |

---

## 部署流程

### 自動部署
```bash
bash scripts/deploy.sh
```

三階段：
1. **CSS** — `css/global.css` → Astra Child Theme
2. **Shortcodes** — 跳過（由 Code Snippets 插件管理）
3. **HTML 頁面** — 讀取 `scripts/page-map.json`，透過 WP-CLI 更新

### 手動部署
1. WordPress 後台 → 頁面 → 找到目標
2. 切換**程式碼編輯器**
3. 全選 → 貼入對應 HTML
4. 發佈 / 更新

---

## 安全紅線

- ❌ **絕對不要**在程式碼裡寫死任何 API key、密碼、token
- ❌ **絕對不要**修改 `scripts/page-map.json` 內的 WordPress 頁面 ID（會破壞線上頁面對應）
- ❌ **絕對不要**動 `wp-config.php`、`.htaccess`、WordPress 核心檔案
- ❌ **絕對不要**在 commit 裡包含 `.env`、`gha-creds-*.json`、私鑰
- ✅ 部署前先在 staging 測，再上 production
- ✅ 改 CSS 前先看現有 prefix 結構，不要破壞隔離

---

## 相關文件

| 文件 | 說明 |
|---|---|
| `docs/architecture.md` | 完整架構藍圖（必讀） |
| `docs/audience-landing-pages.md` | 四分眾入口設計細節 |
| `docs/article-category-slugs.md` | WordPress 文章分類對照 |
| `docs/import-map.md` | 外部資料匯入對照表 |
| `README.md` | 快速入口、品牌速查 |
