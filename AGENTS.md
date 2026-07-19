# AGENTS.md — 尊嚴農業網站程式現行入口

本檔只保留現行工作規則。2026-07-14 前的舊版內容已歸檔至網站營運系統的 `99_歷史資料索引/舊交接/`，不得再用其中的舊色碼、舊導覽、Astra 架構或舊部署流程推定現況。

## 開工必讀

由本 repo 根目錄依序讀取：

1. `../../START-HERE.md`
2. `../../progress.md`
3. `../../status.md`
4. `../../01_現行規格/網站現行規格索引.md`
5. `../../01_現行規格/網站現行架構與路由-v4-2026-07-13.md`
6. `../../01_現行規格/網站現行設計規範-清朗田野-2026-07-13.md`
7. 實際 LocalWP：`https://fwdignity-local.local/`

文件與 LocalWP 不一致時，先查實際程式、資料與瀏覽器結果，再更新紀錄；不得拿舊報告代替現況。

## 專案身份

- 計畫全名：農業尊嚴：共構雲嘉農業工作者職業健康社會安全網（「共構」為 Bobo 2026-07-16 拍板用字）
- 執行團隊：國立中正大學 × 臺大醫院雲林分院
- 實踐場域：嘉義縣民雄鄉
- 核心工作：農業工作者職業健康、社會安全、田野公共敘事、知識轉譯與研究行動
- 主要程式：`themes/fwdignity/` 自訂 WordPress theme

## 現行架構摘要

- 頂層導覽固定為：首頁、田野誌、聽見田裡的聲音、田間知識與資源、研究與行動、認識尊嚴農業。
- 「全部內容」沿用 `/thenews/`，放在首頁子選單；不是頂層導覽，也不再稱「最新消息」或「內容資料庫」。
- 田野誌下設田野紀錄與做農的人；農學堂由田間知識與資源的「學習與支持」承接。
- 尊嚴農業倡議目前撤下公開入口，待內容修正後再決定是否重開。
- 同一內容只維護一份 canonical 正文，再以分類、標籤與策展關係多路抵達。

完整路由、分類字典與頁面責任只讀現行 v4 規格，不在本檔重複定義。

## 現行設計摘要

- 唯一現役視覺規格是 `../../01_現行規格/網站現行設計規範-清朗田野-2026-07-13.md`。
- 現行方向為「清朗田野 × 編輯紙本」；色彩、字體、字級、間距、幾何、陰影、縮圖、頁首、頁尾與九組頁型均以該檔為準。
- 不得從 `docs/`、`design-system/`、`design-samples/`、舊 worklogs 或 `HANDOFF.md` 取用舊色值與舊版面規則。
- Canonical 視覺資產位於：
  - `themes/fwdignity/assets/css/site-v2-tokens.css`
  - `themes/fwdignity/assets/css/site-v2-page-foundation.css`
  - `themes/fwdignity/assets/css/site-v3-layout-final.css`
  - `themes/fwdignity/assets/css/layout.css`
  - `themes/fwdignity/assets/css/site-parts.css`
- 不得在個別頁面另造同角色色票、字級、圓角、陰影或縮圖規則。

## 內容與資料規則

- 既有文章、研究資料、引用、日期、人物、數字、照片與附件，未獲 Bobo 明確授權不得改寫、刪除或捏造。
- 沒有資料就隱藏區塊或誠實標示，禁止 placeholder、假統計與假引用。
- 新文章先建立草稿與預覽；只有 Bobo 明確說「發布」才可更動正式 WordPress。
- 正式上站前須維持 APA 7、逐筆驗證引用 DOI／URL，並排除中國大陸機構來源。

## 程式與 Git 邊界

- 本目錄是獨立 Git repo；父層 `bobohome-agent-claude` 透過 `.gitignore` 排除本 repo 內容。
- 實體位置為網站營運系統的 `03_網站程式/site-code/`；`/Users/boboegg/farmerwordpress` 只是相容捷徑。
- LocalWP theme 捷徑直接指向本 repo 的 `themes/fwdignity/`。
- 不得執行 `git reset`、`git clean`、整批 `restore` 或 `git add -A`。
- 工作樹保留大量既有未提交變更；只修改明確在本次任務範圍內的檔案。
- 未獲 Bobo 的拍板詞「上傳」，不得 git push；也不得自行 commit。

## 實作規則

- 所有介面文字、文件與註解使用繁體中文與台灣用語；程式變數與 class 可用英文。
- PHP、CSS、JavaScript 與 template 修改應先找共用來源，避免以頁面專屬覆寫修補全站問題。
- PHP shortcode 與 WordPress 設定須以 LocalWP 實際現況為準，不假設舊 Code Snippets 或舊 Astra 流程仍有效。
- 不得修改 WordPress 核心、`wp-config.php`、`.htaccess`、秘密金鑰或不在任務範圍內的資料庫內容。
- 部署腳本、匯入腳本與資料庫寫入都可能改變外部狀態；沒有明確授權只可檢查，不可執行。
- 測試與瀏覽器檢查以變更風險為準；不得用舊的通過紀錄宣稱新畫面已驗收。

## 本輪特別限制

- 不 reset、不 clean、不 commit、不 push。
- 本輪資料夾整併不等於正式站發布；LocalWP 與正式 WordPress 必須分開判斷。
