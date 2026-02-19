# 尊嚴農業網站：系統與介面建置藍圖

> **計畫全名**：農業尊嚴：共建雲嘉農業工作者職業健康社會安全網
> **本文件用途**：作為所有前端開發與介面設計的唯一參考依據，AI 協作工具（如 Claude）應在開始任何頁面建置前先閱讀本文件。

---

## 行政摘要

本網站捨棄傳統政令宣導式的條列網頁，採用**矩陣式資訊架構（Matrix Information Architecture）**，定位為動態的「農業健康與安全知識樞紐（Knowledge Hub）」。

前端透過**四大分眾入口（橫軸）**針對不同生命週期的農民進行精準內容策展；後端則建構**七大資料庫主選單（縱軸）**，確保知識儲存的系統性與學術嚴謹度。

整體網站視覺應用「功能性柔和色系（Functional Pastels）」，以降低視覺疲勞並提升資訊可讀性。所有文獻與數據呈現嚴格遵守 **APA 格式第 7 版**，並系統性排除中國大陸來源數據，以台灣本土實證數據及日、韓等具備相似高齡農業背景之研究為學術基石。

---

## 品牌設計規格

| 項目 | 數值 |
|------|------|
| 品牌主色 | `#5C8607` |
| 網站背景色 | `#E3E9D8` |
| 字型（中文） | Noto Sans TC |
| 字型（英數） | Lato |
| 字型（內文） | Lora（部分頁面） |
| 圖示庫 | Font Awesome 6.4.x |
| 視覺風格 | 功能性柔和色系（Functional Pastels） |
| APA 規範 | 第 7 版，禁止引用中國大陸來源 |

---

## 整體架構：矩陣式 (橫軸 × 縱軸)

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

## 一、首頁：分流大廳（The Lobby）

首頁設計原則：訪客不會看到傳統「最新消息」文字牆或無意義輪播圖，而是直接面對**四大分眾入口卡片**，第一時間降低認知負荷。

- **頂部**：全域導覽列（Global Navigation，含七大資料庫選單）
- **中央**：四個帶有專屬功能性柔和色系的大型點擊區塊

---

### 橫軸 1：新手務農（Start）— 防禦包

| 規格 | 說明 |
|------|------|
| 主色調 | 鼠尾草綠（Sage Green） |
| 介面模式 | 檢核清單（Checklist）+ 進度條 |
| 版型 | 左側固定導覽步驟，右側動態表單 |

**功能亮點**：首屏強制顯示「務農風險快篩表」。使用者勾選預計種植的作物與操作機具後，畫面下方即時吐出對應的農藥防護標準與基礎防護具（PPE）網格型錄。

---

### 橫軸 2：青農創業（Pro）— 升級包

| 規格 | 說明 |
|------|------|
| 主色調 | 板岩藍（Slate Blue） |
| 介面模式 | 儀表板（Dashboard）+ 比較圖表 |
| 版型 | 左側省工農機投資報酬率圖表，右側保險試算器 |

**功能亮點**：左側展示「省工農機」投資報酬率與降低勞動損耗的量化圖表；右側直接內嵌「農民退休儲金 vs. 勞保」高階比較試算器。訴求財務與產能的精準計算。

---

### 橫軸 3：資深農友（Senior）— 樂活包

| 規格 | 說明 |
|------|------|
| 主色調 | 陶土橘（Terracotta） |
| 介面模式 | Mobile-First + App-like Interface |
| 最小字體 | 18px 以上 |
| 最小按鈕高度 | 48px 觸控熱區 |

**功能亮點**：網頁兩側留白（模擬手機螢幕寬度）。螢幕最下方鎖定固定式導航列（Bottom Navigation），三個核心大圖示：
- 找醫師（地圖）
- 算津貼（計算機）
- 看影片（微學習）

---

### 橫軸 4：一般公眾（Public）— 認同包

| 規格 | 說明 |
|------|------|
| 主色調 | 麥穗黃（Harvest Gold） |
| 介面模式 | 敘事性滾動（Scrollytelling） |
| 視覺素材 | 滿版高畫質雲嘉農鄉紀實攝影 |

