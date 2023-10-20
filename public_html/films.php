<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Star Wars Database - Films</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container-fluid px-5">
        <!-- Logo -->
        <img class="img-fluid proj-img-width-15 mx-auto d-block mt-4" src="img/star_wars_logo.png" id="main-logo" alt="Star Wars logo"/>

        <!-- Navigation -->
        <div class="container-fluid mb-2">
            <nav class="nav justify-content-center proj-font-jedi fs-2 topnav">
                <a class="nav-item nav-link" href="index.html">Home</a>
                <a class="nav-item nav-link" href="films.php">Films</a>
                <a class="nav-item nav-link" href="planets.php">Planets</a>
                <a class="nav-item nav-link" href="people.php">People</a>
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

        <?php
            // Database access
            try {
                $open_review_s_db = new PDO("sqlite:resources/star_wars.db");
                $open_review_s_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die($e->getMessage());
            }

            // Set header
            $header = 'Films';

            try {
                if (isset($_GET['id'])) {
                    $query = "SELECT film_title FROM film WHERE filmID=:id";
                    $stmt = $open_review_s_db->prepare($query);
                    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $header = $result['film_title'];
                }
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        ?>

        <h1 class="text-white text-center proj-font-jedi"><?= $header; ?></h1>

        <?php
            if (!isset($_GET['id'])) {
                try {
                    echo '<div class="row row-cols-1 row-cols-sm-3 row-cols-md-5">';
                    $query = "SELECT filmID, film_title, film_release_date, image_url FROM film";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='col proj-font-jedi text-center position-relative proj-overflow mb-5 films'>";
                        echo "<a class='stretched-link link-underline link-underline-opacity-0' href='films.php?id={$row['filmID']}'>";
                        echo $row['film_title'];
                        echo "</a>";
                        echo '<br>';
                        echo "<img class='img-fluid' alt='{$row['film_title']}' src='{$row['image_url']}'/>";
                        echo "</div>";
                    }
                    echo '</div>';
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            } else {
                try {
                    echo "<div class='container-fluid clearfix p-4 proj-bg-deep-grey text-white rounded-5'>";

                    // Get current film tuple
                    $query = "SELECT * FROM film WHERE filmID={$_GET['id']}";
                    $result = $open_review_s_db->query($query);
                    $currentFilm = $result->fetch(PDO::FETCH_ASSOC);

                    // Right panel
                    echo '<div class="float-sm-end bg-white p-2 rounded-3 text-white ms-3">';

                    // Poster
                    echo "<img class='mx-auto d-block' alt='poster' height='400' src='{$currentFilm['image_url']}'/>";
                    echo "<br/>";

                    // Director
                    echo '<div class="mb-2 p-2 proj-bg-deep-grey rounded-2">';
                    echo "<b>Directed by</b>";
                    echo "<br>";
                    echo $currentFilm['film_director'];
                    echo "<br>";
                    echo '</div>';

                    // Producers
                    echo '<div class="mb-2 p-2 proj-bg-deep-grey rounded-2">';
                    echo "<b>Produced by</b>";
                    $query = "
                        SELECT distinct producer.producer_name 
                        FROM producer, film_producer, film 
                        WHERE film.filmID={$_GET['id']}
                        and film.filmID = film_producer.filmID
                        and film_producer.producerID=producer.producerID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div>{$row['producer_name']}</div>";
                    }
                    echo '</div>';

                    // Release
                    echo '<div class="mb-2 p-2 proj-bg-deep-grey rounded-2">';
                    echo "<b>Released on</b>";
                    echo "<br>";
                    echo $currentFilm['film_release_date'];
                    echo '</div>';
                    echo '</div>';

                    // Opening crawl
                    echo "<b class='proj-font-jedi h4'>Description</b>";
                    echo '<p><i>"';
                    echo $currentFilm['film_opening_crawl'];
                    echo '"</i></p>';

                    // Characters in the film
                    echo "<b class='proj-font-jedi h4'>Characters</b>";
                    echo "<p>";
                    $query = "
                        SELECT distinct people.people_name
                        FROM people, film_people, film 
                        WHERE film.filmID={$_GET['id']}
                        and film.filmID = film_people.filmID
                        and film_people.peopleID=people.peopleID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo $row['people_name'];
                        echo " ● ";
                    }
                    echo "</p>";

                    // Planets
                    echo "<b class='proj-font-jedi h4'>Planets</b>";
                    echo "<p>";
                    $query = "
                        SELECT distinct planet.planet_name
                        FROM planet, film_planet, film 
                        WHERE film.filmID={$_GET['id']}
                        and film.filmID = film_planet.filmID
                        and film_planet.planetID=planet.planetID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo $row['planet_name'];
                        echo " ● ";
                    }
                    echo "</p>";

                    // Species
                    echo "<b class='proj-font-jedi h4'>Species</b>";
                    echo "<p>";
                    $query = "
                        SELECT distinct species.species_name
                        FROM species, film_species, film 
                        WHERE film.filmID={$_GET['id']}
                        and film.filmID = film_species.filmID
                        and film_species.speciesID=species.speciesID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo $row['species_name'];
                        echo " ● ";
                    }
                    echo "</p>";

                    // Starships
                    echo "<b class='proj-font-jedi h4'>Starships</b>";
                    echo "<p>";
                    $query = "
                        SELECT distinct starship.starship_name
                        FROM starship, film_starships, film 
                        WHERE film.filmID={$_GET['id']}
                        and film.filmID = film_starships.filmID
                        and film_starships.starshipID=starship.starshipID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo $row['starship_name'];
                        echo " ● ";
                    }
                    echo "</p>";

                    // Vehicles
                    echo "<b class='proj-font-jedi h4'>vehicles</b>";
                    echo "<p>";
                    $query = "
                        SELECT distinct vehicle.vehicle_name
                        FROM vehicle, film_vehicles, film 
                        WHERE film.filmID={$_GET['id']}
                        and film.filmID = film_vehicles.filmID
                        and film_vehicles.vehicleID=vehicle.vehicleID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo $row['vehicle_name'];
                        echo " ● ";
                    }
                    echo "</p>";
                    echo '</div>';
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            }
        ?>
    </div>
</body>
</html>