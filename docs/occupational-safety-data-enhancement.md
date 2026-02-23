# 職業安全頁面群：國際數據增補紀錄

> **修改日期**：2026-02-23  
> **影響範圍**：職業安全主頁面（01–07 區塊）＋ 3 個子頁面  
> **修改目的**：為所有職業安全相關頁面補充國際權威機構的量化數據與文獻引用，提升內容的學術可信度與說服力

---

## 一、引用數據總覽

以下為本次增補所使用的國際數據來源及關鍵統計數字，所有引用均排除中國大陸來源。

### 1.1 機構與文獻清單

| 代號 | 機構全名 | 主要引用文件 |
|------|----------|-------------|
| **CDC/NIOSH** | Centers for Disease Control and Prevention / National Institute for Occupational Safety and Health | Agricultural Safety (2024)、Hierarchy of Controls (2021)、Revised Lifting Equation |
| **OSHA** | Occupational Safety and Health Administration, U.S. Department of Labor | Agricultural Operations: Hazards & Controls、Heat Illness Prevention、PPE Standards |
| **BLS** | Bureau of Labor Statistics, U.S. Department of Labor | Census of Fatal Occupational Injuries (CFOI) |
| **WHO** | World Health Organization | Pesticide Safety (2022) |
| **ILO** | International Labour Organization | Safety and Health in Agriculture (2023) |

### 1.2 關鍵統計數字

| 數據 | 來源 | 使用位置 |
|------|------|----------|
| 農業致死率 **18.6 / 10 萬 FTE**，為全行業平均的 **5 倍** | CDC/NIOSH | 01 Hero、溫室 Hero、省工農機 Hero |
| 農業致死者中 **56% 年齡 ≥ 55 歲** | CDC/NIOSH | 01 Hero |
| 農業傷害中 **29% 為跌倒所致** | CDC/NIOSH | 02 區塊導讀、溫室人因卡、省工農機採收 |
| 2021–2022 年農業非致命傷害約 **21,020 件** | CDC/NIOSH | 05 區塊 |
| 全球使用超過 **1,000 種農藥** | WHO | 溫室化學卡 |
| 每年約 **38.5 萬例急性農藥中毒** | ILO | 溫室化學卡、省工農機田間管理 |
| 每年約 **11,000 人因農藥中毒死亡** | WHO | 個人防護具呼吸欄 |
| 全球約 **8.79 億** 農業勞動者 | ILO | 07 全球概況 |
| 農業佔全球勞動人口約 27%，卻佔職業致死事故**近半數** | ILO | 07 全球概況、個人防護具 Hero |
| **拖拉機翻覆**長期位居農業致死事故首位 | BLS (CFOI) | 省工農機 Hero |
| NIOSH 人工抬舉上限 **23 公斤** | NIOSH RLE | 省工農機搬運 |
| OSHA WBGT **28°C** 即進入警戒區 | OSHA Heat | 溫室熱危害卡 |
| 美國每年約 **20,000 件**工作場所眼部傷害 | BLS | 個人防護具眼部 |
| 美國約 **2,200 萬**勞工暴露於有害噪音 | CDC | 個人防護具聽力 |
| 農業工作者噪音性聽力損失發生率特別高 | CDC/NIOSH | 個人防護具聽力 |
| NIOSH 職業性哮喘與過敏性肺炎風險顯著提高 | NIOSH | 溫室生物卡 |
| 農藥經皮膚吸收佔總暴露量 **65%–90%** | 研究文獻 | 個人防護具手部 |
| 「過度勞累」（overexertion）為農業非致命傷害首因 | NIOSH | 省工農機整地 |
| 農保平均年齡 **67.3 歲**、農職保投保率約 **30%** | 台灣農保統計 | 07 台灣概況 |
| OSHA 農業 **12 大危害類別** | OSHA | 溫室 Hero |
| NIOSH 安全控制層級（五層）工程控制 > PPE | NIOSH | 02 導讀、溫室環控、省工農機 Hero、個人防護具 Hero |

---

## 二、主頁面 01–07 區塊修改明細

主頁面路徑：`pages/occupational-safety/01` ~ `pages/occupational-safety/07`  
組裝結果：`pages/occupational-safety/職業安全`（1,214 行）

### 01 — Hero 區塊
| 修改項目 | 說明 |
|----------|------|
| 新增數據段落 | 加入 CDC/NIOSH 致死率 18.6/10 萬 FTE（5× 全行業）、56% 致死者 ≥ 55 歲 |
| 強化語氣 | 將定性描述升級為定量佐證 |