**功能亮點**：隨滑鼠向下滾動，浮現雲嘉農業職災熱力圖、高齡化數據資訊圖表（Infographics），並交錯嵌入 Podcast 播放器，直接播放農民訪談錄音，建立公眾倡議共感。

---

## 二、七大主選單：核心資料庫（縱軸）

學術研究者、政策制定者可不透過分流入口，直接從頂部導覽列進入七大結構化資料庫。

---

### 01 關於計畫（About）

- **介面類型**：靜態資訊頁面 + 多媒體展示
- **CSS 樣式**：使用全站 `.my-team-card` 展示中正大學跨域研究團隊（透明懸浮卡片）
- **地圖模組**：嵌入互動式地圖，標示民雄等重點實踐場域之農業人口數據

---

### 02 職業安全（Occupational Safety）— `/prevention`

- **介面類型**：網格卡片（Grid Cards）數位型錄 + 互動式工具

**頁面結構（建置優先順序）**：

```
1. Hero 區塊（頁面標題 + 一句話說明）
2. Tabs 切換：
   ├── [主動降載・省工農機]
   └── [被動防禦・個人防護具 PPE]
3. Grid Cards 型錄（每卡含 PDF 一頁圖卡下載）
4. 風險評估工具區：
   ├── 紅綠燈快篩系統（Web Form）
   ├── 安全控制層級動態圖（Hierarchy of Controls，NIOSH 5 層）
   └── 四大危害深度檢核（生物・物理・化學・人因）
```

> **注意**：型錄 Grid Cards 為頁面主體，風險評估工具為頁面下半部的延伸學習區，不可反客為主。

---

### 03 健康促進（Health Promotion）— `/healthgood`

- **介面類型**：地理資訊整合 + 影音資料庫
- **首屏核心**：互動式人體圖（游標點擊腰/膝/肩 → Modal 播放復健運動處方 GIF 或短影音，1-3 分鐘）
- **職醫地圖**：串接 Google Maps API，標示勞動部認可「職業傷病診治網絡醫院」及在地合作診所，提供一鍵導航
- **其他模組**：整合即時氣象資料顯示高溫警報；提供可下載列印的急救紅卡

---

### 04 經濟與保險（Economic Security）— `/money`

- **介面類型**：資訊圖解（Infographics）+ 互動試算工具（Web Form）
- **核心工具**：農民輸入年齡、年資、傷病類型後，系統輸出可申請的補助或理賠估算
- **涵蓋險種**：職災保險、農保、老農津貼、農業保險
- **法規呈現**：複雜條文轉化為步驟懶人包（Step-by-step guide），以流程圖折疊於試算器下方

---

### 05 培力與實踐（Empowerment）— `/農學堂`

- **介面類型**：輕量級 LMS + 活動行事曆
- **農學堂**：影片網格（Video Grid），每支微學習影片標示時長與難易度
- **工作坊**：清單式（List View）呈現雲嘉近期線下課程，附帶內建報名表單

---

### 06 尊嚴農業倡議（Advocacy）— `/尊嚴農業倡議`

- **介面類型**：單頁式（One-page）深度專題報導
- **版型核心**：垂直時間軸（Vertical Timeline）串接倡議行動與焦點團體會議紀錄
- **Call to Action**：頁面底部設置政策連署或社群分享按鈕

---

### 07 研究成果（Research）— `/研究成果`

- **介面類型**：學術資料庫 + 文獻列表介面
- **APA 規範**：所有條目嚴格以 APA 第 7 版格式排版，**禁止引用任何中國大陸文獻與數據**
- **下載按鈕**：每筆文獻附 `[PDF 下載, X.X MB]` 按鈕，標示檔案大小
- **影音成果**：整合 Spotify / Apple Podcast 嵌入代碼，支援網頁內直接收聽

---

## 三、技術規範

### 3-1 色彩系統（Color System）

#### 全站品牌色

