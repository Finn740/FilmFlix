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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_video'])) {
    $videoName        = htmlspecialchars(trim($_POST['videoName']));
    $videoDescription = htmlspecialchars(trim($_POST['videoDescription']));
    $videoLink        = htmlspecialchars(trim($_POST['videoLink']));
 
    $stmt = $pdo->prepare("INSERT INTO video (videoName, videoDescription, videoLink) VALUES (?, ?, ?)");
    $stmt->execute([$videoName, $videoDescription, $videoLink]);
    header("Location: Admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Style/Admin.css">
</head>

<body>

   
<nav class="navbar">
   <h1 class="Welkom">Welkom, Admin</h1>
    <button onclick="location.href='index.php'">Home</button>
    <button onclick="location.href='video.php'">Video's</button>
    <button onclick="location.href='admin.php'">Admin</button>
</nav>

<div class="form-box">
    <h2>Video toevoegen</h2>
    <form method="POST" action="Admin.php" enctype="multipart/form-data">
        <label>Naam:</label>
        <input type="text" name="videoName" placeholder="Naam van de video" required>
        <label>Beschrijving:</label>
        <input name="videoDescription" placeholder="Beschrijving van video"></input>
        <label>Video bestand:</label>
        <input type="file" name="fileToUpload" accept="video/mp4" required><br><br>
        <input type="submit" value="Upload video" name="submit">
    </form>
</div>

<br>
<p class="Overzicht">Overzicht video's</p>

    <!-- SEARCH -->
    <form class="search-bar">
        <input type="text" placeholder="Zoek in deze pagina..." name="search">
        <button type="submit">Zoek</button>
    </form>

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
                <td><?= htmlspecialchars($video['videoName']); ?></td>
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