# Design Inspirations

Bobo 在 2026-04-08 ~ 04-09 期間透過 Gemini / Codex 對話產出的 4 個 farmerwordpress 視覺原型 demo。**全部都不是直接可上線的成品**，是給未來開發團隊（含 Codex / Gemini）參考用的「靈感原型庫」。

## 4 個版本

| 檔名 | 主題 | 特色 | 狀態 |
|---|---|---|---|
| `01-nature-premium.html` | 明亮溫暖大地色 | 粒子飄落、manifesto pinned scroll、3D tilt cards、自訂 cursor | 🟡 色彩偏 |
| `02-director-cut.html` | 暗黑電影感 | sticky 220vh hero、多層 plane parallax、4 hotspot、scene cut overlay | 🟡 色彩偏 |
| `03-style-lab.html` | **4 主題切換** | CSS 變數架構、color-mix() 現代語法、reduced motion toggle、tabs 篩選 | ⭐ **架構最值得參考** |
| `04-apex-vision.html` | 全黑科技儀表板 | 載入動畫、粒子網路連線、雷達掃描、glass cards、glare effect | ❌ 語氣失準（太 SaaS） |

## 共同問題（4 版都需要改造才能上線）

- ❌ 品牌色都不對（沒用 `#5C8607 / #E3E9D8 / #D4A017`）
- ❌ 不是 WordPress 區塊（沒 `<!-- wp:html -->` 包裹）
- ❌ CSS 用 `body{}/html{}/*{}` 全域選擇器
- ❌ 沒有 CSS prefix 隔離（會跟現有 67 個頁面衝突）
- ❌ 第三方 CDN 依賴（GSAP from cloudflare/jsdelivr）
- ❌ 內容是 placeholder

## Bobo 的決策（2026-04-09）

- **保留 1-2 個主題**（明亮溫暖 + 暗黑科技），不要 4 個
- **主推明亮溫暖**，暗黑當第二選項
- **視覺豐富度需要比這 4 版更高**
- 採用 v3 (Style Lab) 的 **CSS 變數架構**
- 採用 v2 (Director Cut) 的 **4 hotspot** 結構
- 採用 v4 (Apex Vision) 的 **粒子網路連線**（但不要 cursor:none）
- 拋棄 v1 (Nature Premium) 跟 v4 的科技 SaaS 語氣

## 用途

當 Codex 或 Gemini 被派任去刻新前端元件時，可以讀這 4 個檔案當靈感來源——但**絕對不要直接複製貼上**，要根據 `farmerwordpress/AGENTS.md` 的品牌規範改造後才能用。

```bash
# 從 Claude Code 派任時可以這樣引用：
/codex:rescue 參考 design-samples/inspirations/03-style-lab.html 的 CSS 變數架構，
              寫一個新的 css/global.css，但用 AGENTS.md 規範的品牌色
```
