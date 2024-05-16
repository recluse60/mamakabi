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
} else {
    echo "Kullanıcı bulunamadı.";
    exit();
}

// Sayfalama
$limit = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Besleme aktivitelerini al
$sql_feeding_count = "SELECT COUNT(*) FROM feeding_activity WHERE musteri_id = ?";
$stmt_feeding_count = $pdo->prepare($sql_feeding_count);
$stmt_feeding_count->execute([$musteri_id]);
$total_activities = $stmt_feeding_count->fetchColumn();

$total_pages = ceil($total_activities / $limit);

$sql_feeding = "SELECT feed_date, scale FROM feeding_activity WHERE musteri_id = ? ORDER BY feed_date DESC LIMIT ? OFFSET ?";
$stmt_feeding = $pdo->prepare($sql_feeding);
$stmt_feeding->execute([$musteri_id, $limit, $offset]);
$feeding_activities = $stmt_feeding->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaMeAk - Aktivite Kaydı</title>
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
            background-color: rgba(128, 128, 128, 0.5);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: rgba(128, 128, 128, 0.5);
            color: white;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            color: black;
            border: 1px solid rgba(245, 151, 110, 0.6);
            border-radius: 5px;
        }
        .pagination a.active {
            background-color: rgba(245, 151, 111, 1);
            color: white;
            border: 1px solid rgba(245, 151, 111, 1);
        }
        .pagination a:hover {
            background-color: rgba(245, 151, 111, 0.58);
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
        <h2>Hoşgeldiniz <?php echo htmlspecialchars($full_name); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Ölçek</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($feeding_activities) > 0): ?>
                    <?php foreach ($feeding_activities as $activity): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($activity['feed_date']); ?></td>
                            <td><?php echo htmlspecialchars($activity['scale']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Besleme kaydı bulunamadı.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">&laquo; Önceki</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Sonraki &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
