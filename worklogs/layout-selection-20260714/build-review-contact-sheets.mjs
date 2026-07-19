import fs from 'node:fs';
import path from 'node:path';
import { spawnSync } from 'node:child_process';
import { families, caseKey } from './layout-config.mjs';

const projectRoot = '/Users/boboegg/farmerwordpress';
const artifactDir = path.join(projectRoot, 'output/playwright/layout-selection-20260714');
const contactDir = path.join(artifactDir, 'contact-sheets');
const sliceDir = path.join(contactDir, 'slices');
const pageDir = path.join(contactDir, 'pages');
const result = JSON.parse(fs.readFileSync(path.join(artifactDir, 'results.json'), 'utf8'));
const resultMap = new Map(result.cases.map((item) => [item.key, item]));

fs.mkdirSync(sliceDir, { recursive: true });
fs.mkdirSync(pageDir, { recursive: true });

function run(args) {
  const response = spawnSync('/opt/homebrew/bin/magick', args, { encoding: 'utf8' });
  if (response.status !== 0) {
    throw new Error('ImageMagick 失敗：' + args.join(' ') + '\n' + response.stderr);
  }
}

function makeSlice(item, segment, output) {
  const cropWidth = item.mobile ? 390 : 1440;
  const cropHeight = item.mobile ? 650 : 900;
  const targetWidth = item.mobile ? 260 : 360;
  const targetHeight = item.mobile ? 433 : 225;
  const maxY = Math.max(0, item.captureHeight - cropHeight);
  const y = segment === 'top' ? 0 : segment === 'middle' ? Math.round(maxY / 2) : maxY;
  run([
    path.join(artifactDir, item.screenshot),
    '-crop', cropWidth + 'x' + cropHeight + '+0+' + y,
    '+repage',
    '-resize', targetWidth + 'x' + targetHeight + '!',
    '-quality', '90',
    output
  ]);
}

function makeHeader(width, text, output) {
  run([
    '-size', width + 'x48',
    '-background', '#F5F5F2',
    '-fill', '#283121',
    '-font', '/System/Library/Fonts/Supplemental/Arial.ttf',
    '-gravity', 'center',
    '-pointsize', '20',
    'label:' + text,
    output
  ]);
}

function makeGrid(slicePaths, prefix, output) {
  const rows = [];
  for (let index = 0; index < slicePaths.length; index += 3) {
    const row = prefix + '--row-' + (index / 3 + 1) + '.jpg';
    run([...slicePaths.slice(index, index + 3), '+append', row]);
    rows.push(row);
  }
  run([...rows, '-append', output]);
}

const segments = ['top', 'middle', 'bottom'];
const options = ['A', 'B', 'C'];

for (const family of families) {
  const desktopSheets = [];

  for (const page of family.pages) {
    const slicePaths = [];
    for (const segment of segments) {
      for (const option of options) {
        const key = caseKey({
          family: family.id,
          option,
          pageId: page.id,
          mobile: false
        });
        const item = resultMap.get(key);
        if (!item?.passed || !item.screenshot) throw new Error('缺少通過案例：' + key);
        const output = path.join(sliceDir, key + '--' + segment + '.jpg');
        makeSlice(item, segment, output);
        slicePaths.push(output);
      }
    }

    const montagePath = path.join(pageDir, family.id + '--' + page.id + '--desktop-grid.jpg');
    makeGrid(slicePaths, path.join(pageDir, family.id + '--' + page.id + '--desktop'), montagePath);

    const headerPath = path.join(pageDir, family.id + '--' + page.id + '--desktop-header.jpg');
    makeHeader(1140, page.id + ' | columns A B C | rows top middle bottom', headerPath);
    const pageSheet = path.join(pageDir, family.id + '--' + page.id + '--desktop.jpg');
    run([headerPath, montagePath, '-append', pageSheet]);
    desktopSheets.push(pageSheet);
  }

  const desktopFamilySheet = path.join(contactDir, family.id + '--desktop.jpg');
  run([...desktopSheets, '-append', desktopFamilySheet]);

  const mobilePage = family.pages.find((page) => page.id === family.mobileRepresentative);
  const mobileSlices = [];
  for (const segment of segments) {
    for (const option of options) {
      const key = caseKey({
        family: family.id,
        option,
        pageId: mobilePage.id,
        mobile: true
      });
      const item = resultMap.get(key);
      if (!item?.passed || !item.screenshot) throw new Error('缺少通過案例：' + key);
      const output = path.join(sliceDir, key + '--' + segment + '.jpg');
      makeSlice(item, segment, output);
      mobileSlices.push(output);
    }
  }
  const mobileMontage = path.join(pageDir, family.id + '--mobile-grid.jpg');
  makeGrid(mobileSlices, path.join(pageDir, family.id + '--mobile'), mobileMontage);
  const mobileHeader = path.join(pageDir, family.id + '--mobile-header.jpg');
  makeHeader(840, mobilePage.id + ' mobile | columns A B C | rows top middle bottom', mobileHeader);
  const mobileFamilySheet = path.join(contactDir, family.id + '--mobile.jpg');
  run([mobileHeader, mobileMontage, '-append', mobileFamilySheet]);

  console.log('CONTACT ' + family.id);
}

console.log('SUMMARY ' + JSON.stringify({ families: families.length, desktopSheets: families.length, mobileSheets: families.length }));
