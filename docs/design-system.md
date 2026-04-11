# 尊嚴農業網站設計系統 — V16 OKLCH Tinted Neutral 定案

> **定案日期**：2026-04-11
> **取代**：V14 Happy Hues（白底量瞎）+ V15 v1-v4（漸進式 override）
> **Source of truth**：[css/global.css line 3005-3855](../css/global.css)
> **三方 skill 共識**：ui-ux-pro-max + impeccable + stitch taste-design

## 1. 設計哲學

1. **全站統一 tinted neutral bg** — 不再每頁獨立 palette bg
2. **60-30-10 視覺權重** — 60% neutral + 30% text + 10% brand accent
3. **漂浮卡片層級** — bg neutral-50 → card neutral-0 + border + shadow + 16px radius
4. **Brand 只在 accent** — 按鈕 / 連結 / icon / stat number / kicker
5. **OKLCH 感知一致** — 所有 brand 色 lightness 52-72%，中高齡眼中等價刺激
6. **無漸層 / 無色條 / 無純白** — 三大 anti-pattern 全殺

## 2. 核心 Tokens（OKLCH）

### Neutral 色系（全站 bg + text）

| Token | 值 | 用途 |
|-------|-----|------|
| `--neutral-0` | `oklch(99.5% 0.004 75)` | 卡片近白（浮現層）|
| `--neutral-50` | `oklch(95% 0.014 72)` | 主 bg 明顯暖米（底層）|
| `--neutral-100` | `oklch(92% 0.016 68)` | surface alt |
| `--neutral-200` | `oklch(86% 0.018 62)` | border |
| `--neutral-400` | `oklch(62% 0.020 60)` | muted text |
| `--neutral-700` | `oklch(38% 0.025 55)` | secondary text |
| `--neutral-900` | `oklch(22% 0.028 50)` | 主文字 深暖近黑 |

### 漂浮感 Shadow

```css
--v15-card-shadow: 0 4px 16px oklch(22% 0.02 50 / 0.05),
                   0 1px 3px oklch(22% 0.02 50 / 0.08);
```

### 字級（保留 Happy Hues 精準尺寸）

| 元素 | 尺寸 | 行高 | font-weight |
|------|------|------|-------------|
| Hero H1 | clamp(40px, 6vw, 56px) | 1.15 | 900 |
| Card H2 | 32px | 36px (1.1) | 900 |
| Body | 19.2px | 34.56px (1.8) | 400 |
| Button | 17.6px | 1 | 700 |
| Tag / Kicker | 16px | 1 | 900 |

字級差鐵律：H2 : Body = 32 ÷ 19.2 = **1.67 倍**

### 圓角（V15 柔和取代 V14 3px 方角）

| Token | 值 | 用途 |
|-------|-----|------|
| `--v14-radius` | `10px` | 按鈕 / tag / 小元素 |
| `--v14-radius-card` | `16px` | 卡片 / 面板 |

## 3. 28 頁 Brand Palette

```css
/* 7 主題 + 1 獨立頁 */
.fw-page.p-about    { --brand: oklch(58% 0.20 25);  }  /* 珊瑚紅 */
.fw-page.p-safety   { --brand: oklch(68% 0.20 50);  }  /* 警示橘 */
.fw-page.p-health   { --brand: oklch(62% 0.16 155); }  /* 薄荷綠 */
.fw-page.p-economy  { --brand: oklch(58% 0.18 245); }  /* 信任藍 */
.fw-page.p-study    { --brand: oklch(70% 0.17 80);  }  /* 豐收金 */
.fw-page.p-advocacy { --brand: oklch(55% 0.22 290); }  /* 電紫 */
.fw-page.p-research { --brand: oklch(55% 0.14 235); }  /* 學術藍 */
.fw-page.p-news     { --brand: oklch(65% 0.19 40);  }  /* 橙 */

/* 健康促進 4 子頁 */
.fw-page.p-heat      { --brand: oklch(60% 0.21 25);  }
.fw-page.p-msd       { --brand: oklch(58% 0.16 230); }
.fw-page.p-pesticide { --brand: oklch(60% 0.13 180); }
.fw-page.p-mental    { --brand: oklch(62% 0.14 300); }

/* 職業安全 3 子頁 */
.fw-page.p-ppe        { --brand: oklch(70% 0.17 85);  }
.fw-page.p-machinery  { --brand: oklch(58% 0.16 140); }
.fw-page.p-greenhouse { --brand: oklch(68% 0.14 150); }

/* 經濟保險 5 子頁 */
.fw-page.p-occ     { --brand: oklch(52% 0.16 250); }
.fw-page.p-crop    { --brand: oklch(52% 0.13 165); }
.fw-page.p-subsidy { --brand: oklch(72% 0.18 88);  }
.fw-page.p-calc1   { --brand: oklch(58% 0.12 195); }
.fw-page.p-calc2   { --brand: oklch(50% 0.08 70);  }

/* 研究成果 4 子頁 */
.fw-page.p-pub { --brand: oklch(52% 0.14 240); }
.fw-page.p-dl  { --brand: oklch(55% 0.22 275); }
.fw-page.p-rel { --brand: oklch(58% 0.22 285); }
.fw-page.p-pod { --brand: oklch(58% 0.17 320); }

/* 4 分眾入口 */
.fw-page.p-young       { --brand: oklch(65% 0.19 115); }  /* 萊姆綠 */
.fw-page.p-beginner    { --brand: oklch(68% 0.16 15);  }  /* 溫暖粉橘 */
.fw-page.p-experienced { --brand: oklch(50% 0.10 55);  }  /* 深棕 */
.fw-page.p-public      { --brand: oklch(58% 0.10 220); }  /* 灰藍 */
```

