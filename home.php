<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bg.jpg');
            color: #fff;
        }

        /* Header Styles */
        h1 {
            text-align: center;
            font-family: 'verdana';
            color: black;
            margin-top: 50px;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeIn 2s ease-in-out;
        }
        h3{
            text-align: center;
            font-family: 'verdana';
            color: black;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        .blink{
            animation: blink-animation 5s steps(2,start)infinite;
        }

        /* Navigation Links */
        .nav {
            margin: 10px auto;
        }

        a {
            display: inline-block;
            margin: 15px;
            padding: 15px 30px;
            font-family: 'papyrus';
            font-size: 1.2rem;
            font-weight: bold;
            padding: 5px;
            text-decoration: none;
            color: black;
            background: steelblue;
            border-radius: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
            animation: fadeIn 2s ease-in-out;
        }

        a:hover {

            background-color: #C70039;
            color: #fff;
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }
       @keyframes blink-animation{
        50%{
            opacity: 0;
        }

       }

        /* Footer Styles */
        footer {
            margin-top: 50px;
            padding: 10px 0;
            background-color: steelblue;
            color: black;
            font-size: 0.9rem;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            a {
                margin: 10px;
                padding: 10px 20px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <h1>Mobile Trolley</h1>
    <h3 class="blink">Gateway for Mobiles</h3>
    <div class="nav">
        <a href="user-login.php">User Login</a>
        <a href="user-registration.php">Register</a>
        <a href="admin-login.php">Admin Login</a>
    </div>
    <footer>
        © 2024 Mobile Trolley. All Rights Reserved.
    </footer>
</body>
</html>

