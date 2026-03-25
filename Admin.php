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

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM video WHERE id = ?");
    $stmt->execute([$deleteId]);
    header("Location: Admin.php");
    exit;
}

if (isset($_POST['submit'])) {
    $videoName        = htmlspecialchars(trim($_POST['videoName']));
    $videoDescription = htmlspecialchars(trim($_POST['videoDescription']));
    $fileName         = basename($_FILES['fileToUpload']['name']);
    $uploadPath       = 'videos/' . $fileName;

    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadPath)) {
        $stmt = $pdo->prepare("INSERT INTO video (videoName, videoDescription, videoLink) VALUES (?, ?, ?)");
        $stmt->execute([$videoName, $videoDescription, $uploadPath]);
        header("Location: Admin.php");
        exit;
    } else {
        echo "Upload mislukt.";
    }
}
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
    <button>Home</button>
    <button>Video's</button>
    <button>Admin</button>
</nav>


<p class="Welkom"><b>Welkom, Admin</b></p>

<div class="form-box">
    <h2>Video toevoegen</h2>
    <form method="POST" action="Admin.php" enctype="multipart/form-data">
        <label>Naam:</label>
        <input type="text" name="videoName" placeholder="Naam van de video" required><br>
        <label>Beschrijving:</label>
        <textarea name="videoDescription" rows="3" placeholder="Beschrijving van de video"></textarea><br>
        <label>Video bestand:</label>
        <input type="file" name="fileToUpload" accept="video/mp4" required><br>
        <input type="submit" value="Upload video" name="submit">
    </form>
</div>

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
            <td>
                <a href="Admin.php?delete=<?php echo $video['id']; ?>" onclick="return confirm('Weet je zeker dat je deze video wilt verwijderen?')">
                    <button class="Verwijder">Verwijder</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<button class="Vorige">Vorige</button>
<button class="Volgende">Volgende</button>

</body>
</html>