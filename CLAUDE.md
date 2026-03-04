# CLAUDE.md — AI Collaboration Guide for farmerwordpress

## Project Overview

**Project:** 尊嚴農業 (Farmer Dignity Initiative)
**Full Name:** 農業尊嚴：共建雲嘉農業工作者職業健康社會安全網
**Purpose:** Custom frontend code for a WordPress website focused on agricultural worker occupational health and safety in Taiwan's Yunlin & Chiayi region.

All HTML files are designed to be pasted directly into WordPress page editor (code mode) — there is no build system or deployment pipeline.

## Required Reading

**Before any page development task, read [`docs/architecture.md`](./docs/architecture.md) first.** It is the single source of truth for site structure, brand specs, interface design, and page-building guidelines.

Other key docs:
- `docs/article-category-slugs.md` — WordPress category taxonomy and WP_Query examples
- `docs/audience-landing-pages.md` — 4 audience segment page specifications and color schemes
- `docs/occupational-safety-data-enhancement.md` — International data sources and citations
- `docs/import-map.md` — File mapping for batch page imports

## Tech Stack

| Layer | Technology |
|-------|-----------|
| CMS | WordPress with Astra theme |
| Frontend | Custom HTML5 / CSS3 / inline JavaScript (no framework) |
| Fonts | Google Fonts (Noto Sans TC, Lato, Lora) |
| Icons | Font Awesome 6.4.2 (CDN) |
| Shortcodes | Custom WordPress PHP shortcodes |
| Scripts | Bash (page import automation) |

**No build tools** — no Webpack, Vite, Gulp, or preprocessors. HTML is authored and pasted directly into WordPress.

## Directory Structure

```
farmerwordpress/
├── css/
│   └── global.css                # Site-wide CSS extending Astra theme
├── docs/                         # Design & implementation documentation
│   ├── architecture.md           # PRIMARY REFERENCE — complete site blueprint
│   ├── article-category-slugs.md # WordPress category taxonomy mapping
│   ├── audience-landing-pages.md # Audience segmentation specs
│   ├── occupational-safety-data-enhancement.md
│   └── import-map.md             # External file import mapping
├── pages/                        # WordPress page HTML blocks by section
│   ├── home/                     # Homepage + shortcodes
│   ├── About/                    # 01 About the Program
│   ├── occupational-safety/      # 02 Occupational Safety (3 subsections)
│   ├── healthgood/               # 03 Health Promotion (4 subsections)
│   ├── economic-insurance/       # 04 Economic & Insurance (5 subsections)
│   ├── farmer-study/             # 05 Empowerment & Practice
│   ├── dignity-farming-initiative/ # 06 Dignity Farming Advocacy
│   ├── research-result/          # 07 Research Outcomes (5 subsections)
│   ├── beginner-farmers/         # Audience: Beginner Farmers
│   ├── young-farmers/            # Audience: Young Farmers
│   ├── experienced-farmers/      # Audience: Experienced Farmers
│   └── Public/                   # Audience: General Public
├── imports/                      # Complete HTML files for batch import
├── shortcodes/                   # WordPress PHP shortcodes
├── scripts/
│   └── import_pages.sh           # Batch import from imports/ to pages/
└── README.md
```

## Site Architecture (Matrix Model)

The site uses a matrix information architecture with two axes:

**Vertical axis — 7 main database menus (縦軸):**

| # | Menu | Slug | Subsections |
|---|------|------|-------------|
| 01 | 關於計畫 (About) | `about` | researchteam, contact |
| 02 | 職業安全 (Occupational Safety) | `occupational-safety` | greenhouse-zone, labor-saving-machinery, personal-protective-equipment |
| 03 | 健康促進 (Health Promotion) | `healthgood` | subpage-heat, subpage-pesticide, subpage-msd, subpage-mental |
| 04 | 經濟與保險 (Economic Insurance) | `economic-insurance` | agricultural-subsidy-resources, crop-insurance, farmer-worker-retirement-calculator, occupational-accident-insurance, worker-occupational-accident-calculator |
| 05 | 培力與實踐 (Empowerment) | `farmer-study` | — |
| 06 | 尊嚴農業倡議 (Advocacy) | `dignity-farming-initiative` | — |
| 07 | 研究成果 (Research) | `research-result` | download-zone, infographic, podcast, related-resources, research-publication |

**Horizontal axis — 4 audience segments (横軸):**

| Audience | Color Name | Primary HEX | Background HEX |
|----------|-----------|-------------|-----------------|
| 新手務農 (Start/Beginner) | Sage Green | `#87AE73` | `#EEF5EA` |
| 青農創業 (Pro/Young) | Slate Blue | `#5B7FA6` | `#E8EFF7` |
| 資深農友 (Senior) | Terracotta | `#C17F5E` | `#F9EDE7` |
| 一般公眾 (Public) | Harvest Gold | `#D4A017` | `#FDF4DC` |

