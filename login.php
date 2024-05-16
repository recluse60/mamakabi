<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('database.php');//database php dosyasını bu dosyaya kullanıma açıyor
include('connection_log.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {// post methodu
    $email = $_POST["username"];
    $password = $_POST["password"];

    try {
        // bu kısımda emaile göre userları çekiyor
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);//sql komutunu hazırlıyor
        $stmt->execute([$email]);//email değişkenini execute(çalıştır)ediyor
        $user = $stmt->fetch(PDO::FETCH_ASSOC);//database verilerini çekiyor FETCH_ASSOC sütun isimlerine indisli dizi döndürür

        // Passwordu kontrol ediyor
        if (password_verify($_POST["password"], $user["password_hash"])) {
            $_SESSION["username"] = $email;//email adında yeni bi session açıyor ve ona databasedeki emaili atıyor 
            $_SESSION["musteri_id"] = $user['musteri_id']; // Set the session variable "musteri_id" to the user's ID
            //usertype a göre user mı admin mi giriş yapıyor ona bakıyor
            $userId = $user['musteri_id'];
            logUserConnection($userId, $_SERVER['REMOTE_ADDR']);
            if ($user["usertype"] == "user") {
                header("location:mamakabimain/userpanel.php");
            } 
        } else {
            echo '<script>alert("Email or password is invalid");</script>';
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FMA Giriş</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    font-family: Georgia, 'Times New Roman', Times, serif;
    margin: 0;
    padding: 0;
    height: 100vh;
    background-color: #f4f4f4;
}

.ortala{
    display: flex;
    align-items: center;
    height: 80%;
    margin-left: 10px;
    

    
}

a{
    text-decoration: none;
    color: black;
}

.login-container {
    background-color: #fff;
    padding: 40px;
    border-radius: 50px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 90%;
    margin: 0 auto;
    
}

form {
    display: flex;
    flex-direction: column;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
}

.input-group input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 95%;
}

button {
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
    display: block;
}

button:hover {
    background-color: #0056b3;
}

.navbar{
            display: flex;
            justify-content: space-between;
            padding: 15px 5px;
            width: 70%;
            margin: 0 auto;
           
            
        }

        .logo img {
    width: 50px;  /* Fixed width */
    height: auto; /* Maintain aspect ratio */
}

@media (min-width: 768px) {
    .logo img {
        width: 100px; /* Larger size on wider screens */
    }
}
.logo a {
    text-decoration: none; /* Altı çizili metni kaldırır */
}
        .navbar a{
            text-decoration: none;
            margin-left: 20px;
        }

        .navbar button{
            padding: 15px 30px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 10px;
            margin-left: 10px;
        }

        .navbar button:hover{
            background-color: #014c9c;
            transition: .5s;  
        }


@media screen and (max-width: 600px) {
    .login-container {
        width: 90%;
    }
}

    </style>
</head>
<body>
    <div class="navbar">
    <div class="logo">
            <a href="index.php">
            <img src="logo/logomain.png" alt="FMA LOGO">
        </a>
        </div>
        <div style="display: flex;">
            
            <a href="./register.html"> <button>Kayıt Ol</button> </a>
        </div>
    </div>

    <div class="ortala">
        <div class="login-container">
            <form action="#" method="POST">
                <h2>Giriş Yap</h2>
                <div class="input-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Giriş</button>
            </form>
        </div>
    </div>
</body>
</html>