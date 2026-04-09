# 交接文件 — farmerwordpress

**最後更新**：2026-04-09 晚間
**上次 session**：2026-04-09 長對話（P0 → P0.5 → P1 pilot 三次翻車才成功）

---

## 🎯 當前狀態（下次接手必讀）

### P0 ✅ 完成（CSS bug 修正）
- `.fw-page body` 無效 selector 修為 `.fw-page` 本體（commit `dcf41f3`）
- `:focus-visible` 從 box-shadow-only 升級為 outline + box-shadow + forced-colors

### P0.5 ✅ 完成（L3 地基）
- `@layer` skeleton：`base, tokens, legacy, ripening, pages, dc-v3`
- GSAP 3.12.5 本地 bundle（`assets/js/gsap.min.js` + `ScrollTrigger.min.js`）
- deploy.sh 新增 `assets/js/` 部署步驟 `[1/4]~[4/4]`
- `pages/home/index.new.html` → rename 為 `.deprecated`
- `.gitignore` 加 `node_modules/`

### P1 ✅ 完成（新手務農 pilot，連打 3 次才 OK）
- commit `f7f76f7`：初版 L3 Director Cut（7/8 元素）
- commit `85720e1`：修 hero 空白（把 JS 移到外部檔 `assets/js/dc-v3-start.js`）
- commit `327fa93`：刪 dc-reveal 概念 + 縮 hero-wrap 120vh（終於所有內容可見）
- **線上驗證**：hero 可見、stats/scenarios/about/cta/emergency/explore 全部可見
- **計數器**：目前顯示 0，可能 ScrollTrigger 觸發問題，待 Bobo 認可後再優化

---

## ⏭️ 下一步（按 v2 順序）

| Phase | 範圍 | 做法 |
|---|---|---|
| **P3** | 其他 3 分眾頁（青農 / 資深農友 / 一般民眾） | 套 P1 新手務農模板，只改色 token |
| **P2** | 首頁 L3 Director Cut 重寫 | 用 `director-cut-dignity` skill 的 L3 範本 |
| **P4** | 主題 3 頁（健康 / 職安 / 經保） | S3 Interactive Story / S5 Warm Tech |
| **P5** | 倡議頁 | S1 Cinematic + S8 Bold |
| **P6** | 研究 6 頁 + 最新消息 + 關於我們 + 農學堂 | S2 Editorial Prestige |

**P1 驗收到 100% 前不要開 P3**。Bobo 還要 live 驗收 counter、hover、手機版。

---

## 🚨 鐵律（絕對不能違反）

### 1. 文字一字不改
派 Codex/Gemini 前必須列 VERBATIM 字串清單。部署前跑 content diff 驗證。
教訓檔：`~/.claude/projects/-Users-boboegg/memory/feedback_content_preservation.md`

### 2. 內容預設可見
不可用 `.dc-reveal { opacity: 0 }` + ScrollTrigger 這種設計。
教訓檔：`~/.claude/projects/-Users-boboegg/memory/feedback_content_default_visible.md`

### 3. JS 必須外部檔案
WordPress wp:html block 會破壞 inline `<script>`（`Invalid or unexpected token`）。
正確做法：寫 `assets/js/xxx.js`，頁面用 `<script src="/wp-content/themes/astra-child/assets/js/xxx.js" defer></script>`。

### 4. Hero 文字絕不 scrub opacity
GSAP scrub 只能動背景 parallax、光束、noise canvas。文字永遠立即可見。

### 5. Hero-wrap 高度 ≤ 120vh desktop / ≤ 100vh mobile
180vh 會讓使用者覺得「只有 hero 沒內容」就離開。

### 6. 不改 global.css（P1–P6 freeze mode）
新樣式進頁面內嵌 `<style>` + scoped namespace，或未來的 `dc-v3.css`。

---

## 📦 今日 commits（已全部 push）

```
327fa93 fix(p1-pilot): 刪 dc-reveal + 縮 hero-wrap 120vh
85720e1 fix(p1-pilot): 修 hero 空白與 JS 語法錯誤
f7f76f7 feat(p1-pilot): 新手務農 L3 Director Cut 升級
c070c1a docs(handoff): 2026-04-09 晚間 L3 Director Cut 方向固化
dcf41f3 feat(foundation): P0+P0.5 地基修正與 L3 前置作業
```

---

## 🎨 設計基線

- **skill**：`~/.claude/skills/director-cut-dignity/SKILL.md`
- **完整指南**：`~/vault/Projects/P8-尊嚴農業/design-guide.md`（15 章，必讀第 13 章「P1 Pilot 實戰教訓」）
- **L3 範本**：`~/.claude/skills/director-cut-dignity/references/director-cut-template.md`
- **Memory 入口**：`~/.claude/projects/-Users-boboegg/memory/reference_farmerwordpress_design_index.md`
- **規範 v2**：`~/.claude/projects/-Users-boboegg/memory/reference_dignity_farming_design_spec.md`

---

## 🔧 技術環境

- **Repo**：`github.com/Boboegg/farmerwordpress`
- **Domain**：https://fwdignity.com
- **WordPress 路徑**：`/home/u886115453/domains/fwdignity.com/public_html`
- **部署**：push main → GitHub Actions → SSH Hostinger → `bash scripts/deploy.sh` → 1-2 min live
- **維修模式**：2026-04-09 Bobo 手動關掉，live 公開可見
- **GSAP**：本地 bundle `assets/js/gsap.min.js` + `ScrollTrigger.min.js`（版本 3.12.5）
- **JS 管理**：`npm install gsap --save-dev`

---

## 💡 下次接手開場白建議

```
我是下一個 Claude，讀完 HANDOFF.md + memory 了。

今天繼續 P1 驗收（counter / hover / 手機版）還是直接開 P3 三個分眾頁？
```
