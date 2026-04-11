# 交接文件 — farmerwordpress V14 套用

日期：2026-04-11
狀態：V14 Happy Hues 風定案 + 記錄同步完成，等套用到生產

---

## 🎯 目標

把 V14 Happy Hues 風設計系統套用到生產環境的 24 個頁面。V14 預覽檔已拍板，現在要改 `global.css` tokens + 24 個 page HTML wrapper，然後部署到 Hostinger。

---

## ✅ 已完成（Phase 1：決策+同步）

### V14 設計規範（Bobo 已拍板「就這版 漂亮！」）
- **每頁獨立 palette**（不繼承父頁）— 24 頁各自配色
- **Happy Hues 精準尺寸**：標題 32px / 內文 19.2px = 1.67 倍差
- **line-height 對比**：標題 36px 壓扁、內文 34.56px 放開
- **border-radius 3px 方角**（不是 12-16 圓潤）
- **按鈕無 box-shadow**
- **Highlight 回歸語義**：`<a>` + underline，不當 badge/tag
- **橘黃電紫霓虹全解放**（打破 V1 冷色禁忌）

### 同步的檔案
- ✅ `~/.claude/projects/-Users-boboegg/memory/reference_dignity_farming_v14_happy_hues.md`（新增）
- ✅ `~/.claude/projects/-Users-boboegg/memory/MEMORY.md`（索引更新，標記 V1 廢棄）
- ✅ `~/vault/Projects/P8-尊嚴農業/status.md`（V14 決策加在頂部）
- ✅ `~/farmerwordpress/docs/design-system.md`（V14 版全改寫）
- ✅ `~/farmerwordpress/HANDOFF.md`（此檔）
- ✅ `~/farmerwordpress/test/palette-v14.html`（定案預覽檔）
- ✅ Git commit `690de88` + push 到 GitHub

---

## 🔨 下一步（Phase 2：套用到生產）

### Step 1：改寫 `~/farmerwordpress/css/global.css`

**目前狀況**：global.css 約 2700 行，含 V1 暖米 tokens、V6 嘗試殘留、舊 ch-* wrapper 規則。

**要做的事**：
1. 在 `:root` 新增 V14 共用 tokens：
   ```css
   :root {
     /* V14 字級系統 */
     --fs-h1: 56px;
     --fs-h2: 32px;
     --fs-body: 19.2px;
     --fs-button: 17.6px;
     --fs-tag: 12px;

     /* V14 行高系統 */
     --lh-h1: 64.4px;
     --lh-h2: 36px;
     --lh-body: 34.56px;

     /* V14 共用按鈕/卡片 */
     --radius: 3px;
     --card-padding: 40px;
     --btn-padding: 19.2px 32px;
   }
   ```

2. 建立 24 個 `.fw-page.p-*` scoped palette（每個定義 --bg, --headline, --paragraph, --button, --button-text, --highlight, --tag-bg, --tag-text, --line），色值完整清單在 `docs/design-system.md` 第 4 節。

3. 建立 V14 共用 utility classes：
   ```css
   .fw-page .scene { padding: 40px; background: var(--bg); border-radius: 3px; box-shadow: none; border: 1px solid var(--line); }
   .fw-page .scene h2 { font-size: 32px; font-weight: 900; line-height: 36px; color: var(--headline); margin: 0 0 16px; }
   .fw-page .scene .body { font-size: 19.2px; line-height: 34.56px; color: var(--paragraph); margin: 0 0 40px; }
   .fw-page .scene .body a { color: var(--highlight); text-decoration: underline; text-decoration-thickness: 2px; font-weight: 700; }
   .fw-page .btn-primary { padding: 19.2px 32px; font-size: 17.6px; font-weight: 700; line-height: 1; border-radius: 3px; background: var(--button); color: var(--button-text); box-shadow: none; border: 0; }
   .fw-page .btn-ghost { padding: 17.2px 30px; font-size: 17.6px; font-weight: 700; line-height: 1; border-radius: 3px; background: transparent; color: var(--headline); border: 2px solid var(--headline); }
   .fw-page .scene-tag { font-size: 12px; font-weight: 900; letter-spacing: 0.15em; padding: 6px 14px; background: var(--tag-bg); color: var(--tag-text); border-radius: 3px; }
   ```

4. **保留**：全站 header/footer/sidebar 的 Astra 覆蓋規則、字體載入、prefers-reduced-motion、a11y 規則。

5. **刪除**：舊的 V1 暖米特定 tokens（`--brand`, `--accent`, `--color-brand-*`, `--ch-*-bg` 等），以及之前 V6 嘗試留下的 `.fw-page.ch-*` 覆蓋規則。

