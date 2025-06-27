<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Digi Locker</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@800&family=Zen+Dots&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('assets/images/back.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Orbitron', sans-serif;
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .box {
            background: rgba(0, 0, 0, 0.75);
            padding: 50px;
            border-radius: 20px;
            border: 3px solid #00fff7;
            box-shadow: 0 0 25px #00ffcc, 0 0 50px #00f2fe;
            text-align: center;
            max-width: 600px;
            width: 90%;
        }

        h1 {
            font-size: 40px;
            margin-bottom: 20px;
            background: linear-gradient(to right, #00f2fe, #4facfe, #38f9d7, #43e97b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px #00fff7, 0 0 40px #00ffcc;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 10px #00fff7, 0 0 20px #00ffcc;
            }
            to {
                text-shadow: 0 0 30px #43e97b, 0 0 40px #38f9d7;
            }
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #f0f0f0;
        }

        .btn {
            display: inline-block;
            margin: 10px 15px;
            padding: 12px 28px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            border: none;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.6);
            transition: 0.3s;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn:hover {
            background: linear-gradient(to right, #43e97b, #38f9d7);
            box-shadow: 0 0 25px rgba(0, 255, 255, 1);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>WELCOME TO DIGILOCKER</h1>
        <p>Securely store and manage your important documents</p>
        <a href="login.php" class="btn"><i class="fas fa-sign-in-alt"></i> Login</a>
        <a href="register.php" class="btn"><i class="fas fa-user-plus"></i> Register</a>
        <a href="admin_auth.php" class="btn"><i class="fas fa-user-shield"></i> Admin</a>
    </div>
</body>
</html>
