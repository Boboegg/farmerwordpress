# assets/js/

本地 bundle JavaScript，避免 CDN 依賴（GDPR / LCP / CSP / 可用性）。

## 檔案

| 檔案 | 版本 | 來源 | 授權 |
|---|---|---|---|
| `gsap.min.js` | 3.12.5 | `npm install gsap@3.12.5` | GreenSock Standard No-Charge License |
| `ScrollTrigger.min.js` | 3.12.5 | 同上 | 同上 |

## 使用方式

### 在 HTML 頁面引用（透過 Astra child theme 路徑）

```html
<script src="/wp-content/themes/astra-child/assets/js/gsap.min.js" defer></script>
<script src="/wp-content/themes/astra-child/assets/js/ScrollTrigger.min.js" defer></script>
```

`defer` 屬性讓 script 在 HTML parse 完成後才執行，不阻塞 LCP。

### 為什麼不用 CDN

1. **GDPR / 隱私**：CDN 會記錄使用者 IP
2. **LCP**：CDN 需要額外 DNS + TLS handshake（150-400ms）
3. **CSP**：用 CDN 要放寬 `script-src`
4. **可用性**：CDN 下架全站掛
5. **快取控制**：本地 bundle 可用 `filemtime()` 自動 cache bust

## 更新方式

```bash
cd /Users/boboegg/farmerwordpress
npm install gsap@<new-version> --save-dev
cp node_modules/gsap/dist/gsap.min.js assets/js/
cp node_modules/gsap/dist/ScrollTrigger.min.js assets/js/
```

## 部署

`scripts/deploy.sh` 會把 `assets/` 複製到 `$THEME_PATH/assets/`
