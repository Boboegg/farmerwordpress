# 交接文件 — farmerwordpress

最後更新：2026-04-10
目前分支：main
目前最新 commit：33d34cd

---

## 目標

全站前端美化 — V1 暖米色盤統一 + 各主題頁/子頁配色調和

---

## 已完成（2026-04-10）

### 1) V1 暖米色盤定案
- 底色 `#F7F4ED`、主色 `#4A6932`、輔色 `#B07E12`、警示 `#8B2D14`、資訊 `#2A6B5A`
- 鐵律：新增顏色必須在暖米方向調和，禁止冷色調
- 字體只留 Noto Serif TC + Noto Sans TC，移除 Lato/Lora

### 2) 全站色盤統一
- `css/global.css` :root tokens 全部換成 V1
- 6 個 HTML 頁面 + 3 個 PHP shortcode 舊硬編碼色值清零
- 側邊欄雙框線修復（widget 改無框風格）

### 3) 健康促進 5 頁配色完成
- 主頁：森林綠 `#3D7A5A`
- 熱傷害：燒土橘紅 `#B84A2D`
- 心理健康：橄欖綠 `#4A6932`
- 肌肉骨骼：青石暖藍 `#4A6A8A`
- 農藥安全：深棕 `#7A4E12`
- 僅替換色值，內文零變更

### 4) 部署修復
- `scripts/deploy.sh` 加 python3 前置檢查（Hostinger 無 python3）
- 停用 6 個 Gemini workflow（省 GitHub Actions 額度）
- Copilot code review / coding agent 需到 GitHub 網頁手動關

### 5) 6 大主題頁配色方案已出（待 Bobo 確認）
- 預覽檔：`test/topic-pages-colors.html`
- 職業安全 `#8B2D14` / 經濟保險 `#B07E12` / 農學堂 `#4A6932` / 倡議 `#7A4E12` / 關於我們 `#3D7A5A` / 最新消息 `#4A6A8A`

---

## 遇到的問題

1. **健康促進主頁變色未在前台生效** — deploy log 顯示 ID 1328 成功寫入，疑似瀏覽器/CDN 快取。需 `Cmd+Shift+R` 或清 Hostinger LiteSpeed 快取確認
2. **健康促進 4 子頁不在 page-map** — 檔案沒有 .html 副檔名（`subpage-heat`），且無 WordPress 頁面 ID，部署腳本跳過。需 Bobo 提供 WP 後台的頁面 ID
3. **Hostinger SSH 偶爾連線逾時** — 跟程式碼無關，重跑 workflow 即可

---

## 下一步（按優先順序）

1. **確認健康促進變色是否生效**（清快取驗證）
2. **Bobo 確認 6 大主題頁配色**（看 `test/topic-pages-colors.html`）
3. **批次替換 6 大主題頁色值**（職業安全 45 色最複雜）
4. **取得健康促進 4 子頁 WordPress 頁面 ID**，加入 `scripts/page-map.json`
5. **處理其他子頁面配色**（職業安全 10 子頁、經濟保險 10 子頁...）
6. **行動端優化**（觸控、RWD）
7. **L3 Director Cut 首頁重寫**

---

## 關鍵檔案

| 檔案 | 用途 |
|------|------|
| `/Users/boboegg/farmerwordpress/css/global.css` | 全站樣式（V1 暖米 token） |
| `/Users/boboegg/farmerwordpress/docs/design-system.md` | 設計系統文件（V1 定案版） |
| `/Users/boboegg/farmerwordpress/scripts/page-map.json` | HTML → WP 頁面 ID 映射 |
| `/Users/boboegg/farmerwordpress/scripts/deploy.sh` | Hostinger 部署腳本 |
| `/Users/boboegg/farmerwordpress/test/topic-pages-colors.html` | 6 大主題頁配色預覽（待確認） |
| `/Users/boboegg/farmerwordpress/test/health-v2.html` | 健康促進 5 頁配色預覽 |
| `~/.claude/projects/-Users-boboegg/memory/reference_dignity_farming_design_spec.md` | 設計規範 memory |
| `~/.claude/projects/-Users-boboegg/memory/reference_farmerwordpress_design_index.md` | 設計入口索引 memory |
| `~/vault/Projects/P8-尊嚴農業/status.md` | Vault 專案狀態 |

---

## 快速開工

```bash
cd ~/farmerwordpress
git pull origin main
# 確認同步後，問 Bobo：
# 1. 健康促進清快取後有沒有變色？
# 2. 6 大主題頁配色 OK 嗎？
# 3. 健康促進子頁的 WordPress 頁面 ID？
```