**⚠️ 風險**：現有的 12 個 healthgood/occupational-safety/economic-insurance 子頁和主題頁用的是 `ch-*` class，舊 class 不能一次全刪，否則未改的頁面會裸奔。**建議策略**：舊 ch-* 規則保留做 fallback，新頁面用 p-*，改完一頁就切一頁。

### Step 2：改 24 個 page HTML 的 wrapper class + 按鈕結構

**每個 page HTML 的改動**：

1. **外層 wrapper class**：從 `<div class="fw-page dc-v3 ch-health">` 改成 `<div class="fw-page p-health">`（或加 class 不刪舊，等遷移完再清）

2. **Hero 區結構**：內容不動，但按鈕換成新結構：
   ```html
   <!-- 舊 -->
   <a class="hp-btn-primary" href="...">健康速篩</a>

   <!-- 新 -->
   <a class="btn-primary" href="...">健康速篩</a>
   ```

3. **內容內的 `<a>` 連結**：繼承 `.body a` 樣式自動有 highlight 底色。

4. **⚠️ 文字一字不改** — 遵守 content preservation 鐵律（memory `feedback_content_preservation.md`）。

**24 個檔案路徑**：
```
pages/About/關於我們.html
pages/occupational-safety/職業安全.html
pages/healthgood/健康促進.html
pages/economic-insurance/經濟與保險.html
pages/farmer-study/農學堂.html
pages/dignity-farming-initiative/倡議.html
pages/research-result/研究成果.html
pages/thenews/最新消息.html
pages/healthgood/subpage-heat/subpage-heat
pages/healthgood/subpage-msd/subpage-msd
pages/healthgood/subpage-pesticide/subpage-pesticide
pages/healthgood/subpage-mental/subpage-mental
pages/occupational-safety/personal-protective-equipment/個人防護具
pages/occupational-safety/labor-saving-machinery/省工農機具
pages/occupational-safety/greenhouse-zone/溫室專區
pages/economic-insurance/occupational-accident-insurance/職業災害保險
pages/economic-insurance/crop-insurance/農業保險
pages/economic-insurance/agricultural-subsidy-resources/農業補助資源
pages/economic-insurance/worker-occupational-accident-calculator/農民職業災害試算表
pages/economic-insurance/farmer-worker-retirement-calculator/農民退休金試算表
pages/research-result/research-publication/研究出版.html
pages/research-result/download-zone/下載專區.html
pages/research-result/related-resources/相關資源.html
pages/research-result/podcast/PODCAST.html
```

**Page key → 檔案對照**：
| p-key | 檔案 |
|-------|------|
| `p-about` | pages/About/關於我們.html |
| `p-safety` | pages/occupational-safety/職業安全.html |
| `p-health` | pages/healthgood/健康促進.html |
| `p-economy` | pages/economic-insurance/經濟與保險.html |
| `p-study` | pages/farmer-study/農學堂.html |
| `p-advocacy` | pages/dignity-farming-initiative/倡議.html |
| `p-research` | pages/research-result/研究成果.html |
| `p-news` | pages/thenews/最新消息.html |
| `p-heat` | pages/healthgood/subpage-heat/subpage-heat |
| `p-msd` | pages/healthgood/subpage-msd/subpage-msd |
| `p-pesticide` | pages/healthgood/subpage-pesticide/subpage-pesticide |
| `p-mental` | pages/healthgood/subpage-mental/subpage-mental |
| `p-ppe` | pages/occupational-safety/personal-protective-equipment/個人防護具 |
| `p-machinery` | pages/occupational-safety/labor-saving-machinery/省工農機具 |
| `p-greenhouse` | pages/occupational-safety/greenhouse-zone/溫室專區 |
| `p-occ` | pages/economic-insurance/occupational-accident-insurance/職業災害保險 |
| `p-crop` | pages/economic-insurance/crop-insurance/農業保險 |
| `p-subsidy` | pages/economic-insurance/agricultural-subsidy-resources/農業補助資源 |
| `p-calc1` | pages/economic-insurance/worker-occupational-accident-calculator/農民職業災害試算表 |
| `p-calc2` | pages/economic-insurance/farmer-worker-retirement-calculator/農民退休金試算表 |
| `p-pub` | pages/research-result/research-publication/研究出版.html |
| `p-dl` | pages/research-result/download-zone/下載專區.html |
| `p-rel` | pages/research-result/related-resources/相關資源.html |
| `p-pod` | pages/research-result/podcast/PODCAST.html |

### Step 3：部署 + 驗證

```bash
cd ~/farmerwordpress
git add css/global.css pages/
git commit -m "feat(v14): 套用 Happy Hues palette 到 24 頁"
git push origin main
# GitHub Actions 自動部署到 Hostinger（約 57 秒）
gh run list --limit 1  # 確認部署成功
```

