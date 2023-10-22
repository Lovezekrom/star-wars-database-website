<!DOCTYPE html>
<html lang="en">
<head>
    <title>Star Wars: Process Film</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="author" content="Star Wars">
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header>
    <div class="container-fluid px-5">
        <!-- Logo -->
        <a href="index.html"><img class="img-fluid proj-img-width-15 mx-auto d-block mt-4" src="img/star_wars_logo.png" id="main-logo" alt="Star Wars logo"/></a>

        <!-- Navigation -->
        <div class="container-fluid mb-2">
            <nav class="nav justify-content-center fs-2 proj-nav proj-font-jedi">
                <a class="nav-item nav-link proj-glow" href="index.html">Home</a>
                <a class="nav-item nav-link proj-glow" href="films.php">Films</a>
                <a class="nav-item nav-link proj-glow" href="planets.php">Planets</a>
                <a class="nav-item nav-link proj-glow" href="people.php">People</a>
            </nav>
        </div>
    </div>
</header>
<main>
    <div class="container-fluid px-5 pb-5">
        <?php
            // Database access
            try {
                $open_review_s_db = new PDO("sqlite:resources/star_wars.db");
                $open_review_s_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die($e->getMessage());
            }

            if (!isset($_POST['inputTitle'], $_POST['inputOpening'], $_POST['inputFirstName'], $_POST['inputLastName'], $_POST['inputDate'], $_POST['inputImageLink'])) {
                echo '<h1 class="text-white text-center proj-font-main fw-bold text-break mt-5">Something went wrong</h1>';
            } else {
                $inputTitle = $_POST['inputTitle'];
                $inputEpisodeNum = $_POST['inputEpisodeNum'];
                $inputOpening = $_POST['inputOpening'];
                $inputFirstName = $_POST['inputFirstName'];
                $inputLastName = $_POST['inputLastName'];
                $inputDate = $_POST['inputDate'];
                $inputImageLink = $_POST['inputImageLink'];

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
                    echo '<h1 class="text-white text-center proj-font-main fw-bold text-break mt-5">Success!</h1>';
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            }
        ?>
        <div class="container-fluid d-flex justify-content-center">
            <a class="btn btn-success btn-lg" href="films.php" role="button">Return</a>
        </div>
    </div>
</main>
<footer>
    <div class="footer-column">
        <a href="index.html"><img src="img/star_wars_logo.png" alt="Star Wars logo" style="width: 10vw; height: 5vw;
        justify-content: left; top: 50%; bottom: 50%"></a>
    </div>

    <div class="footer-column">
        Main Pages<br><br>
        <a href="index.html">Homepage</a> <br>
        <a href="films.php">Films</a> <br>
        <a href="planets.php">Planets</a> <br>
        <a href="people.php">People</a> <br>
    </div>

    <div class="footer-column">
        Information<br><br>
        <a href="mandalorian_info.html">The Mandalorian</a> <br>
        <a href="darth_vader_info.html">Darth Vader</a> <br>
        <a href="lightsaber_info.html">Lightsabers</a> <br>
        <a href="films.php">More...</a> <br>

    </div>

    <div class="footer-column">
        Email: Fakestarwars@gmail.com
        <br>
        Ph: +(00) 123 456 789
        <br><br>
        Hutt No.123 Lars farm, Tatooine, <br>Tri-planetary system J11
    </div>
</footer>
</body>
</html>