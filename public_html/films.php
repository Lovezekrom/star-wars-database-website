<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Star Wars Database - Films</title>
    <link rel="stylesheet" href="css/matty.css" />
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container-fluid px-5 pb-5">
    <!-- Logo -->
    <a href="index.html"><img class="img-fluid proj-img-width-15 mx-auto d-block mt-4" src="img/star_wars_logo.png" id="main-logo" alt="Star Wars logo"/></a>

    <!-- Navigation -->
    <div class="container-fluid mb-2">
        <nav class="nav justify-content-center proj-font-jedi fs-2 topnav">
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

    <h1 class="text-white text-center proj-font-jedi text-break"><?= $header; ?></h1>

    <?php
        if (!isset($_GET['id'])) {
            try {
                echo "
                <div class='row row-cols-auto proj-font-jedi justify-content-center'>
                    <div class='col text-center position-relative m-4 grow proj-overflow' style='width: 300px'>
                        <a class='text-white glow stretched-link link-underline link-underline-opacity-0' href='new_film.php'>
                            Add new film
                        </a>
                        <div class='text-white font-monospace fs-1 d-flex align-items-center justify-content-center bg-black border border-4 border-light-subtle rounded-3' style='aspect-ratio: 2/3;'>
                            +
                        </div>
                    </div>";
                $query = "SELECT * FROM film ORDER BY film_episode_id";
                $result = $open_review_s_db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "
                    <div class='col text-center position-relative m-4 grow proj-overflow' style='width: 300px'>
                        <a class='glow stretched-link link-underline link-underline-opacity-0' href='films.php?id={$row['filmID']}' >
                            {$row['film_title']}
                        </a>
                        <div class='d-flex align-items-center justify-content-center bg-black border border-4 border-warning rounded-3 overflow-hidden' style='aspect-ratio: 2/3;'>
                            <img class='img-fluid' alt='{$row['film_title']} image' src='{$row['image_url']}'/>
                        </div>
                    </div>";
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

                // Director
                echo '<div class="mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Directed by</b>";
                echo "<br>";
                echo $currentFilm['film_director'];
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
                    echo "<br>{$row['producer_name']}";
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
                echo "<b class='proj-font-jedi h4'>opening crawl</b>";
                echo '<p><i>';
                echo $currentFilm['film_opening_crawl'];
                echo '</i></p>';

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
<footer>
    <div class="footercolumn">
        <a href="index.html"><img src="img/star_wars_logo.png" alt="Star Wars logo" style="width: 10vw; height: 5vw;
        justify-content: left; top: 50%; bottom: 50%"></a>
    </div>

    <div class="footercolumn">
        Main Pages<br><br>
        <a href="index.html">Homepage</a> <br>
        <a href="films.php">Films</a> <br>
        <a href="planets.php">Planets</a> <br>
        <a href="people.php">People</a> <br>
    </div>

    <div class="footercolumn">
        Information<br><br>
        <a href="">The Mandalorian</a> <br>
        <a href="">Darth Vader</a> <br>
        <a href="">Lightsabers</a> <br>
        <a href="films.php">More...</a> <br>

    </div>

    <div class="footercolumn">
        Email: Fakestarwars@gmail.com
        <br>
        Ph: +(00) 123 456 789
        <br><br>
        Hutt No.123 Lars farm, Tatooine, <br>Tri-planetary system J11
    </div>
</footer>
</html>