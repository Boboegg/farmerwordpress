# 尊嚴農業 — farmerwordpress

> 農業尊嚴：共建雲嘉農業工作者職業健康社會安全網

**執行團隊**：國立中正大學 × 臺大醫院雲林分院
**實踐場域**：嘉義縣民雄鄉

---

## 這個 Repo 是什麼

此 Repository 存放「尊嚴農業」WordPress 網站的所有自訂前端程式碼，包含頁面 HTML 區塊、全站 CSS、WordPress Shortcode 程式，以及自動化部署腳本。

所有 HTML 檔案設計為直接貼入 WordPress 頁面編輯器（程式碼模式），或透過 `scripts/deploy.sh` 自動部署。

---

## 開始之前，請先閱讀

**→ [`/docs/architecture.md`](./docs/architecture.md)** — 完整網站架構規劃、介面設計邏輯、品牌規格、技術規範、部署流程。

AI 協作工具在處理任何頁面任務前，應以此為首要參考依據。

---

## 目錄結構

```
farmerwordpress/
├── docs/                              ← 架構藍圖、設計規格
│   ├── architecture.md                ← 網站完整規劃藍圖（必讀）
│   ├── website-architecture-presentation.md ← 簡報版說明
│   ├── audience-landing-pages.md      ← 分眾入口設計說明
│   ├── article-category-slugs.md      ← WordPress 文章分類對照
│   ├── import-map.md                  ← 外部匯入檔案對照
│   └── occupational-safety-data-enhancement.md ← 職安數據增補紀錄
│
├── css/
│   └── global.css                     ← 全站自訂 CSS（Astra 主題追加）
│
├── pages/                             ← 各頁面 HTML 區塊
│   ├── home/                          ← 首頁 + 側邊欄 Widget
│   ├── About/                         ← 01 關於計畫
│   ├── occupational-safety/           ← 02 職業安全（7 區塊 + 3 子頁）
│   ├── healthgood/                    ← 03 健康促進（6 區塊 + 4 子頁）
│   ├── economic-insurance/            ← 04 經濟與保險（5 區塊 + 5 子頁）
│   ├── farmer-study/                  ← 05 培力與實踐（農學堂）
│   ├── dignity-farming-initiative/    ← 06 尊嚴農業倡議
│   ├── research-result/               ← 07 研究成果（5 子頁）
│   ├── beginner-farmers/              ← 橫軸：新手務農
│   ├── young-farmers/                 ← 橫軸：青農
│   ├── experienced-farmers/           ← 橫軸：資深農友
│   ├── Public/                        ← 橫軸：一般民眾
│   └── thenews/                       ← 最新消息
│
├── shortcodes/                        ← PHP Shortcodes（參考用）
│
└── scripts/                           ← 部署與匯入腳本
    ├── deploy.sh                      ← 主部署腳本（三階段）
    ├── import-wp-pages.sh             ← WP-CLI 頁面匯入
    ├── page-map.json                  ← HTML 檔案 → WordPress 頁面 ID
    └── generate_pptx.py               ← 簡報產生器
```

---

## 品牌快速參考（現行）

| 項目 | 值 |
|------|----|
| 主色（苔蘚墨綠） | `#3D5A2C` |
| 背景（米紙白） | `#FBFAF7` |
| 輔色（古銅稻穗金） | `#A8740B` |
| 警示色（燒土紅） | `#8B2D14` |
| 字型 | Noto Serif TC / Noto Sans TC |
| 圖示 | Font Awesome 6.4.x |

詳細設計規格請見 [`/docs/design-system.md`](./docs/design-system.md) 與 [`/docs/architecture.md`](./docs/architecture.md)。

---

## 部署

### 自動部署（推薦）

```bash
bash scripts/deploy.sh
```

三階段：
1. **CSS** — `css/global.css` → Astra Child Theme
2. **Shortcodes** — 由 WordPress Code Snippets 插件管理（跳過）
3. **HTML 頁面** — 讀取 `scripts/page-map.json`，透過 WP-CLI 更新

### 手動部署

1. 進入 WordPress 後台 → 頁面 → 找到目標頁面
2. 切換至**程式碼編輯器**
3. 全選 → 貼入對應 HTML 檔案內容
4. 發佈 / 更新

### 關鍵注意事項

- 所有 HTML 必須用 `<!-- wp:html -->` / `<!-- /wp:html -->` 包裹
- CSS 使用前綴隔離（`prev-`、`hp-`、`money-` 等），不會互相衝突
- 禁止在 `<style>` 中使用 `body{}`、`html{}`、`*{}` 等全域選擇器

---

## 選單結構

### 縱軸：七大主選單

| # | 主選單 | slug | 子頁數 |
|---|--------|------|--------|
| 01 | 關於計畫 | `about` | 2（研究團隊、聯絡我們） |
| 02 | 職業安全 | `occupational-safety` | 3（溫室、省工農機、PPE） |
| 03 | 健康促進 | `healthgood` | 4（熱傷害、農藥、肌骨、心理） |
| 04 | 經濟與保險 | `economic-insurance` | 5（補助、農保、職災保險、退休金、職災試算） |
| 05 | 培力與實踐 | `farmer-study` | 0 |
| 06 | 尊嚴農業倡議 | `dignity-farming-initiative` | 0 |
| 07 | 研究成果 | `research-result` | 5（研究出版、Podcast、圖文、下載、資源） |

### 橫軸：四大分眾入口

| 分眾 | slug | CSS 前綴 |
|------|------|----------|
| 新手務農 | `beginner-farmers` | `start-` |
| 青農 | `young-farmers` | `young-` |
| 資深農友 | `experienced-farmers` | `senior-` |
| 一般民眾 | `public` | `public-` |

---

## 開發進度

| 階段 | 狀態 | 內容 |
|------|------|------|
| Phase 1 | ✅ | 首頁 + 側邊欄 Widget |
| Phase 2 | ✅ | 職業安全（主頁 + 3 子頁 + 國際數據） |
| Phase 3 | ✅ | 健康促進（主頁 + 4 子頁） |
| Phase 4 | ✅ | 經濟與保險（主頁 + 5 子頁 + 試算器） |
| Phase 5 | ✅ | 尊嚴農業倡議 |
| Phase 6 | ✅ | 研究成果（主頁重新設計 + 5 子頁） |
| Phase 7 | ✅ | 四大分眾入口（差異化設計） |
| Phase 8 | 🔄 | 農學堂 LMS、部署自動化 |

---

## 相關文件

| 文件 | 說明 |
|------|------|
| [`docs/architecture.md`](./docs/architecture.md) | 完整架構藍圖（必讀） |
| [`docs/design-system.md`](./docs/design-system.md) | 設計系統整合版（token/字體/動效/相容） |
| [`docs/website-architecture-presentation.md`](./docs/website-architecture-presentation.md) | 簡報版說明 |
| [`docs/audience-landing-pages.md`](./docs/audience-landing-pages.md) | 分眾入口設計 |
| [`docs/article-category-slugs.md`](./docs/article-category-slugs.md) | 文章分類對照 |
| [`docs/occupational-safety-data-enhancement.md`](./docs/occupational-safety-data-enhancement.md) | 職安數據增補 |
| [`docs/import-map.md`](./docs/import-map.md) | 外部匯入對照 |
