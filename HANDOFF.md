# 交接文件 — 尊嚴農業 V15 FUSION 設計探索

日期：2026-04-19
狀態：V14 Happy Hues 已淘汰，V15 FUSION 雛型完成，待明天拍板與落地

---

## 🎯 目標

為尊嚴農業網站建立新設計系統（取代 V14 Happy Hues），具備：
- 明亮溫暖配色（TA 農民+一般民眾友善）
- 28 頁 OKLCH palette 切色架構
- 五幕敘事主線（衝擊→證據→方法→承諾→邀請）
- 炫砲但克制的動畫（WordPress 可實作）
- 字級收斂 + 圓潤 + Noto Sans TC
- WP-ready scope（`.fw-page-v15` 容器）

---

## ✅ 已完成（今天 2026-04-19）

### 探索過程
1. 用 pre-ripening 老茶米黃試 → Bobo 評「黃底咖啡色醜」否決
2. Bobo 找出 V15 原版色系（28 頁 OKLCH），方向對
3. 做出 showcase 版（Milestone）→ Bobo 喜歡
4. Bobo 要更激進 → 做 Ultra 版（全炫技）
5. Bobo 評「炫泡但各自獨立沒意義、標題太大、沒用 agents」
6. 派兩個 agent 審查（design-reviewer + gongbu）→ 整合出 FINAL + FUSION

### 三版檔案（全部鎖定保存）

| 檔案絕對路徑 | 角色 | 狀態 |
|------|------|------|
| `/Users/boboegg/farmerwordpress/test/palette-v15-showcase-MILESTONE-2026-04-19.html` | Milestone（Bobo 喜歡） | 🔒 鎖定 |
| `/Users/boboegg/farmerwordpress/test/palette-v15-FINAL.html` | 純敘事整合版 | 🔒 鎖定 |
| `/Users/boboegg/farmerwordpress/test/palette-v15-FUSION.html` | 雙版精華合體（最新） | 🆕 待拍板 |

補充：
- `/Users/boboegg/farmerwordpress/test/palette-v15-ultra.html` — 激進炫技（agents 否決，封存）
- `/Users/boboegg/farmerwordpress/test/HANDOFF-2026-04-19.md` — 今天 session 完整交接

### Agent 審查（結論已納入 FUSION）
- **Design Reviewer**：15 項 findings，CRITICAL 3 項（字級爆衝 / 敘事斷裂 / 零真實影像）+ 五幕敘事骨架
- **Gongbu 工部**：9 效果 WP 落地檢查（2 GREEN / 5 YELLOW / 2 RED）+ 4 步 checklist；RED：`cursor: none` 全域、hscroll 550vh

---

## 🚫 遇到的問題

### 有效方法
- 派 2 agent 並行審查（design + WP）→ 交叉驗證
- 先鎖 Milestone 再做 Ultra → 保留喜歡的版本
- `.fw-page-v15` scope + `--v15-` 變數前綴 → 不污染 global.css
- Scramble 只用一次（Proof 3 數字）→ 不稀釋

### 無效方法
- 自己一個人硬寫 6 版 → 違反 Mode D pipeline（沒用 agents）
- Hero 190px 爆字 + Fraunces serif + 老茶米黃 → 視覺暴力
- 每 section 獨立炫技（cursor/magnetic/3D tilt/hscroll 550vh）→ agents 否決
- 連改同檔 6 次 → 觸發 Longcut 振盪 hook

### 核心教訓
- 動畫服務敘事，不是展示技術
- 農民+一般民眾 ≠ Awwwards 設計圈
- 真實農民/田野照是最強整合膠水（全是佔位，待補）

---

## 🔄 下一步

### Phase 2：拍板 + 規格文件（明天優先）
1. 打開 3 版並排比對：
   ```bash
   open /Users/boboegg/farmerwordpress/test/palette-v15-showcase-MILESTONE-2026-04-19.html
   open /Users/boboegg/farmerwordpress/test/palette-v15-FINAL.html
   open /Users/boboegg/farmerwordpress/test/palette-v15-FUSION.html
   ```
2. Bobo 決定 FUSION 是否定案 / 要微調哪裡
3. 定案後產出規格文件：`/Users/boboegg/farmerwordpress/design-system/palette-system-v15-fusion.md`

### Phase 3：WordPress 落地
4. 備份 CSS：`cp ~/farmerwordpress/css/global.css ~/farmerwordpress/css/global.css.pre-v15-fusion.bak`
5. CSS 改寫：FUSION 的 `.fw-page-v15` 區塊加到 `global.css` 尾部
6. HTML 落地：先首頁 `~/farmerwordpress/pages/home/主頁/index.html`，外包 `<div class="fw-page-v15" data-page="home">` wp:html
7. JS 檔案化：`~/farmerwordpress/js/v15-fusion.js` + Code Snippets `wp_enqueue_script` 只在 `is_front_page()` enqueue
8. Astra 衝突檢查：sticky header top、側欄 z-index、`overflow: hidden` 祖元素
9. 部署：`scripts/deploy.sh --dry-run` → content diff → Hostinger → 4 裝置測試

### Phase 4：擴展
10. 章節頁套用（依 data-page 一頁一頁）
11. 真實影像替換（4 個佔位）
12. Memory 更新：棄用 v14，新增 v15-fusion reference
13. Vault `~/vault/Projects/P8-尊嚴農業/status.md` 更新

### 明天要回答的 7 議題
1. FUSION 是否定案？
2. 影像佔位何時替換真實照？
3. 字級還要再收？CTA 120px 會不會太大？
4. 承諾幕 feature row vs 左右切割？
5. WP 落地從哪頁先做？
6. 28 頁實做順序？
7. V14 是否全面被 V15 取代？

---

## 🔑 關鍵檔案（絕對路徑）

### 必讀
- `/Users/boboegg/farmerwordpress/HANDOFF.md`（本檔）
- `/Users/boboegg/farmerwordpress/test/HANDOFF-2026-04-19.md`（session 細節）
- `/Users/boboegg/farmerwordpress/test/palette-v15-FUSION.html`（最新）

### 設計比對
- `/Users/boboegg/farmerwordpress/test/palette-v15-showcase-MILESTONE-2026-04-19.html`
- `/Users/boboegg/farmerwordpress/test/palette-v15-FINAL.html`

### 既有架構（落地要改）
- `/Users/boboegg/farmerwordpress/css/global.css`（739 行，`--brand: #5C8607`）
- `/Users/boboegg/farmerwordpress/css/oryzo-inspired.css`（暗色動畫）
- `/Users/boboegg/farmerwordpress/pages/home/主頁/index.html`
- `/Users/boboegg/farmerwordpress/AGENTS.md`（scope 鐵律）

### Memory 紅線
- `~/.claude/projects/-Users-boboegg/memory/feedback_farmerwordpress_deployment.md`
- `~/.claude/projects/-Users-boboegg/memory/feedback_content_default_visible.md`
- `~/.claude/projects/-Users-boboegg/memory/reference_dignity_farming_v14_happy_hues.md`（明天棄用）
- `~/.claude/projects/-Users-boboegg/memory/reference_farmerwordpress_design_index.md`

---

## 🎬 新對話開場範例

```
我昨晚停在 V15 FUSION 設計探索。先讀：
1. ~/farmerwordpress/HANDOFF.md
2. 打開 palette-v15-FUSION.html

然後開始：FUSION 是否定案？
```

---

## 📌 備註
舊版 HANDOFF.md（V14 套用 2026-04-11）已被本檔取代。V14 歷史見 `git log HANDOFF.md` commit `5f88beb`。
