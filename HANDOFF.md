# 交接文件 — farmerwordpress 視覺爆改工作

**日期**：2026-04-09  
**對話時長**：約 8 小時（從早上 4 層 AI 編排架構規劃到傍晚首頁視覺爆改）  
**接手 Claude 必讀**：本檔 + 以下 5 個 memory 檔（會自動載入）

---

## 🎯 當前最關鍵的待決事項（接手第一件事）

Bobo 在我寫的 `pages/home/index.new.html` 視覺版本後說：

> 「但是這種視覺跟我們一開始討論的 差少很多」

意思是這版**內容保留正確**但**視覺不夠爆改**——他要 v2 Director Cut / v4 Apex Vision demo 等級的氣場，不是「溫和升級」。

我已經提了 4 個選項給他選但他**還沒回答**就要交接：

| 選項 | 內容 |
|---|---|
| **A** | 純視覺爆改不加任何文字（Hero 100vh、字 100px、4 層 plane parallax、letter-by-letter、scene cut、cursor spotlight、manifesto pinned scroll 重複利用副標） |
| **B** | 允許用「英文/數字/裝飾元素」當 deco（例如 watermark "001"、"DIGNITY"、"2026"） |
| **C** | Bobo 提供新文字段落（manifesto / 計畫宣言）才能塞滿大版面 |
| **D** | 接受版面高度，但狂塞動畫密度 |
| **A+D** | （我推薦）只重用既有文字 + 拉滿動畫和高度，達 v2 demo 80% 氣場 |

**接手第一件事：問 Bobo 選哪個 A/B/C/D，然後動。**

---

## 🚨 鐵律（永遠不能違反）

**派任 AI（Codex/Gemini）改既有 farmerwordpress 頁面時：絕對不能動文字內容。**

理由：今天派 Codex Phase 3 改首頁，brief 我寫得太鬆給了「自由發揮文案」授權，Codex 寫了 1500 行新內容（manifesto / KPI / Action / 團隊介紹）→ 我沒檢查就部署 → Bobo 看到後說「**首頁的內容有大問題 你不只是美化 而是把文字也都亂改了**」「**我們當時不是說只要先美化嗎 我有說文字可以亂改嗎？**」→ 立即 git revert + push 自動回滾。

完整教訓寫在 `~/.claude/projects/-Users-boboegg/memory/feedback_content_preservation.md`，**接手前必讀**。

**Bobo 的真正想法**：
> 「我從頭到尾都是要網站互動性、美化、JS、UIUX的進化 重來都不是讓CODEX來給我亂寫字 而是透過他的前端設計比你厲害 來幫你設計好設計稿」

**結論**：可以派 Codex 做設計稿（互動/JS/動畫），但**所有文字必須從原始 pages/home/index.html 一字不改抄過來**。

---

## 📊 farmerwordpress 線上現況

- **Domain**: https://fwdignity.com（Hostinger 託管）
- **WordPress 路徑**：`/home/u886115453/domains/fwdignity.com/public_html`
- **Theme**：`astra-child`
- **部署機制**：**push main → GitHub Actions 自動 SSH Hostinger → git pull → bash scripts/deploy.sh**（1-2 分鐘自動上線）
- **最近 commit**：
  ```
  9c9679a Revert "feat(home): 部署 Codex 寫的新首頁"  ← 線上目前是這個（原始首頁狀態）
  3af7566 fix(deploy): deploy.sh 同時部署 global.new.css
  98cfbf5 feat(home): 部署 Codex 寫的新首頁              ← 已 revert
  64c0ff3 fix(css): LINE@ 按鈕 color 對比修正
  0ded91f fix(css): 收緊 [class*="label-"] selector
  8afaadd hotfix(css): 加入 design system v2.0 token compat layer
  5ef0c93 fix(colors): 擴大色碼遷移涵蓋 39 個無副檔名 HTML 內容檔
  56d4043 refactor(colors): 18 個頁面的硬寫色碼遷移到 design tokens
  7598c3c feat(design): design system v2.0 + 雙主題 + 4 demo 參考檔
  04277a7 chore(ci): 加入 4 層 AI 編排架構 + Gemini AI Actions
  ```

