<?php
//Tüm gerekli alanların şartlarını belirlediğimiz bölüm
if (empty($_POST["fullname"])) {
    die("İsminizi giriniz");
}
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    die("Emailinizi giriniz");
}
if (strlen($_POST["password"]) < 8){
    die("Şifreniz 8 karakterden az karakter içeremez");
}
if (!preg_match("/[a-z]/i", $_POST["password"])){
    die("Şifreniz en az bir harf içermeli");
}
if (!preg_match("/[0-9]/", $_POST["password"])){
    die("Şifreniz en az bir sayı içermeli");
}
if ($_POST["password"] !== $_POST["passwordconfirm"]){
    die("Şifreler eşleşmiyor");
}

session_start();
include('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $telno = $_POST["phone"];
    $cinsiyet = $_POST["gender"];
 

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);/*password u olduğu gibi database e aktarmak yerine onu anahtarlıyor yani
    eğer şifren 123456 ise onu anahtarlayıp $2y$10$A3SlZ0zCJW/rmAUOp/qHROB bu şekilde devam eden bi string e çeviriyor */
    try {
        // Check if email already exists
        $sql_check_email = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt_check_email = $conn->prepare($sql_check_email);
        $stmt_check_email->execute([$email]);
        $email_count = $stmt_check_email->fetchColumn();//email sayısına bakıyor
        //eğer aynı email den varsa error veriyor
        if ($email_count > 0) {
            die("Error: Email already exists.");
        }

        // Her şart sağlandıysa users tablosuna girdiğimiz verileri aktarıyor
        $sql_insert_user = "INSERT INTO users (full_name,email, password_hash, telno , cinsiyet) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);
        $stmt_insert_user->execute([$full_name,$email, $password_hash,$telno,$cinsiyet]);

         // kayıt başarılı olduğunda kayıdın başarılı olduğunu gösteren html e yönlendiriyor
         echo '<script>alert("Kayıt Başarılı");</script>';
         header("Location: login.php");
        
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

?>
