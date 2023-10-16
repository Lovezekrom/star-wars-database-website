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
<img src="img/star_wars_logo.png" id="main-logo" alt="Star Wars logo"/>

<!-- Navigation -->
<nav class="nav-bar">
    <a href="index.html">Home</a>
    <a href="films.php">Films</a>
    <a href="planets.php">Planets</a>
    <a href="people.php">People</a>
</nav>

<hr>

<!-- Search -->
<form class="search-bar">
    <input placeholder="SEARCH" type="text"/>
    <button type="submit"><i class="material-icons">search</i></button>
</form>

<h1>
    <?php
    try {
        $open_review_s_db = new PDO("sqlite:resources/star_wars.db");
        $open_review_s_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!isset($_GET['id'])) {
            echo 'Films';
        } else {
            $query = "SELECT film_title FROM film WHERE filmID=:id";
            $stmt = $open_review_s_db->prepare($query);
            $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            echo $result['film_title'];
        }

    } catch (PDOException $e) {
        die($e->getMessage());
    }
    ?>
</h1>

    <?php

    try {
        $open_review_s_db = new PDO("sqlite:resources/star_wars.db");
        $open_review_s_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die($e->getMessage());
    }

    if (!isset($_GET['id'])) {
        try {
            echo '<div class="grid-container">';
            $res = $open_review_s_db->query("SELECT filmID, film_title, film_release_date, image_url FROM film");
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                echo "<div>";
                echo $row['film_title'] . "<br />";
                echo "<a href='films.php?id=" . $row['filmID'] . "'><img height='400' src='" . $row['image_url'] . "'/></a><br />";
                echo $row['film_release_date'] . "<br/><br />";
                echo "</div>";
            }
            echo "</div>";
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    } else {
        try {
            echo '<div class="grid-container-2">';
            $query = "SELECT filmID, film_title, film_opening_crawl, film_director, film_release_date, image_url FROM film WHERE filmID=".$_GET['id'];
            $res = $open_review_s_db->query($query);
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                echo "<div id='opening-crawl'>";
                echo $row['film_opening_crawl'];
                echo "</div>";

                echo "<div id='movie-info'>";
                echo "<img height='400' src='" . $row['image_url'] . "'/><br />";
                echo "Directed by " . $row['film_director'] . "<br>";
                echo "Released on " . $row['film_release_date'] . "<br>";
                echo "</div>";
            }
            echo "</div>";
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    ?>

</body>
</html>

