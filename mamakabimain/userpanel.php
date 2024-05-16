<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../index.php");
    exit();
}

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'mamakabi';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $pass, $options);
} catch (\PDOException $e) {
    die(" " . $e->getMessage());
}

// Kullanıcı bilgilerini al
$musteri_id = $_SESSION['musteri_id'];
$sql_user = "SELECT full_name FROM users WHERE musteri_id = ?";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute([$musteri_id]);
$user = $stmt_user->fetch();

if ($user) {
    $full_name = $user['full_name'];

    // Yem durumu bilgilerini al
    $sql_feeding = "SELECT yemmiktarı, sondolum FROM yemdurum WHERE yemid = 1"; // Sabit bir yemid kullanıyoruz
    $stmt_feeding = $pdo->prepare($sql_feeding);
    $stmt_feeding->execute();
    $feeding_activity = $stmt_feeding->fetch();
} else {
    echo "Kullanıcı bulunamadı.";
    exit();
}

$kalanYem = $feeding_activity ? $feeding_activity['yemmiktarı'] : 0;
$sonDolumTarihi = $feeding_activity ? $feeding_activity['sondolum'] : 'Yok';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset_yem'])) {
        $kalanYem = 10; // Kalan yem miktarını 10 olarak güncelle
        $sql_update_yem = "UPDATE yemdurum SET yemmiktarı = 10, sondolum = NOW() WHERE yemid = 1"; // Yemid 1'i sabit kullanıyoruz
        $stmt_update_yem = $pdo->prepare($sql_update_yem);
        $stmt_update_yem->execute();
        header("Location: userpanel.php"); // Sayfayı yeniden yükleyerek güncellenmiş veriyi alın
        exit();
    } else {
        $id = 1;  // Sabit bir ID değeri belirliyoruz
        $date = $_POST['date'];
        $morning = $date . ' ' . $_POST['morning'];
        $afternoon = $date . ' ' . $_POST['afternoon'];
        $evening = $date . ' ' . $_POST['evening'];
        $scale = isset($_POST['scale']) ? $_POST['scale'] : 1;
        $motordurum = isset($_POST['motordurum']) ? $_POST['motordurum'] : 0;

        // Güncelleme sorgusu
        $query = "UPDATE motor SET date=?, morning=?, afternoon=?, evening=?, motordurum=?, scale=? WHERE id=?";
        $params = [$date, $morning, $afternoon, $evening, $motordurum, $scale, $id];

        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            // Kalan yem miktarını güncelle
            $new_yem_miktari = $kalanYem - ($scale * 3); // Sabah, öğle ve akşam için ölçekleri düş
            $sql_update_yem = "UPDATE yemdurum SET yemmiktarı = ? WHERE yemid = 1";
            $stmt_update_yem = $pdo->prepare($sql_update_yem);
            $stmt_update_yem->execute([$new_yem_miktari]);

            // feeding_activity tablosuna verileri ekle
            $feeding_dates = [$morning, $afternoon, $evening];
            foreach ($feeding_dates as $feed_date) {
                $sql_insert_activity = "INSERT INTO feeding_activity (musteri_id, feed_date, scale) VALUES (?, ?, ?)";
                $stmt_insert_activity = $pdo->prepare($sql_insert_activity);
                $stmt_insert_activity->execute([$musteri_id, $feed_date, $scale]);
            }

            header("Location: userpanel.php"); // Sayfayı yeniden yükleyerek güncellenmiş veriyi alın
            exit();
        } catch (\PDOException $e) {
            echo "" . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaMeAk</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Georgia, 'Times New Roman', Times, serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            margin: 0;
        }
        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(128, 128, 128, 0.5);
            color: white;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .logo img {
            height: 100px;
        }
        .navbar button {
            padding: 10px 20px;
            background-color: rgba(245, 151, 111, 1);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: block;
            margin-bottom: 10px;
            width: auto;
            text-align: center;
        }
        .navbar button:hover {
            background-color: rgba(245, 151, 111, 0.58);
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            padding: 20px;
            margin-top: 120px; /* Navbar yüksekliğini hesaba kat */
        }
        .sidebar {
            width: 200px;
            background-color: rgba(128, 128, 128, 0.5);
            color: white;
            padding: 20px;
            position: fixed;
            top: 130px;
            left: 0;
            bottom: 0;
            overflow-y: auto; /* İçerik taşarsa kaydırma çubuğu ekle */
        }
        .sidebar button {
            padding: 10px 20px;
            background-color: rgba(245, 151, 111, 1);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: block;
            margin-bottom: 10px;
            width: 100%;
            text-align: center;
        }
        .sidebar button:hover {
            background-color: rgba(245, 151, 111, 0.58);
        }
        .content {
            flex: 1;
            padding: 20px;
            margin-left: 220px; /* Sidebar genişliği kadar boşluk bırak */
        }
        .motor-control {
            background-color: rgba(128, 128, 128, 0.5);
            padding: 20px;
            border-radius: 30px;
            width: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px; /* Üstte boşluk bırak */
        }
        .chart-container {
            background-color: rgba(128, 128, 128, 0.5);
            padding: 20px;
            border-radius: 10px;
            margin-left: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #myChart {
            width: 200px;
            height: 200px;
        }
        .background-blur {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('images/back.png');
            background-size: cover;
            background-repeat: no-repeat;
            filter: blur(5px);
            z-index: -1;
            background-position: center center;
            background-attachment: fixed;
        }
        .date-picker {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 100%;
        }
        input, button {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid white;
            background-color: rgba(245, 151, 111, 1);
        }
        label {
            margin-right: 10px;
        }
        h2 {
            margin-bottom: 10px;
        }
        .alert {
            display: none;
            color: white;
            background-color: red;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 1.2em;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
                margin-bottom: 20px;
            }
            .content {
                margin-left: 0;
            }
            .motor-control {
                width: 100%;
                margin-left: 0;
            }
            .chart-container {
                width: 100%;
                margin-left: 0;
                margin-top: 20px;
            }
            .date-picker {
                flex-direction: column;
                align-items: flex-start;
            }
            .date-picker label {
                margin-bottom: 5px;
            }
            .date-picker input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="background-blur"></div>    
