# 尊嚴農業網站：系統與介面建置藍圖

> **計畫全名**：農業尊嚴：共建雲嘉農業工作者職業健康社會安全網
> **執行團隊**：國立中正大學 × 臺大醫院雲林分院
> **實踐場域**：嘉義縣民雄鄉
> **本文件用途**：作為所有前端開發與介面設計的唯一參考依據，AI 協作工具（如 Claude）應在開始任何頁面建置前先閱讀本文件。

---

## 行政摘要

本網站捨棄傳統政令宣導式的條列網頁，採用**矩陣式資訊架構（Matrix Information Architecture）**，定位為動態的「農業健康與安全知識樞紐（Knowledge Hub）」。

前端透過**四大分眾入口（橫軸）**針對不同生命週期的農民進行精準內容策展；後端則建構**七大資料庫主選單（縱軸）**，確保知識儲存的系統性與學術嚴謹度。

整體網站視覺應用「功能性柔和色系（Functional Pastels）」，以降低視覺疲勞並提升資訊可讀性。所有文獻與數據呈現嚴格遵守 **APA 格式第 7 版**，並系統性排除中國大陸來源數據，以台灣本土實證數據及日、韓等具備相似高齡農業背景之研究為學術基石。

---

## 一、整體架構：矩陣式 (橫軸 × 縱軸)

```
                    ┌──────────────────────────────────────────────┐
                    │           七大資料庫主選單（縱軸）              │
                    │  01關於 02職安 03健康 04保險 05培力 06倡議 07研究 │
    ┌───────────────┼──────────────────────────────────────────────┤
    │  新手務農 Start │   依使用者身分，動態撈取對應縱軸模組的精選內容    │
    │  青農創業 Pro   │                                              │
    │  資深農友 Senior│                                              │
    │  一般公眾 Public│                                              │
    └───────────────┴──────────────────────────────────────────────┘
    （橫軸・分眾入口）
```

---

## 二、首頁：分流大廳（The Lobby）

首頁設計原則：訪客不會看到傳統「最新消息」文字牆或無意義輪播圖，而是直接面對**四大分眾入口卡片**，第一時間降低認知負荷。

- **頂部**：全域導覽列（Global Navigation，含七大資料庫選單）
- **中央**：四個帶有專屬功能性柔和色系的大型點擊區塊
- **動態內容**：透過 Shortcode 呈現亮點輪播、雜誌式新聞版面、Podcast 網格

---

## 三、橫軸：四大分眾入口

| 分眾 | 定位 | 主色調 | 介面模式 | 核心功能 |
|------|------|--------|----------|----------|
| **新手務農** | 防禦包 | 鼠尾草綠 `#87AE73` | 檢核清單 + 進度條 | 務農風險快篩表 → PPE 型錄 |
| **青農創業** | 升級包 | 板岩藍 `#5B7FA6` | 儀表板 + 比較圖表 | 投資報酬率 + 退休金試算 |
| **資深農友** | 樂活包 | 陶土橘 `#C17F5E` | Mobile-First + App | 找醫師・算津貼・看影片 |
| **一般公眾** | 認同包 | 麥穗黃 `#D4A017` | 敘事性滾動 | 攝影紀實 + Podcast + 倡議 |

### 共用六段結構

所有 4 頁共享以下骨架，但每段的內容、數據、語氣完全依受眾客製：

| # | 區段 | 功能 |
|---|------|------|
| ① | Hero | 情境開場白 + 計畫簡介 |
| ② | 數據震撼區 | 3 張統計卡（含來源標註） |
| ③ | 場景導航 | 4 張情境卡 + 行動連結 |
| ④ | 計畫簡介 | 4 行動支柱（基礎調查 / 制度研究 / 健康教育 / 社會倡議） |
| ⑤ | 快速行動 | 2 顆 CTA 按鈕 |
| ⑥ | 緊急 + 探索 | 119 / 1925 / 113 + 底部固定導覽 |

> 詳細的四頁差異化設計（開場語、數據、CTA）請見 `/docs/audience-landing-pages.md`。

---

## 四、縱軸：七大主選單核心資料庫

### 01 關於計畫（About）

