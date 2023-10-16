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
            $cat = 1;
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
        $query = "
            SELECT filmID, film_title, film_opening_crawl, film_director, film_release_date, image_url 
            FROM film
            WHERE filmID={$_GET['id']}";

        // Film info
        $result = $open_review_s_db->query($query);
        $film_info = $result->fetch(PDO::FETCH_ASSOC);
        echo "<div id='opening-crawl'>";
        echo "<b>Description</b>";
        echo "<br><br>";
        echo $film_info['film_opening_crawl'];
        echo "<br><br>";

        // Characters in the film
        echo "<b>Characters</b>";
        echo "<br><br>";
        $get_people = "
            SELECT distinct people.people_name 
            FROM people, film_people, film 
            WHERE film.filmID={$_GET['id']}
            and film.filmID = film_people.filmID
            and film_people.peopleID=people.peopleID";
        $people_result = $open_review_s_db->query($get_people);
        while ($film_people = $people_result->fetch(PDO::FETCH_ASSOC)) {
            echo $film_people['people_name'] . "<br>";
        }
        echo "</div>";

        // Right panel
        echo "<div id='movie-info'>";
        echo "<img alt='poster' height='400' src='" . $film_info['image_url'] . "'/><br />";
        echo "<b>Directed by</b><br>" . $film_info['film_director'] . "<br><br>";

        // Producers of the film
        echo "<b>Produced by</b><br>";
        $get_producers = "
            SELECT distinct producer.producer_name 
            FROM producer, film_producer, film 
            WHERE film.filmID={$_GET['id']}
            and film.filmID = film_producer.filmID
            and film_producer.producerID=producer.producerID";
        $producers_result = $open_review_s_db->query($get_producers);
        while ($film_producer = $producers_result->fetch(PDO::FETCH_ASSOC)) {
            echo $film_producer['producer_name'] . "<br>";
        }
        echo "<br>";
        echo "<b>Released on</b><br>" . $film_info['film_release_date'] . "<br>";
        echo "</div>";
        echo "</div>";
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
?>
</body>
</html>