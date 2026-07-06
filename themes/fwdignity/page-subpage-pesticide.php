<?php
/**
 * fwdignity · page-subpage-pesticide.php
 * 農藥安全子頁(slug=subpage-pesticide)· 紫 #7C3AED
 */
get_header();
?>

<div class="fw-page-layout">

<main class="hps" id="main" role="main">

  <a href="/healthgood/" class="hps-back"><i class="fas fa-arrow-left"></i> 回到健康促進總覽</a>

  <!-- Hero -->
  <section class="hps-hero">
    <div class="hps-hero-deco" aria-hidden="true">
      <i class="fas fa-flask-vial hps-deco-1"></i>
      <i class="fas fa-spray-can-sparkles hps-deco-2"></i>
    </div>
    <div class="hps-hero-inner">
      <span class="hps-badge"><i class="fas fa-flask-vial"></i> 農藥安全</span>
      <h1 class="hps-title" data-hook-title>農藥<span class="accent">安全指南</span></h1>
      <p class="hps-tagline">正確防護、安全使用、中毒急救——從源頭降低農藥危害。</p>
      <div class="hps-intro">
        <p>農藥是農業生產的重要工具，但使用不當就是健康殺手。台灣 1985–2006 年間有機磷農藥中毒仍為主要職業與非職業暴露型態之一（Lin et al., 2008）。職業性農藥暴露中，<strong>皮膚吸收是主要途徑</strong>，遠高於吸入或口腔攝入（U.S. Environmental Protection Agency [EPA], 2013）。很多農友噴藥時只戴口罩卻不戴手套，等於最大的暴露途徑完全沒有防護。</p>
        <p>2020 年台灣全面禁用巴拉刈（paraquat）後，農藥自殺整體下降了 <strong>37%</strong>、巴拉刈自殺下降 <strong>58%</strong>，且未觀察到方法替代效應，證實農藥管制具明確公共衛生效益（Chang et al., 2022）。這一頁整理了安全用藥流程、防護裝備選擇、中毒急救步驟與相關法規。</p>
      </div>
    </div>
  </section>

  <!-- ① 暴露途徑 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-hand"></i></div>
      <div>
        <h2>農藥怎麼<span class="accent">進入你的身體</span></h2>
        <p>知道風險在哪裡，才知道該防哪裡。</p>
      </div>
    </div>
    <div class="hps-route-grid">
      <div class="hps-route main">
        <div class="hps-route-pct"><i class="fas fa-hand-paper"></i></div>
        <h4>皮膚吸收（最主要途徑）</h4>
        <p>手部、前臂、臉部是最常接觸的部位。農藥會穿透一般棉質手套和衣物，必須使用耐化學品的材質。皮膚有傷口時吸收速率更快（EPA, 2013）。</p>
      </div>
      <div class="hps-route">
        <div class="hps-route-pct"><i class="fas fa-wind"></i></div>
        <h4>呼吸吸入</h4>
        <p>噴霧時的細小液滴、密閉空間中的揮發氣體。溫室內和高溫天風險更高（EPA, 2013）。</p>
      </div>
      <div class="hps-route">
        <div class="hps-route-pct"><i class="fas fa-utensils"></i></div>
        <h4>口腔攝入</h4>
        <p>作業後未洗手就吃東西、喝水或抽菸。雖較不常見，但若直接誤食農藥濃液則極度危險（EPA, 2013）。</p>
      </div>
    </div>
    <p class="hps-p" style="font-size:.9em;color:#666;">*職業農藥暴露以皮膚為主要途徑，EPA《Recognition and Management of Pesticide Poisonings》第 6 版明確指出應以皮膚防護為優先（EPA, 2013）。具體分配百分比因作業型態而異。</p>
  </section>

  <!-- ② 防護裝備毒性表 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-shield-halved"></i></div>
      <div>
        <h2>防護裝備：依<span class="accent">毒性等級穿戴</span></h2>
        <p>農藥瓶上標示的毒性等級決定了你該穿多少防護（農業部動植物防疫檢疫署 [防檢署]，2024；World Health Organization [WHO]，2020）。</p>
      </div>
    </div>
    <div class="hps-ppe-table-wrap">
      <table class="hps-ppe-table">
        <thead>
          <tr><th>毒性等級</th><th>標示顏色</th><th>最低防護要求</th></tr>
        </thead>
        <tbody>
          <tr><td><strong>低毒</strong></td><td><span class="hps-label" style="background:#4CAF50;">綠色</span></td><td>長袖衣褲、耐化學手套、防護口罩</td></tr>
          <tr><td><strong>中等毒</strong></td><td><span class="hps-label" style="background:#2196F3;">藍色</span></td><td>上述 + 防護衣連身式、寬沿帽、長統膠靴、防護圍裙</td></tr>
          <tr><td><strong>高毒</strong></td><td><span class="hps-label" style="background:#FF9800;">黃色</span></td><td>上述 + 防飛濺護目鏡、化學濾罐式防毒面具</td></tr>
          <tr><td><strong>極毒</strong></td><td><span class="hps-label" style="background:#F44336;">紅色</span></td><td>全套化學防護衣、全面式防毒面具（專業人員操作）</td></tr>
        </tbody>
      </table>
    </div>

    <div class="hps-tip-grid">
      <div class="hps-tip"><i class="fas fa-hand"></i><div><strong>手套是第一優先</strong><p>皮膚吸收是職業農藥暴露的主要途徑，手部又是最常接觸的部位（EPA, 2013）。選擇丁腈（nitrile）材質，拋棄式最好。棉手套會吸附農藥，反而更危險。</p></div></div>
      <div class="hps-tip"><i class="fas fa-wind"></i><div><strong>口罩不等於防毒面具</strong><p>一般外科口罩或布口罩無法過濾農藥蒸氣（EPA, 2013）。噴灑中等毒以上農藥時，必須使用含有機蒸氣/農藥濾罐的防毒面具（CNS 14258 Z3035，經濟部標準檢驗局，n.d.）。</p></div></div>
      <div class="hps-tip"><i class="fas fa-shirt"></i><div><strong>噴藥衣物單獨清洗</strong><p>噴藥後的衣物不可與一般衣物混洗。先用大量清水沖洗，再單獨以肥皂水清洗，在通風處晾乾（EPA, 2013）。</p></div></div>
    </div>

    <div class="hps-note"><i class="fas fa-link"></i><span>完整的防護具選購和穿脫 SOP → <a href="/occupational-safety/personal-protective-equipment/">個人防護具（PPE）專區</a></span></div>
  </section>

  <!-- ③ 安全用藥流程 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-list-check"></i></div>
      <div>
        <h2>安全<span class="accent">用藥流程</span></h2>
        <p>從買藥到施藥完畢，每個環節都有需要注意的細節（防檢署，2024；EPA, 2013）。</p>
      </div>
    </div>
    <div class="hps-sop-steps">
      <div class="hps-sop"><div class="hps-sop-num">1</div><div><h4>購買：看清標示</h4><p>確認農藥許可證字號、有效期限、毒性等級標示顏色。只向合法農藥販賣業者購買（防檢署，2024）。</p></div></div>
      <div class="hps-sop"><div class="hps-sop-num">2</div><div><h4>儲存：上鎖、分開、遠離</h4><p>放在上鎖的專用儲藏室，遠離食物和飲水。保持原裝原瓶，<strong>絕對不可分裝到飲料瓶中</strong>。兒童和老人不可接觸（WHO, 2020）。</p></div></div>
      <div class="hps-sop"><div class="hps-sop-num">3</div><div><h4>稀釋：按標籤比例，不多不少</h4><p>依照瓶身標示的稀釋倍數操作。加倍濃度不會更有效，只會增加殘留和中毒風險。在通風處稀釋，穿戴完整防護具（EPA, 2013）。</p></div></div>
      <div class="hps-sop"><div class="hps-sop-num">4</div><div><h4>施藥：順風、避開高溫</h4><p>噴灑時站在上風處，讓藥霧隨風飄離自己。避免在正午高溫或大風天施藥（高溫加速揮發，大風增加飄散）。溫室施藥後關閉入口，等待通風（EPA, 2013）。</p></div></div>
      <div class="hps-sop"><div class="hps-sop-num">5</div><div><h4>遵守安全採收期</h4><p>最後一次施藥到採收的最短天數，印在每一瓶農藥的標籤上。這是確保農產品上的農藥殘留降到安全值以下所需的最低時間（防檢署，2024）。</p></div></div>
      <div class="hps-sop"><div class="hps-sop-num">6</div><div><h4>施藥後：清洗與再進入（REI）</h4><p>施藥後立刻脫除防護具（先洗手套外層再脫）、用肥皂沐浴更衣。美國 EPA 工作者保護標準（Worker Protection Standard）規定的<strong>再進入間隔（Restricted Entry Interval, REI）依農藥種類不同，最短 4 小時、最長可達 72 小時</strong>（EPA, n.d.）。台灣農藥標籤上亦標示 REI，務必依標籤指示執行，不可憑經驗縮短。</p></div></div>
    </div>
  </section>

  <!-- ④ 中毒辨識 + 急救 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon" style="background:rgba(220,38,38,0.1); color:#DC2626;"><i class="fas fa-kit-medical"></i></div>
      <div>
        <h2>中毒辨識<span class="accent">與急救</span></h2>
        <p>越早辨識、越早處理，傷害越小。</p>
      </div>
    </div>

    <h3 class="hps-h3">急性有機磷中毒症狀（DUMBELS 口訣）</h3>
    <div class="hps-symptom-grid">
      <div class="hps-symptom"><strong>D</strong>iarrhea — 腹瀉</div>
      <div class="hps-symptom"><strong>U</strong>rination — 頻尿</div>
      <div class="hps-symptom"><strong>M</strong>iosis — 瞳孔縮小</div>
      <div class="hps-symptom"><strong>B</strong>radycardia / Bronchorrhea — 心跳慢、支氣管分泌增多</div>
      <div class="hps-symptom"><strong>E</strong>mesis — 嘔吐</div>
      <div class="hps-symptom"><strong>L</strong>acrimation — 流淚</div>
      <div class="hps-symptom"><strong>S</strong>alivation — 流口水</div>
    </div>
    <p class="hps-p">其他警示：肌肉抽搐、呼吸困難、頭暈、視力模糊、大量出汗。嚴重時可能出現意識喪失、呼吸衰竭（Lin et al., 2008；EPA, 2013；WHO, 2020）。</p>

    <h3 class="hps-h3">急救步驟</h3>
    <div class="hps-firstaid-grid">
      <div class="hps-firstaid"><i class="fas fa-hand-sparkles"></i><h4>皮膚接觸</h4><p>脫除污染衣物 → 用大量清水沖洗至少 15–20 分鐘 → 用肥皂徹底清洗（EPA, 2013）</p></div>
      <div class="hps-firstaid"><i class="fas fa-eye"></i><h4>眼睛接觸</h4><p>撐開眼瞼 → 用大量清水持續沖洗至少 15–20 分鐘 → 不可揉眼（EPA, 2013）</p></div>
      <div class="hps-firstaid"><i class="fas fa-lungs"></i><h4>吸入</h4><p>立刻移到通風處 → 鬆開衣領 → 若呼吸困難立刻打 119（EPA, 2013）</p></div>
      <div class="hps-firstaid"><i class="fas fa-glass-water"></i><h4>誤食</h4><p><strong>不可催吐</strong>（腐蝕性農藥催吐會二次傷害食道）→ 不要灌牛奶 → 立刻打 119 → 帶農藥瓶到醫院（EPA, 2013）</p></div>
    </div>

    <a href="tel:0228717121" class="hps-emergency-card">
      <div class="hps-emergency-left">
        <i class="fas fa-phone-volume"></i>
        <div>
          <strong>毒藥物防治諮詢中心</strong>
          <p>24 小時專線</p>
        </div>
      </div>
      <span class="hps-emergency-num">02-2871-7121</span>
    </a>

    <div class="hps-note"><i class="fas fa-exclamation-circle"></i><span>送醫時務必帶上農藥瓶或拍下標籤。醫生需要知道中毒的是哪一種農藥，才能對症下藥。</span></div>
  </section>

  <!-- ⑤ 慢性影響 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-hourglass-half"></i></div>
      <div>
        <h2>農藥的<span class="accent">慢性健康影響</span></h2>
        <p>不是沒有急性中毒就安全——長期低劑量暴露也會累積傷害。</p>
      </div>
    </div>
    <div class="hps-chronic-grid">
      <div class="hps-chronic"><h4><i class="fas fa-brain"></i> 神經系統</h4><p>遲發性多發性神經病變、認知功能下降、憂鬱症狀。有機磷農藥長期暴露與神經精神疾病風險增加相關（WHO, 2020；Lin et al., 2008）。</p></div>
      <div class="hps-chronic"><h4><i class="fas fa-lungs"></i> 呼吸系統</h4><p>慢性支氣管炎、肺功能下降。長期低劑量吸入風險於 WHO 農藥危害分類指引中被列為需持續監測之慢性健康問題（WHO, 2020）。</p></div>
      <div class="hps-chronic"><h4><i class="fas fa-ribbon"></i> 癌症風險</h4><p>部分農藥（如嘉磷賽 glyphosate、某些有機氯化物）被國際癌症研究機構（International Agency for Research on Cancer [IARC]）列為第 2A 類「可能致癌物」。長期暴露可能增加非何杰金氏淋巴瘤、白血病風險（IARC, 2017）。</p></div>
    </div>
    <div class="hps-note"><i class="fas fa-stethoscope"></i><span><strong>定期健康檢查：</strong>長期接觸農藥的農友建議定期做膽鹼酯酶（cholinesterase）檢測，這是監測有機磷農藥暴露的指標（WHO, 2020）。台大雲林分院、嘉義長庚等醫院可安排。</span></div>
  </section>

  <!-- 交叉連結 -->
  <section class="hps-sec hps-cross reveal">
    <h3><i class="fas fa-link"></i> 相關主題</h3>
    <div class="hps-cross-grid">
      <a href="/occupational-safety/personal-protective-equipment/"><i class="fas fa-mask-face"></i><span>個人防護具（PPE）</span><small>完整的防毒面具選購和穿脫 SOP</small></a>
      <a href="/healthgood/subpage-heat/"><i class="fas fa-temperature-arrow-up"></i><span>熱危害預防</span><small>高溫天噴藥：農藥揮發更快，穿防護衣散熱更差</small></a>
      <a href="/occupational-safety/greenhouse-zone/"><i class="fas fa-warehouse"></i><span>溫室作業安全</span><small>溫室內農藥殘留濃度更高，REI 間隔更重要</small></a>
    </div>
  </section>

  <!-- References 全展開（APA 7） -->
  <style>
    .hps-refs-list.apa7 { list-style: none; padding: 0; }
    .hps-refs-list.apa7 li { padding-left: 2em; text-indent: -2em; margin-bottom: .8em; line-height: 1.6; }
    .hps-refs-list.apa7 li em { font-style: italic; }
    .hps-refs-list.apa7 li a { word-break: break-all; }
  </style>
  <section class="hps-sec hps-refs">
    <div class="hps-refs-open">
      <h2><i class="fas fa-book-open"></i> 參考<span class="accent">資料</span></h2>
      <p class="hps-refs-note">本頁內容依 APA 第 7 版格式引用以下台灣政府與國際公共衛生機構公開資料。所有引用之 DOI 均已於 Crossref 驗證。</p>
      <ul class="hps-refs-list apa7">
        <li>農業部動植物防疫檢疫署（2024）。<em>農藥資訊服務網</em>。https://pesticide.baphiq.gov.tw/</li>
        <li>經濟部標準檢驗局（n.d.）。<em>CNS 14258 Z3035 呼吸防護具之選擇、使用及維護方法</em> [國家標準]。https://www.cnsonline.com.tw/</li>
        <li>臺北榮民總醫院毒藥物防治諮詢中心（n.d.）。<em>毒藥物諮詢服務</em>。https://www.pcc-vghtpe.tw/</li>
        <li>Chang, S.-S., Lin, C.-Y., Lee, M.-B., Shen, L.-J., Gunnell, D., &amp; Eddleston, M. (2022). The early impact of paraquat ban on suicide in Taiwan. <em>Clinical Toxicology, 60</em>(1), 131–135. https://doi.org/10.1080/15563650.2021.1937642</li>
        <li>International Agency for Research on Cancer. (2017). <em>Some organophosphate insecticides and herbicides</em> (IARC Monographs on the Evaluation of Carcinogenic Risks to Humans, Vol. 112). World Health Organization. https://publications.iarc.fr/549</li>
        <li>Lin, T.-J., Walter, F. G., Hung, D.-Z., Tsai, J.-L., Hu, S.-C., Chang, J.-S., Deng, J.-F., Chase, P. B., Denninghoff, K., &amp; Chan, H.-M. (2008). Epidemiology of organophosphate pesticide poisoning in Taiwan. <em>Clinical Toxicology, 46</em>(9), 794–801. https://doi.org/10.1080/15563650801986695</li>
        <li>U.S. Environmental Protection Agency. (2013). <em>Recognition and management of pesticide poisonings</em> (6th ed.). https://www.epa.gov/pesticide-worker-safety/recognition-and-management-pesticide-poisonings</li>
        <li>U.S. Environmental Protection Agency. (n.d.). <em>Restrictions to protect workers after pesticide applications</em>. Retrieved April 24, 2026, from https://www.epa.gov/pesticide-worker-safety/restrictions-protect-workers-after-pesticide-applications</li>
        <li>World Health Organization. (2020). <em>The WHO recommended classification of pesticides by hazard and guidelines to classification, 2019 edition</em>. https://www.who.int/publications/i/item/9789240005662</li>
      </ul>
      <p class="hps-refs-note"><em>本次改版移除：</em>Boedeker et al. (2020) 已於 2024-10-09 由 BMC Public Health 撤稿（retracted），原引用之「全球 3.85 億非自願農藥中毒」與台灣年均 390 人住院之精確數字均已下架；原 Liao 2006 引用因作者/期刊無法核實，已更換為 Lin et al. (2008)。</p>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
