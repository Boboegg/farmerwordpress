# 尊嚴農業網站設計系統 — V14 Happy Hues 風定案

> 更新日期：2026-04-11
> 取代：V1 暖米（2026-04-10）已棄用
> 靈感來源：https://www.happyhues.co/
> 預覽：[test/palette-v14.html](../test/palette-v14.html)

## 1. 設計哲學

1. **每頁獨立 palette**（不繼承父頁）— 24 頁每頁都有自己的配色組合
2. **大量留白 + 一個鮮豔焦點** — 不是把顏色擠滿
3. **對比來自尺寸不是色彩** — 字級差 1.67 倍、line-height 對比
4. **文字不是純黑** — 用帶該頁色調的深色變體
5. **Highlight 回歸語義** — 只用在 `<a>` 連結 + underline
6. **按鈕方角無陰影** — border-radius 3px、純色塊

## 2. Happy Hues 精準尺寸（必遵守）

| 元素 | 尺寸 | 行高 | font-weight | 用途 |
|------|------|------|-------------|------|
| Hero H1 | 56px | 64.4 (1.15) | 900 | 整站入口頂部 |
| Card H2 | 32px | 36px (1.1) | 900 | 每頁主標 |
| Body | 19.2px | 34.56px (1.8) | 400 | 內文 |
| Button | 17.6px | 1 | 700 | CTA |
| Tag | 12px | 1 | 900 | 小標籤 |

**字級差鐵律**：H2 : Body = 32 ÷ 19.2 = **1.67 倍**（Happy Hues 強對比核心）

## 3. CSS 核心規範

```css
/* Card container */
.scene {
  padding: 40px;
  background: var(--bg);
  border-radius: 3px;        /* 方角！不是 12-16 */
  border: 1px solid var(--line);
  box-shadow: none;          /* 無陰影 */
}

/* Headline */
.scene h2 {
  font-size: 32px;
  font-weight: 900;
  line-height: 36px;         /* 壓扁 1.1 */
  margin: 0 0 16px;
  color: var(--headline);
  letter-spacing: 0.01em;
}

/* Body */
.scene .body {
  font-size: 19.2px;
  font-weight: 400;
  line-height: 34.56px;      /* 放開 1.8 */
  margin: 0 0 40px;
  color: var(--paragraph);
}

/* Link / highlight */
.scene .body a {
  color: var(--highlight);
  text-decoration: underline;
  text-decoration-thickness: 2px;
  text-underline-offset: 3px;
  font-weight: 700;
}

/* Button */
.btn-primary {
  padding: 19.2px 32px;
  font-size: 17.6px;
  font-weight: 700;
  line-height: 1;
  border-radius: 3px;
  background: var(--button);
  color: var(--button-text);
  box-shadow: none;
  border: 0;
}

/* Tag */
.scene-tag {
  font-size: 12px;
  font-weight: 900;
  letter-spacing: 0.15em;
  padding: 6px 14px;
  background: var(--tag-bg);
  color: var(--tag-text);
  border-radius: 3px;
}
```

## 4. 24 頁獨立 Palette 對應表

### 7 主題頁 + 1 獨立頁

| 頁面 | BG | Headline | Button | Button Text | Highlight | 基礎 palette |
|------|-----|----------|--------|-------------|-----------|-------------|
| 關於我們 | `#eaddcf` | `#020826` | `#f25042` | `#fffffe` | `#f25042` | P7 Red Gold Navy |
| 職業安全 | `#fffffe` | `#020826` | `#ff8906` | `#020826` | `#e53170` | P5 Orange Red |
| 健康促進 | `#fffffe` | `#272343` | `#2cb67d` | `#272343` | `#2cb67d` | P4 變體 |
| 經濟保險 | `#fffffe` | `#094067` | `#3da9fc` | `#fffffe` | `#ef4565` | P15 Blue Coral |
| 農學堂 | `#fffffe` | `#00473e` | `#faae2b` | `#00473e` | `#fa5246` | P13 變體 |
| 倡議 | `#fffffe` | `#2b2c34` | `#6246ea` | `#fffffe` | `#e45858` | P12 Electric Purple |
| 研究成果 | `#fffffe` | `#00214D` | `#00ebc7` | `#00214D` | `#ff5470` | P16 變體 |
| 最新消息 | `#fffffe` | `#0d0d0d` | `#ff8e3c` | `#0d0d0d` | `#d9376e` | P9 Orange Magenta |

### 健康促進 4 子頁

| 頁面 | BG | Headline | Button | Button Text | Highlight |
|------|-----|----------|--------|-------------|-----------|
| 熱傷害 | `#f8f5f2` | `#232323` | `#f45d48` | `#fffffe` | `#ff8906` |
| 肌肉骨骼 | `#f8f5f2` | `#094067` | `#3da9fc` | `#fffffe` | `#094067` |
| 農藥安全 | `#f2f4f6` | `#181818` | `#4fc4cf` | `#181818` | `#078080` |
| 心理健康 | `#fffffe` | `#0e172c` | `#a786df` | `#0e172c` | `#a786df` |

### 職業安全 3 子頁

| 頁面 | BG | Headline | Button | Button Text | Highlight |
|------|-----|----------|--------|-------------|-----------|
| 個人防護具 | `#eaddcf` | `#020826` | `#f9bc60` | `#020826` | `#f25042` |
| 省工農機 | `#fffffe` | `#242629` | `#2cb67d` | `#242629` | `#7f5af0` |
| 溫室專區 | `#fffffe` | `#33272a` | `#c3f0ca` | `#33272a` | `#ff8ba7` |