| 名稱 | HEX | 用途 |
|------|-----|------|
| 品牌主色・尊嚴綠 | `#5C8607` | 全站主按鈕、連結、強調色 |
| 品牌主色・深尊嚴綠（hover） | `#4a6b05` | 按鈕 hover 狀態 |
| 尊嚴綠（深版） | `#2c5e2e` | 側邊欄標題、tag 按鈕 hover |
| 全站背景 | `#E3E9D8` | 整體頁面底色 |
| 全站輔色・稻金黃 | `#D4A017` | 圖示點綴、裝飾線、強調 highlight |

#### 四大分眾入口色（Functional Pastels）

> 以下 HEX 為建議值，部署前請與設計師確認。

| 分眾 | 色彩名稱 | 建議 HEX | 色彩背景 HEX | 說明 |
|------|---------|----------|------------|------|
| 新手務農（Start） | 鼠尾草綠 Sage Green | `#87AE73` | `#EEF5EA` | 穩健、安全感 |
| 青農創業（Pro） | 板岩藍 Slate Blue | `#5B7FA6` | `#E8EFF7` | 科技、數據感 |
| 資深農友（Senior） | 陶土橘 Terracotta | `#C17F5E` | `#F9EDE7` | 溫暖、高對比 |
| 一般公眾（Public） | 麥穗黃 Harvest Gold | `#D4A017` | `#FDF4DC` | 農鄉、共感 |

#### 職業安全頁面（02）危害控制層級色

| 層級 | 名稱 | 漸層色 |
|------|------|--------|
| Level 1 消除 | Emerald | `#10b981 → #34d399` |
| Level 2 取代 | Sky Blue | `#0ea5e9 → #38bdf8` |
| Level 3 工程 | Blue | `#3b82f6 → #60a5fa` |
| Level 4 行政 | Amber | `#f59e0b → #fbbf24` |
| Level 5 PPE | Rose | `#f43f5e → #fb7185` |

#### 風險評估紅綠燈色

| 風險等級 | 顏色 | HEX |
|---------|------|-----|
| 高度風險（分數 ≥ 8） | 紅 | `#EF5350` |
| 中度風險（分數 3–7） | 橘 | `#FFB74D` |
| 低度風險（分數 ≤ 2） | 綠 | `#81C784` |

---

### 3-2 CSS 設計 Token（`:root` 變數）

以下為各 Widget 已採用的 CSS 變數，全站保持一致，新頁面應優先使用這些變數而非直接寫死色碼。

```css
:root {
    /* === 品牌色 === */
    --astra-brand:       #5C8607;
    --astra-brand-hover: #4a6b05;
    --astra-text:        #343F1E;
    --astra-surface:     #FDFAF1;   /* 頁面內容區底色 */
    --astra-border:      rgba(53, 64, 31, 0.15);
    --astra-shadow:      0 15px 40px rgba(52, 63, 30, 0.05);

    /* === 職業安全 Widget 專用 === */
    --text-brand:        #047857;   /* Widget 1 強調綠 */
    --bg-glass:          rgba(255, 255, 255, 0.65);
    --border-glass:      rgba(255, 255, 255, 0.9);
    --shadow-glass:      0 8px 32px 0 rgba(31, 38, 135, 0.05);
    --shadow-float:      0 10px 25px -5px rgba(0,0,0,0.1);

    /* === 危害分類色 === */
    --phys-bg:   #FFF3E0; --phys-text:  #E65100;  /* 物理性 */
    --chem-bg:   #F3E5F5; --chem-text:  #7B1FA2;  /* 化學性 */
    --bio-bg:    #E8F5E9; --bio-text:   #2E7D32;  /* 生物性 */
    --ergo-bg:   #E3F2FD; --ergo-text:  #1565C0;  /* 人因性 */

    /* === 風險評估紅綠燈 === */
    --risk-green:  #81C784;
    --risk-orange: #FFB74D;
    --risk-red:    #EF5350;
}
```

---

### 3-3 全站 CSS（`/css/global.css`）已定義的 Class

> 建置新頁面前，請先確認使用或避開以下已定義的 class，以免產生樣式衝突。

