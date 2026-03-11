<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Detail Page</title>
    <link rel="stylesheet" href="Style/Video.css">
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

<a href="index.php"><img src="images/film_flix-nobg.png" alt="Logo" class="logo">
<img src ="images/film_flix-nobg.png" alt="Logo" class="logo">
</a>

<h1>Video Titel</h1>
<p>Beschrijving van de video</p>    
<video width="600" controls>
  <source src="movie.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>

<h2>Video Titel</h2>

<p> van de video</p>

<hr class="divider">

<h2>Reacties</h2>

<p>Username:</p>
<p>Geplaats op DD-MM-YYYY:</p>
<P>Comment van de gebruiker:</P>

<hr class="divider">

<p>Username:</p>
<p>Geplaats op DD-MM-YYYY:</p>
<P>Comment van de gebruiker:</P>

<hr class="divider">

<h2>Nieuwe reactie</h2>

<textarea rows="4" cols="50" placeholder="Plaats hier je reactie..."></textarea>


<button>Reactie Plaatsen</button>



    
    
</body>
</html>