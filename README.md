# 尊嚴農業 — farmerwordpress

> 農業尊嚴：共建雲嘉農業工作者職業健康社會安全網

## 這個 Repo 是什麼

此 Repository 存放「尊嚴農業」WordPress 網站的所有自訂前端程式碼，包含頁面 HTML 區塊、全站 CSS，以及 WordPress Shortcode 程式。

所有 HTML 檔案的內容設計為直接貼入 WordPress 頁面編輯器（程式碼模式），不需要額外佈署流程。

---

## 開始之前，請先閱讀

**→ [`/docs/architecture.md`](./docs/architecture.md)**

本文件包含完整的網站架構規劃、介面設計邏輯、品牌規格，以及所有頁面的建置指引。AI 協作工具在處理任何頁面任務前，應以此為首要參考依據。

---

## 目錄結構

```
farmerwordpress/
├── docs/
│   └── architecture.md      # 網站完整規劃藍圖（必讀）
├── css/
│   └── global.css           # 全站自訂 CSS（Astra 主題追加）
├── pages/
│   ├── home/                         # 首頁
│   ├── About/                        # 01 關於計畫
│   ├── occupational-safety/          # 02 職業安全
│   ├── healthgood/                   # 03 健康促進
│   ├── economic-insurance/           # 04 經濟與保險
│   ├── farmer-study/                 # 05 培力與實踐（農學堂）
│   ├── dignity-farming-initiative/   # 06 尊嚴農業倡議
│   ├── research-result/              # 07 研究成果
│   └── ...
└── shortcodes/              # WordPress PHP Shortcodes
```

---

## 品牌快速參考

| 項目 | 值 |
|------|----|
| 主色 | `#5C8607` |
| 背景 | `#E3E9D8` |
| 字型 | Noto Sans TC / Lato / Lora |

詳細設計規格請見 [`/docs/architecture.md`](./docs/architecture.md)。

---

## 最新版選單對照（WordPress）

> 依目前 WordPress 後台已調整名稱與代稱整理。

### 縱軸七大主選單

| 編號 | 主選單（中文） | 主選單（英文） | 代稱（slug） |
|------|----------------|----------------|--------------|
| 01 | 關於計畫 | About | about |
| 02 | 職業安全 | Occupational Safety | occupational-safety |
| 03 | 健康促進 | Health Promotion | healthgood |
| 04 | 經濟與保險 | Economic Insurance / Economic Security | economic-insurance |
| 05 | 培力與實踐（農學堂） | Empowerment / Farmer Study | farmer-study |
| 06 | 尊嚴農業倡議 | Dignity Farming Initiative / Advocacy | dignity-farming-initiative |
| 07 | 研究成果 | Research Results | research-result |

### 縱軸子選單

| 所屬主選單 | 子選單（中文） | 子選單（英文） | 代稱（slug） |
|------------|----------------|----------------|--------------|
| 關於計畫 | 研究團隊 | Research Team | researchteam |
| 關於計畫 | 聯絡我們 | Contact | contact |
| 職業安全 | 溫室專區 | Greenhouse Zone | greenhouse-zone |
| 職業安全 | 省工農機具 | Labor-saving Machinery | labor-saving-machinery |
| 職業安全 | 個人防護具 | Personal Protective Equipment | personal-protective-equipment |
| 健康促進 | 熱傷害 | Heat Hazard | subpage-heat |
| 健康促進 | 農藥安全 | Pesticide Safety | subpage-pesticide |
| 健康促進 | 肌肉骨骼 | Musculoskeletal Health | subpage-msd |
| 健康促進 | 心理健康 | Mental Health | subpage-mental |
| 經濟與保險 | 農業補助資源 | Agricultural Subsidy Resources | agricultural-subsidy-resources |
| 經濟與保險 | 農業保險 | Crop Insurance | crop-insurance |
| 經濟與保險 | 農民退休金試算表 | Farmer Retirement Calculator | farmer-worker-retirement-calculator |
| 經濟與保險 | 職業災害保險 | Occupational Accident Insurance | occupational-accident-insurance |
| 經濟與保險 | 農民職業災害試算表 | Worker Occupational Accident Calculator | worker-occupational-accident-calculator |
| 研究成果 | 下載專區 | Download Zone | download-zone |
| 研究成果 | 圖文資訊 | Infographic | infographic |
| 研究成果 | Podcast | Podcast | podcast |
| 研究成果 | 相關資源 | Related Resources | related-resources |
| 研究成果 | 研究出版 | Research Publication | research-publication |