### 02 — Tab 切換 + 省力農機 Grid Cards
| 修改項目 | 說明 |
|----------|------|
| 區塊導讀 | 加入 NIOSH 控制層級引用、CDC/NIOSH 29% 跌倒統計 |
| 資料來源標註 | 新增 `prev-section-source` 標示 CDC/NIOSH (2024) |
| ⚠️ 圖片標記 | 全部 8 張卡片的 Unsplash 圖片加上 `❗️ 圖片替換` 註解 |

### 03 — Tab 2 PPE 指南
| 修改項目 | 說明 |
|----------|------|
| 無須修改 | 此區塊使用 Font Awesome 圖示，無佔位圖片問題 |

### 04 — 互動測驗
| 修改項目 | 說明 |
|----------|------|
| 新增數據呼叫盒 | 頂部加入 `prevQ-data-callout` 提示框（NIOSH 統計農業傷害常見歸因） |
| 5 題提示強化 | 每題 hint 加入對應數據佐證（如 NIOSH 安全控制層級、OSHA 定義、CDC 跌倒統計） |

### 05 — 風險速查表
| 修改項目 | 說明 |
|----------|------|
| 新增統計段落 | 加入 CDC/NIOSH 2021–2022 年非致命傷害 21,020 件參考 |

### 06 — 危害辨識輪盤
| 修改項目 | 說明 |
|----------|------|
| 危害卡數據 | 各危害類型加入 WHO/NIOSH 數據佐證（農藥中毒、噪音暴露等） |
| 參考資料 | 從原本數量擴充至 3 筆 |

### 07 — 全球與台灣概況 + 參考資料
| 修改項目 | 說明 |
|----------|------|
| 新增區塊 | 「全球與台灣農業職災概況」段落，含 6 張統計卡片 |
| 卡片內容 | ILO 8.79 億勞動者、CDC/NIOSH 18.6/10 萬致死率、BLS CFOI、ILO 近半數致死佔比、台灣農保 67.3 歲 / 投保率 30%、農業 12 大危害 |
| 參考資料 | 從 5 筆擴充至 11 筆 |

---

## 三、子頁面修改明細

### 3.1 溫室專區

路徑：`pages/occupational-safety/greenhouse-zone/溫室專區`  
CSS 前綴：`gh-`  
修改次數：**8 處**

| # | 位置 | 修改說明 |
|---|------|----------|
| 1 | Hero | 新增 `gh-hero-data` 數據方塊：OSHA 12 類危害、CDC/NIOSH 5× 致死率、封閉空間加劇風險 |
| 2 | 熱危害卡 | 加入 OSHA WBGT 28°C 警戒閾值 |
| 3 | 化學危害卡 | 加入 WHO 1,000+ 農藥 + ILO 38.5 萬急性中毒/年 |
| 4 | 生物危害卡 | 「長期吸入黴菌孢子」→ NIOSH 職業性哮喘與過敏性肺炎風險提高 |
| 5 | 人因危害卡 | 加入 CDC/NIOSH 29% 跌倒 + 溫室濕滑地面風險 |
| 6 | 環境控制標題 | 加入 NIOSH 安全控制層級說明（工程控制 > PPE） |
| 7 | 參考資料 | 4 → 8 筆（新增 CDC/NIOSH agriculture、WHO pesticides、ILO agriculture、OSHA Heat） |
| 8 | CSS | 新增 `.gh-hero-data` 樣式區塊 |

### 3.2 省工農機具

路徑：`pages/occupational-safety/labor-saving-machinery/省工農機具`  
CSS 前綴：`mach-`  
修改次數：**8 處**

| # | 位置 | 修改說明 |
|---|------|----------|
| 1 | Hero | 新增 `mach-hero-data` 數據方塊：CDC/NIOSH 18.6/10 萬 FTE、BLS 拖拉機翻覆首因、NIOSH 層級 |
| 2 | 整地・痛點 | 加入 NIOSH「過度勞累」為非致命傷害首因，背部傷害佔比最高 |
| 3 | 田間管理・痛點 | 加入 ILO 全球每年 38.5 萬急性農藥中毒 |
| 4 | 採收・痛點 | 加入 CDC/NIOSH 29% 跌倒、墜落致死為主因之一 |
| 5 | 搬運・痛點 | 加入 NIOSH 修訂版人工抬舉方程式（RLE），單次上限 23 kg |
| 6 | 安全提醒導言 | 加入 OSHA 農機事故（捲入旋轉部件、拖拉機翻覆）為主要致命傷害類型 |
| 7 | 參考資料 | 3 → 8 筆（新增 CDC/NIOSH、BLS CFOI、ILO、NIOSH RLE、WHO） |
| 8 | CSS | 新增 `.mach-hero-data` 樣式區塊 |

