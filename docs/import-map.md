# 外部頁面匯入對照

把完整 HTML 檔放到 `imports/` 後，可用 `scripts/import_pages.sh` 批次匯入到 `pages/`。

## 檔名對照

| 匯入檔名（imports） | 目標 pages 路徑 |
|---|---|
| `page-研究成果.html` | `pages/research-result/研究成果` |
| `page-尊嚴農業倡議.html` | `pages/dignity-farming-initiative/尊嚴農業倡議` |
| `page-農學堂.html` | `pages/farmer-study/農學堂` |
| `prevention-page-complete.html` | `pages/occupational-safety/職業安全` |
| `subpage-crop-insurance.html` | `pages/economic-insurance/crop-insurance/農業保險` |
| `subpage-greenhouse.html` | `pages/occupational-safety/greenhouse-zone/溫室專區` |
| `subpage-machine.html` | `pages/occupational-safety/labor-saving-machinery/省工農機具` |
| `subpage-ppe.html` | `pages/occupational-safety/personal-protective-equipment/個人防護具` |
| `subpage-subsidy.html` | `pages/economic-insurance/agricultural-subsidy-resources/農業補助資源` |
| `subpage-occupational-insurance.html` | `pages/economic-insurance/occupational-accident-insurance/職業災害保險` |
| `subpage-heat.html` | `pages/healthgood/subpage-heat/熱傷害` |
| `subpage-pesticide.html` | `pages/healthgood/subpage-pesticide/農藥安全` |
| `subpage-msd.html` | `pages/healthgood/subpage-msd/肌肉骨骼` |
| `subpage-mental.html` | `pages/healthgood/subpage-mental/心理健康` |

## 使用方式

1. 建立資料夾：`imports/`
2. 把完整原始檔放到 `imports/`，檔名需與上表一致。
3. 執行：`bash scripts/import_pages.sh`
4. 匯入完成後檢查對應頁面內容。
