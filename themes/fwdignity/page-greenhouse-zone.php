<?php
/**
 * Template Name: 溫室專區
 * Slug: greenhouse-zone
 * fwdignity · page-greenhouse-zone.php
 * 溫室作業子頁(slug=greenhouse-zone)· 琥珀 #F59E0B
 * 🔴 紅線:文字 / 數字 / 引用 1:1 復用原頁。
 */
get_header();
?>

<div class="fw-page-layout">

<main class="os-sub" id="main" role="main">

  <a href="/occupational-safety/" class="os-back"><i class="fas fa-arrow-left"></i> 回到職業安全總覽</a>

  <!-- Hero -->
  <section class="os-sub-hero" style="background-image:linear-gradient(135deg,rgba(254,243,199,0.95) 0%,rgba(254,252,247,0.85) 45%,rgba(245,158,11,0.32) 100%),url(https://images.pexels.com/photos/31086217/pexels-photo-31086217.jpeg?w=1400&auto=compress);background-size:cover;background-position:center;">
    <div class="os-sub-hero-deco" aria-hidden="true">
      <i class="fas fa-warehouse os-sub-deco-1"></i>
      <i class="fas fa-sun os-sub-deco-2"></i>
    </div>
    <div class="os-sub-hero-inner">
      <span class="os-sub-badge"><i class="fas fa-warehouse"></i> 溫室安全專區</span>
      <h1 class="os-sub-title" data-hook-title>溫室<span class="accent">作業安全</span></h1>
      <p class="os-sub-tagline">密閉空間讓每一種風險都被放大。</p>
      <div class="os-sub-intro">
        <p>溫室裡的環境跟露天不一樣。因為密閉性高,溫室有四個特別的問題:<strong>更熱</strong>(比外面高 5-15°C)、<strong>農藥殘留更濃</strong>(通風不足時散不掉)、<strong>黴菌更多</strong>(高溫高濕是黴菌的最愛)、<strong>姿勢更受限</strong>(空間窄,經常要蹲著彎著)。</p>
        <p>這些風險在露天環境下可能還好,但在溫室裡會被放大好幾倍。了解溫室特有的危害,才能做對防護。</p>
      </div>
      <div class="os-sub-data">
        <h4><i class="fas fa-chart-line"></i> 數據佐證</h4>
        <p>美國職業安全衛生署（Occupational Safety and Health Administration [OSHA], n.d.）將高溫、農藥、呼吸系統危害列為農業重要危害類別，而溫室環境同時具備以上三者。2022 年美國農業致死率為全產業平均的 <strong>5 倍</strong>（每 10 萬 FTE 18.6 人）（National Institute for Occupational Safety and Health [NIOSH], 2024; U.S. Bureau of Labor Statistics [BLS], 2023），其中密閉空間作業的風險尤需重視。</p>
      </div>
    </div>
  </section>

  <!-- 溫室四大特有危害 -->
  <section class="os-sec os-gh-hazards">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-triangle-exclamation"></i> HAZARDS · 溫室四大特有危害</span>
      <h2 class="os-sec-title">跟露天相比,溫室環境有 <span class="accent">4 種加重風險</span></h2>
    </div>

    <div class="os-gh-grid">
      <div class="os-gh-card reveal" style="--gc:#C62828; --gcl:#FEF2F2;">
        <div class="os-gh-icon"><i class="fas fa-temperature-arrow-up"></i></div>
        <h3>熱危害(加重)</h3>
        <div class="os-gh-stat"><span class="os-gh-big">+5~15°C</span><span>溫室內部比外部高</span></div>
        <p>外面 30°C 時溫室裡可能超過 40°C。加上高濕度(常超過 80%),人體散熱效率急劇下降,是<strong>熱衰竭和中暑</strong>的高風險場景。美國 OSHA 高氣溫作業指引指出,WBGT（濕球黑球溫度,Wet-Bulb Globe Temperature）超過 <strong>28°C 即進入警戒區</strong>（OSHA, n.d.-b；勞動部職業安全衛生署,2024),溫室內極易越過此閾值。</p>
        <div class="os-gh-tip"><i class="fas fa-lightbulb"></i> 夏天中午(11:00-15:00)是最危險的時段,盡量避開（勞動部職業安全衛生署,2024）。</div>
      </div>

      <div class="os-gh-card reveal" style="--gc:#7B1FA2; --gcl:#FAF5FF;">
        <div class="os-gh-icon"><i class="fas fa-flask-vial"></i></div>
        <h3>化學性暴露(加重)</h3>
        <div class="os-gh-stat"><span class="os-gh-big">2~4 hr</span><span>施藥後最少通風時間</span></div>
        <p>溫室施藥後如果沒有充分通風就進去,農藥蒸氣的濃度會比露天高很多。全球使用超過 <strong>1,000 種農藥</strong>,直接接觸者面臨最高健康風險（World Health Organization [WHO], 2022）。密閉溫室更讓暴露濃度倍增。一定要遵守農藥標示的<strong>再進入間隔時間（restricted-entry interval, REI）</strong>（U.S. Environmental Protection Agency [EPA], 2023）。</p>
        <div class="os-gh-tip"><i class="fas fa-lightbulb"></i> 進入前先打開通風 2-4 小時,聞不到味道不代表安全。</div>
      </div>

      <div class="os-gh-card reveal" style="--gc:#2E7D32; --gcl:#F0FDF4;">
        <div class="os-gh-icon"><i class="fas fa-virus"></i></div>
        <h3>生物性危害(加重)</h3>
        <div class="os-gh-stat"><span class="os-gh-big">&gt; 80%</span><span>溫室常見相對濕度</span></div>
        <p>高溫高濕環境是<strong>黴菌繁殖</strong>的最佳條件。長期接觸黴菌孢子與有機粉塵的農業工作者,罹患<strong>職業性哮喘與過敏性肺炎</strong>的風險顯著提高（NIOSH, 2024）。溫室中蚊蟲密度也比戶外高。</p>
        <div class="os-gh-tip"><i class="fas fa-lightbulb"></i> 如果經常在溫室裡咳嗽或鼻子不舒服,要注意黴菌問題。</div>
      </div>

      <div class="os-gh-card reveal" style="--gc:#1565C0; --gcl:#EFF6FF;">
        <div class="os-gh-icon"><i class="fas fa-person-walking-arrow-loop-left"></i></div>
        <h3>人因性危害(加重)</h3>
        <div class="os-gh-stat"><span class="os-gh-big">空間窄</span><span>作業姿勢更受限</span></div>
        <p>溫室內走道窄、高度低,經常要<strong>蹲著、彎腰、側身</strong>進行移苗和採收。膝蓋和腰椎承受的壓力比露天作業更大。美國 2021–2022 年非致命農業傷害中約 <strong>三成為滑跌墜（slip, trip, fall）</strong>所致（NIOSH, 2024）,溫室濕滑地面更加劇滑倒風險。</p>
        <div class="os-gh-tip"><i class="fas fa-lightbulb"></i> 軌道式搬運機可以大幅減少溫室內的搬運需求。</div>
      </div>
    </div>
  </section>

  <!-- 環境控制 -->
  <section class="os-sec os-gh-controls reveal">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-sliders"></i> CONTROLS · 環境控制</span>
      <h2 class="os-sec-title">環境控制建議 — <span class="accent">讓溫室更安全</span></h2>
      <p class="os-sec-sub">在穿戴防護具之前,先把溫室的環境條件改善好,效果更好也更持久。根據 NIOSH 安全控制層級,<strong>工程控制（第三層）</strong>的保護效果遠優於僅依賴個人防護具（第五層）（NIOSH, 2021）。</p>
    </div>
    <div class="os-gh-ctrl-grid">
      <div class="os-gh-ctrl" style="--gc:#0EA5E9;">
        <div class="os-gh-ctrl-icon"><i class="fas fa-wind"></i></div>
        <h3>通風</h3>
        <ul>
          <li>設置進排氣風扇,每小時換氣至少 <strong>10-20 次</strong></li>
          <li>施藥後至少通風 <strong>2-4 小時</strong>再進入</li>
          <li>通風口位置要形成對流(一高一低效果最好)</li>
        </ul>
      </div>
      <div class="os-gh-ctrl" style="--gc:#C62828;">
        <div class="os-gh-ctrl-icon"><i class="fas fa-temperature-half"></i></div>
        <h3>溫濕度監測</h3>
        <ul>
          <li>安裝溫濕度計,放在作業高度的位置</li>
          <li>設定警戒值:溫度 <strong>&gt; 35°C</strong> 或濕度 <strong>&gt; 80%</strong> 時啟動降溫</li>
          <li>有條件的話用自動監測系統,超標會發出警報</li>
        </ul>
      </div>
      <div class="os-gh-ctrl" style="--gc:#F59E0B;">
        <div class="os-gh-ctrl-icon"><i class="fas fa-sun"></i></div>
        <h3>遮蔭降溫</h3>
        <ul>
          <li>遮光網遮光率 <strong>50-70%</strong>(太密會影響作物生長)</li>
          <li>水牆降溫系統或屋頂噴水系統效果好</li>
          <li>白色或銀色屋頂材料反射陽光,降低溫室內溫度</li>
        </ul>
      </div>
      <div class="os-gh-ctrl" style="--gc:#15803D;">
        <div class="os-gh-ctrl-icon"><i class="fas fa-clock"></i></div>
        <h3>作息調整</h3>
        <ul>
          <li>高溫時段（<strong>11:00-15:00</strong>）減少或暫停溫室作業（勞動部職業安全衛生署,2024）</li>
          <li>改為清晨或傍晚進入溫室</li>
          <li>每工作 <strong>30 分鐘</strong>到陰涼處休息並補充水分</li>
          <li>現場要備有飲用水和遮蔭休息區</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- SOP 4 步驟 -->
  <section class="os-sec os-gh-sop reveal">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-list-check"></i> SOP · 作業標準流程</span>
      <h2 class="os-sec-title">溫室作業<span class="accent">標準流程(SOP)</span></h2>
      <p class="os-sec-sub">依照「作業前 → 作業中 → 施藥後 → 作業後」四個階段,建立安全習慣。</p>
    </div>
    <div class="os-gh-sop-grid">
      <div class="os-gh-sop-card" style="--gc:#2E7D32;">
        <div class="os-gh-sop-num">1</div>
        <h3>作業前</h3>
        <ul>
          <li>檢查通風系統是否正常運轉</li>
          <li>確認溫度和濕度在安全範圍內</li>
          <li>穿戴適當的防護具</li>
          <li>準備好飲用水</li>
        </ul>
      </div>
      <div class="os-gh-sop-card" style="--gc:#0EA5E9;">
        <div class="os-gh-sop-num">2</div>
        <h3>作業中</h3>
        <ul>
          <li>每 30 分鐘到外面休息一次、喝水</li>
          <li>出現<strong>頭暈、噁心、心跳加快</strong>立即停止作業,移到陰涼處（熱中暑早期症狀；勞動部職業安全衛生署,2024）</li>
          <li>跟同事互相注意對方的狀況</li>
        </ul>
      </div>
      <div class="os-gh-sop-card" style="--gc:#7B1FA2;">
        <div class="os-gh-sop-num">3</div>
        <h3>施藥後</h3>
        <ul>
          <li>啟動全部通風設備</li>
          <li>確認通風時間滿足農藥標示的 REI 才能再進入（EPA, 2023）</li>
          <li>最少通風 2-4 小時,密閉溫室可能需要更久</li>
          <li>再次進入時穿戴呼吸防護具</li>
        </ul>
      </div>
      <div class="os-gh-sop-card" style="--gc:#E65100;">
        <div class="os-gh-sop-num">4</div>
        <h3>作業後</h3>
        <ul>
          <li>依正確順序脫除防護具(外到內)</li>
          <li>用肥皂徹底洗手,盡快沐浴</li>
          <li>記錄當日作業時數和環境溫濕度(自我管理用)</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- 急救準備 -->
  <section class="os-sec os-gh-emergency reveal">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-kit-medical"></i> EMERGENCY · 急救準備</span>
      <h2 class="os-sec-title">急救<span class="accent">準備</span></h2>
      <p class="os-sec-sub">溫室裡發生意外時要能馬上處理。以下是基本的準備:</p>
    </div>
    <div class="os-gh-emerg-grid">
      <div class="os-gh-emerg">
        <div class="os-gh-emerg-icon"><i class="fas fa-briefcase-medical"></i></div>
        <h4>急救箱</h4>
        <p>放在<strong>溫室入口處</strong>,方便取用。內容物:生理食鹽水、冰敷包、紗布、消毒液、OK 繃、剪刀。定期檢查有沒有過期。</p>
      </div>
      <div class="os-gh-emerg">
        <div class="os-gh-emerg-icon"><i class="fas fa-phone-volume"></i></div>
        <h4>急救聯絡卡</h4>
        <p>張貼在溫室入口明顯處。內容:<strong>119</strong>、最近的醫療院所電話和地址、家人聯絡電話、熱中暑急救步驟。</p>
      </div>
      <div class="os-gh-emerg">
        <div class="os-gh-emerg-icon"><i class="fas fa-fire-extinguisher"></i></div>
        <h4>中暑急救步驟</h4>
        <p>① 移到陰涼處 → ② 鬆開衣物 → ③ 用濕毛巾或冰袋冷敷脖子、腋下、鼠蹊部 → ④ 補充水分 → ⑤ 意識不清立刻打 119（勞動部職業安全衛生署,2024）</p>
      </div>
      <div class="os-gh-emerg">
        <div class="os-gh-emerg-icon"><i class="fas fa-bug"></i></div>
        <h4>蛇咬處置</h4>
        <p>雲嘉農田常見毒蛇:龜殼花、赤尾青竹絲。被咬後:<strong>保持冷靜</strong> → <strong>記住蛇的外觀</strong>(拍照更好)→ <strong>盡速就醫</strong>。不要嘗試吸毒液或用繩子綁紮。</p>
      </div>
    </div>
  </section>

  <!-- References (APA 7 全展開) -->
  <style>
    .os-refs-list.apa7 { list-style: none; padding: 0; }
    .os-refs-list.apa7 li { padding-left: 2em; text-indent: -2em; margin-bottom: .8em; line-height: 1.6; }
    .os-refs-list.apa7 li em { font-style: italic; }
    .os-refs-list.apa7 li a { word-break: break-all; }
  </style>
  <section class="os-sec os-refs">
    <div class="os-refs-open">
      <div class="os-sec-head">
        <span class="os-kicker"><i class="fas fa-book-open"></i> REFERENCES · 參考資料</span>
        <h2 class="os-sec-title" style="margin:0;">參考<span class="accent">資料</span></h2>
        <p class="os-refs-note">本頁內容依 APA 第 7 版格式引用下列公開資料。中文文獻在前,英文文獻在後。</p>
      </div>
      <ul class="os-refs-list apa7">
        <li>勞動部（n.d.）。<em>職業安全衛生設施規則</em>。勞動部勞動法令查詢系統。https://laws.mol.gov.tw/FLAW/FLAWDAT0201.aspx?id=FL015021</li>
        <li>勞動部職業安全衛生署（2024）。<em>高氣溫戶外作業勞工熱危害預防指引</em>。https://www.osha.gov.tw/</li>
        <li>National Institute for Occupational Safety and Health. (2021). <em>Hierarchy of controls</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/hierarchy-of-controls/about/index.html</li>
        <li>National Institute for Occupational Safety and Health. (2024). <em>Agricultural safety</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/agriculture/about/index.html</li>
        <li>Occupational Safety and Health Administration. (n.d.). <em>Agricultural operations: Hazards &amp; controls</em>. U.S. Department of Labor. https://www.osha.gov/agricultural-operations/hazards</li>
        <li>Occupational Safety and Health Administration. (n.d.-b). <em>Heat — Overview</em>. U.S. Department of Labor. https://www.osha.gov/heat</li>
        <li>U.S. Bureau of Labor Statistics. (2023). <em>Census of fatal occupational injuries, 2022</em> [Data set]. U.S. Department of Labor. https://www.bls.gov/iif/fatal-injuries.htm</li>
        <li>U.S. Environmental Protection Agency. (2023). <em>Worker Protection Standard (WPS): Restricted-entry intervals</em>. https://www.epa.gov/pesticide-worker-safety/agricultural-worker-protection-standard-wps</li>
        <li>World Health Organization. (2022). <em>Pesticide residues in food</em>. https://www.who.int/news-room/fact-sheets/detail/pesticide-residues-in-food</li>
      </ul>
      <h4 class="os-refs-subhead"><i class="fas fa-camera"></i> 圖片來源</h4>
      <p class="os-refs-note">Hero 背景取自 <a href="https://www.pexels.com/license/" target="_blank" rel="noopener">Pexels</a>(photo 31086217),依 Pexels License 可免費商用。</p>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
