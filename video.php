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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM video WHERE id = ?");
$stmt->execute([$id]);
$video = $stmt->fetch();

$stmtComments = $pdo->prepare("SELECT * FROM comments WHERE video_id = ? ORDER BY created_at ASC");
$stmtComments->execute([$id]);
$comments = $stmtComments->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
    $user    = htmlspecialchars(trim($_POST['username'] ?? 'Anoniem'));
    $comment = htmlspecialchars(trim($_POST['comment']));
    $stmtInsert = $pdo->prepare("INSERT INTO comments (video_id, username, comment, created_at) VALUES (?, ?, ?, NOW())");
    $stmtInsert->execute([$id, $user, $comment]);
    header("Location: video.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $video ? htmlspecialchars($video['videoName']) . ' - FilmFlix' : 'Video niet gevonden'; ?></title>
    <link rel="stylesheet" href="Style/video.css">
    <link rel="stylesheet" href="Style/Style.css">

    <title>Video Detail Page</title>
    <link rel="stylesheet" href="Style/video.css">

</head>
<body>
<header>
  <nav class="navbar">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="video.php">Video's</a></li>
      <li><a href="Admin.php">Admin</a></li>
    </ul>
  </nav>
</header>

<a href="index.php">
    <img src="images/film_flix-nobg.png" alt="Logo" class="logo">
</a>

<?php if (!$video): ?>
    <p style="padding: 40px;">Video niet gevonden.</p>
<?php else: ?>

<div style="padding: 20px 40px;">
    <h1><?php echo htmlspecialchars($video['videoName']); ?></h1>
    <p><?php echo htmlspecialchars($video['videoDescription']); ?></p>

    <video width="600" controls>
<source src="<?php echo htmlspecialchars($video['videoLink']); ?>" type="video/mp4">
        Jouw browser ondersteunt de video tag niet.
    </video>

    <hr class="divider">
    <h2>Reacties</h2>

    <?php if (empty($comments)): ?>
        <p>Nog geen reacties. Wees de eerste!</p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong></p>
            <p>Geplaatst op: <?php echo date('d-m-Y', strtotime($comment['created_at'])); ?></p>
            <p><?php echo htmlspecialchars($comment['comment']); ?></p>
            <hr class="divider">
        <?php endforeach; ?>
    <?php endif; ?>

    <h2>Nieuwe reactie</h2>
    <form method="POST" action="video.php?id=<?php echo $id; ?>">
        <input type="text" name="username" placeholder="Jouw naam" style="display:block; margin-bottom:10px; padding:8px; width:300px;"><br>
        <textarea name="comment" rows="4" cols="50" placeholder="Plaats hier je reactie..." required></textarea><br><br>
        <button type="submit">Reactie Plaatsen</button>
    </form>
</div>

<?php endif; ?>


<p>Username:</p>
<p>Geplaats op DD-MM-YYYY:</p>
<P>Comment van de gebruiker:</P>

<hr class="divider">

<h2>Nieuwe reactie</h2>

<textarea rows="4" cols="50" placeholder="Plaats hier je reactie..."></textarea>


<button>Reactie Plaatsen</button>



    
    

</body>
</html>