## Brand & Design Specs

### Core Colors

| Name | HEX | Usage |
|------|-----|-------|
| Brand Green | `#5C8607` | Primary buttons, links, emphasis |
| Brand Hover | `#4a6b05` | Button hover states |
| Deep Green | `#2c5e2e` | Sidebar titles, tag hover |
| Background | `#E3E9D8` | Full page background |
| Accent Gold | `#D4A017` | Icons, decorative lines, highlights |

### Typography

- **Chinese:** Noto Sans TC (weights: 300, 400, 500, 700, 900)
- **English/Numbers:** Lato (weights: 400, 700, 900)
- **Body (optional):** Lora (weights: 400, 600 + italics)

### CSS Design Tokens

Use the `:root` CSS variables defined in `docs/architecture.md` section 3-2 (e.g., `--astra-brand`, `--astra-brand-hover`, `--astra-text`, etc.). New pages should reference these variables instead of hardcoded hex values.

## Critical Rules

### WordPress Embedding — FORBIDDEN Patterns

Never write these in `<style>` blocks:

```css
/* FORBIDDEN — breaks Astra theme */
body { ... }
* { margin: 0; padding: 0; }
.container { ... }
html { ... }
```

**Instead:**
- Use a unique wrapper class (e.g., `.risk-assessment-wrapper`) instead of `body` or `.container`
- Use `margin: 0 auto` on wrapper for centering instead of `display: flex` on body
- Scope all styles with page-specific prefixes

### CSS Class Naming Convention

Every page must use a **unique prefix** to prevent cross-page conflicts:

| Page | Prefix examples |
|------|----------------|
| Home | `.service-portal-*`, `.topic-*` |
| Occupational Safety | `.prev-*`, `.gh-*`, `.mach-*`, `.ppe-*` |
| Audience pages | `.start-*`, `.young-*`, `.senior-*`, `.public-*` |
| Research | `.res-*` |

Check `css/global.css` for existing shared classes (`.content-card`, `.my-team-card`, `.sidebar-*`, `.action-item`, `.social-btn`, `.tag-cloud`, `.link-grid`, etc.) and reuse them where appropriate.

### Font & Icon Loading

Load Google Fonts and Font Awesome **once per page**, at the top of the first HTML block:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700;900&family=Lato:wght@400;700;900&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
```

### Accessibility

- Senior audience pages: minimum 18px font, 48–60px button/touch target height
- Maintain high contrast ratios across all pages
- Use semantic HTML (`<header>`, `<section>`, `<article>`)

### Responsive Breakpoints

- Mobile: 640px
- Desktop: 1024px
- Sidebar: 992px
- Must match Astra theme breakpoints

### Data & Citation Standards

- **Format:** APA 7th Edition for all references
- **Prohibited:** Any mainland China sources — strictly forbidden
- **Preferred sources:** CDC/NIOSH, WHO, ILO, OSHA, BLS, Taiwan government statistics
- **Secondary sources:** Research from Japan, South Korea, Australia (similar aging farmer demographics)

## Common Page Structure

Most pages follow this pattern:

1. Font/icon `<link>` tags (once per page)
2. `<style>` block with scoped classes (unique prefix)
3. Hero section — title, subtitle, context
4. Content blocks — cards, tabs, grids
5. Interactive tools — quizzes, calculators, visualizations
6. Data/stats visualization
7. References (APA formatted)
8. Call-to-action section

## Deployment Workflow

1. Create/edit HTML content in the appropriate `pages/` subdirectory
2. Copy the complete HTML (including `<link>`, `<style>`, and structure)
3. In WordPress: open the target page → switch to **Code Editor** (not Visual Editor)
4. Replace entire page content and publish/update

### Batch Import

Place complete HTML files in `imports/` with names matching `docs/import-map.md`, then run:

```bash
bash scripts/import_pages.sh
```

## Language

- **Primary:** Traditional Chinese (Taiwan)
- **Technical terms:** English
- **Encoding:** UTF-8
- **Region focus:** Yunlin & Chiayi County (雲嘉地區)

## Development Phases

```
Phase 1 (complete)  — Homepage: audience navigation + 3 core topics
Phase 2 (active)    — /occupational-safety: full page rebuild
Phase 3 (pending)   — /healthgood: interactive body map + clinic map
                    — /economic-insurance: calculators
Phase 4 (pending)   — Audience landing pages (Start/Pro/Senior/Public)
                    — Farmer Academy LMS
                    — Research database
```
