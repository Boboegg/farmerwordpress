# 尊嚴農業 WordPress 網站程式

> 現行狀態：自訂 WordPress theme `fwdignity`，LocalWP 持續建置；本 repo 不再使用舊 Astra、V15 FUSION、`css/global.css` 或貼入頁面 HTML 作為現行架構。

## 開工入口

任何 AI 或開發者依序讀取：

1. [AGENTS.md](./AGENTS.md)
2. [網站營運系統 START-HERE](../../START-HERE.md)
3. [專案進度](../../progress.md)
4. [目前狀態](../../status.md)
5. [現行規格索引](../../01_現行規格/網站現行規格索引.md)
6. [現行公開內容狀態](../../02_內容流程/現行公開內容狀態.md)
7. 實際 LocalWP：`https://fwdignity-local.local/`

若文字紀錄與 LocalWP、WordPress 資料或 theme 不一致，先查實際狀態，再更新紀錄；不得從 `docs/`、`design-system/`、舊 `pages/`、舊 `test/` 或歷史 worklog 推定現況。

## 現行程式

- 主要 theme：`themes/fwdignity/`
- 現行視覺來源：`themes/fwdignity/assets/css/`，角色與優先序以「清朗田野」規格為準。
- 本機維運及回歸檢查：`scripts/`
- 匯入、修正與驗證證據：`worklogs/`

## 現行網站架構

頂層導覽固定為：首頁、田野誌、聽見田裡的聲音、田間知識與資源、研究與行動、認識尊嚴農業。「全部內容」沿用 `/thenews/`，收在首頁子選單。

資訊架構、路由、分類契約與公開數量不得在本檔另立版本；只讀網站營運系統的 v4 規格與現行公開內容狀態。

## Git 與發布邊界

- 工作樹含大量既有未提交變更，禁止 reset、clean、整批 restore 與 `git add -A`。
- 未獲 Bobo 拍板詞「上傳」不得 push；本輪亦不得自行 commit。
- LocalWP 修改不等於正式站發布。正式 WordPress 只有 Bobo 明確說「發布」才可變更。
- 既有文章、研究資料、數字、照片與附件未獲授權不得改寫或刪除。

## 歷史資料

本檔 2026-07-15 前的舊 Astra／七大選單／V15 說明已完整保存於：

`../../99_歷史資料索引/舊交接/README-site-code-legacy-2026-07-15.md`

歷史資料只供追溯，不是現行操作說明。