### 經濟與保險 5 子頁

| 頁面 | BG | Headline | Button | Button Text | Highlight |
|------|-----|----------|--------|-------------|-----------|
| 職災險 | `#fffffe` | `#094067` | `#094067` | `#fffffe` | `#ef4565` |
| 農業險 | `#fffffe` | `#00473e` | `#00473e` | `#fffffe` | `#faae2b` |
| 補助資源 | `#fffffe` | `#272343` | `#ffd803` | `#272343` | `#ffd803` |
| 職災試算 | `#fffffe` | `#020826` | `#078080` | `#fffffe` | `#e16162` |
| 退休金試算 | `#fffffe` | `#020826` | `#8c7851` | `#fffffe` | `#eebbc3` |

### 研究成果 4 子頁

| 頁面 | BG | Headline | Button | Button Text | Highlight |
|------|-----|----------|--------|-------------|-----------|
| 研究出版 | `#fffffe` | `#00214D` | `#00214D` | `#fffffe` | `#00ebc7` |
| 下載專區 | `#fffffe` | `#242629` | `#7f5af0` | `#fffffe` | `#2cb67d` |
| 相關資源 | `#f2f4f6` | `#181818` | `#994ff3` | `#fffffe` | `#4fc4cf` |
| Podcast | `#f3d2c1` | `#271c19` | `#9656a1` | `#fffffe` | `#e78fb3` |

## 5. 按鈕文字色判定規則

```
if Primary 色的 relative luminance < 0.25:
    button_text = #fffffe (白)
else:
    button_text = headline 色 (深色)
```

淺亮按鈕（黃/青/粉/薄荷）用**深色文字**，深鮮按鈕（電紫/深藍/深酒紅）用**白字**。

## 6. Wrapper Class 命名

每頁 HTML 最外層用 `.fw-page.p-{pageKey}`：

```html
<!-- wp:html -->
<div class="fw-page p-health">
  <!-- 健康促進頁內容 -->
</div>
<!-- /wp:html -->
```

Page keys：`p-about`, `p-safety`, `p-health`, `p-economy`, `p-study`, `p-advocacy`, `p-research`, `p-news`, `p-heat`, `p-msd`, `p-pesticide`, `p-mental`, `p-ppe`, `p-machinery`, `p-greenhouse`, `p-occ`, `p-crop`, `p-subsidy`, `p-calc1`, `p-calc2`, `p-pub`, `p-dl`, `p-rel`, `p-pod`

## 7. 原始 Happy Hues 17 Palette 色值庫

```
P1 Pink Navy: bg#fef6e4 text#001858 btn#f582ae acc#8bd3dd
P2 Peach Purple: bg#f3d2c1 text#271c19 btn#e78fb3 acc#ffc0ad
P3 Pink Mint: bg#fffffe text#33272a btn#ff8ba7 acc#c3f0ca
P4 Yellow Mint: bg#fffffe text#272343 btn#ffd803 acc#e3f6f5
P5 Orange Red: bg#fffffe text#020826 btn#ff8906 acc#f25f4c
P6 Lavender Gold: bg#fffffe text#020826 btn#eebbc3 acc#d4d8f0
P7 Red Gold Navy: bg#eaddcf text#020826 btn#f25042 acc#f9bc60
P8 Green Coral: bg#fffffe text#020826 btn#abd1c6 acc#e16162
P9 Orange Magenta: bg#fffffe text#0d0d0d btn#ff8e3c acc#d9376e
P10 Teal Red: bg#f8f5f2 text#232323 btn#078080 acc#f45d48
P11 Pink Purple: bg#fffffe text#0e172c btn#a786df acc#fec7d7
P12 Electric Purple: bg#fffffe text#2b2c34 btn#6246ea acc#e45858
P13 Gold Pink Red: bg#fffffe text#00473e btn#faae2b acc#fa5246
P14 Violet Mint: bg#fffffe text#242629 btn#7f5af0 acc#2cb67d
P15 Blue Coral: bg#fffffe text#094067 btn#3da9fc acc#ef4565
P16 Neon Triple: bg#fffffe text#00214D btn#00ebc7 acc#ff5470
P17 Purple Yellow Teal: bg#f2f4f6 text#181818 btn#994ff3 acc#fbdd74
```

## 8. 字體（不變）

- **標題**：Noto Serif TC (weight 900)
- **內文**：Noto Sans TC (weight 400, 700)

## 9. 廢棄紀錄

- ❌ **V1 暖米**（2026-04-10）：bg #F7F4ED、brand #4A6932、accent #B07E12、禁冷色
- ❌ **V2 ~ V13**（2026-04-11）：各種失敗迭代（太暗、太豐富、太保守、橘黃不敢用）

演化路徑詳見：memory `reference_dignity_farming_v14_happy_hues.md`

## 10. 派任 Checklist

改任何頁面前檢查：

- [ ] 內文字級 ≥ 19.2px
- [ ] 標題:內文 ≥ 1.67 倍
- [ ] 標題 line-height ≤ 1.1 倍（壓扁）
- [ ] 內文 line-height 1.8 倍（放開）
- [ ] border-radius 3px（不是 12-16）
- [ ] Button 無 box-shadow
- [ ] Highlight 只用在 `<a>` + underline
- [ ] 使用該頁對應的 Happy Hues palette
- [ ] 文字一字不改（content preservation）
