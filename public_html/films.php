<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Star Wars Database - Films</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
<img src="img/star_wars_logo.png" width="10%" class="centre" alt="Star Wars logo"/>

<!-- Navigation -->
<nav>
    <a href="index.html">Home</a>
    <a href="films.php">Films</a>
    <a href="planets.php">Planets</a>
    <a href="people.php">People</a>
</nav>

<hr>

<!-- Search -->
<form>
    <label for="search-bar"></label>
    <input placeholder="SEARCH" type="text" id="search-bar"/>
    <button type="submit"><i class="material-icons">search</i></button>
</form>

<h1>Films</h1>

<?php
try {
    $open_review_s_db = new PDO("sqlite:resources/star_wars.db");
    $open_review_s_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($e->getMessage());
}

try {
    $res = $open_review_s_db->query("SELECT filmID, film_title, film_release_date, image_url FROM film");
    while($row = $res->fetch(PDO::FETCH_ASSOC)) {
        echo $row['film_title'] . "<br />";
        echo "<a href='films.php?id=" . $row['filmID'] ."'><img height='300' src='" . $row['image_url'] . "' /></a><br />";
        echo $row['film_release_date'] . "<br /><br />";
    }
} catch (PDOException $e) {
    die($e->getMessage());
}
?>

</body>
</html>