<div class="navbar">
    <div class="logo">
        <img src="images/logomain.png" alt="FMA LOGO">
    </div>
    <button onclick="location.href='../logout.php'">Çıkış Yap</button>
</div>
<div class="container">
    <div class="sidebar">
        <button onclick="location.href='userpanel.php'">Ana Sayfa</button>
        <button onclick="location.href='aktivite.php'">Aktivite Kaydı</button>
    </div>
    <div class="content">
        <form method="post" action="">
            <div class="motor-control">
                <h2>Besleme Zamanı</h2>
                <div class="date-picker">
                    <label for="date">Tarih Seç:</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="date-picker">
                    <label for="morning">Sabah:</label>
                    <input type="time" id="morning" name="morning" required>
                </div>
                <div class="date-picker">
                    <label for="afternoon">Öğle:</label>
                    <input type="time" id="afternoon" name="afternoon" required>
                </div>
                <div class="date-picker">
                    <label for="evening">Akşam:</label>
                    <input type="time" id="evening" name="evening" required>
                </div>
                <div class="date-picker">
                    <label for="scale">Ölçek Sayısı:</label>
                    <input type="number" id="scale" name="scale" min="1" required>
                </div>
                <button type="submit">AYARLA</button>
            </div>
        </form>
    </div>
    <div class="chart-container">
        <canvas id="myChart"></canvas>
        <p>Son Yem Dolum Tarihi: <span id="sonDolumTarihi"><?php echo htmlspecialchars($sonDolumTarihi); ?></span></p>
        <div class="alert" id="alert">Yem miktarı %10 veya daha az!</div>
        <form method="post" action="">
            <button type="submit" name="reset_yem">Yem Dolumunu Güncelle</button>
        </form>
    </div>
</div>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    let kalanYem = <?php echo $kalanYem; ?>;
    const yemOrani = (kalanYem / 10) * 100; // Kalan yem oranı yüzde cinsinden

    const data = {
        labels: ['Kalan Yem', 'Kullanılan Yem'],
        datasets: [{
            data: [yemOrani, 100 - yemOrani],
            backgroundColor: [
                yemOrani <= 10 ? 'red' : 'rgba(245, 151, 111, 1)',
                'rgba(245, 151, 111, 0.3)'
            ],
            hoverOffset: 4
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                        }
                    }
                }
            }
        }
    };

    const myChart = new Chart(ctx, config);

    function checkYemOrani() {
        if (kalanYem <= 1) {
            document.getElementById('alert').style.display = 'block';
        } else {
            document.getElementById('alert').style.display = 'none';
        }
    }

    // İlk kontrol
    checkYemOrani();

    // Ölçek miktarını gönderirken kalan yem miktarını güncelleme
    document.querySelector('form').addEventListener('submit', function(event) {
        const scaleInput = document.getElementById('scale');
        const scaleValue = parseInt(scaleInput.value, 10);
        kalanYem = Math.max(0, kalanYem - scaleValue); // Kalan yem miktarını güncelle

        // Grafiği güncelle
        myChart.data.datasets[0].data[0] = (kalanYem / 10) * 100;
        myChart.data.datasets[0].data[1] = 100 - ((kalanYem / 10) * 100);
        myChart.data.datasets[0].backgroundColor[0] = kalanYem <= 1 ? 'red' : 'rgba(245, 151, 111, 1)';
        myChart.update();

        // Yem oranını kontrol et
        checkYemOrani();
    });

    // Yem dolumunu güncelle butonuna tıklama olayını dinleme
    document.querySelector('button[name="reset_yem"]').addEventListener('click', function(event) {
        event.preventDefault(); // Sayfanın yeniden yüklenmesini engelle

        kalanYem = 10; // Kalan yem miktarını 10 olarak güncelle

        // Grafiği güncelle
        myChart.data.datasets[0].data[0] = 100;
        myChart.data.datasets[0].data[1] = 0;
        myChart.data.datasets[0].backgroundColor[0] = 'rgba(245, 151, 111, 1)';
        myChart.update();

        // Yem oranını kontrol et
        checkYemOrani();

        // Veritabanını güncellemek için formu gönder
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = ''; // Aynı sayfada kalmak için
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'reset_yem';
        input.value = '1';
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    });
</script>
</body>
</html>
