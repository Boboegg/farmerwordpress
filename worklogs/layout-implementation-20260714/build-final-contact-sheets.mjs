import fs from 'node:fs';
import path from 'node:path';
import { spawnSync } from 'node:child_process';

const projectRoot = '/Users/boboegg/farmerwordpress';
const artifactDir = path.join(projectRoot, 'output/playwright/layout-final-20260714');
const contactDir = path.join(artifactDir, 'contact-sheets');
const sliceDir = path.join(contactDir, 'slices');
const results = JSON.parse(fs.readFileSync(path.join(artifactDir, 'results.json'), 'utf8'));
const resultMap = new Map(results.cases.map((item) => [item.key, item]));

const representatives = [
  { family:'home', id:'home' },
  { family:'news', id:'news' },
  { family:'guide', id:'guide' },
  { family:'field', id:'field-learning' },
  { family:'topics', id:'topic-body' },
  { family:'media', id:'podcast' },
  { family:'research', id:'research' },
  { family:'about', id:'about' },
  { family:'single', id:'single-field' }
];

fs.mkdirSync(sliceDir, { recursive: true });

function run(args) {
  const response = spawnSync('/opt/homebrew/bin/magick', args, { encoding:'utf8' });
  if (response.status !== 0) throw new Error(`ImageMagick 失敗：${args.join(' ')}\n${response.stderr}`);
}

function makeSlice(item, segment, output) {
  const cropWidth = item.viewport.width;
  const cropHeight = item.mobile ? Math.min(844, item.captureHeight) : Math.min(900, item.captureHeight);
  const maxY = Math.max(0, item.captureHeight - cropHeight);
  const y = segment === 'top' ? 0 : segment === 'middle' ? Math.round(maxY / 2) : maxY;
  const targetWidth = item.mobile ? 260 : 480;
  const targetHeight = item.mobile ? 433 : 300;
  run([
    path.join(artifactDir, item.screenshot),
    '-crop', `${cropWidth}x${cropHeight}+0+${y}`,
    '+repage',
    '-resize', `${targetWidth}x${targetHeight}!`,
    '-quality', '90',
    output
  ]);
}

function makeHeader(width, label, output) {
  run([
    '-size', `${width}x52`,
    '-background', '#F5F5F2',
    '-fill', '#283121',
    '-font', '/System/Library/Fonts/Supplemental/Arial.ttf',
    '-gravity', 'center',
    '-pointsize', '20',
    `label:${label}`,
    output
  ]);
}

for (const representative of representatives) {
  const desktop = resultMap.get(`${representative.family}--${representative.id}--desktop`);
  const mobile = resultMap.get(`${representative.family}--${representative.id}--mobile`);
  if (!desktop?.passed || !mobile?.passed) throw new Error(`缺少通過案例：${representative.family}`);

  const desktopSlices = [];
  const mobileSlices = [];
  for (const segment of ['top', 'middle', 'bottom']) {
    const desktopPath = path.join(sliceDir, `${representative.family}--desktop--${segment}.jpg`);
    const mobilePath = path.join(sliceDir, `${representative.family}--mobile--${segment}.jpg`);
    makeSlice(desktop, segment, desktopPath);
    makeSlice(mobile, segment, mobilePath);
    desktopSlices.push(desktopPath);
    mobileSlices.push(mobilePath);
  }

  const desktopRow = path.join(sliceDir, `${representative.family}--desktop-row.jpg`);
  const mobileRow = path.join(sliceDir, `${representative.family}--mobile-row.jpg`);
  const mobileCanvas = path.join(sliceDir, `${representative.family}--mobile-canvas.jpg`);
  const header = path.join(sliceDir, `${representative.family}--header.jpg`);
  run([...desktopSlices, '+append', desktopRow]);
  run([...mobileSlices, '+append', mobileRow]);
  run([mobileRow, '-background', '#FDFBF7', '-gravity', 'center', '-extent', '1440x433', mobileCanvas]);
  makeHeader(1440, `${representative.family}｜桌機與手機：上段／中段／頁尾`, header);
  run([header, desktopRow, mobileCanvas, '-append', path.join(contactDir, `${representative.family}.jpg`)]);
  console.log(`CONTACT ${representative.family}`);
}

console.log(`SUMMARY ${JSON.stringify({ families:representatives.length })}`);

