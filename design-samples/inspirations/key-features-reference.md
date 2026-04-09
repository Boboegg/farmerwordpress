# Design Inspirations - 4 版本關鍵特色摘要

> Bobo 跟 Gemini/Codex 對話產出的 4 個視覺原型的精華對比，給未來 Codex/Gemini 派任時參考。完整原始 HTML 太大不全存，重點都在這。

## v1 — Nature Premium (明亮溫暖大地色)

### 色票（**錯誤的，不要用**）
```css
--bg: #FBFAF7;       /* 米白 */
--brand: #3D5A2C;    /* 軍綠（過深，應該用 #5C8607）*/
--accent: #A8740B;   /* 褐土（應該用 #D4A017）*/
```

### 值得抄的功能
1. **粒子飄落 canvas** — sunlit pollen 微粒緩慢上升，60 顆，半透明 brand/accent 雙色
2. **Manifesto pinned scroll** — 文字漸層橫掃效果（`background-position` 動畫），sticky 區
3. **3D Tilt Cards** — `perspective(1000px) rotateX/Y` + 滑鼠跟隨光斑
4. **Stats counter** — 數字從 0 跳動到目標值（GSAP `roundProps:textContent`）
5. **Magnetic buttons** — 滑鼠靠近時按鈕被吸引位移

### 不要抄
- ❌ `cursor: none` + 自訂 cursor → 中高齡看不到滑鼠
- ❌ 複雜 hover 互動 → 老人家會點不到

---

## v2 — Director Cut (暗黑電影感)

### 色票（**錯誤的，不要用**）
```css
--bg: #0e1116;       /* 近全黑 */
--brand: #69d08d;    /* 螢光綠 */
--accent: #f1b24b;   /* 金色 */
```

### 值得抄的功能（**重點**）
1. **Sticky 220vh 三幕分鏡 hero** — `position:sticky; top:0; height:100vh` + 220vh 容器，捲動時 hero 保持固定，內容隨 scroll 變化
2. **多層 plane parallax** — 4 層 plane（bg1 / bg2 / fog / beam）各自動畫
3. **Noise canvas** — `createImageData` 即時生成噪點，`mix-blend-mode: soft-light`
4. **Scanner beam** — 對角線高光帶，scroll 時橫掃整個 hero
5. **🔥 4 個 hotspot**（健康促進 / 職業安全 / 經濟保險 / 倡議研究）→ **比 v1/v4 都多一個，最貼近 farmerwordpress 七大主選單**
6. **Hotspot panel 帶 KPI + Action** — 點擊熱區後顯示「核心指標」+「建議動作」兩欄，**比純展示更實用**
7. **Scene cut overlay** — 區段切換時白光閃爍效果
8. **GSAP ScrollTrigger.batch** — 卡片批次入場動畫，效能更好

### 不要抄
- ❌ 全黑背景 → 跟既有 67 個淺色頁面風格分裂
- ❌ 從 jsdelivr 載 GSAP → 第三方 CDN

---

## v3 — Style Lab (4 主題切換器) ⭐ **架構主推**

### 色票
```css
/* 4 套主題用 .theme-xxx class 切換 */
.theme-cinematic { --brand: #3D5A2C; ... }
.theme-editorial { --brand: #1C3F73; ... }
.theme-interactive { --brand: #57D18B; ... }  /* 暗色 */
.theme-warm { --brand: #A05A2C; ... }
```

### 值得抄的功能（**最重要**）
1. **🔥 CSS 變數做 multi-theme 設計系統** — `:root` 定義 token，`.theme-xxx` 覆寫變數，**body class 切換立刻換主題**。這是 Bobo 整個 design system 的基礎
2. **🔥 CSS color-mix() 函式** — `color-mix(in oklab, var(--brand), transparent 75%)` 取代手算 rgba，乾淨優雅
3. **🔥 vanilla JS（無 GSAP）** — 自己用 IntersectionObserver + requestAnimationFrame 做 reveal/counter，**零第三方依賴**
4. **Reduced Motion UI toggle** — 使用者可以即時切換看效果，不用改系統設定
5. **Hero spotlight follow** — 滑鼠位置追蹤的 radial-gradient 光斑（用 CSS var 做）
6. **Tabs 篩選 + 6 卡片** — 健康/職安/保險三類分頁切換
7. **Container 1150px** — 比較緊湊
8. **Step 1-3 路徑時間軸** — meta 設計但結構清楚

