# 尊嚴農業網站設計系統（整合版）

> 更新日期：2026-04-09
> 適用範圍：全站 HTML 區塊、shortcode 輸出、Astra child theme 的 [css/global.css](../css/global.css)

## 1. 設計定位

全站採用 S7 混合風格作為預設基線：
- Cinematic Documentary（50%）
- Editorial Motion（30%）
- Interactive Storytelling（20%）

核心原則：
- 學術嚴謹優先（APA 與來源可追溯）
- 視覺強度服務可讀性，不是炫技
- 中高齡可用性優先於效果密度

## 2. 色彩系統（稻穗熟成）

### 全站核心 token
- --bg: #FBFAF7
- --brand: #3D5A2C
- --brand-deep: #2A3F1E
- --brand-accent: #A8740B
- --brand-warning: #8B2D14
- --fg: #1F1A14
- --fg-muted: #5C5448
- --border: #D8CFB8

### 相容 token（color migration 專用）
- --color-bg: #FBFAF7
- --color-bg-elevated: #FFFFFF
- --color-text: #1F1A14
- --color-muted: #5C5448
- --color-line: #D8CFB8
- --color-brand: #3D5A2C
- --color-brand-deep: #3D5A2C
- --color-accent: #A8740B
- --color-warn: #8B2D14
- --color-info: #1565C0

## 3. 字體與排版

字體策略：
- 中文襯線：Noto Serif TC
- 中文無襯線：Noto Sans TC

排版規範：
- 字級 8 階：12 / 14 / 16 / 18 / 22 / 28 / 36 / 48
- Hero H1 上限：48px
- 內文行高：1.85
- 中文最佳閱讀欄寬：640px

## 4. 無障礙與動效

必須保留：
- skip-to-content
- focus-visible 外框
- prefers-reduced-motion 降級
- 觸控裝置隔離 hover

動效規範：
- UI 回饋：120-180ms
- 區塊進場：280-480ms
- 禁止無限循環吸睛動畫

## 5. 命名與 scope

- 全頁包覆 class：.fw-page
- 章節頁：.fw-page.ch-*
- 分眾頁：.fw-page.aud-*
- 新互動層使用 dc-v3 命名空間，避免污染 legacy 規則

## 6. 相容性約定

為避免舊頁面破版，保留以下 alias：
- --brand-dark
- --brand-hover
- --brand-bg
- --radius
- --shadow

說明：上述 alias 對應到現行 token，供舊的 inline style 與 shortcode 持續運作。

## 7. 單一事實來源

設計與實作以以下檔案為準：
- [css/global.css](../css/global.css)
- [docs/design-system.md](./design-system.md)
- [docs/architecture.md](./architecture.md)
