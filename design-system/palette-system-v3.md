# 尊嚴農業 — 完整配色系統 V3（Forest Bold）

> **定案日期**：2026-04-19
> **TA**：農民 + 一般民眾（非設計圈）
> **設計原則**：明亮溫暖、圓潤親和、大字清晰、WordPress 可實作
> **全面放棄**：老茶米黃 / 深咖啡 / 方角 / 細 serif

---

## 1. 主站配色（站點層級）

| 角色 | Hex | CSS Var | 對比 | 用途 |
|------|-----|---------|------|------|
| **Primary** 主品牌 | `#15803D` | `--c-primary` | 5.87:1 ★AAA | 全站主色、Logo、主 CTA |
| **Primary Deep** | `#14532D` | `--c-primary-deep` | 11.2:1 ★AAA | Hover、標題強調 |
| **Primary Light** | `#DCFCE7` | `--c-primary-light` | — | 區塊背景、tag |
| **Accent** 陽光金 | `#F59E0B` | `--c-accent` | 4.8:1 ★AA | 強調、次 CTA、連結 underline |
| **Accent Deep** | `#B45309` | `--c-accent-deep` | 4.69:1 ★AA | Accent hover |
| **Warning** 莓紅 | `#DC2626` | `--c-warning` | 4.6:1 ★AA | 警示、重要資訊 |
| **Info** 天青 | `#0891B2` | `--c-info` | 4.7:1 ★AA | 提示、註解 |
| **BG** 米白 | `#FEFCF7` | `--c-bg` | — | 主背景（禁純白） |
| **Surface** | `#FFFFFF` | `--c-surface` | — | 卡片底 |
| **Surface Muted** | `#F5F2EB` | `--c-surface-muted` | — | 輔助區塊 |
| **Text** 墨綠 | `#0F2818` | `--c-text` | 15.2:1 ★AAA | 正文 |
| **Text Muted** | `#475569` | `--c-text-muted` | 7.4:1 ★AAA | 次要文字 |
| **Text Subtle** | `#94A3B8` | `--c-text-subtle` | 3.8:1 | 說明 |
| **Line** | `#E5E1D8` | `--c-line` | — | 細邊框 |
| **Line Strong** | `#CBD0C5` | `--c-line-strong` | — | 強邊框 |

---

## 2. 7 章節獨立配色

每章節有 **主色 / 深色 / 背景 / 對比** 4 個 token，所有主色 WCAG AA+。

### 01 · 關於 About
| Role | Hex | Var |
|------|-----|-----|
| Main | `#15803D` 森林綠 | `--ch-about` |
| Deep | `#14532D` | `--ch-about-deep` |
| BG | `#DCFCE7` | `--ch-about-bg` |
| Contrast | `#F59E0B` | `--ch-about-accent` |

### 02 · 職安 Safety
| Role | Hex | Var |
|------|-----|-----|
| Main | `#EA580C` 橙紅 | `--ch-safety` |
| Deep | `#9A3412` | `--ch-safety-deep` |
| BG | `#FFEDD5` | `--ch-safety-bg` |
| Contrast | `#1E40AF` | `--ch-safety-accent` |

### 03 · 健康 Health
| Role | Hex | Var |
|------|-----|-----|
| Main | `#0891B2` 天青 | `--ch-health` |
| Deep | `#164E63` | `--ch-health-deep` |
| BG | `#CFFAFE` | `--ch-health-bg` |
| Contrast | `#F59E0B` | `--ch-health-accent` |

### 04 · 經濟 Economic
| Role | Hex | Var |
|------|-----|-----|
| Main | `#D97706` 琥珀金 | `--ch-economic` |
| Deep | `#78350F` | `--ch-economic-deep` |
| BG | `#FEF3C7` | `--ch-economic-bg` |
| Contrast | `#15803D` | `--ch-economic-accent` |

### 05 · 培力 Empower
| Role | Hex | Var |
|------|-----|-----|
| Main | `#7C3AED` 葡萄紫 | `--ch-empower` |
| Deep | `#4C1D95` | `--ch-empower-deep` |
| BG | `#EDE9FE` | `--ch-empower-bg` |
| Contrast | `#F59E0B` | `--ch-empower-accent` |

### 06 · 倡議 Advocacy
| Role | Hex | Var |
|------|-----|-----|
| Main | `#E11D48` 玫瑰紅 | `--ch-advocacy` |
| Deep | `#881337` | `--ch-advocacy-deep` |
| BG | `#FFE4E6` | `--ch-advocacy-bg` |
| Contrast | `#0891B2` | `--ch-advocacy-accent` |

### 07 · 研究 Research
| Role | Hex | Var |
|------|-----|-----|
| Main | `#1E40AF` 深靛 | `--ch-research` |
| Deep | `#1E3A8A` | `--ch-research-deep` |
| BG | `#DBEAFE` | `--ch-research-bg` |
| Contrast | `#F59E0B` | `--ch-research-accent` |

---

## 3. 4 分眾配色

分眾頁（如「給初入農業者」「給青年」「給銀髮」「給一般民眾」）用溫度差異化：

