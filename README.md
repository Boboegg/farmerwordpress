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
│   ├── home/                # 首頁
│   ├── prevention/          # 02 職業安全
│   ├── healthgood/          # 03 健康促進
│   ├── money/               # 04 經濟與保險
│   ├── 農學堂/               # 05 培力與實踐
│   ├── 尊嚴農業倡議/          # 06 倡議
│   ├── 研究成果/             # 07 研究成果
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
