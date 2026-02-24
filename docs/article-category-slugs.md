# WordPress 文章分類代稱對照表

> **用途**：記錄本網站所有 WordPress 文章分類（Category）的中文名稱與英文代稱（slug），避免建立文章或開發功能時混淆。
> **排序**：階層式遞增（父分類 → 子分類）
> **最後更新**：2026-02-23

---

## 分類總覽（階層式）

| 層級 | 中文名稱 | 英文代稱 (slug) | 說明 |
|:----:|----------|-----------------|------|
| ■ | **安全裝備** | `equipment` | 職業安全裝備相關文章 |
| └─ | 個人防護 | `ppe` | 個人防護具 |
| └─ | 省力機具 | `assistive-tech` | 省工農機具 |
| └─ | 影音與教材 | `media` | 安全裝備影音教材 |
| ■ | **Podcast** | `podcast` | Podcast 節目 |
| ■ | **Youtube** | `youtube` | YouTube 影片 |
| ■ | **下載中心** | `downloads` | 檔案下載中心 |
| └─ | 圖解懶人包 | `infographic` | 圖解懶人包 |
| └─ | 影片教學 | `video` | 教學影片 |
| ■ | **最新消息** | `news` | 最新消息總分類 |
| └─ | 媒體報導 | `press` | 媒體報導 |
| └─ | 活動快訊 | `event` | 活動快訊 |
| └─ | 網站公告 | `notices` | 網站公告 |
| ■ | **環境設施** | `environment` | 環境設施相關 |
| └─ | 溫室 | `greenhouse` | 溫室專區 |
| ■ | **研究出版** | `research` | 研究出版品 |
| └─ | 年度報告 | `annual-reports` | 年度報告 |
| └─ | 政策白皮書 | `policy-papers` | 政策白皮書 |
| └─ | 數據圖表 | `data-viz` | 數據視覺化圖表 |
| ■ | **經濟保險** | `insurance` | 經濟與保險相關 |
| └─ | 職災保險 | `occupation-ins` | 職業災害保險 |
| └─ | 補助資源 | `ins-subsidy-insurance` | 農業補助資源 |
| └─ | 農作物保險 | `crop-ins` | 農作物保險 |
| ■ | **職業健康** | `health` | 職業健康促進 |
| └─ | 熱危害 | `heat-stress` | 熱傷害防護 |
| └─ | 農藥安全 | `chemical-safety` | 農藥安全使用 |
| └─ | 骨骼肌肉 | `musculoskeletal` | 肌肉骨骼傷害預防 |
| ■ | **農學堂** | `agri-school` | 農學堂專區 |
| └─ | 課程活動 | `education-farmer` | 課程與活動 |
| ■ | **勞動圖像** | `labor-image` | 農民勞動圖像蒐集 |
| └─ | 稻作 | `rice-farming` | 稻作勞動圖像 |

---

## 快速查詢（按英文 slug 字母排序）

| slug | 中文名稱 | 父分類 |
|------|----------|--------|
| `agri-school` | 農學堂 | — |
| `annual-reports` | 年度報告 | 研究出版 |
| `assistive-tech` | 省力機具 | 安全裝備 |
| `chemical-safety` | 農藥安全 | 職業健康 |
| `crop-ins` | 農作物保險 | 經濟保險 |
| `data-viz` | 數據圖表 | 研究出版 |
| `downloads` | 下載中心 | — |
| `education-farmer` | 課程活動 | 農學堂 |
| `environment` | 環境設施 | — |
| `equipment` | 安全裝備 | — |
| `event` | 活動快訊 | 最新消息 |
| `greenhouse` | 溫室 | 環境設施 |
| `health` | 職業健康 | — |
| `heat-stress` | 熱危害 | 職業健康 |
| `infographic` | 圖解懶人包 | 下載中心 |
| `ins-subsidy-insurance` | 補助資源 | 經濟保險 |
| `insurance` | 經濟保險 | — |
| `labor-image` | 勞動圖像 | — |
| `media` | 影音與教材 | 安全裝備 |
| `musculoskeletal` | 骨骼肌肉 | 職業健康 |
| `news` | 最新消息 | — |
| `notices` | 網站公告 | 最新消息 |
| `occupation-ins` | 職災保險 | 經濟保險 |
| `podcast` | Podcast | — |
| `policy-papers` | 政策白皮書 | 研究出版 |
| `ppe` | 個人防護 | 安全裝備 |
| `press` | 媒體報導 | 最新消息 |
| `research` | 研究出版 | — |
| `rice-farming` | 稻作 | 勞動圖像 |
| `video` | 影片教學 | 下載中心 |
| `youtube` | Youtube | — |

---

## WP_Query 使用範例

```php
// 撈取某分類文章（以 podcast 為例）
$args = array(
    'category_name' => 'podcast',  // 使用 slug
    'posts_per_page' => 10,
    'orderby' => 'date',
    'order' => 'DESC',
);
$query = new WP_Query($args);

// 撈取父分類及所有子分類文章（以 news 為例）
$args = array(
    'cat' => get_cat_ID('news'),  // 會包含 press, event, notices
    'posts_per_page' => 10,
);
```

---

## 備註

- 所有 slug 均為小寫英文，單字間以 `-` 連接
- 父分類文章會自動包含子分類，若只要特定子分類請直接指定子分類 slug
- 新增分類時請同步更新本文件