- **介面類型**：靜態資訊頁面 + 多媒體展示
- **CSS 樣式**：使用全站 `.my-team-card` 展示中正大學跨域研究團隊
- **子頁面**：研究團隊 / 聯絡我們
- **檔案**：`pages/About/關於我們.html`

### 02 職業安全（Occupational Safety）

- **介面類型**：網格卡片數位型錄 + 互動式工具
- **CSS 前綴**：`prev-`
- **頁面結構**：Hero → Tabs 切換（省工農機 / PPE）→ Grid Cards → 風險評估工具 → 危害辨識輪盤 → 全球與台灣概況
- **子頁面**：溫室專區（`gh-`）/ 省工農機具（`mach-`）/ 個人防護具（`ppe-`）
- **檔案**：`pages/occupational-safety/職業安全.html`

### 03 健康促進（Health Promotion）

- **介面類型**：地理資訊整合 + 影音資料庫
- **CSS 前綴**：`hp-`
- **頁面結構**：Hero（含統計）→ Tab 切換（熱傷害 / 農藥 / 肌骨 / 心理）→ 健康風險速篩工具
- **子頁面**：熱傷害 / 農藥安全 / 肌肉骨骼 / 心理健康
- **檔案**：`pages/healthgood/健康促進.html`

### 04 經濟與保險（Economic Security）

- **介面類型**：資訊圖解 + 互動試算工具
- **CSS 前綴**：`money-`
- **頁面結構**：Hero（含統計）→ 三大主題卡片（職災保險 / 農作物保險 / 補助資源）→ 線上試算工具
- **子頁面**：農業補助資源 / 農業保險 / 農民退休金試算表 / 職業災害保險 / 農民職業災害試算表
- **檔案**：`pages/economic-insurance/經濟與保險.html`

### 05 培力與實踐（Empowerment / 農學堂）

- **介面類型**：輕量級 LMS + 活動行事曆
- **頁面結構**：影片網格 + 工作坊清單 + 報名表單
- **檔案**：`pages/farmer-study/農學堂.html`

### 06 尊嚴農業倡議（Advocacy）

- **介面類型**：單頁式深度專題報導
- **CSS 前綴**：`adv-`
- **頁面結構**：Hero → 為什麼需要倡議 → 核心主張 → 垂直時間軸 → 焦點團體 → 政策連署
- **檔案**：`pages/dignity-farming-initiative/倡議.html`

### 07 研究成果（Research）

- **介面類型**：學術資料庫 + 文獻列表
- **CSS 前綴**：`res-`（主頁與子頁共用）
- **頁面結構**：Hero（含介紹文字、統計、行動按鈕）→ 成果分類五宮格 → 最新成果精選 → 研究取徑 → 合作夥伴 → 交叉連結
- **子頁面**：研究出版 / 影音 Podcast（`pod-`）/ 圖文資訊 / 下載專區 / 相關資源
- **檔案**：`pages/research-result/研究成果`

---

## 五、品牌設計規格

### 5-1 色彩系統

#### 全站品牌色

| 名稱 | HEX | 用途 |
|------|-----|------|
| 品牌主色・尊嚴綠 | `#5C8607` | 全站主按鈕、連結、強調色 |
| 深尊嚴綠（hover） | `#4A6B05` | 按鈕 hover 狀態 |
| 深尊嚴綠（側邊欄） | `#2C5E2E` | 側邊欄標題、tag 按鈕 hover |
| 全站背景 | `#E3E9D8` | 整體頁面底色 |
| 輔色・稻金黃 | `#D4A017` | 圖示點綴、裝飾線 |

#### 分眾入口色（Functional Pastels）

| 分眾 | 色彩名稱 | HEX | 背景 HEX |
|------|---------|------|----------|
| 新手務農 | 鼠尾草綠 | `#87AE73` | `#EEF5EA` |
| 青農創業 | 板岩藍 | `#5B7FA6` | `#E8EFF7` |
| 資深農友 | 陶土橘 | `#C17F5E` | `#F9EDE7` |
| 一般公眾 | 麥穗黃 | `#D4A017` | `#FDF4DC` |

#### 職業安全頁面危害控制層級色

