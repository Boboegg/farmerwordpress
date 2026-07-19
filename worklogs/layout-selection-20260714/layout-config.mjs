export const BASE_URL = 'https://fwdignity-local.local';

export const families = [
  {
    id: 'home',
    number: '01',
    title: '首頁',
    mission: '先理解計畫、看見即時警示，並選擇下一個入口。',
    recommendation: 'A',
    pages: [
      { id: 'home', title: '首頁', path: '/' }
    ],
    mobileRepresentative: 'home',
    options: {
      A: { name: '任務先行', summary: '計畫核心與即時警示並列，先讓訪客知道下一步能去哪裡。', tradeoff: '工具導向最清楚；單一照片的品牌衝擊較弱。' },
      B: { name: '編輯頭版', summary: '以一則真實內容建立頭條層級，再接最新消息與內容入口。', tradeoff: '更新感強；需要可靠的頭條 fallback。' },
      C: { name: '田野展卷', summary: '以真實場域照片建立第一印象，內容像章節往下展開。', tradeoff: '地方感最強；實用入口會較晚出現。' }
    }
  },
  {
    id: 'news',
    number: '02',
    title: '最新消息',
    mission: '先看最近發生什麼，再依類型與日期找到舊內容。',
    recommendation: 'A',
    pages: [
      { id: 'news', title: '最新消息', path: '/thenews/' }
    ],
    mobileRepresentative: 'news',
    options: {
      A: { name: '頭條＋時間流', summary: '最新一則成為頭條，後續內容改為易掃描的日期流。', tradeoff: '層級清楚；最新一則自然獲得較高權重。' },
      B: { name: '日期索引', summary: '左側工具與日期索引，右側是高密度單欄消息列表。', tradeoff: '累積後最好找；照片與敘事感較弱。' },
      C: { name: '主題編輯網格', summary: '用不等寬網格同時呈現多種內容類型。', tradeoff: '內容多樣性高；去重與策展規則較複雜。' }
    }
  },
  {
    id: 'guide',
    number: '03',
    title: '田間知識與資源',
    mission: '從要處理的任務開始，不要求使用者先理解網站分類。',
    recommendation: 'A',
    pages: [
      { id: 'guide', title: '田間知識與資源', path: '/farming-guide/' }
    ],
    mobileRepresentative: 'guide',
    options: {
      A: { name: '任務清單', summary: '六個動詞任務形成可由上而下閱讀的工作清單。', tradeoff: '最好理解；多條件精細查找在第二層。' },
      B: { name: '雙軸工作台', summary: '左側保留任務導覽，右側直接閱讀各任務內容。', tradeoff: '最能承接雙軸架構；第一眼操作量較大。' },
      C: { name: '情境起步', summary: '把任務卡做成大小有別的起步路徑，強調具體使用情境。', tradeoff: '入口具體；沒有內容的情境必須自動退場。' }
    }
  },
  {
    id: 'field',
    number: '04',
    title: '田野入口與集合',
    mission: '把人、地方、活動與觀察組成可閱讀的公共敘事。',
    recommendation: 'A',
    pages: [
      { id: 'field-learning', title: '田野誌', path: '/field-learning/' },
      { id: 'field-notes', title: '田野紀錄', path: '/field-notes/' },
      { id: 'people', title: '做農的人', path: '/people-who-farm/' },
      { id: 'farmer-study', title: '農學堂', path: '/farmer-study/' }
    ],
    mobileRepresentative: 'field-learning',
    options: {
      A: { name: '田野雜誌', summary: '主稿、短訊與活動紀錄形成有節奏的編輯頁。', tradeoff: '最能容納照片與長短內容；主稿需有 fallback。' },
      B: { name: '時間軸', summary: '日期與地點成為閱讀主線，內容改為連續紀錄。', tradeoff: '累積感最強；缺少真實田野日期時必須降級。' },
      C: { name: '主題地圖', summary: '先看問題與路徑，再閱讀掛在其下的最近內容。', tradeoff: '長期研究問題清楚；最新動態要多一次選擇。' }
    }
  },
  {
    id: 'topics',
    number: '05',
    title: '五個田野主題',
    mission: '說清楚每一題在問什麼，並讓多種內容共同回答。',
    recommendation: 'A',
    pages: [
      { id: 'topic-body', title: '身體如何承受農務？', path: '/people-who-farm/body-and-farm-work/' },
      { id: 'topic-change', title: '農業工作正在怎麼改變？', path: '/people-who-farm/changing-farm-work/' },
      { id: 'topic-support', title: '誰在撐起農村？', path: '/people-who-farm/rural-support/' },
      { id: 'topic-risk', title: '風險由誰承擔？', path: '/people-who-farm/risk-and-responsibility/' },
      { id: 'topic-knowledge', title: '知識如何走進田間？', path: '/people-who-farm/knowledge-in-the-field/' }
    ],
    mobileRepresentative: 'topic-body',
    options: {
      A: { name: '問題專刊', summary: '問句與核心說明在左，真實主稿與其他證據在右。', tradeoff: '編輯觀點最清楚；依賴公開資格與去重。' },
      B: { name: '導讀＋證據列', summary: '窄版導讀搭配側邊主題索引，內容集中為證據清單。', tradeoff: '掃描最快；照片與情感角色較弱。' },
      C: { name: '交叉主題索引', summary: '主題導覽與內容標籤並列，強調不同問題彼此牽動。', tradeoff: '關係最完整；理解成本最高。' }
    }
  },
  {
    id: 'media',
    number: '06',
    title: '聽見田裡的聲音',
    mission: '快速選一集開始聽，再依系列、主題與年份繼續找。',
    recommendation: 'B',
    pages: [
      { id: 'podcast', title: '聽見田裡的聲音', path: '/podcast/' }
    ],
    mobileRepresentative: 'podcast',
    options: {
      A: { name: '主打單集＋節目架', summary: '一集主打內容先行，近期內容與系列形成節目架。', tradeoff: '第一步最明確；舊集掃描效率普通。' },
      B: { name: '播放清單', summary: '縮短主打區，讓可篩選的單欄集數清單成為主體。', tradeoff: '大量內容最好找；封面作品感較弱。' },
      C: { name: '聲音年表', summary: '單集沿年份與時間線排列，強調地方聲音的累積。', tradeoff: '歷史感最完整；找特定主題仍需篩選。' }
    }
  },
  {
    id: 'research',
    number: '07',
    title: '研究、資源與倡議',
    mission: '先理解研究如何轉成行動，再核對成果、出版與資源。',
    recommendation: 'A',
    pages: [
      { id: 'research', title: '研究與行動', path: '/research-results/' },
      { id: 'publications', title: '研究出版', path: '/research-results/research-publications/' },
      { id: 'resources', title: '相關資源', path: '/research-results/related-resources/' },
      { id: 'initiative', title: '尊嚴農業倡議', path: '/dignity-farming-initiative/' }
    ],
    mobileRepresentative: 'research',
    options: {
      A: { name: '研究到行動路徑', summary: '問題、研究、共作與公開成果形成連續閱讀路徑。', tradeoff: '一般讀者最好理解；完整書目較晚出現。' },
      B: { name: '出版書架', summary: '年份與成果類型成為側欄，引用與資源列是主體。', tradeoff: '學術查找最快；行動故事較薄。' },
      C: { name: '證據索引', summary: '以緊湊欄位與模組呈現研究問題、方法與產出。', tradeoff: '查核最清楚；成果量少時容易顯空。' }
    }
  },
  {
    id: 'about',
    number: '08',
    title: '認識尊嚴農業與研究團隊',
    mission: '回答誰在做、為什麼做、在哪裡做，以及由誰負責。',
    recommendation: 'A',
    pages: [
      { id: 'about', title: '認識尊嚴農業', path: '/about/' },
      { id: 'team', title: '研究團隊', path: '/about/researchteam/' }
    ],
    mobileRepresentative: 'about',
    options: {
      A: { name: '計畫敘事', summary: '核心句、場域與合作機構先建立脈絡，再進入團隊。', tradeoff: '信任建立最完整；直接找人要多捲一段。' },
      B: { name: '機構檔案', summary: '事實表與角色名錄優先，資訊像可查核的機構檔案。', tradeoff: '查找最快；地方感與人的溫度較少。' },
      C: { name: '人物與場域', summary: '場域照片與人物交錯，呈現實際共作關係。', tradeoff: '最有現場感；照片不足時必須降級。' }
    }
  },
  {
    id: 'single',
    number: '09',
    title: '文章單篇',
    mission: '先判斷內容型態與價值，再進入不受干擾的長文或收聽。',
    recommendation: 'A',
    pages: [
      { id: 'single-news', title: '消息單篇｜天然的婚禮花束?!', path: '/?p=1633' },
      { id: 'single-field', title: '田野／活動單篇｜灶腳到田野', path: '/?p=1829' },
      { id: 'single-media', title: 'Podcast 單篇｜EP218', path: '/?p=2092' }
    ],
    mobileRepresentative: 'single-field',
    options: {
      A: { name: '純閱讀', summary: '麵包屑、標題、媒體與 780px 正文依自然文件流排列。', tradeoff: '最安靜穩定；長文中段沒有快速目錄。' },
      B: { name: '欄外導覽', summary: '有真正 H2／H3 時產生側邊目錄，沒有章節就自動退回純閱讀。', tradeoff: '研究長文最好導航；短文不會看出明顯差異。' },
      C: { name: '雜誌開場', summary: '標題與真實照片／播放器形成分割第一屏，正文仍回到 780px。', tradeoff: '內容型態辨識最強；無媒體文章必須降級。' }
    }
  }
];

export const familyMap = Object.fromEntries(families.map((family) => [family.id, family]));

export const desktopCases = families.flatMap((family) =>
  family.pages.flatMap((page) =>
    ['A', 'B', 'C'].map((option) => ({
      family: family.id,
      option,
      pageId: page.id,
      title: page.title,
      path: page.path,
      viewport: { width: 1440, height: 1100 },
      mobile: false
    }))
  )
);

export const mobileCases = families.flatMap((family) => {
  const page = family.pages.find((item) => item.id === family.mobileRepresentative);
  return ['A', 'B', 'C'].map((option) => ({
    family: family.id,
    option,
    pageId: page.id,
    title: page.title,
    path: page.path,
    viewport: { width: 390, height: 844 },
    mobile: true
  }));
});

export const allCases = [...desktopCases, ...mobileCases];

export function caseKey(testCase) {
  return [testCase.family, testCase.option.toLowerCase(), testCase.pageId, testCase.mobile ? 'mobile' : 'desktop'].join('--');
}

