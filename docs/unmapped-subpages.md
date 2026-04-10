# 未映射子頁清單（待補 WordPress Page ID）

更新日期：2026-04-09
來源：`bash scripts/audit-page-coverage.sh`

## 1. 未映射且為 `.html` 的非空檔案

- pages/research-result/page-研究成果-研究報告.html
- pages/shared/sidebar.html（共用片段，通常不需映射為單一 WP 頁面）

## 2. 未映射且為 legacy 非 `.html` 檔案

以下多為歷史分段稿、子頁來源或手動匯入來源，待補 ID 後建議逐步收斂成 `.html` canonical：

- pages/About/關於計畫
- pages/dignity-farming-initiative/尊嚴農業倡議
- pages/dignity-farming-initiative/說明檔
- pages/economic-insurance/01
- pages/economic-insurance/02
- pages/economic-insurance/03
- pages/economic-insurance/04
- pages/economic-insurance/05
- pages/economic-insurance/agricultural-subsidy-resources/說明檔
- pages/economic-insurance/agricultural-subsidy-resources/農業補助資源
- pages/economic-insurance/crop-insurance/農業保險
- pages/economic-insurance/farmer-worker-retirement-calculator/農民退休金試算表
- pages/economic-insurance/occupational-accident-insurance/職業災害保險
- pages/economic-insurance/worker-occupational-accident-calculator/農民職業災害試算表
- pages/economic-insurance/經濟與保險
- pages/experienced-farmers/experienced-farmers
- pages/healthgood/01
- pages/healthgood/02
- pages/healthgood/03
- pages/healthgood/04
- pages/healthgood/05
- pages/healthgood/06
- pages/healthgood/newfolder
- pages/healthgood/subpage-heat/subpage-heat
- pages/healthgood/subpage-heat/熱傷害
- pages/healthgood/subpage-mental/subpage-mental
- pages/healthgood/subpage-mental/心理健康
- pages/healthgood/subpage-msd/subpage-msd
- pages/healthgood/subpage-msd/肌肉骨骼
- pages/healthgood/subpage-pesticide/subpage-pesticide
- pages/healthgood/subpage-pesticide/農藥安全
- pages/healthgood/健康促進
- pages/home/index.new.html.deprecated
- pages/home/主頁
- pages/home/側邊欄工具箱
- pages/home/分眾入口
- pages/home/友善連結
- pages/home/快速連結
- pages/home/熱門關鍵字
- pages/home/社群媒體
- pages/occupational-safety/01
- pages/occupational-safety/02
- pages/occupational-safety/03
- pages/occupational-safety/04
- pages/occupational-safety/05
- pages/occupational-safety/06
- pages/occupational-safety/07
- pages/occupational-safety/greenhouse-zone/溫室專區
- pages/occupational-safety/labor-saving-machinery/省工農機具
- pages/occupational-safety/personal-protective-equipment/個人防護具
- pages/occupational-safety/職業安全
- pages/research-result/infographic/圖文資訊
- pages/thenews/最新消息

## 3. 空白 placeholder 狀態

目前 `empty_non_gitkeep = 0`，先前空白 alias 已改為 `deprecated-alias` 指示檔。

## 4. 下一步

1. 先在 WordPress 建立對應頁面並取得 page ID。
2. 將目標來源收斂成 `.html` canonical（每頁一份）。
3. 補進 `scripts/page-map.json` 後再跑：`bash scripts/audit-page-coverage.sh` 驗證。
