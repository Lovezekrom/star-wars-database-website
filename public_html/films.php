<!DOCTYPE html>
<html lang="en">
<head>
    <title>Star Wars: Films</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Everything about Star Wars movies that you need to know. Find out more about your favourite movies.">
    <meta name="keywords" content="star wars, film guide, jedi, starships, planets, director, producer, release">
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
                <a class="nav-item nav-link glow" href="index.html">Home</a>
                <a class="nav-item nav-link glow" href="films.php">Films</a>
                <a class="nav-item nav-link glow" href="planets.php">Planets</a>
                <a class="nav-item nav-link glow" href="people.php">People</a>
            </nav>
        </div>

        <!-- Search bar -->
        <div class="proj-bg-deep-grey rounded-pill proj-font-main">
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

        <h1 class="text-white text-center proj-font-main fw-bold text-break mt-5"><?= $header; ?></h1>

        <?php
            if (!isset($_GET['id'])) {
                try {
                    echo "
                    <div class='row row-cols-auto proj-font-main justify-content-center'>
                        <div class='col text-center position-relative m-4 grow overflow-hidden text-nowrap' style='width: 300px'>
                            <a class='link-light fs-4 fw-bold stretched-link link-underline link-underline-opacity-0' href='new_film.php'>
                                Add new film
                            </a>
                            <div class='text-danger font-monospace fs-1 d-flex align-items-center justify-content-center bg-black border border-4 border-danger rounded-3' style='aspect-ratio: 2/3;'>
                                +
                            </div>
                        </div>";
                    $query = "SELECT * FROM film ORDER BY film_episode_id";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "
                        <article class='col text-center position-relative m-4 grow overflow-hidden text-nowrap' style='width: 300px'>
                            <a class='link-light fs-4 fw-bold stretched-link link-underline link-underline-opacity-0' href='films.php?id={$row['filmID']}' >
                                {$row['film_title']}
                            </a>
                            <div class='d-flex align-items-center justify-content-center bg-black border border-4 border-white rounded-3 overflow-hidden' style='aspect-ratio: 2/3;'>
                                <img class='img-fluid' alt='{$row['film_title']} image' src='{$row['image_url']}'/>
                            </div>
                        </article>";
                    }
                    echo '</div>';
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            } else {
                echo "<div class='container-fluid clearfix p-4 proj-bg-deep-grey text-white rounded-5 text-break'>";
                try {
                    // Get current film tuple
                    $query = "SELECT * FROM film WHERE filmID={$_GET['id']}";
                    $result = $open_review_s_db->query($query);
                    $currentFilm = $result->fetch(PDO::FETCH_ASSOC);
                    $new_url = str_replace('/revision/latest', '', $currentFilm['image_url']);
                } catch (PDOException $e) {
                    die($e->getMessage());
                }

                //<!-- Modal -->
                echo "
                <div class='text-black modal fade' id='imgFilmModal' tabindex='-1' aria-labelledby='imgFilmModalLabel' aria-hidden='true'>
                  <div class='modal-dialog modal-xl'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h1 class='modal-title fs-5 text-capitalize' id='imgFilmModalLabel'>{$currentFilm['film_title']}</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                        <img class='w-100' alt='{$currentFilm['film_title']} image' src='$new_url'/>
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                ";

                // Right panel
                echo '<article class="float-sm-end bg-white p-2 rounded-3 text-white ms-3" style="width: 20rem;">';

                // Poster
                echo "<img class='img-fluid mx-auto d-block rounded-2' data-bs-toggle='modal' data-bs-target='#imgFilmModal' alt='poster' src='$new_url' style='cursor: pointer'/>";
                echo "<br>";

                // Title
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Title</b>";
                echo "<br>";
                echo $currentFilm['film_title'];
                echo '</div>';

                // Director
                echo '<div class="mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Directed by</b>";
                echo "<br>";
                echo $currentFilm['film_director'];
                echo '</div>';

                // Producers
                echo '<div class="mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Produced by</b>";
                try {
                    $query = "
                        SELECT distinct producer.producer_name 
                        FROM producer, film_producer, film 
                        WHERE film.filmID={$_GET['id']}
                        and film.filmID = film_producer.filmID
                        and film_producer.producerID=producer.producerID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<br>{$row['producer_name']}";
                    }
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo '</div>';

                // Release
                echo '<div class="mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Released on</b>";
                echo "<br>";
                echo $currentFilm['film_release_date'];
                echo '</div>';
                echo '</article>';

                // Opening crawl
                echo "<article>";
                echo "<b class='fw-bold h2'>Opening crawl</b>";
                echo "<p class='fs-4'><i>";
                echo $currentFilm['film_opening_crawl'];
                echo '</i></p>';
                echo "</article>";

                // Characters in the film
                echo "<article>";
                echo "<b class='fw-bold h2'>Characters</b>";
                echo "<p class='fs-4'>";
                try {
                    $query = "
                        SELECT distinct people.peopleID, people.people_name
                        FROM people, film_people, film 
                        WHERE film.filmID={$_GET['id']}
                        and film.filmID = film_people.filmID
                        and film_people.peopleID=people.peopleID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "
                        <a class='link-light link-underline link-underline-opacity-0 link-underline-opacity-100-hover' href='people.php?id={$row['peopleID']}'>
                            {$row['people_name']}
                        </a>●";
                    }
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo "</p>";
                echo "</article>";

                // Planets
                echo "<article>";
                echo "<b class='fw-bold h2'>Planets</b>";
                echo "<p class='fs-4'>";
                try {
                    $query = "
                        SELECT distinct planet.planetID, planet.planet_name
                        FROM planet, film_planet, film 
                        WHERE film.filmID={$_GET['id']}
                        and film.filmID = film_planet.filmID
                        and film_planet.planetID=planet.planetID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "
                        <a class='link-light link-underline link-underline-opacity-0 link-underline-opacity-100-hover' href='planets.php?id={$row['planetID']}'>
                            {$row['planet_name']}
                        </a>●";
                    }
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo "</p>";
                echo "</article>";

                // Species
                echo "<article>";
                echo "<b class='fw-bold h2'>Species</b>";
                echo "<p class='fs-4'>";

                try {
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
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo "</p>";
                echo "</article>";

                // Starships
                echo "<article>";
                echo "<b class='fw-bold h2'>Starships</b>";
                echo "<p class='fs-4'>";
                try {
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
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo "</p>";
                echo "</article>";

                // Vehicles
                echo "<article>";
                echo "<b class='fw-bold h2'>Vehicles</b>";
                echo "<p class='fs-4'>";
                try {
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
                    echo "</article>";

                    echo '</div>';
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            }
        ?>
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