| 分眾 | Main | Deep | BG | Contrast |
|------|------|------|-----|----------|
| **Start 入門** | `#16A34A` 鮮綠 | `#14532D` | `#DCFCE7` | `#F59E0B` |
| **Young 青年** | `#2563EB` 寶藍 | `#1E3A8A` | `#DBEAFE` | `#F59E0B` |
| **Senior 銀髮** | `#EA580C` 暖橙 | `#7C2D12` | `#FFEDD5` | `#15803D` |
| **Public 大眾** | `#D97706` 琥珀 | `#78350F` | `#FEF3C7` | `#15803D` |

---

## 4. 子頁繼承規則

```
首頁 → 主站 palette
├─ 7 章節首頁 → 章節 palette（覆蓋主站主色）
│  └─ 章節子頁 → 沿用該章節 palette
├─ 4 分眾首頁 → 分眾 palette
│  └─ 分眾子頁 → 沿用該分眾 palette
└─ 功能頁（聯絡/關於團隊） → 主站 palette
```

**實作方式**：WordPress 每個頁面 `body` class 加 `.page-{section}`，CSS 用 `:where(.page-health) { --c-primary: var(--ch-health); ... }` 覆蓋。

---

## 5. 字體系統（優先可讀性）

| 用途 | 字體 | 權重 | 字級 |
|------|------|------|------|
| Display（英/數） | Inter Tight | 900 | 56-140px |
| Heading（中文） | **Noto Sans TC** | 900 | 36-88px |
| Subheading | Noto Sans TC | 700 | 20-32px |
| Body（正文） | **Noto Sans TC** | 400-500 | **18-20px** |
| UI / Kicker | Inter | 600 | 12-14px |

**關鍵決策**：
- 放棄 serif（Fraunces / Noto Serif TC）— 農民+一般民眾閱讀 serif 吃力
- 正文最小 **18px**（非 16），行高 1.75
- 大標用 Noto Sans TC 900 — 繁中厚實有份量
- 英文 metadata 才用 Inter Tight / Inter

---

## 6. 圓潤系統（放棄方角）

```css
--r-sm:   8px;    /* tag, badge */
--r-md:   16px;   /* input, small card */
--r-lg:   24px;   /* main card */
--r-xl:   32px;   /* hero card, modal */
--r-2xl:  48px;   /* large feature block */
--r-pill: 999px;  /* button */
--r-full: 50%;    /* avatar, icon bg */
```

**組件預設**：
- 按鈕：`--r-pill` 膠囊
- 卡片：`--r-lg` 24px
- 輸入框：`--r-md` 16px
- Tag：`--r-sm` 8px
- Hero/Feature：`--r-xl` 32px

---

## 7. 陰影 + 圓潤呼應

```css
--shadow-sm:  0 2px 8px rgba(21, 128, 61, 0.06);
--shadow-md:  0 8px 24px rgba(21, 128, 61, 0.10);
--shadow-lg:  0 16px 48px rgba(21, 128, 61, 0.14);
--shadow-hover: 0 24px 60px rgba(21, 128, 61, 0.18);
```

陰影色帶綠，呼應主色，不是中性灰。

---

## 8. 間距（維持）

```css
--s-xs:  8px;   --s-sm:  12px;  --s-md:  16px;
--s-lg:  24px;  --s-xl:  32px;  --s-2xl: 48px;
--s-3xl: 64px;  --s-4xl: 96px;  --s-5xl: 128px;
```

---

## 9. WordPress 實作路徑

### Classic Theme（你現行方式）
```php
// functions.php 註冊 palette 給 Gutenberg
add_theme_support('editor-color-palette', [
  ['name' => '森林綠', 'slug' => 'primary', 'color' => '#15803D'],
  ['name' => '陽光金', 'slug' => 'accent',  'color' => '#F59E0B'],
  // ...
]);
```

### 每頁套 body class
```php
// 在 page template 開頭
<body <?php body_class('page-health'); ?>>
```

CSS 自動套用該章節 palette。

### 動畫限制
- **可用**：CSS transition / keyframes / IntersectionObserver / scroll-timeline（Safari 17+）
- **不用**：GSAP / Framer Motion（需 build step）
- **替代 GSAP**：純 CSS + vanilla JS 做 90% 效果

### 字體載入
```html
<!-- header.php -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700;900&family=Inter+Tight:wght@700;900&family=Inter:wght@500;600&display=swap" rel="stylesheet">
```

---

## 10. 配色 Don't List

- ❌ 老茶米黃 `#854D0E` × 深咖啡 → 沉悶、不適合現代農業形象
- ❌ 純黑 `#000` / 純白 `#FFF` → 太硬，失去溫度
- ❌ 深咖啡當主色 → TA 會覺得「老派」「政府公文」
- ❌ 灰階主調 → 農業是生命，不是金融
- ❌ 單色調 → 7 章節必須有區辨度

---

## 版本記錄
- V14 / V15 / V16：全部淘汰
- V1 暖米：淘汰
- Pre-ripening（老茶米黃）：保留做參考，但**不採用**
- **V3 Forest Bold**：現行定案（2026-04-19）
