<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vue + Laravel</title>
</head>
<body>
    <div id="app">
        <router-view></router-view> <!-- ✅ Vue sẽ tự load HomePage khi path = "/" -->
    </div>

    @vite('resources/js/app.js')  <!-- Nếu bạn dùng Vite -->
</body>
</html>
