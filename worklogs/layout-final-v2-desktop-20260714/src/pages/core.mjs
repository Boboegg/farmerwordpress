export const corePages = [
  {
    id: '01',
    slug: 'home',
    title: '首頁',
    decision: '警示先行｜8:4 編輯頭版 B',
    body: `
      <section class="mock-alert home-alert" aria-labelledby="home-alert-title">
        <div class="mock-alert__label">
          <p class="mock-kicker">民雄田間快訊</p>
          <strong class="mock-alert__heading" id="home-alert-title">今日作業前，先看田間環境</strong>
        </div>
        <div class="mock-alert__content" role="status">
          <strong>高溫熱危害預警</strong>
          <p>⚠️ 高溫特報 (35°C)：請務必多喝水、每工作30分鐘至陰涼處休息，慎防熱衰竭！</p>
        </div>
        <a class="mock-alert__action mock-link" href="https://fwdignity-local.local/farming-guide/">查看田間知識與資源 <span aria-hidden="true">→</span></a>
      </section>

      <section class="mock-hero home-editorial" aria-labelledby="home-editorial-title">
        <div class="mock-grid mock-grid--8-4 home-editorial__grid">
          <article class="home-lead">
            <figure class="mock-media home-lead__media">
              <img src="https://fwdignity-local.local/wp-content/uploads/2026/07/h0rR-pG8Gt1CY089B2pq4.jpg" alt="EP218：愛上種田的父女檔 張耀廣、張立涵">
            </figure>
            <div class="home-lead__copy">
              <p class="mock-meta">Podcast｜2026.07.02</p>
              <h2><a href="https://fwdignity-local.local/podcast-ep-218/">EP218：愛上種田的父女檔 張耀廣、張立涵</a></h2>
              <p>我們的節目訪問過很多位農夫，節目裡我常問他們：會不會希望自己的孩子也務農？大部分...</p>
              <a class="mock-button" href="https://fwdignity-local.local/podcast-ep-218/">閱讀消息</a>
            </div>
          </article>

          <aside class="mock-aside home-intro" aria-labelledby="home-editorial-title">
            <p class="mock-kicker">尊嚴農業</p>
            <h1 id="home-editorial-title">共構雲嘉農業工作者職業健康的社會安全網</h1>
            <p>本計畫聚焦農業工作者之職業健康與社會安全網建構，回應農民長期面臨之高溫曝露、重複性勞動、農藥使用及政策保障不足等風險。</p>
            <nav class="home-intro__actions" aria-label="計畫主要入口">
              <a class="mock-button" href="https://fwdignity-local.local/about/">認識計畫</a>
              <a class="mock-link" href="https://fwdignity-local.local/farming-guide/">查看田間知識與資源 <span aria-hidden="true">→</span></a>
            </nav>
          </aside>
        </div>
      </section>

      <section class="mock-section home-latest" aria-labelledby="home-latest-title">
        <header class="mock-section__head">
          <div>
            <p class="mock-kicker">News &amp; Updates</p>
            <h2 id="home-latest-title">最新消息</h2>
          </div>
          <a class="mock-link" href="https://fwdignity-local.local/thenews/">看全部最新消息</a>
        </header>
        <div class="home-latest__list">
          <article class="mock-card home-latest__item">
            <figure class="mock-media">
              <img src="https://fwdignity-local.local/wp-content/uploads/2026/05/52ZTreIH_Cwu6Ai__Zavj-1024x1024.jpg" alt="EP211：我是農婦，也是營銷大師 民雄青農 陳怡吟、簡伶君、陳珮欣">
            </figure>
            <div>
              <p class="mock-meta">Podcast｜2026.05.14</p>
              <h3><a href="https://fwdignity-local.local/podcast-ep-211/">EP211：我是農婦，也是營銷大師 民雄青農 陳怡吟、簡伶君、陳珮欣</a></h3>
              <p>雖然不少人從小對未來的工作充滿想像跟期待，但有時，生命的多樣的歷程，總是讓人生有...</p>
            </div>
          </article>
          <article class="mock-card home-latest__item">
            <figure class="mock-media">
              <img src="https://fwdignity-local.local/wp-content/uploads/2026/05/ex6Cu2NUAEljPfs8OPwT7-1024x1024.jpg" alt="EP210：從鏡頭到鋤頭 陳韻如的斜槓哲學">
            </figure>
            <div>
              <p class="mock-meta">Podcast｜2026.05.07</p>
              <h3><a href="https://fwdignity-local.local/podcast-ep-210/">EP210：從鏡頭到鋤頭 陳韻如的斜槓哲學</a></h3>
              <p>認識今天的來賓陳韻如，大概是二十年前，當時，她是南藝大的研究生，也是紀錄片導演，...</p>
            </div>
          </article>
        </div>
      </section>
    `,
  },
  {
    id: '02',
    slug: 'thenews',
    title: '最新消息',
    decision: '頭條＋兩則次要消息｜年份／月份索引＋單欄時間流',
    body: `
      <header class="mock-section news-intro">
        <p class="mock-kicker">News &amp; Updates</p>
        <h1>最新消息</h1>
        <p>收錄尊嚴農業的消息文章、田野紀錄、Podcast、YouTube、影音與教材；可依內容形式與主題交叉查找。</p>
      </header>

      <section class="mock-section news-featured" aria-label="精選消息">
        <div class="mock-grid mock-grid--7-5 news-featured__grid">
          <article class="mock-card news-headline">
            <figure class="mock-media news-headline__media">
              <img src="https://fwdignity-local.local/wp-content/uploads/2026/07/h0rR-pG8Gt1CY089B2pq4.jpg" alt="EP218：愛上種田的父女檔 張耀廣、張立涵">
            </figure>
            <div class="news-headline__copy">
              <p class="mock-meta">Podcast｜2026.07.02</p>
              <h2><a href="https://fwdignity-local.local/podcast-ep-218/">EP218：愛上種田的父女檔 張耀廣、張立涵</a></h2>
              <p>我們的節目訪問過很多位農夫，節目裡我常問他們：會不會希望自己的孩子也務農？大部分的人都告訴我：不會。</p>
              <a class="mock-link" href="https://fwdignity-local.local/podcast-ep-218/">閱讀內容 <span aria-hidden="true">→</span></a>
            </div>
          </article>

          <div class="news-secondary-list" aria-label="次要消息">
            <article class="mock-card news-secondary">
              <figure class="mock-media news-secondary__media">
                <img src="https://fwdignity-local.local/wp-content/uploads/2026/05/52ZTreIH_Cwu6Ai__Zavj-1024x1024.jpg" alt="EP211：我是農婦，也是營銷大師 民雄青農 陳怡吟、簡伶君、陳珮欣">
              </figure>
              <div>
                <p class="mock-meta">Podcast｜2026.05.14</p>
                <h3><a href="https://fwdignity-local.local/podcast-ep-211/">EP211：我是農婦，也是營銷大師 民雄青農 陳怡吟、簡伶君、陳珮欣</a></h3>
                <p>雖然不少人從小對未來的工作充滿想像跟期待，但有時，生命的多樣的歷程，總是讓人生有著不同的規劃與安排。</p>
              </div>
            </article>
            <article class="mock-card news-secondary">
              <figure class="mock-media news-secondary__media">
                <img src="https://fwdignity-local.local/wp-content/uploads/2026/05/ex6Cu2NUAEljPfs8OPwT7-1024x1024.jpg" alt="EP210：從鏡頭到鋤頭 陳韻如的斜槓哲學">
              </figure>
              <div>
                <p class="mock-meta">Podcast｜2026.05.07</p>
                <h3><a href="https://fwdignity-local.local/podcast-ep-210/">EP210：從鏡頭到鋤頭 陳韻如的斜槓哲學</a></h3>
                <p>認識今天的來賓陳韻如，大概是二十年前，當時，她是南藝大的研究生，也是紀錄片導演，不過，她現在在水林種田，也在學校教書，是一位斜槓農夫。</p>
              </div>
            </article>
          </div>
        </div>
      </section>

      <section class="mock-section news-archive" aria-labelledby="news-archive-title">
        <header class="mock-section__head">
          <div>
            <p class="mock-kicker">全部公開內容</p>
            <h2 id="news-archive-title">找到 45 篇內容</h2>
          </div>
          <a class="mock-link" href="https://fwdignity-local.local/thenews/">開啟完整主題篩選</a>
        </header>

        <div class="news-archive__layout">
          <aside class="mock-aside news-date-index" aria-labelledby="news-date-index-title">
            <h2 id="news-date-index-title">年份／月份</h2>
            <nav aria-label="消息年份與月份索引">
              <section class="news-date-index__year" aria-labelledby="news-year-2026">
                <h3 id="news-year-2026">2026 年 <span>16</span></h3>
                <ul class="mock-list">
                  <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2026-07">7 月 <span>1</span></a></li>
                  <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2026-05">5 月 <span>2</span></a></li>
                  <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2026-04">4 月 <span>4</span></a></li>
                  <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2026-03">3 月 <span>1</span></a></li>
                  <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2026-02">2 月 <span>5</span></a></li>
                  <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2026-01">1 月 <span>3</span></a></li>
                </ul>
              </section>
              <ul class="mock-list news-date-index__past">
                <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2025">2025 年 <span>13</span></a></li>
                <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2024">2024 年 <span>2</span></a></li>
                <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2023">2023 年 <span>8</span></a></li>
                <li><a href="https://fwdignity-local.local/thenews/" data-preview-index="2022">2022 年 <span>6</span></a></li>
              </ul>
            </nav>
          </aside>

          <div class="mock-timeline news-timeline">
            <section class="news-timeline__group" aria-labelledby="news-month-202607">
              <h3 id="news-month-202607"><time datetime="2026-07">2026 年 7 月</time></h3>
              <ol class="mock-list">
                <li class="news-timeline__item">
                  <time datetime="2026-07-02">07.02</time>
                  <div>
                    <p class="mock-meta">Podcast</p>
                    <h4><a href="https://fwdignity-local.local/podcast-ep-218/">EP218：愛上種田的父女檔 張耀廣、張立涵</a></h4>
                    <p>我們的節目訪問過很多位農夫，節目裡我常問他們：會不會希望自己的孩子也務農？</p>
                  </div>
                </li>
              </ol>
            </section>

            <section class="news-timeline__group" aria-labelledby="news-month-202605">
              <h3 id="news-month-202605"><time datetime="2026-05">2026 年 5 月</time></h3>
              <ol class="mock-list">
                <li class="news-timeline__item">
                  <time datetime="2026-05-14">05.14</time>
                  <div>
                    <p class="mock-meta">Podcast</p>
                    <h4><a href="https://fwdignity-local.local/podcast-ep-211/">EP211：我是農婦，也是營銷大師 民雄青農 陳怡吟、簡伶君、陳珮欣</a></h4>
                    <p>雖然不少人從小對未來的工作充滿想像跟期待，但有時，生命的多樣的歷程，總是讓人生有著不同的規劃與安排。</p>
                  </div>
                </li>
                <li class="news-timeline__item">
                  <time datetime="2026-05-07">05.07</time>
                  <div>
                    <p class="mock-meta">Podcast</p>
                    <h4><a href="https://fwdignity-local.local/podcast-ep-210/">EP210：從鏡頭到鋤頭 陳韻如的斜槓哲學</a></h4>
                    <p>認識今天的來賓陳韻如，大概是二十年前，當時，她是南藝大的研究生，也是紀錄片導演。</p>
                  </div>
                </li>
              </ol>
            </section>

            <section class="news-timeline__group" aria-labelledby="news-month-202604">
              <h3 id="news-month-202604"><time datetime="2026-04">2026 年 4 月</time></h3>
              <ol class="mock-list">
                <li class="news-timeline__item">
                  <time datetime="2026-04-30">04.30</time>
                  <div>
                    <p class="mock-meta">Podcast</p>
                    <h4><a href="https://fwdignity-local.local/podcast-ep-209/">EP209：兩個女人與鳳梨田的酸甜人生 郭合、吳芳滿</a></h4>
                    <p>在嘉義民雄三興村鳳梨田裡，經常會看見到一對母女的身影，她們辛勤的勞動，種著好吃的鳳梨。</p>
                  </div>
                </li>
                <li class="news-timeline__item">
                  <time datetime="2026-04-23">04.23</time>
                  <div>
                    <p class="mock-meta">Podcast</p>
                    <h4><a href="https://fwdignity-local.local/podcast-ep-208/">EP208：如何找到農業品牌運營心法 鄭宜玲</a></h4>
                    <p>會認識今天的來賓鄭宜玲，是因為鳳梨哥則諭，印象中，有天則諭說要介紹新朋友跟我認識。</p>
                  </div>
                </li>
              </ol>
            </section>

            <section class="news-timeline__group" aria-labelledby="news-month-202603">
              <h3 id="news-month-202603"><time datetime="2026-03">2026 年 3 月</time></h3>
              <ol class="mock-list">
                <li class="news-timeline__item">
                  <time datetime="2026-03-31">03.31</time>
                  <div>
                    <p class="mock-meta">農學堂</p>
                    <h4><a href="https://fwdignity-local.local/%e3%80%8c%e6%b4%bb%e5%8b%95%e7%b4%80%e9%8c%84%e3%80%8d%e8%be%b2%e5%ad%b8%e5%a0%82%ef%bc%8d%e7%81%b6%e8%85%b3%e5%88%b0%e7%94%b0%e9%87%8e%ef%bc%9a%e5%a5%b3%e6%80%a7%e8%be%b2%e6%b0%91%e7%9a%84%e5%a4%9a/">「活動紀錄」農學堂－灶腳到田野：女性農民的多重勞動與性別分工</a></h4>
                    <p>作為農學堂的第一堂課，這場座談提前額滿、盛況空前。</p>
                  </div>
                </li>
              </ol>
            </section>
          </div>
        </div>
      </section>
    `,
  },
  {
    id: '03',
    slug: 'farming-guide',
    title: '田間知識與資源',
    decision: '單層 3×2 六入口任務卡',
    body: `
      <header class="mock-section guide-intro">
        <p class="mock-kicker">田間知識與資源</p>
        <h1>田間知識與資源</h1>
        <p>把健康、農藥、農機、經濟、保險與制度等跨專業資訊整理成容易理解、可以查證，也知道下一步去哪裡確認的內容。</p>
      </header>

      <section class="mock-section guide-tasks" aria-labelledby="guide-tasks-title">
        <header class="mock-section__head">
          <div>
            <p class="mock-kicker">01</p>
            <h2 id="guide-tasks-title">六大知識入口</h2>
          </div>
        </header>

        <div class="mock-card-grid mock-card-grid--3 guide-card-grid">
          <article class="mock-card guide-task-card" id="guide-task-body-health">
            <p class="mock-meta">01</p>
            <h3>身體與健康</h3>
            <p>肩頸／手腕／下背負荷、高溫、疲勞、就醫與職業傷病。</p>
            <p class="guide-task-card__status"><span aria-hidden="true">●</span> 內容整理中</p>
            <a class="mock-link" href="https://fwdignity-local.local/farming-guide/#fg-task-body-health">查看此入口 <span aria-hidden="true">→</span></a>
          </article>

          <article class="mock-card guide-task-card" id="guide-task-pesticide-protection">
            <p class="mock-meta">02</p>
            <h3>農藥與防護</h3>
            <p>標示辨識、防護、暴露後處理與正式查詢。</p>
            <p class="guide-task-card__status"><span aria-hidden="true">●</span> 內容整理中</p>
            <a class="mock-link" href="https://fwdignity-local.local/farming-guide/#fg-task-pesticide-protection">查看此入口 <span aria-hidden="true">→</span></a>
          </article>

          <article class="mock-card guide-task-card" id="guide-task-machinery-environment">
            <p class="mock-meta">03</p>
            <h3>農機與工作環境</h3>
            <p>設備操作風險、姿勢、流程與改善案例。</p>
            <p class="guide-task-card__status"><span aria-hidden="true">●</span> 內容整理中</p>
            <a class="mock-link" href="https://fwdignity-local.local/farming-guide/#fg-task-machinery-environment">查看此入口 <span aria-hidden="true">→</span></a>
          </article>

          <article class="mock-card guide-task-card" id="guide-task-economy-security">
            <p class="mock-meta">04</p>
            <h3>經濟、保險與保障</h3>
            <p>農職保、申請流程、可查詢窗口與制度更新。</p>
            <p class="guide-task-card__status"><span aria-hidden="true">●</span> 內容整理中</p>
            <a class="mock-link" href="https://fwdignity-local.local/farming-guide/#fg-task-economy-security">查看此入口 <span aria-hidden="true">→</span></a>
          </article>

          <article class="mock-card guide-task-card" id="guide-task-learning-support">
            <p class="mock-meta">05</p>
            <h3>學習與支持</h3>
            <p>農學堂教材、學習路徑、社區與醫療支持。</p>
            <p class="guide-task-card__status"><span aria-hidden="true">●</span> 內容整理中</p>
            <a class="mock-link" href="https://fwdignity-local.local/farming-guide/#fg-task-learning-support">查看此入口 <span aria-hidden="true">→</span></a>
          </article>

          <article class="mock-card guide-task-card" id="guide-task-tools-resources">
            <p class="mock-meta">06</p>
            <h3>工具與正式資源</h3>
            <p>檢核表、下載、外部查詢與更新紀錄。</p>
            <p class="guide-task-card__status"><span aria-hidden="true">●</span> 內容整理中</p>
            <a class="mock-link" href="https://fwdignity-local.local/farming-guide/#fg-task-tools-resources">查看此入口 <span aria-hidden="true">→</span></a>
          </article>
        </div>
      </section>
    `,
  },
];
