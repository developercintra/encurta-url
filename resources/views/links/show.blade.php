<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Link - Encurtador</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --secondary: #6b7280;
            --secondary-hover: #4b5563;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light-bg: #f9fafb;
            --border: #e5e7eb;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        
        body {
            background-color: #f3f4f6;
            color: #1f2937;
            line-height: 1.5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        
        .header h1 {
            font-size: 1.875rem;
            font-weight: 600;
            color: #111827;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            color: white;
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: var(--secondary-hover);
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .grid-cols-1 {
            grid-template-columns: 1fr;
        }
        
        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .grid-cols-4 {
            grid-template-columns: repeat(4, 1fr);
        }
        
        @media (min-width: 768px) {
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-expired {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-inactive {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .stat-box {
            text-align: center;
            padding: 1.5rem;
            border-radius: 0.5rem;
        }
        
        .stat-number {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .input-group {
            margin-bottom: 1rem;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        
        .input-with-action {
            display: flex;
            align-items: center;
        }
        
        .input-with-action input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem 0 0 0.375rem;
            background-color: #f9fafb;
        }
        
        .input-with-action button {
            padding: 0.5rem;
            background-color: #e5e7eb;
            border: 1px solid #d1d5db;
            border-left: none;
            border-radius: 0 0.375rem 0.375rem 0;
            cursor: pointer;
        }
        
        .input-with-action button:hover {
            background-color: #d1d5db;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        th {
            background-color: #f9fafb;
            font-weight: 500;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        tr:hover {
            background-color: #f9fafb;
        }
        
        .notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background-color: var(--success);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 50;
            animation: fadeIn 0.3s, fadeOut 0.3s 1.7s forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 1.5rem;
        }
        
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1rem;
        }
        
        .tab {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            color: #6b7280;
        }
        
        .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .qrcode-container {
            text-align: center;
            padding: 1.5rem;
        }
        
        .qrcode-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
        }
        
        .more-details {
            margin-top: 1rem;
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
            border-left: 4px solid var(--primary);
        }
        
        .more-details summary {
            cursor: pointer;
            font-weight: 500;
        }
        
        .more-details-content {
            margin-top: 1rem;
        }
        
        .visits-table-container {
            overflow-x: auto;
        }
        
        @media (max-width: 768px) {
            .grid-cols-2, .grid-cols-3, .grid-cols-4 {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detalhes do Link</h1>
            <a href="#" class="btn btn-secondary">← Voltar</a>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div>
                    <span class="status-badge status-active">Ativo</span>
                    <span class="text-sm text-gray-500 ml-3">Criado em 15/08/2023 14:30</span>
                </div>
                <div>
                    <a href="#" class="btn btn-primary btn-sm">📱 Ver QR Code</a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Link</h3>
                        
                        <div class="input-group">
                            <label>URL Original</label>
                            <div class="input-with-action">
                                <input type="text" value="https://www.example.com/very-long-url-that-needs-to-be-shortened" readonly>
                                <button onclick="copyToClipboard('https://www.example.com/very-long-url-that-needs-to-be-shortened')">📋</button>
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <label>URL Curta</label>
                            <div class="input-with-action">
                                <input type="text" value="https://short.url/abc123" readonly>
                                <button onclick="copyToClipboard('https://short.url/abc123')">📋</button>
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <label>Slug</label>
                            <input type="text" value="abc123" readonly class="w-full p-2 border border-gray-300 rounded-md bg-gray-50">
                        </div>
                        
                        <div class="input-group">
                            <label>Data de Expiração</label>
                            <input type="text" value="15/11/2023 14:30" readonly class="w-full p-2 border border-gray-300 rounded-md bg-gray-50">
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="stat-box" style="background-color: #eff6ff;">
                                <div class="stat-number" style="color: #1d4ed8;">245</div>
                                <div class="stat-label">Total de Cliques</div>
                            </div>
                            <div class="stat-box" style="background-color: #f0fdf4;">
                                <div class="stat-number" style="color: #15803d;">198</div>
                                <div class="stat-label">Visitas Únicas</div>
                            </div>
                        </div>
                        
                        <div class="tabs">
                            <div class="tab active" onclick="changeTab('daily')">Diário</div>
                            <div class="tab" onclick="changeTab('weekly')">Semanal</div>
                            <div class="tab" onclick="changeTab('monthly')">Mensal</div>
                        </div>
                        
                        <div class="tab-content active" id="daily-tab">
                            <div class="chart-container">
                                <canvas id="dailyChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="tab-content" id="weekly-tab">
                            <div class="chart-container">
                                <canvas id="weeklyChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="tab-content" id="monthly-tab">
                            <div class="chart-container">
                                <canvas id="monthlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="more-details">
                    <details>
                        <summary>Mais detalhes</summary>
                        <div class="more-details-content grid grid-cols-3 gap-4">
                            <div>
                                <strong>Primeiro acesso:</strong> 15/08/2023 15:45
                            </div>
                            <div>
                                <strong>Último acesso:</strong> 22/10/2023 09:12
                            </div>
                            <div>
                                <strong>Dispositivos móveis:</strong> 58%
                            </div>
                            <div>
                                <strong>Navegador mais usado:</strong> Chrome
                            </div>
                            <div>
                                <strong>País principal:</strong> Brasil
                            </strong>
                            </div>
                        </div>
                    </details>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2>Últimas Visitas</h2>
            </div>
            
            <div class="card-body">
                <div class="visits-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Data e Hora</th>
                                <th>User Agent</th>
                                <th>IP</th>
                                <th>Localização</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>22/10/2023 09:12:45</td>
                                <td>Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15</td>
                                <td>192.168.1.1</td>
                                <td>São Paulo, BR</td>
                            </tr>
                            <tr>
                                <td>21/10/2023 18:30:22</td>
                                <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36</td>
                                <td>192.168.1.2</td>
                                <td>Rio de Janeiro, BR</td>
                            </tr>
                            <tr>
                                <td>20/10/2023 14:15:33</td>
                                <td>Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36</td>
                                <td>192.168.1.3</td>
                                <td>Belo Horizonte, BR</td>
                            </tr>
                            <tr>
                                <td>19/10/2023 11:45:09</td>
                                <td>Mozilla/5.0 (Linux; Android 13; SM-S901B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Mobile Safari/537.36</td>
                                <td>192.168.1.4</td>
                                <td>Porto Alegre, BR</td>
                            </tr>
                            <tr>
                                <td>18/10/2023 16:20:57</td>
                                <td>Mozilla/5.0 (iPad; CPU OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1</td>
                                <td>192.168.1.5</td>
                                <td>Curitiba, BR</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-500">
                        Mostrando 5 de 198 visitas
                    </span>
                    <button class="btn btn-secondary btn-sm ml-3">Carregar mais</button>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2>QR Code</h2>
            </div>
            
            <div class="card-body">
                <div class="qrcode-container">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://short.url/abc123" alt="QR Code">
                    <p class="mt-3 text-sm text-gray-600">
                        Escaneie este QR Code para acessar o link
                    </p>
                    <a href="#" class="btn btn-primary mt-2">💾 Baixar QR Code</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const notification = document.createElement('div');
                notification.textContent = 'Copiado para a área de transferência!';
                notification.className = 'notification';
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 2000);
            });
        }
        
        function changeTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Deactivate all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Activate selected tab
            document.querySelector(`.tab:nth-child(${tabName === 'daily' ? 1 : tabName === 'weekly' ? 2 : 3})`).classList.add('active');
            
            // Show selected tab content
            document.getElementById(`${tabName}-tab`).classList.add('active');
        }
        
        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Daily chart
            const dailyCtx = document.getElementById('dailyChart').getContext('2d');
            const dailyChart = new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Cliques por dia',
                        data: [12, 19, 15, 17, 22, 30, 28],
                        backgroundColor: 'rgba(79, 70, 229, 0.5)',
                        borderColor: 'rgb(79, 70, 229)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Weekly chart
            const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
            const weeklyChart = new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                    datasets: [{
                        label: 'Cliques por semana',
                        data: [85, 102, 78, 94],
                        backgroundColor: 'rgba(79, 70, 229, 0.5)',
                        borderColor: 'rgb(79, 70, 229)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Monthly chart
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out'],
                    datasets: [{
                        label: 'Cliques por mês',
                        data: [65, 59, 80, 81, 56, 55, 72, 89, 95, 62],
                        fill: false,
                        borderColor: 'rgb(79, 70, 229)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>