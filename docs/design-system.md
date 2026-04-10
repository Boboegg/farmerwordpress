# 尊嚴農業網站設計系統（品牌回正版）

> 更新日期：2026-04-09
> 適用範圍：全站 HTML 區塊、shortcode 輸出、Astra child theme 的 [css/global.css](../css/global.css)

## 1. 設計定位

此版以「品牌回正 + 全站一致」為目標：
- 可信與可讀：學術網站可以有設計，但資訊辨識與長文閱讀優先
- 分眾明確：四分眾必須有實質視覺差異，不是同模板換標題
- 互動可降級：JS 或動效失效時，內容仍完整可讀可操作

## 2. 品牌色彩（正式規格）

### 全站品牌色
- 主色（尊嚴綠）：#5C8607
- 背景色：#E3E9D8
- 輔色（稻金黃）：#D4A017
- Hover 深綠：#4A6B05
- 警示色：#8B2D14

### Token 對應（實作來源為 global.css）
- --brand / --color-brand：#5C8607
- --brand-deep / --color-brand-deep：#4A6B05
- --brand-accent / --color-accent：#D4A017
- --bg / --color-bg：#E3E9D8
- --bg-white / --color-bg-elevated：#F7F9F1

## 3. 四分眾差異配色

- 新手（start）：主色 #87AE73，背景 #EEF5EA
- 青農（young）：主色 #5B7FA6，背景 #E8EFF7
- 資深（senior）：主色 #C17F5E，背景 #F9EDE7
- 公眾（public）：主色 #D4A017，背景 #FDF4DC

上述色彩以 aud token 固定，不可再改成單一深綠吞掉分眾差異。

## 4. 字體與排版

### 字體家族（正式規格）
- 中文正文：Noto Sans TC
- 英文標題：Lato
- 英文襯線強調：Lora

### 版面節奏
- 字級 8 階：12 / 14 / 16 / 18 / 22 / 28 / 36 / 48
- Hero H1 上限：48px
- 正文行高建議：1.8 左右
- 長文閱讀寬度建議：640px

## 5. 共享元件（統一在 global.css）

以下元件必須集中維護，不在各頁重複造輪：
- 卡片：.fw-card
- CTA：.fw-cta-* / .fw-cta-row
- Tab：.fw-tabs / .fw-tab
- Stats：.fw-stats / .fw-stat
- Alert：.fw-alert
- Citation：.fw-citation
- Bottom Nav：.fw-bottom-nav / .fw-bottom-link

頁內 <style> 僅保留頁面差異樣式，不再複製共通元件樣式。

## 6. 動效規範（dc-v3）

保留：
- scroll progress
- reveal 進場
- 必要 hover / focus 回饋

限制：
- 移除或降級會降低可讀性的 pinned / parallax 特效
- 觸控裝置禁止 hover 錯位殘留
- prefers-reduced-motion 必須直接顯示內容
- JS 失效時不得出現內容隱藏

## 7. 結構與命名

- 全頁 wrapper：.fw-page
- 章節頁：.fw-page.ch-*
- 分眾頁：.fw-page.aud-*
- 原 prefix（prev- / hp- / money- / start- / young- / senior- / public- / adv- / res-）持續保留

## 8. 相容性約定

為避免舊頁面或 shortcode 斷樣，以下 alias 不得刪除：
- --brand-dark
- --brand-hover
- --brand-bg
- --radius
- --shadow

## 9. 單一事實來源

設計與實作以以下檔案為準：
- [css/global.css](../css/global.css)
- [docs/design-system.md](./design-system.md)
- [docs/architecture.md](./architecture.md)
