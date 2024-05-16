<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaMeAk</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Georgia, 'Times New Roman', Times, serif;
}

a {
    text-decoration: none;
}

.container {
    width: 70%;
    margin: 0 auto;
}

.navbar {
    display: flex;
    justify-content: space-between;
    padding: 15px 5px;
    margin-bottom: 20px;
}

.logo img {
    width: 50px; /* Fixed width */
    height: auto; /* Maintain aspect ratio */
}

@media (min-width: 768px) {
    .logo img {
        width: 100px; /* Larger size on wider screens */
    }
}

.navbar button {
    padding: 15px 30px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 10px;
    margin-left: 10px;
}

.navbar button:hover {
    background-color: #014c9c;
    transition: .5s;
}

.content {
    width: 100%;
}

.slogan {
    font-size: 30px;
    text-align: center;
    margin-top: 50px;
    font-weight: bold;
    width: 100%;
    font-family: 'Times New Roman', Times, serif;
}

.content1, .content2 {
    display: flex;
    padding: 15px 25px;
    margin-top: 30px;
    align-items: center;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    border-radius: 20px;
    flex-direction: column; /* Adjusts the layout for better readability */
}

.content1 img {
    width: 300px; /* Adjust image size as needed */
}

.text h2, .content2 h2 {
    font-size: 35px;
}

.text p, .content2 p {
    font-size: 20px;
    letter-spacing: 1px;
}

.motor-control {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 15px 25px;
    border-radius: 20px;
}

.mc-content {
    width: 50%;
}

.mc-content input, .mc-content button {
    width: 100%;
    height: 40px;
    margin-top: 10px;
    border-radius: 5px;
}

.mc-content button {
    padding: 15px 10px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 10px;
    cursor: pointer;
}

.carousel {
    max-width: 80%;
    margin: auto;
    overflow: hidden;
    border-radius: 30px;  /* Daha belirgin bir yuvarlaklık için değer */
}

.carousel .carousel-inner {
    border-radius: 30px;  /* İç konteyner için yuvarlak köşeler */
}

.carousel .carousel-inner .carousel-item img {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 30px;  /* Resimler için yuvarlak köşeler */
}
.slider-container {
    width: 100%;
    overflow: hidden;
}

.slide img {
    width: 100%;
    height: auto;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="navbar">
        <div class="logo">
            <img src="logo/logomain.png" alt="FMA LOGO">
        </div>
            <div>
                <a href="login.php"> <button>Giriş Yap</button> </a>
                <a href="register.html"> <button>Kayıt Ol</button> </a>
            </div>
        </div>

        
<div id="carouselExampleRide" class="carousel slide" data-bs-ride="true">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="mamakabimain/images/slide1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="mamakabimain/images/slide2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="mamakabimain/images/slide3.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>




            
            <div class="content1">
                <div class="text">
                    <h2>Projenin Amacı</h2> <br>
                    <p>
                        Uzaktan yönetilebilir mama kabı, evcil hayvan sahiplerinin karşılaştığı günlük sorunlardan biri olan besleme sürecini optimize etmek ve modern yaşamın getirdiği zorlukları hafifletmek için geliştirilmiş bir yenilikçi çözümdür. Bu proje, evcil hayvan sahiplerine sağladığı bir dizi avantajla evcil dostlarına daha iyi bakma ve onların sağlığını ve refahını güvence altına alma amacını taşır.
                    </p>
                </div>
                <img src="mamakabimain/images/kopekimg.png" alt="" >
            </div>
            <div class="content2">
                <h2>Projenin Özellikleri</h2> <br>
                <p> 
                    <strong>Otomatik Besleme:</strong><br>
                    Bu proje, evcil hayvan sahiplerinin işlerini veya seyahatlerini sürdürürken dahi evcil dostlarının beslenmesini sağlar. Otomatik besleme özelliği sayesinde, sahipler herhangi bir müdahalede bulunmadan belirlenen zamanlarda mama kabının otomatik olarak açılmasını sağlayabilirler.
                </p>
                <br>
                <p> 
                    <strong>Uzaktan Kontrol:</strong><br>
                    Uygulama veya web arayüzü aracılığıyla, sahipler herhangi bir yerden mama kabının kontrolünü sağlayabilirler. Bu sayede, evcil hayvanlarının ne kadar mama tükettiklerini izleyebilir, gerektiğinde mama miktarını ayarlayabilir ve hatta besleme zamanlarını uzaktan programlayabilirler.
                </p>
                <br>
                <p> 
                    <strong>Esneklik ve Kolaylık:</strong><br>
                    Uzaktan yönetilebilir mama kabı, sahiplerin günlük rutinlerini kolaylaştırır ve daha esnek bir yaşam tarzı sunar. Sahipler, çalışma saatlerini uzatmak veya aniden çıktıkları bir seyahatte bile evcil dostlarının beslenmesini sağlamak için bu projeden yararlanabilirler.
                </p>
            </div>
            
        </div>
        
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>