### Bobo 的偏好
- 保留 **2 個 theme**（`.theme-light` 明亮溫暖 + `.theme-dark` 暗黑科技）
- 拋棄 editorial / warm 兩個方向
- **這是 Phase 1 的架構基礎**

---

## v4 — Apex Vision / Cyber-Agri Noir (全黑科技儀表板)

### 色票（**錯誤的，不要用**）
```css
--bg: #030705;       /* 全黑 */
--brand: #00e676;    /* 矩陣螢光綠 */
--accent: #f5a623;   /* 警示黃 */
```

### 值得抄的功能
1. **🔥 粒子網路 + 連線** — 80 顆粒子，鄰近 120px 自動連線，最複雜的 canvas 動畫（**Phase 3 視覺爆改可用**）
2. **載入動畫** — `INITIALIZING SYSTEM` 進度條，進入體驗前暖場
3. **雷達掃描** — 旋轉 conic-gradient（CSS-only），無需 JS
4. **Glass cards + glare** — `--gx/--gy` CSS var 做滑鼠跟隨光斑
5. **Hover 浮動 panel** — radar node hover 時右下角浮 panel 顯示「即時數據」
6. **Manifesto 動畫** — 跟 v1 類似但用 GSAP scrub
7. **Card icon block** — 48x48 圓角方塊放序號圖示

### 不要抄
- ❌ `cursor: none` + 自訂 cursor → 同 v1
- ❌ 「ENVIRONMENTAL TELEMETRY / COMPLIANCE 92% / LATENCY 12ms」**軍事 SaaS 語氣** → 跟農業計畫完全不搭
- ❌ 全黑背景 → 同 v2

---

## 📋 給 Codex 的派任檢查清單

當派 Codex 寫新的 design system / 視覺爆改時，**必須**：

### 從 v3 抄
- [ ] CSS 變數架構（`:root` + `.theme-light` / `.theme-dark`）
- [ ] CSS `color-mix()` 函式做半透明
- [ ] 純 vanilla JS（不要 GSAP CDN）
- [ ] IntersectionObserver-based reveal
- [ ] Reduced motion UI toggle

### 從 v2 抄
- [ ] **4 hotspot 結構**（不是 3 個）
- [ ] Sticky scrub hero 概念（但不要 220vh，太暈）
- [ ] Hotspot panel 帶 KPI + Action 兩欄
- [ ] 多層 plane parallax（簡化版）

### 從 v1 + v4 抄
- [ ] 粒子 canvas（**v4 的網路連線版**）
- [ ] Manifesto pinned scroll
- [ ] 3D Tilt cards（但 perspective 角度小一點）
- [ ] Stats counter

### **絕對不要抄**
- [ ] ❌ `cursor: none` + 自訂 cursor
- [ ] ❌ 第三方 CDN（GSAP from cloudflare/jsdelivr）
- [ ] ❌ 全黑背景（除非是 .theme-dark）
- [ ] ❌ 軍事 / SaaS 語氣文案
- [ ] ❌ 錯誤的品牌色

### **必須遵守 farmerwordpress AGENTS.md**
- [ ] 用 `#5C8607 / #E3E9D8 / #D4A017` 規範色
- [ ] HTML 用 `<!-- wp:html -->` 包裹
- [ ] 不用 `body{}/html{}/*{}` 全域選擇器
- [ ] CSS class 加 prefix 隔離（首頁用 `home-`）
- [ ] Noto Sans TC + Noto Serif TC 字型
- [ ] Font Awesome 6.4.x 圖示
- [ ] 響應式三層斷點
- [ ] 中高齡友善（內文 18px+）