| 層級 | 名稱 | 漸層色 |
|------|------|--------|
| L1 消除 | Emerald | `#10B981 → #34D399` |
| L2 取代 | Sky Blue | `#0EA5E9 → #38BDF8` |
| L3 工程 | Blue | `#3B82F6 → #60A5FA` |
| L4 行政 | Amber | `#F59E0B → #FBBF24` |
| L5 PPE | Rose | `#F43F5E → #FB7185` |

#### 風險評估紅綠燈色

| 風險等級 | HEX |
|---------|-----|
| 高度風險（≥ 8 分） | `#EF5350` |
| 中度風險（3–7 分） | `#FFB74D` |
| 低度風險（≤ 2 分） | `#81C784` |

### 5-2 字型

| 用途 | 字型 |
|------|------|
| 中文 | Noto Sans TC |
| 英數 | Lato |
| 內文裝飾 | Lora |
| 圖示 | Font Awesome 6.4.x |

### 5-3 APA 規範

- 所有文獻嚴格遵守 **APA 第 7 版**格式
- **系統性排除中國大陸來源**數據
- 以台灣本土實證 + 日韓澳相似高齡農業背景研究為學術基石

---

## 六、技術規範

### 6-1 技術堆疊

| 項目 | 技術 |
|------|------|
| CMS | WordPress + Astra Child Theme |
| 前端 | 純 HTML/CSS（無 build 工具） |
| CSS | Custom Properties（設計 Token）+ 前綴隔離 |
| 動態功能 | PHP Shortcodes（透過 Code Snippets 插件管理） |
| 部署 | `scripts/deploy.sh` → WP-CLI 自動匯入 |

### 6-2 WordPress 嵌入規範（關鍵！）

#### `<!-- wp:html -->` 標籤要求

**每個 HTML 頁面都必須用 `<!-- wp:html -->` 和 `<!-- /wp:html -->` 包裹**，否則 WordPress 會將 `<style>` 視為一般文字處理，導致 CSS 完全跑掉。

```html
<!-- wp:html -->

<!-- 你的 HTML + CSS 內容 -->

<!-- /wp:html -->
```

#### 禁止項目

| 禁止項目 | 原因 |
|---------|------|
| `body { ... }` | 覆蓋 Astra 主題全站樣式 |
| `* { margin:0; padding:0; }` | 全域重置破壞 WordPress 元件間距 |
| `.container { ... }` | 與 Astra / Bootstrap 保留 class 衝突 |
| `html { ... }` | 影響全域 scroll behavior |

**解法**：所有全域選擇器改為頁面專屬 wrapper（如 `.res-hero`、`.hp-hero`）。

### 6-3 CSS 前綴隔離制度

每個頁面/模組使用獨立 CSS 前綴，避免跨頁衝突：

| 頁面 | CSS 前綴 | 說明 |
|------|----------|------|
| 職業安全 | `prev-` | prevention |
| 健康促進 | `hp-` | health promotion |
| 經濟與保險 | `money-` | economic |
| 尊嚴農業倡議 | `adv-` | advocacy |
| 研究成果 | `res-` | research |
| 影音 Podcast | `pod-` | podcast |
| 溫室專區 | `gh-` | greenhouse |
| 省工農機具 | `mach-` | machinery |
| 個人防護具 | `ppe-` | PPE |
| 新手務農 | `start-` | beginner |
| 青農 | `young-` | young farmer |
| 資深農友 | `senior-` | senior |
| 一般民眾 | `public-` | public |

### 6-4 CSS 設計 Token（`:root` 變數）

```css
:root {
    /* 品牌色 */
    --brand: #5C8607;
    --brand-dark: #4a6b05;
    --brand-light: #eef5ea;
    --brand-hover: #3d5a04;

    /* 強調色 */
    --accent: #d4a017;

    /* 文字色 */
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --text-muted: #9ca3af;

    /* 中性色 */
    --bg-white: #ffffff;
    --bg-light: #f8fafc;
    --border-light: #e2e8f0;

    /* 元件 Token */
    --radius-card: 16px;
    --radius-badge: 20px;
    --radius-btn: 50px;
    --shadow-card: 0 4px 16px rgba(0,0,0,0.05);
    --shadow-card-hover: 0 15px 30px rgba(0,0,0,0.08);
    --transition-card: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}
```

