<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Test</title>
    @vite('resources/js/app.js')
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
        }
        header {
            padding: 20px;
            background: rgba(0,0,0,0.3);
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .hero {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 80vh;
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .btn {
            padding: 12px 25px;
            background: #ff6b6b;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover {
            background: #ff4757;
        }
    </style>
</head>
<body class="antialiased">
    <header>
        🚀 Laravel + Vite + Vue Test
    </header>
    <div class="hero">
        <h1>Welcome to My Project</h1>
        <p>Đây là trang Home đang chạy thử với Vue component</p>
        <button class="btn">Bắt đầu ngay</button>
    </div>

    <div id="app" style="margin-top:50px; text-align:center;">
        <home-page></home-page>
    </div>
</body>
</html>

