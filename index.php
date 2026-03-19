<?php
session_start();
$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $inputUser = trim($_POST['username'] ?? '');
    $inputPass = $_POST['password'] ?? '';
    if (empty($inputUser) || empty($inputPass)) {
        $loginError = 'Vul alle velden in.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$inputUser]);
        $user = $stmt->fetch();
        if ($user && password_verify($inputPass, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $loginError = 'Ongeldige gebruikersnaam of wachtwoord.';
        }
    }
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "filmflix";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Verbinding mislukt: " . $e->getMessage());
}

$stmt = $pdo->query("SELECT * FROM video");
$videos = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmFlix - Home</title>
    <link rel="stylesheet" href="Style/homepage.css">
    <link rel="stylesheet" href="Style/Style.css">
</head>
<body>
<header>
<ul>
  <li><a href="index.php">Home</a></li>
  <li><a href="video.php">Video's</a></li>
  <li><a href="Admin.php">Admin</a></li>
  <li>
    <?php if (isset($_SESSION['user_id'])): ?>
      <span>👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
      <a href="index.php?logout=1">Uitloggen</a>
    <?php else: ?>
      <a href="#" onclick="openLogin()">Inloggen</a>
    <?php endif; ?>
  </li>
</ul>
</header>

<div class="login-overlay <?= $loginError ? 'active' : '' ?>" id="loginOverlay">
    <div class="login-popup">
        <button class="login-close" onclick="closeLogin()">&#x2715;</button>
        <h2>Inloggen</h2>
        <?php if ($loginError): ?>
            <p class="login-error"><?= htmlspecialchars($loginError) ?></p>
        <?php endif; ?>
        <form method="POST" action="index.php">
            <label for="username">Gebruikersnaam</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" required>
            <input type="hidden" name="login_submit" value="1">
            <button type="submit" class="login-btn">Inloggen</button>
        </form>
    </div>
</div>

<section id="welcome">
    <h1>Welkom bij FilmFlix</h1>
    <h2>Bekijk onze nieuwste video's</h2>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Zoeken..." onkeyup="filterVideos()">
        <button onclick="filterVideos()">🔍</button>
    </div>
    <hr class="divider">
</section>

<section id="videos">
    <?php if (empty($videos)): ?>
        <p style="padding-left: 50px;">Geen video's gevonden.</p>
    <?php else: ?>
        <?php foreach ($videos as $video): ?>
        <div class="video-card">
            <img src="images/placeholder.jpg" alt="Video thumbnail">
            <div class="video-content">
                <h2><?php echo htmlspecialchars($video['videoName']); ?></h2>
                <p><?php echo htmlspecialchars($video['videoDescription']); ?></p>
                <a href="video.php?id=<?php echo (int)$video['id']; ?>">
                    <button>BEKIJK VIDEO</button>
                </a>
            </div>
        </div>
        <hr class="divider">
        <?php endforeach; ?>
    <?php endif; ?>

    <br><br>
    <div id="nextBack">
        <button>Vorige</button>
        <button>Volgende</button>
    </div>
</section>

<script>
function filterVideos() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.video-card');
    cards.forEach(card => {
        const title = card.querySelector('h2').textContent.toLowerCase();
        const desc = card.querySelector('p').textContent.toLowerCase();
        card.style.display = (title.includes(input) || desc.includes(input)) ? 'flex' : 'none';
    });
}
function openLogin() {
    document.getElementById('loginOverlay').classList.add('active');
}
function closeLogin() {
    document.getElementById('loginOverlay').classList.remove('active');
}
document.getElementById('loginOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeLogin();
});
</script>

</body>
</html>