### 6-5 字型載入規則

Font Awesome 與 Google Fonts 每個頁面**只載入一次**，放在所有 HTML 的最頂端：

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700;900&family=Lato:wght@400;700;900&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
```

### 6-6 全站 CSS（`/css/global.css`）已定義 Class

| Class | 用途 |
|-------|------|
| `.content-card` | 一般內容卡片（hover 上浮，border-top 尊嚴綠） |
| `.my-team-card` | 研究團隊透明懸浮卡片 |
| `.sidebar-wrapper` | Sticky 側邊欄容器（top: 120px） |
| `.side-widget` / `.side-title` | 側邊欄小工具 |
| `.tool-list` / `.tool-link` | 側邊欄工具連結 |
| `.tag-cloud` / `.tag-btn` | 關鍵字雲 |
| `.action-item` / `.action-icon` | 行動設計清單 |
| `.social-btn` | 社群按鈕 |
| `.service-portal-*` | 首頁身分導航 |
| `.cat-*` | 全站分類配色系統 |

---

## 七、部署系統

### 7-1 部署流程

```bash
bash scripts/deploy.sh
```

三階段自動化部署：
1. **CSS 部署** — `css/global.css` → Astra Child Theme
2. **Shortcodes** — 由 Code Snippets 插件管理（不部署至 mu-plugins）
3. **HTML 頁面匯入** — 讀取 `scripts/page-map.json`，透過 WP-CLI 更新頁面

### 7-2 頁面對照表（page-map.json）

| HTML 檔案路徑 | WordPress 頁面 ID |
|---|---|
| `pages/home/index.html` | 1420 |
| `pages/About/關於我們.html` | 1476 |
| `pages/occupational-safety/職業安全.html` | 1329 |
| `pages/healthgood/健康促進.html` | 1328 |
| `pages/economic-insurance/經濟與保險.html` | 1330 |
| `pages/farmer-study/農學堂.html` | 1489 |
| `pages/dignity-farming-initiative/倡議.html` | 1380 |
| `pages/research-result/研究成果` | 1331 |
| `pages/research-result/research-publication/研究出版` | 1392 |
| `pages/research-result/related-resources/相關資源` | 1332 |
| `pages/thenews/最新消息` | 1156 |

> ID 為 0 表示尚未設定，部署時跳過。新頁面需先在 WordPress 建立後，將 ID 填入此檔案。

### 7-3 PHP Shortcodes

| Shortcode | 功能 | 管理方式 |
|-----------|------|----------|
| `home_highlight_slider` | 首頁亮點輪播 | Code Snippets |
| `news_magazine_layout` | 雜誌式新聞版面 | Code Snippets |
| `knowledge_podcast_grid` | Podcast 網格 | Code Snippets |
| `podcast_full_archive` | Podcast 完整存檔 | Code Snippets |
| `my_news_loop` | 新聞迴圈 | Code Snippets |
| `weather_widget` | 天氣小工具 | Code Snippets |
| `fb_feed_widget` | Facebook 動態牆 | Code Snippets |

---

## 八、程式碼結構

```
farmerwordpress/
├── docs/                          ← 架構藍圖、設計規格
│   ├── architecture.md            ← 本文件（必讀）
│   ├── website-architecture-presentation.md  ← 簡報版
│   ├── audience-landing-pages.md  ← 分眾入口設計說明
│   ├── article-category-slugs.md  ← WordPress 文章分類對照
│   ├── import-map.md              ← 外部匯入檔案對照
│   └── occupational-safety-data-enhancement.md  ← 職安數據增補紀錄
│
├── css/
│   └── global.css                 ← 全站設計 Token + 共用樣式
│
├── pages/
│   ├── home/                      ← 首頁 + 側邊欄 Widget
│   │   ├── index.html             ← 首頁主體
│   │   ├── 分眾入口                ← 側邊欄 Widget
│   │   ├── 快速連結 / 側邊欄工具箱 / 熱門關鍵字 / 友善連結
│   │   └── 社群媒體
│   │
│   ├── About/                     ← 01 關於計畫
│   ├── occupational-safety/       ← 02 職業安全（7 區塊 + 3 子頁）
│   ├── healthgood/                ← 03 健康促進（6 區塊 + 4 子頁）
│   ├── economic-insurance/        ← 04 經濟與保險（5 區塊 + 5 子頁）
│   ├── farmer-study/              ← 05 培力與實踐（農學堂）
│   ├── dignity-farming-initiative/ ← 06 尊嚴農業倡議
│   ├── research-result/           ← 07 研究成果（5 子頁）
│   │
│   ├── beginner-farmers/          ← 橫軸：新手務農
│   ├── young-farmers/             ← 橫軸：青農
│   ├── experienced-farmers/       ← 橫軸：資深農友
│   ├── Public/                    ← 橫軸：一般民眾
│   │
│   ├── thenews/                   ← 最新消息
│   └── shared/sidebar.html        ← 共用側邊欄模板
│
├── shortcodes/                    ← PHP Shortcodes（參考用，實際由 Code Snippets 管理）
└── scripts/                       ← 部署與匯入腳本
    ├── deploy.sh                  ← 主部署腳本
    ├── import-wp-pages.sh         ← WP-CLI 頁面匯入
    ├── page-map.json              ← 頁面 → WordPress ID 對照
    └── generate_pptx.py           ← 簡報產生器
