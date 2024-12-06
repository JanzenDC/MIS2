<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Macayo Integrated School</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('dist/img/light.jfif') no-repeat center center;
            background-size: cover;
           
        }

        header {
            width: 100%;
            padding: 20px;
            background-color: transparent;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.8rem;
            color: white;
            font-weight: bold;
            margin-left: 30px;
            margin-right: auto;
        }

        .logo img {
            width: 100px;
            height: auto;
            margin-right: 30px;
        }

        nav {
            flex: 1;
            display: flex;
            justify-content: flex-end;
            margin-right: 30px;
            margin-top: -30px;
        }

        nav ul {
            list-style: none;
            display: flex;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.3s ease;
            padding: 8px 20px;
            border-radius: 5px;
        }

        .login-btn-top {
            background-color: #007BFF;
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .login-btn-top:hover {
            background-color: #0056b3;
        }

        .container {
            display: flex;
            width: 700px;
            height: 430px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .welcome-section {
            flex: 1;
            background: url('dist/img/cmdbg.jpg') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            color: #fff;
        }

        .welcome-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .welcome-section h2, .welcome-section p {
            position: relative;
            z-index: 2;
        }

        .welcome-section h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .welcome-section p {
            font-size: 1.2rem;
            text-align: center;
        }

        .login-section {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-top: 20px;
        }

        .login-section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .input-box {
            margin-bottom: 20px;
        }

        .input-box input {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 8px;
        }

        .forgot-password {
            display: block;
            text-align: right;
            margin-top: -10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: #007BFF;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
        }

        .signup-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">
        <img src="dist/img/macayo_logo.png" alt="School Logo" style="width: 100px; height: auto;">
        Macayo Integrated School
    </div>
    <nav>
        <ul>
            <li><a href="admission_portal.php">Admission Portal</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="login_page.php" class="login-btn-top">LOGIN</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <div class="welcome-section">
        <h2>Welcome </h2>
        <p>Please log in using your personal information to stay connected with us.</p>
    </div>
    <div class="login-section">
        <h2>LOGIN</h2>
        <form action="login.php" method="POST">
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <a href="#" class="forgot-password">Forgot password?</a>
            <button type="submit" class="login-btn">Log In</button>
            <p class="signup-link">Don't have an account? <a href="#">Signup</a></p>
        </form>
    </div>
</div>
</body>
</html>
