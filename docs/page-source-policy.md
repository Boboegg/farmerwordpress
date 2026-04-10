# 頁面來源與部署政策（Canonical Source Policy）

更新日期：2026-04-09

## 1. 目標

避免「有做頁面但不會上線」與「同一頁有多個版本」：

- 每個可部署頁面只允許一個 canonical source。
- `scripts/page-map.json` 只對應 canonical source。
- 非 `.html` 的歷史片段檔先保留內容，但視為 legacy，不直接部署。

## 2. Canonical 規則

- 可部署來源：`pages/**/*.html`
- 正式映射來源：`scripts/page-map.json` 已列出的路徑
- 非 `.html` 檔案：暫列為 legacy/manual fragments
- 空白檔（0 byte）：視為 placeholder，不可作為來源

## 3. 目前 canonical（已映射）

- pages/home/index.html
- pages/About/關於我們.html
- pages/occupational-safety/職業安全.html
- pages/healthgood/健康促進.html
- pages/economic-insurance/經濟與保險.html
- pages/farmer-study/農學堂.html
- pages/dignity-farming-initiative/倡議.html
- pages/research-result/研究成果.html
- pages/research-result/research-publication/研究出版.html
- pages/research-result/related-resources/相關資源.html
- pages/thenews/最新消息.html
- pages/test/測試頁面.html
- pages/beginner-farmers/新手務農.html
- pages/young-farmers/青農.html
- pages/experienced-farmers/資深農友.html
- pages/Public/一般民眾.html
- pages/research-result/download-zone/下載專區.html
- pages/research-result/podcast/PODCAST.html

## 4. legacy 收斂策略

- `pages/home/index.new.html.deprecated`：維持參考用途，不進部署。
- `pages/*/01~07` 與其他無副檔名內容檔：先保留，待你補 WordPress page ID 後，再轉為對應 `.html` canonical。
- `pages/shared/sidebar.html`：共用片段，不對應單獨 WordPress 頁面。

## 5. 稽核流程

每次改版後執行：

```bash
bash scripts/audit-page-coverage.sh
```

或輸出 JSON 給自動流程：

```bash
bash scripts/audit-page-coverage.sh --json
```

部署時 `scripts/deploy.sh` 也會自動執行覆蓋檢查（預設 `warn`）：

- `DEPLOY_COVERAGE_MODE=warn`：列出未映射 `.html`，但不中止部署（預設）
- `DEPLOY_COVERAGE_MODE=strict`：只要有未映射 `.html` 就中止部署
- `DEPLOY_COVERAGE_MODE=off`：略過覆蓋檢查

範例：

```bash
DEPLOY_COVERAGE_MODE=strict WP_PATH=/your/wp/path bash scripts/deploy.sh
```

## 6. 擴充 page-map 建議

當你補上 WordPress page ID 時，優先處理：

1. 三大資料庫子頁（職安 / 健促 / 經保）
2. 研究成果補充子頁（含 infographic）
3. 其餘 legacy 檔案（確認是否仍需要）
