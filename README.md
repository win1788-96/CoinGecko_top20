API HUB
自訂 Gem
API HUB說
既然你的專案已經進化到擁有「前端霓虹監控」與「後端自動分頁儲存」的完整生態系，這份 README.md 將會涵蓋所有安裝與運行的核心細節。

⚡️ CYBER_TICKER & AUTO_SHEETS_VAULT v3.0
這是一個具備 賽博龐克視覺特效 的即時加密貨幣監控系統。它不只提供前端的即時警報，還能透過後端腳本自動將數據按週分類存入 Google 試算表。

🌌 系統兩大核心 (Core Modules)
1. 🖥️ Frontend Terminal (index.php)
動態更新動畫：數據更新時，卡片會觸發青色閃爍（Flash Effect）。

全螢幕紅色警戒：當任一主流幣跌幅超過 5% 時，背景切換至呼吸紅警告模式。

即時監控：每 5 秒與 CoinGecko API 同步一次。

2. 📊 Data Vault (save_to_sheets.php)
自動週分頁：數據會自動存入對應週別的工作表（例如 2026-W10）。

後端靜默執行：配合 Cron Job，每分鐘自動抓取數據存檔。

雲端同步：透過 Google Apps Script (GAS) 進行高效能批次寫入。

🛠️ 安裝指南 (Deployment)
第一步：配置 Google 試算表 (GAS)
建立一份全新的 Google 試算表。

前往「擴充功能」 > 「Apps Script」。

貼入 doPost 腳本（負責按週分頁與寫入）。

重要：部署為「網頁應用程式」，並設定存取權限為 「所有人 (Anyone)」。

複製產生的 Web App URL。
數據結構 (Data Schema)存入 Google Sheets 的資料欄位如下：時間戳記幣種名稱代號價格 (USD)24H 漲跌 %2026-03-04 00:15BitcoinBTC67500.2-2.5
