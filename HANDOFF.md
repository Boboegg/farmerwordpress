# 交接文件 — farmerwordpress

最後更新：2026-04-09
目前分支：main
目前最新 commit：696ee36a80b4b6f91aad914ca9b95ac4f18031dd

---

## 本輪已完成（可直接視為桌機版第一輪完成）

### 1) 設計系統整合
- `css/global.css`：設計 token 與相容層收斂，舊 alias 保留，對齊現行稻穗熟成配色。
- `docs/design-system.md`：新增設計系統說明文件。
- `README.md`：品牌快查與設計系統入口同步更新。
- `docs/architecture.md`：補上 IA 與 design token 的權威來源分工。

### 2) 視覺與互動（桌機優先）
- 首頁 L3 互動層已上線（不改文案）。
- 三個分眾頁已接上共用互動：青農、資深農友、一般民眾。
- 七個主章節頁已接上共用互動：關於我們、職業安全、健康促進、經濟與保險、尊嚴農業倡議、研究成果、農學堂。

### 3) 新增/更新的互動資產
- `assets/js/dc-v3-audience.js`：分眾頁共用互動。
- `assets/js/dc-v3-sections.js`：主章節頁共用互動。
- `assets/js/dc-v3-start.js`：首頁互動腳本（既有檔，持續使用）。

### 4) Git 狀態
- 本地工作樹乾淨（無未提交變更）。
- 已成功 push 到遠端 `origin/main`。

---

## 本輪核心原則（已落地）

- 文案保持原樣，不做內容改寫。
- 先完成桌機視覺與互動，再做行動端精修。
- 互動採漸進增強，保留 `prefers-reduced-motion` 降級。
- 主要互動以本地 GSAP/ScrollTrigger 檔案載入。

---

## 下一步（你重開 VS 後可直接接）

1. 行動端優化（最高優先）
- 針對首頁、三分眾頁、七章節頁做手機與平板版面調整。
- 針對觸控裝置隔離 hover 行為，避免殘留。

2. 驗收清單
- 手機 Safari/Chrome 檢查：首屏高度、按鈕可點擊區、文字可讀性。
- 動畫負載檢查：低階裝置與 reduced-motion 是否正常降級。
- WordPress 貼上後檢查：程式碼模式與前台顯示一致。

3. 若要上線
- 走既有部署流程：`bash scripts/deploy.sh`。

---

## 本輪重點檔案

- `css/global.css`
- `docs/design-system.md`
- `docs/architecture.md`
- `README.md`
- `assets/js/dc-v3-start.js`
- `assets/js/dc-v3-audience.js`
- `assets/js/dc-v3-sections.js`
- `pages/home/index.html`
- `pages/young-farmers/青農.html`
- `pages/experienced-farmers/資深農友.html`
- `pages/Public/一般民眾.html`
- `pages/About/關於我們.html`
- `pages/occupational-safety/職業安全.html`
- `pages/healthgood/健康促進.html`
- `pages/economic-insurance/經濟與保險.html`
- `pages/dignity-farming-initiative/倡議.html`
- `pages/research-result/研究成果.html`
- `pages/farmer-study/農學堂.html`

---

## 快速開工提示

重開 VS 後先做兩件事：

1. `git pull origin main`
2. 直接開始「行動端優化」批次（首頁 -> 分眾 -> 主章節）
