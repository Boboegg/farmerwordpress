# 尊嚴農業網站設計系統 — V1 暖米定案

> 更新日期：2026-04-10
> 適用範圍：全站 HTML 區塊、shortcode 輸出、Astra child theme 的 [css/global.css](../css/global.css)

## 1. 設計定位

- 可信與可讀：學術網站可以有設計，但資訊辨識與長文閱讀優先
- 分眾明確：四分眾必須有實質視覺差異，不是同模板換標題
- 互動可降級：JS 或動效失效時，內容仍完整可讀可操作
- 暖米基調：所有視覺元素在暖色系、大地色系方向調和

## 2. 品牌色彩 — V1 暖米（2026-04-10 定案）

### 全站品牌色
- 主色（橄欖綠）：#4A6932
- 深色：#354D22
- 背景色（暖米白）：#F7F4ED
- 章節輪替背景：#EFE9DD
- 卡片背景：#FDFCF8
- 輔色（蜂蜜金）：#B07E12
- 警示色：#8B2D14
- 資訊色：#2A6B5A

### Token 對應（實作來源為 global.css）
- --brand / --color-brand：#4A6932
- --brand-deep / --color-brand-deep：#354D22
- --accent / --color-accent：#B07E12
- --bg / --color-bg：#F7F4ED
- --bg-white / --color-bg-elevated：#FDFCF8
- --warn / --color-warn：#8B2D14
- --info / --color-info：#2A6B5A

### 新增顏色規則
- 必須在暖米色盤方向調和（暖色系、大地色系）
- 禁止冷色調（冷藍、冷灰、紫、粉）
- 對比度通過 WCAG AA（≥ 4.5:1）
- 先建 test/ HTML 預覽，確認後才寫進 global.css

## 3. 四分眾差異配色

- 新手（start）：主色 #5A7D48，深色 #4A6932，背景 #E8EDDF
- 青農（young）：主色 #4A6E8A，深色 #3A5D82，背景 #E4EEF5
- 資深（senior）：主色 #A8694A，深色 #8A5438，背景 #F5ECE4
- 民眾（public）：主色 #B8880F，深色 #9A6E08，背景 #FDF3D7

## 4. 字體與排版

### 字體家族（只用兩個，禁止其他）
- 中文標題：Noto Serif TC
- 中文正文：Noto Sans TC

### 版面節奏
- 字級 8 階：12 / 14 / 16 / 18 / 22 / 28 / 36 / 48
- Hero H1 上限：48px
- 正文最小：18px（中高齡 override）
- 正文行高：1.85
- 長文閱讀寬度：640px

## 5. 共享元件（統一在 global.css）

以下元件集中維護，不在各頁重複造輪：
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
- 原 prefix 持續保留

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
- memory `reference_dignity_farming_design_spec.md`