```

---

## 九、頁面設計共同模式

### 9-1 Hero 區塊（所有主頁面共用模式）

- Badge 英文標籤 + 大標題 + 副標語
- 2-3 段介紹文字 + 重點列表（`<ul>` + check icon）
- 主要 / 次要行動按鈕
- 右側統計卡片（3 張，各帶圖標 + 數字 + 說明 + 來源）

### 9-2 內容區塊

- 白色卡片 + 圓角 16px + 陰影
- Section Header：圖標 + 標題 + 說明 + 底部分隔線
- 24px 區塊間距

### 9-3 交叉連結

- 底部四宮格導覽到其他主題頁
- 子頁面「回到總覽」返回連結
- 子頁間 pill-style 導覽列

### 9-4 RWD 響應式

- 桌面優先設計
- 768px 斷點：單欄化、縮小標題、移除 sticky
- 992px 斷點：側邊欄取消固定

---

## 十、開發進度路線圖

| 階段 | 狀態 | 內容 |
|------|------|------|
| **Phase 1** | ✅ 完成 | 首頁：身分導航 + 三大核心主題 + 側邊欄 Widget |
| **Phase 2** | ✅ 完成 | 職業安全：主頁 7 區塊 + 溫室 + 省工農機 + PPE（含國際數據增補） |
| **Phase 3** | ✅ 完成 | 健康促進：主頁 6 區塊 + 熱傷害 + 農藥安全 + 肌骨 + 心理健康 |
| **Phase 4** | ✅ 完成 | 經濟與保險：主頁 + 補助資源 + 農業保險 + 職災保險 + 試算表 ×2 |
| **Phase 5** | ✅ 完成 | 尊嚴農業倡議：時間軸 + 焦點團體 + 核心主張 |
| **Phase 6** | ✅ 完成 | 研究成果：主頁重新設計 + 研究出版 + Podcast + 圖文 + 下載 + 相關資源 |
| **Phase 7** | ✅ 完成 | 分眾入口：新手務農 / 青農 / 資深農友 / 一般民眾（四頁差異化設計） |
| **Phase 8** | 🔄 進行中 | 農學堂 LMS 完善、全站部署自動化、內容持續更新 |

---

## 十一、國際引用數據來源

| 機構 | 引用文件 |
|------|----------|
| **CDC/NIOSH** | Agricultural Safety (2024)、Hierarchy of Controls、Revised Lifting Equation |
| **OSHA** | Agricultural Operations: Hazards & Controls、Heat Illness Prevention、PPE Standards |
| **BLS** | Census of Fatal Occupational Injuries (CFOI) |
| **WHO** | Pesticide Safety (2022) |
| **ILO** | Safety and Health in Agriculture (2023) |

---

*最後更新：2026-03-09*