---

## ✅ 今天完成的事

### 一、4 層 AI 編排架構（完整建置）
- **L0 Claude Code**（總指揮）
- **L1 Codex CLI** + `openai/codex-plugin-cc` plugin（前端設計師）
- **L1 Gemini CLI** + 5 個官方 extensions（Stitch / web-accessibility / Conductor / code-review / nanobanana）
- **L2 GitHub Actions** 11 個 Gemini AI workflow（從 google-github-actions/run-gemini-cli 下載）

詳見 memory: `reference_ai_orchestration_architecture.md`

### 二、`farmerwordpress/AGENTS.md` 寫好（Codex/Gemini/Claude 三方共讀的專案 context）
- 含品牌規範、目錄結構、AI 工具分工決策表、安全紅線

### 三、Skills 寫好
- `~/.claude/skills/frontend-orchestrator/SKILL.md` — 前端任務分流決策
- `~/.claude/skills/gemini-delegate/SKILL.md` — Gemini 派任橋接器（用 --output-format json）

### 四、`~/.gemini/settings.json` — 讓 Gemini 也讀 AGENTS.md

### 五、Codex Phase 1：Design System v2.0 完成（部分上線）
- ✅ `css/global.new.css`（20KB，雙主題：明亮溫暖 + 暗黑深森林綠）
- ✅ `scripts/migrate-colors.sh`（dry-run + apply + macOS sed -i ''）
- ✅ `design-samples/design-tokens-preview.html`（43KB 互動預覽頁）
- ⚠️ `global.new.css` 已部署到 Hostinger，但**舊頁面還沒切過去用 .ds- 元件**

### 六、Color Migration 完成
- 18 + 39 = **57 個檔案的 589 次替換**
- 把硬寫色碼如 `#c62828` 換成 `var(--color-warn)` 等 token
- 全部已上線

### 七、5 個 hotfix（解決一連串連動 bug）
1. **Compat layer for var(--color-*)** — 線上 global.css 沒定義新變數，加 :root alias
2. **Selector class*="label-"** 太貪婪 — 加 `.fw-page` 父容器限定
3. **LINE@ 按鈕對比** — 加 `#ast-desktop-header` 提高 specificity 蓋過 Astra
4. **deploy.sh 部署 global.new.css** — 之前漏 cp
5. **GitHub repo 整理** — 9 個 deletable repo 加 `DELETE-` 前綴

### 八、5 個重要 memory 檔寫好
1. `reference_ai_orchestration_architecture.md`
2. `feedback_farmerwordpress_deployment.md`
3. `feedback_content_preservation.md`（**最重要**）
4. `feedback_pending_minor_fixes.md`（小問題清單）
5. `reference_dignity_farming_design_spec.md`（design system 官方建議）

---

## ❌ 失敗紀錄（教訓已寫進 memory）

### 失敗 1：派 Codex Phase 3 寫首頁
- **發生**：Codex 寫了 1507 行新首頁，編造大量假內容（manifesto / KPI / 團隊介紹 / stats）
- **發現**：Bobo 看到線上後說「內容有大問題」
- **處理**：git revert + push 自動回滾
- **教訓**：派任時要嚴格列出每個必須保留的文字字串，禁止寫新文案
- **教訓檔**：`~/.claude/projects/-Users-boboegg/memory/feedback_content_preservation.md`

### 失敗 2：派 Codex Phase 4 健康促進改造
- **發生**：取消（避免同樣問題重演）
- **狀態**：未執行

### 失敗 3：審計多次誤判
- 我說「9 個 body{} 違規」→ 實際 0 個（grep regex 太鬆）
- 我說「47 個 *{} 違規」→ 實際 0 個（沒考慮 scoped descendant）
- 我說「64 個 HTML 檔」→ 實際 20 個 .html + 42 個無副檔名 = 62 個
- 我說「90 個 hp- prefix 違規」→ 實際只有 2 個（規範寫 hp- 但 Bobo 用 hpH-）
- **教訓**：用 grep count 當違規證據前，先 spot check 5-10 個樣本看實際內容

