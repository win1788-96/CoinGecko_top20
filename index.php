<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>CYBER_TICKER_ALARM_v2.7</title>
    <style>
        :root {
            --neon-pink: #ff0055;
            --neon-blue: #00f2ff;
            --alert-red: rgba(255, 0, 0, 0.3);
            --bg-dark: #0a0a12;
        }

        body {
            background-color: var(--bg-dark);
            color: #fff;
            font-family: 'Segoe UI', 'Courier New', monospace;
            transition: background 0.5s ease; /* 背景切換平滑化 */
            margin: 0; padding: 20px;
        }

        /* 警報模式：背景變紅並閃爍 */
        body.alert-mode {
            background-color: #2b0000;
            animation: pulse-bg 2s infinite;
        }

        @keyframes pulse-bg {
            0% { background-color: #2b0000; }
            50% { background-color: #550000; }
            100% { background-color: #2b0000; }
        }

        h1 {
            text-align: center;
            color: var(--neon-blue);
            text-shadow: 0 0 10px var(--neon-blue);
            letter-spacing: 5px;
        }

        #crypto-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* 基礎卡片樣式 */
        .coin-card {
            background: #151522;
            border: 1px solid var(--neon-blue);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* 更新時的閃爍動畫 (Flash Effect) */
        .flash-update {
            animation: flash-cyan 0.8s ease-out;
        }

        @keyframes flash-cyan {
            0% { background: var(--neon-blue); box-shadow: 0 0 40px var(--neon-blue); }
            100% { background: #151522; }
        }

        /* 嚴重下跌時的卡片樣式 */
        .critical-drop {
            border-color: var(--neon-pink) !important;
            box-shadow: 0 0 20px var(--neon-pink);
        }

        .price { font-size: 1.8em; margin: 10px 0; font-weight: bold; }
        .up { color: #00ff88; }
        .down { color: var(--neon-pink); }

        /* 警報文字 */
        #alert-msg {
            text-align: center;
            color: var(--neon-pink);
            font-weight: bold;
            height: 24px;
            margin-bottom: 10px;
            text-shadow: 0 0 5px var(--neon-pink);
        }
    </style>
</head>
<body id="main-body">

    <h1>CRITICAL_MARKET_MONITOR</h1>
    <div id="alert-msg">SYSTEM_STABLE</div>
    
    <div id="crypto-container">
        </div>

    <script>
        const API_URL = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=20&page=1&sparkline=false';
        
        async function updateMarket() {
            try {
                const response = await fetch(API_URL);
                const data = await response.json();
                
                let hasCriticalDrop = false;
                const container = document.getElementById('crypto-container');
                container.innerHTML = ''; 

                data.forEach(coin => {
                    const isDrop = coin.price_change_percentage_24h < 0;
                    const isCritical = coin.price_change_percentage_24h <= -5.0; // 跌幅超過 5%
                    
                    if(isCritical) hasCriticalDrop = true;

                    // 創建卡片
                    const card = document.createElement('div');
                    card.className = `coin-card flash-update ${isCritical ? 'critical-drop' : ''}`;
                    
                    card.innerHTML = `
                        <div style="font-size:0.8em; color:var(--neon-blue)">RANK #${coin.market_cap_rank}</div>
                        <div style="font-weight:bold">${coin.name} (${coin.symbol.toUpperCase()})</div>
                        <div class="price">$${coin.current_price.toLocaleString()}</div>
                        <div class="${isDrop ? 'down' : 'up'}">
                            24H: ${coin.price_change_percentage_24h.toFixed(2)}%
                            ${isCritical ? ' [CRITICAL]' : ''}
                        </div>
                    `;
                    container.appendChild(card);
                });

                // 處理全螢幕警報邏輯
                handleAlertSystem(hasCriticalDrop);

            } catch (e) {
                console.error("Link severed...");
            }
        }

        function handleAlertSystem(active) {
            const body = document.getElementById('main-body');
            const msg = document.getElementById('alert-msg');
            
            if (active) {
                body.classList.add('alert-mode');
                msg.innerText = "⚠️ WARNING: （當跌幅超過 5% 時，觸發全螢幕紅色警戒）";
                
                // 這裡可以串接外部 Webhook (例如 Discord)
                // sendWebhookNotification("市場大跌警報！");
            } else {
                body.classList.remove('alert-mode');
                msg.innerText = "SYSTEM_STABLE / MONITORING_FLOW";
            }
        }

        // 模擬 Webhook 發送功能
        function sendWebhookNotification(message) {
            const WEBHOOK_URL = 'YOUR_DISCORD_OR_SLACK_URL';
            // fetch(WEBHOOK_URL, { method: 'POST', body: JSON.stringify({content: message}) });
            console.log("Webhook Triggered: " + message);
        }

        // 每 5 秒執行一次
        setInterval(updateMarket, 5000);
        updateMarket(); // 初始執行
    </script>
</body>
</html>