## 4. V16 Override 結構

實際實作在 `css/global.css` line 3005-3855，共 475 行：

| 區段 | 作用 |
|------|------|
| A. Universal kill | `.fw-page.v14 *:not(icon/badge/btn)` background-image + box-shadow 清除 |
| B. Border-left/right 色條殺 | 所有元素強制 neutral-200 |
| C. Gradient overlay 子層 display:none | `.pod-hero-bg` / `.dc-orb-*` / `.dc-grid-noise` / `.adv-hero-overlay` 等 10+ 層 |
| D. Top-level containers → neutral-0 卡片 | ~100 個 class exhaustive list |
| E. Nested sub-items → transparent | ~80 個 class exhaustive list |
| F. Badge / kicker → 純字 + brand 色 | `-hero-badge` / `-kicker` / `-sec-header` |
| G. Icons → brand 12% tint 圓形 | `[class*="-icon"]` (exclude card/panel/hero) |
| H. Stat number → brand 色 | `[class*="-stat-number"]` |
| I. Hero title/subtitle 統一 | `[class*="-hero-title/subtitle"]` |
| J. Grid containers 透明 | `[class*="-grid"]:not(card/panel)` |

## 5. 新頁面檢查清單

改任何頁面前檢查：

- [ ] Wrapper 加 `v14` + `p-*` class（例：`<div class="fw-page v14 p-health">`）
- [ ] 不用 `linear-gradient` / `radial-gradient`（任何 gradient）
- [ ] 不用 `#fff` / `#fffffe` / `rgba(255,255,255,x)`
- [ ] 不用 `border-left` / `border-right` 超過 1px 帶色（impeccable anti-pattern）
- [ ] 不用硬編碼 `box-shadow`（用 `var(--v15-card-shadow)`）
- [ ] 不用 `--brand-legacy` 自訂變數（用 global.css 定義的 `var(--brand)`）
- [ ] 所有顏色必須 `var(--brand)` 或 `var(--neutral-*)`
- [ ] 新 class name 要加到 V16 override list（`css/global.css` line 3381+）
- [ ] 內文字級 ≥ 19.2px
- [ ] 標題:內文 = 1.67 倍
- [ ] 觸控目標 ≥ 44×44px
- [ ] 文字一字不改（content preservation）

## 6. 字體（不變）

- **標題**：Noto Serif TC (weight 900)
- **內文**：Noto Sans TC (weight 400, 700)

## 7. 廢除紀錄

- ❌ **V1 暖米**（2026-04-10）：bg `#F7F4ED`、brand `#4A6932`、accent `#B07E12`、禁冷色
- ❌ **V14 Happy Hues**（2026-04-11 上午）：24 頁獨立 palette + `#fffffe` 純白 + 3px 方角
  - 失敗原因：純白量瞎 + 打破 60-30-10 + 每頁 bg 獨立失去統一感
- ❌ **V15 v1-v4**（2026-04-11 下午）：漸進式 attribute selector override
  - 失敗原因：`[class*="-hero"]` 打不到 `.pod-hero-bg`、`.dc-home` 等非 hero 結尾 class
- ✅ **V16 定案**（2026-04-11 晚間）：4 agent parallel 盤點 + exhaustive class list

演化路徑詳見：memory `reference_dignity_farming_v16_oklch_neutral.md`

## 8. 派任 Checklist（給 Agent 改頁面時）

- [ ] 內文字級 ≥ 19.2px（不是 18）
- [ ] 標題:內文 ≥ 1.67 倍
- [ ] 標題 line-height ≤ 1.1 倍
- [ ] 內文 line-height 1.8 倍
- [ ] border-radius 10-16px（按鈕 10，卡片 16）
- [ ] Button 無 box-shadow（除非用 `var(--v15-card-shadow)`）
- [ ] Kicker/Badge 純字 + brand 色，無底框
- [ ] 使用該頁對應的 OKLCH brand
- [ ] 不自創色（必須在 28 頁 brand list 裡）
- [ ] HTML wrapper 含 `v14` class

## 參考檔案

- **V15 preview**：[test/palette-v15.html](../test/palette-v15.html)（OKLCH 24 頁預覽）
- **V16 定案 Memory**：`~/.claude/projects/-Users-boboegg/memory/reference_dignity_farming_v16_oklch_neutral.md`
- **V14 廢棄預覽**：[test/palette-v14.html](../test/palette-v14.html)（保留做歷史參考）
- **Skill source**：ui-ux-pro-max / impeccable / stitch taste-design / cinematic-layout (all in `~/.claude/skills/`)
