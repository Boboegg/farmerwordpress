# retired-20260716 — 死碼歸檔（退役＝歸檔不刪除）

2026-07-16 全站設計健檢 W2-B 歸檔。四檔經 grep 全 theme 確認零引用、零 enqueue：

- `v15-master.css`（1,360 行，V15 舊版樣式，clamp 字級／marquee／blob 動畫）
- `v15-master.js`（hero 逐字進場原型）
- `page-beginner-farmers.css`（page-audience.css 的未刪前身）
- `ticker.php`（品牌口號＋天氣跑馬燈，無模板 include；其樣式已自 home-polish.css 移除）

依據：06_網站檢查/2026-07-16-全站設計健檢/00-綜合報告.md §2.3。
若需復用，先按清朗田野 3.3.0 重寫，不得直接搬回。

2026-07-17 W3-P 增補：
- `class-nav-walker.php`（wp_nav_menu 全 theme 零呼叫的死抽象；導覽由 header.php 硬編碼＝v4 規格鎖定，同時移除 register_nav_menus 三組未用選單位置）
