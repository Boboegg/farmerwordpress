# 交接文件 — farmerwordpress

最後更新：2026-04-11
目前分支：main

---

## ⭐ 最新決策：V14 Happy Hues 風定案

**V1 暖米已棄用**，採用 Happy Hues 風 24 頁獨立 palette 系統。

### V14 核心原則
1. **每頁獨立 palette**（不繼承）— 24 頁都有自己的配色組合
2. **Happy Hues 精準尺寸** — 標題 32px、內文 19.2px、**1.67 倍差**
3. **line-height 玩對比** — 標題 36px 壓扁、內文 34.56px 超開
4. **border-radius 3px 方角**（不是 12-16）
5. **按鈕無 box-shadow**
6. **Highlight = `<a>` link + underline**（不當 badge）
7. **橘黃電紫霓虹全解放**

### V14 預覽檔
- `test/palette-v14.html` — 24 頁獨立 palette + Happy Hues 精準尺寸

### 完整規範
Memory: `reference_dignity_farming_v14_happy_hues.md`（24 頁對應表 + CSS 範本）

---

## 待執行的工作

### Phase 1：CSS Tokens 系統重做
更新 `css/global.css`：
1. 刪除舊 V1 暖米 tokens
2. 建立每頁 scoped palette（`.p-about`, `.p-safety`, etc.）
3. 每頁定義：`--bg`, `--headline`, `--paragraph`, `--button`, `--button-text`, `--highlight`, `--tag-bg`, `--tag-text`, `--line`
4. 全站字級系統：`--fs-h1: 56px`, `--fs-h2: 32px`, `--fs-body: 19.2px`, `--fs-button: 17.6px`, `--fs-tag: 12px`
5. 全站 line-height：`--lh-h1: 64.4px`, `--lh-h2: 36px`, `--lh-body: 34.56px`
6. 全站 button 規格：`padding: 19.2px 32px`, `border-radius: 3px`, `box-shadow: none`, `border: 0`

### Phase 2：HTML Wrapper Class 驗證
24 個 page HTML 檔案：
1. 確保每頁有 `.fw-page.p-{pageKey}` wrapper（參考 palette-v14.html 的 class 命名）
2. 換掉舊的 `.ch-*` class（或保留做 fallback）
3. 文字內容一字不改
4. 按鈕/tag 結構調整為符合 Happy Hues 尺寸

### Phase 3：部署
- Git commit + push
- GitHub Actions 自動部署到 Hostinger
- 清快取驗證

---

## 關鍵檔案

| 檔案 | 用途 |
|------|------|
| `test/palette-v14.html` | **V14 定案預覽** |
| `test/palette-v11.html` ~ `v13.html` | 演化參考（失敗版本）|
| `~/.claude/projects/-Users-boboegg/memory/reference_dignity_farming_v14_happy_hues.md` | V14 完整規範 |
| `css/global.css` | 全站 CSS（待改寫）|
| `scripts/page-map.json` | HTML → WP 頁面 ID 映射 |
| `scripts/deploy.sh` | Hostinger 部署腳本 |

---

## 24 頁 Happy Hues palette 對應（快速查找）

| 頁面 | BG | Headline | Button | Highlight |
|------|-----|----------|--------|-----------|
| 關於我們 | `#eaddcf` | `#020826` | `#f25042` 珊瑚紅 | `#f25042` |
| 職業安全 | `#fffffe` | `#020826` | `#ff8906` 鮮橙 | `#e53170` |
| 健康促進 | `#fffffe` | `#272343` | `#2cb67d` 薄荷綠 | `#2cb67d` |
| 經濟保險 | `#fffffe` | `#094067` | `#3da9fc` 亮藍 | `#ef4565` |
| 農學堂 | `#fffffe` | `#00473e` | `#faae2b` 金 | `#fa5246` |
| 倡議 | `#fffffe` | `#2b2c34` | `#6246ea` 電紫 | `#e45858` |
| 研究成果 | `#fffffe` | `#00214D` | `#00ebc7` 霓虹青 | `#ff5470` |
| 最新消息 | `#fffffe` | `#0d0d0d` | `#ff8e3c` 橙 | `#d9376e` |
| 熱傷害 | `#f8f5f2` | `#232323` | `#f45d48` 高溫紅 | `#ff8906` |
| 肌肉骨骼 | `#f8f5f2` | `#094067` | `#3da9fc` 亮藍 | `#094067` |
| 農藥安全 | `#f2f4f6` | `#181818` | `#4fc4cf` 青綠 | `#078080` |
| 心理健康 | `#fffffe` | `#0e172c` | `#a786df` 薰衣草 | `#a786df` |
| 個人防護具 | `#eaddcf` | `#020826` | `#f9bc60` 安全金 | `#f25042` |
| 省工農機 | `#fffffe` | `#242629` | `#2cb67d` 機具綠 | `#7f5af0` |
| 溫室專區 | `#fffffe` | `#33272a` | `#c3f0ca` 嫩芽綠 | `#ff8ba7` |
| 職災險 | `#fffffe` | `#094067` | `#094067` 保險深藍 | `#ef4565` |
| 農業險 | `#fffffe` | `#00473e` | `#00473e` 深綠 | `#faae2b` |
| 補助資源 | `#fffffe` | `#272343` | `#ffd803` 補助黃 | `#ffd803` |
| 職災試算 | `#fffffe` | `#020826` | `#078080` 工具青 | `#e16162` |
| 退休金試算 | `#fffffe` | `#020826` | `#8c7851` 退休棕 | `#eebbc3` |
| 研究出版 | `#fffffe` | `#00214D` | `#00214D` 學術藍 | `#00ebc7` |
| 下載專區 | `#fffffe` | `#242629` | `#7f5af0` 下載紫 | `#2cb67d` |
| 相關資源 | `#f2f4f6` | `#181818` | `#994ff3` 資源紫 | `#4fc4cf` |
| Podcast | `#f3d2c1` | `#271c19` | `#9656a1` 音頻紫 | `#e78fb3` |

---

## 快速開工

```bash
cd ~/farmerwordpress
git pull origin main

# 1. 讀 V14 規範
# 2. 打開 test/palette-v14.html 看視覺
# 3. 開始改 css/global.css tokens
# 4. 改 24 個 page HTML 的 wrapper class
# 5. git push 觸發自動部署
```

---

## 廢棄紀錄

- 2026-04-10 V1 暖米定案（已於 2026-04-11 棄用）
- V2 ~ V13 都是失敗的迭代（太暗/太豐富/對比不夠/橘黃不敢用）
- 詳見 memory `reference_dignity_farming_v14_happy_hues.md` 的「決策演化路徑」