### 3.3 個人防護具（PPE）

路徑：`pages/occupational-safety/personal-protective-equipment/個人防護具`  
CSS 前綴：`ppe-`  
修改次數：**7 處**

| # | 位置 | 修改說明 |
|---|------|----------|
| 1 | Hero | 新增 `ppe-hero-data` 數據方塊：NIOSH PPE 第 5 層定位、ILO 農業致死近半數、OSHA 正確 PPE 降低暴露 |
| 2 | 眼部防護 | 加入 BLS 每年約 20,000 件工作場所眼傷，農林漁牧佔比顯著 |
| 3 | 呼吸防護 | 加入 WHO 每年約 11,000 人農藥中毒死亡，呼吸道吸入為主要途徑 |
| 4 | 手部防護 | 加入皮膚吸收佔總暴露量 65%–90%，手部為最主要接觸部位 |
| 5 | 聽力防護 | 加入 CDC 約 2,200 萬勞工暴露有害噪音，農業特別高 |
| 6 | 參考資料 | 5 → 9 筆（新增 CDC/NIOSH agricultural safety、WHO、ILO、BLS CFOI） |
| 7 | CSS | 新增 `.ppe-hero-data` 樣式區塊 |

---

## 四、新增 CSS 元件

本次增補為 3 個子頁面各新增一個數據呼叫盒（data callout），樣式統一但配色依頁面主題而異：

```css
/* 溫室專區 */
.gh-hero-data {
  background: rgba(76,175,80,.07);
  border-left: 4px solid #4caf50;
  /* ... */
}

/* 省工農機具 */
.mach-hero-data {
  background: rgba(21,101,192,.06);
  border-left: 4px solid #1565c0;
  /* ... */
}

/* 個人防護具 */
.ppe-hero-data {
  background: rgba(198,40,40,.06);
  border-left: 4px solid #c62828;
  /* ... */
}
```

04 互動測驗也新增了 `.prevQ-data-callout` 樣式。

---

## 五、參考資料完整列表

以下為本次增補中所有引用的文獻，按 APA 第 7 版格式排列：

1. Bureau of Labor Statistics. (2024). *Census of Fatal Occupational Injuries (CFOI)*. U.S. Department of Labor. https://www.bls.gov/iif/fatal-injuries.htm

2. CDC/NIOSH. (2024). *Agricultural safety*. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/agricultural-safety/about/index.html

3. ILO. (2023). *Safety and health in agriculture*. International Labour Organization. https://www.ilo.org/global/topics/safety-and-health-at-work/industries-sectors/WCMS_219866/lang--en/index.htm

4. NIOSH. (2021). *Hierarchy of controls*. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/hierarchy-of-controls/about/index.html

5. NIOSH. (2021). *Revised Lifting Equation (RLE)*. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/lifting/about/index.html

6. OSHA. (n.d.). *Agricultural operations: Hazards & controls*. United States Department of Labor. https://www.osha.gov/agricultural-operations/hazards

7. OSHA. (n.d.). *Heat illness prevention*. United States Department of Labor. https://www.osha.gov/heat-exposure

8. OSHA. (n.d.). *Personal protective equipment*. United States Department of Labor. https://www.osha.gov/personal-protective-equipment

9. WHO. (2022). *Pesticide safety*. World Health Organization. https://www.who.int/news-room/questions-and-answers/item/chemical-safety-pesticides

10. 農業部農糧署（年度更新）。農機補助專區。https://www.afa.gov.tw/cht/index.php?code=list&ids=3314

11. 勞動部勞動法令查詢系統。職業安全衛生設施規則。https://laws.mol.gov.tw/FLAW/FLAWDAT0201.aspx?id=FL015021

---

## 六、待辦事項

- [ ] **圖片替換**：02 區塊（Tab 1 省力農機）的 8 張卡片仍使用 Unsplash 佔位圖，需替換為實際農機照片（已標記 `❗️ 圖片替換`）
- [ ] **台灣本土數據**：目前以國際數據為主，未來可加入農委會（農業部）、勞動部職安署的台灣本土統計數據
- [ ] **定期更新**：BLS CFOI、CDC/NIOSH Agricultural Safety 等資料每年更新，建議每年初檢查數據是否有新版本
