# GitHub Actions Gemini AI 啟用指南

此文件說明如何啟用 `.github/workflows/gemini-*.yml` 的 11 個 Gemini AI workflow，以及**如何避免被收費**。

---

## 安全設定原則（必讀）

⚠️ **絕對不要在 Google Cloud Console 啟用 billing**。本設定確保零成本：

1. 用 **Gemini API key**（從 [AI Studio](https://aistudio.google.com/apikey) 申請，**不綁信用卡**）
2. 強制使用 **Gemini 2.5 Flash**（有免費 tier）
3. 不使用 Vertex AI / GCP（那需要 billing）
4. 達到免費 tier 上限 → 自動 rate limited，**永不收費**

---

## 一次性設定（5 分鐘）

### 1. 申請 Gemini API key

1. 前往 https://aistudio.google.com/apikey
2. 用 Bobo 的 Google 帳號登入（zx741236zx@gmail.com）
3. 點 **Create API key** → 選 "Create API key in new project"
4. 複製產生的 key（格式 `AIzaSy...`）
5. **絕對不要**之後到 Google Cloud Console 啟用 billing

### 2. 在 farmerwordpress repo 設定 secrets 與 variables

到 https://github.com/Boboegg/farmerwordpress/settings/secrets/actions 設定：

**Secrets（機密）**：

| Name | Value |
|---|---|
| `GEMINI_API_KEY` | 步驟 1 拿到的 API key |

**Variables（公開變數）**：到 Variables 分頁設定：

| Name | Value | 用途 |
|---|---|---|
| `GEMINI_MODEL` | `gemini-2.5-flash` | **強制用 Flash 模型確保免費** |
| `GEMINI_CLI_VERSION` | `latest` | 用最新版 Gemini CLI |
| `GEMINI_DEBUG` | `false` | 關掉 debug 輸出 |

**❌ 不要設定的變數**（因為會觸發付費路徑）：
- `GOOGLE_CLOUD_PROJECT`、`GCP_WIF_PROVIDER`、`SERVICE_ACCOUNT_EMAIL`：這些是 Vertex AI 路徑，需要 billing
- `GOOGLE_GENAI_USE_VERTEXAI`、`GOOGLE_GENAI_USE_GCA`：強制走 GCP 路徑
- `GOOGLE_API_KEY`：Vertex AI key

### 3. 確認 .gitignore 包含必要項目

打開 `farmerwordpress/.gitignore` 確認有以下兩行（已加入）：

```
.gemini/
gha-creds-*.json
```

---

## 啟用 workflow 後會發生什麼事

設定完成後，**任何人在 farmerwordpress repo 做以下動作都會自動觸發 Gemini AI**：

| 觸發方式 | 哪個 workflow 接 | 做什麼 |
|---|---|---|
| 開新 PR | `gemini-review.yml` | 自動 code review，留 comment 在 PR |
| 開新 issue | `gemini-triage.yml` | 自動分類，貼 label，建議優先級 |
| PR 留言 `@gemini-cli /review` | `gemini-review.yml` | 手動觸發 review |
| Issue 留言 `@gemini-cli /triage` | `gemini-triage.yml` | 手動觸發 triage |
| Issue/PR 留言 `@gemini-cli /approve` | `gemini-plan-execute.yml` | 自動執行 plan 中的修改 |
| Issue/PR 留言 `@gemini-cli <自由指令>` | `gemini-invoke.yml` | 通用助手回應 |
| 排程觸發（每天） | `gemini-scheduled-triage.yml` | 定期 triage 累積的 issues |

### 安全機制

- **只有 OWNER / MEMBER / COLLABORATOR 可以觸發**——隨機網友來留 `@gemini-cli` 不會啟動（dispatch.yml 內建的 author_association 檢查）
- **fork PR 不會觸發 review**——避免外人惡意 PR 浪費 quota
- **每個 workflow 7 分鐘 timeout**——避免卡死燒 quota

---

## 如何測試運作中

設定完成後，**先跑一次驗證**：

1. 開一個測試 issue：「測試 Gemini integration」
2. 等 1-2 分鐘
3. 應該看到 `gemini-triage` workflow 跑起來，issue 會被 Gemini 加 label 跟回應

如果失敗：
- 看 Actions 頁面的 log
- 確認 `GEMINI_API_KEY` secret 設對
- 確認 `GEMINI_MODEL` 變數是 `gemini-2.5-flash`

---

## 想關掉怎麼辦

最簡單的做法：到 repo Settings → Actions → Disable Actions，或者直接刪除 `.github/workflows/gemini-*.yml` 檔案。

API key 也可以到 AI Studio 撤銷（不需要從電腦移除）。

---

## Phase 2 升級路徑（之後如果有需要）

當前設定是「**最小成本零風險**」。未來如果工作量真的很大、Flash 不夠用，再考慮：

1. 升級到 Gemini 2.5 Pro（付費 tier，但仍便宜）
2. 用 Vertex AI + Workload Identity Federation（更安全但需要 GCP project）
3. 改用 Gemini Code Assist 路徑（OAuth，但 GitHub Action runner 不能 OAuth，這條路在 CI 上行不通）

但**現在這個設定已經夠 farmerwordpress 用很久了**，不需要急著升級。

---

## 相關文件

- run-gemini-cli 官方 repo：https://github.com/google-github-actions/run-gemini-cli
- Gemini API pricing：https://ai.google.dev/pricing
- AI Studio：https://aistudio.google.com/apikey
- 本 repo 的 AGENTS.md：[`../AGENTS.md`](../AGENTS.md)
