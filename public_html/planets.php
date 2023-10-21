<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Star Wars Database - Planets</title>
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/matty.css" />
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
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
    $header = 'Planets';

    try {
        if (isset($_GET['id'])) {
            $query = "SELECT planet_name FROM planet WHERE planetID=:id";
            $stmt = $open_review_s_db->prepare($query);
            $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $header = $result['planet_name'];
        }
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    ?>

    <h1 class="text-white text-center proj-font-main fw-bold text-break mt-5"><?= $header; ?></h1>

    <?php
    if (!isset($_GET['id'])) {
        try {
            echo "<div class='row row-cols-auto proj-font-main justify-content-center'>";
            $query = "SELECT planetID, planet_name, image_url FROM planet";
            $result = $open_review_s_db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $new_url = str_replace('/revision/latest', '', $row['image_url']);
                echo "
                <div class='col text-center position-relative m-4 grow overflow-hidden text-nowrap' style='width: 300px'>
                    <a class='link-light fs-4 fw-bold stretched-link link-underline link-underline-opacity-0' href='planets.php?id={$row['planetID']}'>
                        {$row['planet_name']}
                    </a>
                    <div class='d-flex align-items-center justify-content-center bg-black border border-4 border-white rounded-3 overflow-hidden' style='height: 300px'>
                        <img class='img-fluid' alt='{$row['planet_name']} image' src='{$new_url}'/>
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

            // Get current planet tuple
            $query = "SELECT * FROM planet WHERE planetID={$_GET['id']}";
            $result = $open_review_s_db->query($query);
            $currentPlanet = $result->fetch(PDO::FETCH_ASSOC);

            // Right panel
            echo '<div class="float-sm-end bg-white p-2 rounded-3 text-white ms-3" style="width: 20rem;">';

            // Planet image
            $new_url = str_replace('/revision/latest', '', $currentPlanet['image_url']);
            echo "<img class='img-fluid mx-auto d-block rounded-2' data-bs-toggle='modal' data-bs-target='#imgPlanetModal' alt='{$currentPlanet['planet_name']}' width='100%' src='{$new_url}' style='cursor: pointer'/>";
            echo "<br/>";

            //<!-- Modal -->
            echo "
            <div class='text-black modal fade' id='imgPlanetModal' tabindex='-1' aria-labelledby='imgPlanetModalLabel' aria-hidden='true'>
              <div class='modal-dialog modal-xl'>
                <div class='modal-content'>
                  <div class='modal-header'>
                    <h1 class='modal-title fs-5 text-capitalize' id='imgPlanetModalLabel'>{$currentPlanet['planet_name']}</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                  </div>
                  <div class='modal-body'>
                    <img class='w-100' alt='{$currentPlanet['planet_name']} image' src='{$new_url}'/>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Close</button>
                  </div>
                </div>
              </div>
            </div>
            ";

            // Name
            echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
            echo "<b>Planet</b>";
            echo "<br>";
            echo $currentPlanet['planet_name'];
            echo "<br>";
            echo '</div>';

            // Rotation period
            echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
            echo "<b>Rotation Period</b>";
            echo "<br>";
            echo $currentPlanet['planet_rotation_period']." hours";
            echo "<br>";
            echo '</div>';

            // Orbital period
            echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
            echo "<b>Orbital Period</b>";
            echo "<br>";
            echo $currentPlanet['planet_orbital_period']." days";
            echo "<br>";
            echo '</div>';

            // Diameter
            echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
            echo "<b>Diameter</b>";
            echo "<br>";
            echo $currentPlanet['planet_diameter'].' km';
            echo "<br>";
            echo '</div>';

            // Gravity
            echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
            echo "<b>Gravity</b>";
            echo "<br>";
            echo $currentPlanet['planet_gravity'];
            echo "<br>";
            echo '</div>';

            // Surface water
            echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
            echo "<b>Surface water</b>";
            echo "<br>";
            echo $currentPlanet['planet_surface_water'].' %';
            echo "<br>";
            echo '</div>';

            // Population
            echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
            echo "<b>Population</b>";
            echo "<br>";
            $population = $currentPlanet['planet_population'];
            if (is_numeric($population)) {
                if ($population >= 0) {
                    echo number_format($population);
                } else {
                    echo 'Unknown';
                }
            } else {
                echo 'Unknown';
            }
            echo "<br>";
            echo '</div>';
            echo '</div>';

            // Appeared in
            echo "<b class='fw-bold h2'>Appeared in</b>";
            echo '<p class="fs-4">';
            $query = "
                SELECT film.filmID, film.film_title, film.image_url
                FROM film_planet, film 
                WHERE {$currentPlanet['planetID']}=film_planet.planetID
                AND film.filmID=film_planet.filmID";
            $result = $open_review_s_db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "
                    <a class='link-light link-underline link-underline-opacity-0 link-underline-opacity-100-hover' href='films.php?id={$row['filmID']}'>
                        {$row['film_title']}
                    </a>●";
            }
            echo '</p>';

            // Climate
            echo "<b class='fw-bold h2'>Climate</b>";
            echo '<p class="text-capitalize fs-4">';
            $query = "
                SELECT climate.planet_climate
                FROM climate, planet_climate
                WHERE {$currentPlanet['planetID']}=planet_climate.planetID
                AND planet_climate.climateID=climate.planetclimateID";
            $result = $open_review_s_db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo $row['planet_climate'];
                echo " ● ";
            }
            echo '</p>';

            // Terrain
            echo "<b class='fw-bold h2'>Terrain</b>";
            echo '<p class="text-capitalize fs-4">';
            $query = "
                SELECT terrain.planet_terrain
                FROM terrain, planet_terrain
                WHERE {$currentPlanet['planetID']}=planet_terrain.planetID
                AND planet_terrain.terrainID=terrain.planetterrainID";
            $result = $open_review_s_db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo $row['planet_terrain'];
                echo " ● ";
            }
            echo '</p>';

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
        <a href="mandalorian_info.html">The Mandalorian</a> <br>
        <a href="darthvader_info.html">Darth Vader</a> <br>
        <a href="lightsaber_info.html">Lightsabers</a> <br>
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