驗證：
1. 用 Playwright 訪問 https://fwdignity.com/healthgood/ 檢查主題色是否為薄荷綠
2. 每個主題頁抽查 1-2 個
3. 若主題色沒生效 → 檢查 cache（Hostinger LiteSpeed / CDN）

---

## ⚠️ 遇到的問題（吸取教訓）

### 什麼方法有效
1. **去實際看參考網站**（Happy Hues）比自己想像有效 — 用 Chrome DevTools MCP 抓真實色值和 computed styles
2. **Python 驗證每個色**的對比度，不信任 LLM 宣稱的數字（Gemini 曾經灌水對比度到 7+，實際只有 1.8）
3. **並行派 agent 做獨立分析** — 10 專家辯論、門下省審查、thesis challenge 都有貢獻
4. **完整 Happy Hues palette（5-6 色）比單主題色更豐富** — 但 demo 不能全部塞滿變成 dashboard

### 什麼方法沒用
1. ❌ **困在 WCAG AA 4.5:1** — 讓我只敢用深色，所有版本都太暗
2. ❌ **白字按鈕思維** — 所有鮮豔色都過不了白字對比 → 我躲開橘黃
3. ❌ **單主題色設計** — V11 只用 1 色太平淡
4. ❌ **塞滿 accent** — V12 把 Happy Hues 5 色全用大，變 dashboard

### 突破點
**Happy Hues 的關鍵**：按鈕 bg 用 vivid 色 + 按鈕文字用**深色帶色調**（不是白字）。這樣 vivid 橘/黃/電紫全部可用。文字在黃底上是深色，對比反而 AAA 12+。

### 尺寸才是對比之王
**Happy Hues 強對比的秘密不是色彩，是尺寸**：
- 標題 32px vs 內文 19.2px = **1.67 倍差**（不是 1.2-1.4 倍）
- 標題 line-height **0.94-1.1 壓扁**（壓成視覺錘子）
- 內文 line-height 1.8 超開
- border-radius **3px 方角**（不是圓潤）
- 按鈕**無 box-shadow**（純色塊）

---

## 📂 關鍵檔案（新對話必讀）

### 必讀
1. **`~/farmerwordpress/test/palette-v14.html`** — 定案的視覺設計（Bobo 拍板）
2. **`~/farmerwordpress/docs/design-system.md`** — V14 完整規範（含 24 頁色值表）
3. **`~/farmerwordpress/HANDOFF.md`** — 本檔
4. **`~/.claude/projects/-Users-boboegg/memory/reference_dignity_farming_v14_happy_hues.md`** — V14 Memory（含 CSS 範本）

### 要改的檔案
5. **`~/farmerwordpress/css/global.css`** — 2700 行要改
6. **`~/farmerwordpress/pages/*/`** — 24 個 page HTML

### 參考
7. **`~/farmerwordpress/scripts/page-map.json`** — page → WP ID 對應
8. **`~/farmerwordpress/scripts/deploy.sh`** — 部署腳本
9. **`~/farmerwordpress/.github/workflows/deploy.yml`** — CI/CD

### 鐵律 memory
10. **`~/.claude/projects/-Users-boboegg/memory/feedback_content_preservation.md`** — 禁止改文字
11. **`~/.claude/projects/-Users-boboegg/memory/feedback_farmerwordpress_deployment.md`** — 部署風險

---

## 🚀 快速開工指令

```bash
# 1. 拉最新 code
cd ~/farmerwordpress
git pull origin main

# 2. 確認最新 commit 是 V14 定案
git log --oneline -3
# 應該看到 690de88 feat(design): V14 Happy Hues 風定案

# 3. 打開 V14 預覽
open test/palette-v14.html

# 4. 讀規範
cat docs/design-system.md | head -100

# 5. 開始改 global.css
# （建議用 agent 並行處理 24 個 HTML，自己改 global.css）
```

---

## 💡 新對話開場建議

新對話直接告訴它：
> 「繼續 Phase 2 V14 套用。先讀 `~/farmerwordpress/HANDOFF.md` 和 `~/farmerwordpress/docs/design-system.md`。V14 已定案，現在要：(1) 改 global.css 加入 24 個 p-* palette scoped tokens + V14 共用 utility classes；(2) 改 24 個 page HTML 的 wrapper class 和按鈕結構。文字一字不改。做完 commit push 等自動部署。」

---

## 📋 Phase 2 執行順序建議

1. 先改 global.css（30 分鐘）→ commit 但不 push
2. 再開 agent 並行改 24 個 page HTML（每 3-4 個一組，並行 3 個 agent）
3. 全部改完後本地檢查 `test/palette-v14.html` 結構跟 global.css 一致
4. 一次 push 觸發部署
5. Playwright 驗證 live 每頁

**預估時間**：60-90 分鐘持續作業