---

## 📁 當前未提交的本地檔案

```
/Users/boboegg/farmerwordpress/pages/home/index.new.html  ← 我手寫的視覺爆改版（732 行，內容 100% 保留，但 Bobo 嫌不夠爆）
/Users/boboegg/farmerwordpress/pages/home/index.html.before-codex.bak  ← Codex 部署前的原始備份
/Users/boboegg/farmerwordpress/design-samples/home-v2-preview.html  ← 預覽用 wrapper（含 charset）
/Users/boboegg/farmerwordpress/design-samples/screenshots/  ← playwright 截圖（部分已 commit）
```

---

## 🚧 還沒做的事（按優先順序）

### P0 — 接手立刻問
1. **回答 Bobo「視覺不夠爆改」的問題**：問他選 A/B/C/D 還是 A+D，然後動

### P1 — 第二優先
2. **真正爆改 index.new.html**：根據 Bobo 選的方向重寫，目標達到 v2 demo 80% 氣場
3. **驗證 content diff（21 項）通過後才 commit + push**

### P2 — 之後
4. **Phase 4：把 design system v2.0 套到其他 6 個主頁**（健康促進 / 職安 / 經保 / 倡議 / 研究成果 / 培力）—— **必須用「保留所有原文」的 strict prompt**
5. **修首頁 .topic-nav-wrapper 16px overflow**（小問題暫存區內）
6. **把 ai-agent repo 的舊 HANDOFF.md 同步更新**（過期資訊已釐清）

---

## 🛠️ 接手該知道的工具

### Codex 派任（背景模式）
```bash
node "/Users/boboegg/.claude/plugins/cache/openai-codex/codex/1.0.3/scripts/codex-companion.mjs" task --background --write "$(cat /tmp/prompt.txt)"
```

### Codex 狀態查詢
```bash
node "/Users/boboegg/.claude/plugins/cache/openai-codex/codex/1.0.3/scripts/codex-companion.mjs" status <task-id> --json
```

### Codex 取消
```bash
node "/Users/boboegg/.claude/plugins/cache/openai-codex/codex/1.0.3/scripts/codex-companion.mjs" cancel <task-id>
```

### Gemini 派任（用 gemini-delegate skill）
```bash
cd <target-repo>  # 重要：cd 才能讓 Gemini 讀到 AGENTS.md
gemini -p "<prompt>" --output-format json > /tmp/result.json
jq -r '.response' /tmp/result.json
```

### Design system 查詢
```bash
python3 ~/.claude/resources/ui-ux-pro-max/src/ui-ux-pro-max/scripts/search.py "<關鍵字>" --design-system -p "專案名"
```

### Hostinger SSH（可直接用，~/.ssh/config 已設好 alias）
```bash
ssh hostinger
# 進入後 cd /home/u886115453/domains/fwdignity.com/public_html
```

### Content Diff 驗證（部署 farmerwordpress 頁面前必跑）
```bash
cd /Users/boboegg/farmerwordpress
items=("守護" "您的農業生活" "從健康到經濟，我們提供全方位的支持" "健康促進" "熱危害防治" "肌肉骨骼保養" "農藥安全防護" "職業安全" "個人防護具 (PPE)" "省力化輔具" "智慧穿戴裝置" "經濟保險" "職災保險試算" "農作物保險" "補助資源懶人包" "[home_highlight_slider]" '[news_magazine_layout title="最新動態" count="5"]' '[knowledge_podcast_grid title="影音專區" count="6"]' "/healthgood" "/occupational-safety/" "/economic-insurance/")
for item in "${items[@]}"; do
  /usr/bin/grep -qF "$item" pages/home/index.new.html && echo "✓ $item" || echo "✗ MISSING: $item"
done
```

---

## 📚 必讀的 memory 檔（接手前讀完）

