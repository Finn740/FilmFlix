<?php
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
    <title>Admin Dashboard - FilmFlix</title>
    <link rel="stylesheet" href="Style/Admin.css">
</head>
<body>
<header>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="video.php">Video's</a></li>
      <li><a href="Admin.php">Admin</a></li>
    </ul>
  </nav>
</header>

<nav class="admin-nav">
    <p class="logo">FilmFlix</p>
    <button>Overzicht Video's</button>
    <button>Nieuwe video</button>
    <button>Uitloggen</button>
</nav>

<form class="search-bar">
    <input type="text" placeholder="Zoek in deze pagina..." name="search">
    <button type="button">Zoek</button>
</form>

<p class="Welkom"><b>Welkom, Admin</b></p>
<br>
<p class="Overzicht">Overzicht video's</p>

<table>
    <tr>
        <th>Titel</th>
        <th>Bewerk</th>
        <th>Verwijder</th>
    </tr>
    <?php if (empty($videos)): ?>
    <tr>
        <td colspan="3">Geen video's gevonden.</td>
    </tr>
    <?php else: ?>
        <?php foreach ($videos as $video): ?>
        <tr>
            <td><?php echo htmlspecialchars($video['videoName']); ?></td>
            <td><button class="Bewerk">Bewerk</button></td>
            <td><button class="Verwijder">Verwijder</button></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<button class="Vorige">Vorige</button>
<button class="Volgende">Volgende</button>

</body>
</html>