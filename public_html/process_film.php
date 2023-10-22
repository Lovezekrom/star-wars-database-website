<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Star Wars Database - People</title>
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container-fluid px-5 pb-5">
    <!-- Logo -->
    <img class="img-fluid proj-img-width-15 mx-auto d-block mt-4" src="img/star_wars_logo.png" id="main-logo" alt="Star Wars logo"/>

    <!-- Navigation -->
    <div class="container-fluid mb-2">
        <nav class="nav justify-content-center fs-2 proj-nav proj-font-jedi">
            <a class="nav-item nav-link glow" href="index.html">Home</a>
            <a class="nav-item nav-link glow" href="films.php">Films</a>
            <a class="nav-item nav-link glow" href="planets.php">Planets</a>
            <a class="nav-item nav-link glow" href="people.php">People</a>
        </nav>
    </div>

    <!-- Search bar -->
    <div class="proj-bg-deep-grey rounded-pill">
        <form class="row g-2 justify-content-center">
            <div class="col ms-3">
                <label for="search-bar" class="visually-hidden">Search</label>
                <input class="form-control mb-2 ms-2 border-0 fs-4" id="search-bar" placeholder="Search..." type="text"/>
            </div>
            <div class="col-auto">
                <button class="btn btn-lg mt-1 me-2" type="submit">
                    <i class="material-icons text-white">search</i>
                </button>
            </div>
        </form>
    </div>
    <h1 class="text-white text-center proj-font-main fw-bold text-break mt-5">Success!</h1>
    <div class="container-fluid d-flex justify-content-center">
        <a class="btn btn-success btn-lg" href="films.php" role="button">Return</a>
    </div>
</div
</body>
</html>

<?php
    $inputTitle = $_POST['inputTitle'];
    $inputEpisodeNum = $_POST['inputEpisodeNum'];
    $inputOpening = $_POST['inputOpening'];
    $inputFirstName = $_POST['inputFirstName'];
    $inputLastName = $_POST['inputLastName'];
    $inputDate = $_POST['inputDate'];
    $inputImageLink = $_POST['inputImageLink'];

    // Database access
    try {
        $open_review_s_db = new PDO("sqlite:resources/star_wars.db");
        $open_review_s_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die($e->getMessage());
    }

    try {
        $query = "
            INSERT INTO film(film_title, film_episode_id, film_opening_crawl, film_director, film_release_date, image_url)
            VALUES(:film_title, :film_episode_id, :film_opening_crawl, :film_director, :film_release_date, :image_url)";
        $stmt = $open_review_s_db->prepare($query);
        $stmt->bindParam(':film_title', $inputTitle, PDO::PARAM_STR);
        $stmt->bindParam(':film_episode_id', $inputEpisodeNum, PDO::PARAM_INT);
        $stmt->bindParam(':film_opening_crawl', $inputOpening, PDO::PARAM_STR);
        $inputFullName = "{$inputFirstName} {$inputLastName}";
        $stmt->bindParam(':film_director', $inputFullName, PDO::PARAM_STR);
        $stmt->bindParam(':film_release_date', $inputDate, PDO::PARAM_STR);
        $stmt->bindParam(':image_url', $inputImageLink, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }