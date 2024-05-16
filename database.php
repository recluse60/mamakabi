<?php
//server a bağlanmak için lazım olan stringleri değişkene atıyoruz
$servername = "localhost";
$username = "root";
$password = "";
$database = "mamakabi";
//conn değişkenine database bağlantımızı atıyoruz
try {
     $conn = new PDO("mysql:host=$servername;dbname=$database",$username,$password);
     $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
//error yakalarsa connection failed yazısını echolıyor
catch(PDOException $e) {
    echo "Connection Failed" .$e->getMessage();
}
?>