| Class | 用途 |
|-------|------|
| `.content-card` | 一般內容卡片（含 hover 上浮效果，border-top 尊嚴綠） |
| `.my-team-card` | 研究團隊透明懸浮卡片（背景強制透明） |
| `.sidebar-wrapper` | Sticky 側邊欄容器（top: 120px） |
| `.sidebar-section` | 側邊欄區塊 |
| `.sidebar-title` | 側邊欄標題（含右側延伸線） |
| `.side-widget` | 側邊欄小工具外框 |
| `.side-title` | 側邊欄標題（帶 border-bottom） |
| `.tool-list` / `.tool-link` / `.tool-icon` | 側邊欄工具連結組 |
| `.tag-cloud` / `.tag-btn` | 關鍵字雲（hover 變尊嚴綠底色） |
| `.action-item` / `.action-icon` | 行動設計清單項目 |
| `.social-btn` （`.fb` / `.line`） | FB / LINE 社群按鈕（漸層色） |
| `.link-grid` / `.link-item` | 友善連結格狀排版 |
| `.sticky-sidebar-container` | 黏性側邊欄外容器（top: 100px） |
| `.download-icon` | 下載圖示（側邊欄用） |

---

### 3-4 已部署頁面的 CSS Class 清單

以下 Class 已在 `pages/home/index.html` 部署，**新頁面避免重複命名**。

#### 首頁・身分導航（`service-portal-*`）

`.service-portal-wrapper` / `.service-portal` / `.portal-header` / `.portal-grid` /
`.role-btn` / `.role-icon-box` / `.role-desc` / `.portal-footer` / `.report-btn`

#### 首頁・三大核心主題（`topic-*`）

`.topic-nav-wrapper` / `.topic-header` / `.topic-container` / `.topic-card` /
`.card-health` / `.card-equip` / `.card-insurance` / `.card-content` /
`.topic-icon` / `.topic-text` / `.card-arrow`

---

### 3-5 WordPress 嵌入規範（違反項目）

當 HTML 區塊嵌入 WordPress 頁面時，**禁止**在 `<style>` 中出現：

| 禁止項目 | 原因 |
|---------|------|
| `body { ... }` | 覆蓋 Astra 主題全站樣式（包含 `display:flex` 會毀版型） |
| `* { margin:0; padding:0; }` | 全域重置破壞 WordPress 所有元件間距 |
| `.container { ... }` | 與 Astra / Bootstrap 保留 class 衝突 |
| `html { ... }` | 影響全域 scroll behavior 等設定 |

**解法**：
- `body` 的 `padding` → 移到頁面最外層 wrapper
- `body` 的 `display:flex` 居中 → 改用 `margin: 0 auto` 在 wrapper 上
- `.container` → 改為 `.risk-assessment-wrapper`、`.hazard-check-wrapper` 等專屬命名

---

### 3-6 字型載入規則

Font Awesome 與 Google Fonts 每個頁面**只載入一次**，放在所有 widget HTML 的最頂端。

```html
<!-- 字型（每頁僅此一處） -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700;900&family=Lato:wght@400;700;900&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<!-- 圖示庫（每頁僅此一處） -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
```

---

### 頁面檔案位置

| 頁面 | 路徑 |
|------|------|
| 首頁 | `/pages/home/index.html` |
| 職業安全 | `/pages/prevention/index.html` |
| 健康促進 | `/pages/healthgood/` |
| 經濟保險 | `/pages/money/` |
| 農學堂 | `/pages/農學堂/` |
| 尊嚴農業倡議 | `/pages/尊嚴農業倡議/` |
| 研究成果 | `/pages/研究成果/` |

---

## 四、開發優先順序建議

```
Phase 1（已完成）
  ✅ 首頁：身分導航 + 三大核心主題

Phase 2（進行中）
  🔲 /prevention：重建完整頁面結構（Hero → Tabs → Grid Cards → 工具）

Phase 3（待啟動）
  🔲 /healthgood：互動式人體圖 + 職醫地圖
  🔲 /money：試算器（需確認法規數值）

Phase 4
  🔲 各分眾入口頁面（Start / Pro / Senior / Public）
  🔲 農學堂 LMS
  🔲 研究成果資料庫
```

---

*最後更新：2026-02-19*