```
/Users/boboegg/.claude/projects/-Users-boboegg/memory/feedback_content_preservation.md
/Users/boboegg/.claude/projects/-Users-boboegg/memory/feedback_farmerwordpress_deployment.md
/Users/boboegg/.claude/projects/-Users-boboegg/memory/reference_dignity_farming_design_spec.md
/Users/boboegg/.claude/projects/-Users-boboegg/memory/reference_ai_orchestration_architecture.md
/Users/boboegg/.claude/projects/-Users-boboegg/memory/feedback_pending_minor_fixes.md
/Users/boboegg/.claude/projects/-Users-boboegg/memory/reference_wordpress_hostinger.md  ← Hostinger SSH 連線
```

---

## 🔑 關鍵檔案路徑（絕對路徑）

### 設計系統
- `/Users/boboegg/farmerwordpress/css/global.css` — 線上正式 CSS（含 compat layer）
- `/Users/boboegg/farmerwordpress/css/global.new.css` — design system v2.0（含 .ds- 元件）

### 首頁
- `/Users/boboegg/farmerwordpress/pages/home/index.html` — 線上正式（**原始 224 行版本**，已 revert）
- `/Users/boboegg/farmerwordpress/pages/home/index.new.html` — 我手寫的視覺爆改版 v1（732 行，**Bobo 嫌不夠爆**）
- `/Users/boboegg/farmerwordpress/pages/home/index.html.before-codex.bak` — 備份

### 設計參考檔
- `/Users/boboegg/farmerwordpress/design-samples/inspirations/03-style-lab.html` — v3 demo CSS 變數架構
- `/Users/boboegg/farmerwordpress/design-samples/inspirations/key-features-reference.md` — 4 demo 該抄/不該抄清單
- `/Users/boboegg/farmerwordpress/design-samples/design-tokens-preview.html` — Codex Phase 1 預覽頁
- `/Users/boboegg/farmerwordpress/design-samples/home-v2-preview.html` — 我寫的首頁 v1 預覽 wrapper

### 規範
- `/Users/boboegg/farmerwordpress/AGENTS.md` — Codex/Gemini/Claude 三方共讀的專案 context
- `/Users/boboegg/farmerwordpress/.github/SETUP-GEMINI-AI.md` — GitHub Actions 啟用指南
- `/Users/boboegg/farmerwordpress/scripts/page-map.json` — HTML → WP page ID 對應（首頁 = 1420）
- `/Users/boboegg/farmerwordpress/scripts/deploy.sh` — Hostinger 端部署腳本（push 自動觸發）

---

## 💡 對 Bobo 的觀察（接手該知道的）

1. **要進度，不要藉口**：Bobo 喜歡看到實際成果，「我弄完才下班」。不要過度問問題、不要列太多選項，**動手做事**比較重要
2. **不接受過度保守**：說「美化」就是要爆改，不是溫和升級
3. **不接受「藉口式回答」**：我說「訓練資料只到 2025-05」被罵過。**不要再用這種說法**
4. **重視一致性**：我多次「重大發現」其實是誤判，被糾正過。**先 spot check 5-10 個樣本再下結論**
5. **內容神聖**：他自己寫的文字一個字都不能改
6. **設計庫要先用**：他付費 MAX 訂閱期待我會用所有 design system 工具，**不要忘記查 ui-ux-pro-max 資料庫**
7. **Codex 角色明確**：是「前端設計專家」幫忙做設計稿，**不是內容生成器**
8. **vault 操作用 mcp__obsidian__\* 工具**，不要 Read/Write 直接讀寫 vault 檔案
9. **學術網站可以美麗** — 可信度來自引用嚴謹不是視覺無聊
10. **3 次法則** — 同一件事講 3 次就要寫成 skill

---

## 📞 接手第一個對話建議的開場

```
我剛接手昨天 (2026-04-09) 對話的工作。讀完 HANDOFF.md 跟所有 memory 檔了。

我看到上次我（前一個 Claude）給你 4 個選項你還沒回答：
- A. 純視覺爆改不加文字
- B. 允許英文/數字/裝飾元素當 deco
- C. 你提供新文字段落
- D. 接受版面但塞滿動畫
- A+D（推薦）

你選哪個？我馬上動工把首頁做到 v2 demo 80% 氣場。

紅線我記得：文字一字不改，內容神聖。
```
