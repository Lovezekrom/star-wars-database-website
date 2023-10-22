<!DOCTYPE html>
<html lang="en">
<head>
    <title>Star Wars: Characters</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Everything about Star Wars characters that you need to know. Find out more about your favourite characters.">
    <meta name="keywords" content="star wars, characters, name, height, mass, hair, skin, eye, birth, gender, world, species">
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
    <div class="container-fluid px-5">
        <?php
        // Database access
        try {
            $open_review_s_db = new PDO("sqlite:resources/star_wars.db");
            $open_review_s_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        // Set header
        $header = 'People';

        try {
            if (isset($_GET['id'])) {
                $query = "SELECT people_name FROM people WHERE peopleID=:id";
                $stmt = $open_review_s_db->prepare($query);
                $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $header = $result['people_name'];
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
                    $query = "SELECT peopleID, people_name, image_url FROM people";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $new_url = str_replace('/revision/latest', '', $row['image_url']);
                        echo "
                        <article class='col text-center position-relative m-4 grow overflow-hidden text-nowrap' style='width: 300px'>
                            <a class='link-light fs-4 fw-bold stretched-link link-underline link-underline-opacity-0' href='people.php?id={$row['peopleID']}'>
                                {$row['people_name']}
                            </a>
                            <div class='d-flex align-items-center justify-content-center bg-black border border-4 border-white rounded-3 overflow-hidden' style='height: 300px'>
                                <img class='img-fluid' alt='{$row['people_name']} image' src='{$new_url}'/>
                            </div>
                        </article>";
                    }
                    echo '</div>';
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            } else {
                echo "<div class='container-fluid clearfix p-4 proj-bg-deep-grey text-white rounded-5'>";
                try {
                    // Get current people tuple
                    $query = "SELECT * FROM people WHERE peopleID={$_GET['id']}";
                    $result = $open_review_s_db->query($query);
                    $currentPerson = $result->fetch(PDO::FETCH_ASSOC);
                    $new_url = str_replace('/revision/latest', '', $currentPerson['image_url']);
                } catch (PDOException $e) {
                    die($e->getMessage());
                }

                // Modal
                echo "
                <div class='text-black modal fade' id='imgPeopleModal' tabindex='-1' aria-labelledby='imgPeopleModalLabel' aria-hidden='true'>
                  <div class='modal-dialog modal-xl'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h1 class='modal-title fs-5 text-capitalize' id='imgPeopleModalLabel'>{$currentPerson['people_name']}</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                        <img class='w-100' alt='{$currentPerson['people_name']} image' src='{$new_url}'/>
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Close</button>
                      </div>
                    </div>
                  </div>
                </div>";

                // Right panel
                echo '<article class="float-sm-end bg-white p-2 rounded-3 text-white ms-3" style="width: 20rem;">';

                // Person image
                echo "<img class='img-fluid mx-auto d-block rounded-2' data-bs-toggle='modal' data-bs-target='#imgPeopleModal' alt='{$currentPerson['people_name']}' width='100%' src='{$new_url}' style='cursor: pointer'/>";
                echo "<br/>";

                // Name
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Name</b>";
                echo "<br>";
                echo $currentPerson['people_name'];
                echo "<br>";
                echo '</div>';

                // Height
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Height</b>";
                echo "<br>";
                echo $currentPerson['people_height'].' cm';
                echo "<br>";
                echo '</div>';

                // Mass
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Mass</b>";
                echo "<br>";
                echo $currentPerson['people_mass'].' kg';
                echo "<br>";
                echo '</div>';

                // Hair colour
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Hair Colour</b>";
                echo "<br>";
                echo $currentPerson['people_hair_color'];
                echo "<br>";
                echo '</div>';

                // Skin colour
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Skin Colour</b>";
                echo "<br>";
                echo $currentPerson['people_skin_color'];
                echo "<br>";
                echo '</div>';

                // Eye colour
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Eye Colour</b>";
                echo "<br>";
                echo $currentPerson['people_eye_color'];
                echo "<br>";
                echo '</div>';

                // Birth Year
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Birth Year</b>";
                echo "<br>";
                echo $currentPerson['people_birth_year'];
                echo "<br>";
                echo '</div>';

                // Gender
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Gender</b>";
                echo "<br>";
                echo $currentPerson['people_gender'];
                echo "<br>";
                echo '</div>';

                // Home World
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Home World</b>";
                echo "<br>";
                try {
                    $query = "
                    SELECT planet_name
                    FROM planet
                    WHERE planet.planetID={$currentPerson['people_homeworld_id']}";
                    $result = $open_review_s_db->query($query);
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    echo $row['planet_name'];
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo "<br>";
                echo '</div>';

                // Species
                echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
                echo "<b>Species</b>";
                echo "<br>";
                try {
                    $query = "
                    SELECT species_name
                    FROM species
                    WHERE species.speciesID={$currentPerson['people_species_id']}";
                    $result = $open_review_s_db->query($query);
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    echo $row['species_name'];
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo "<br>";
                echo '</div>';
                echo '</article>';

                // Starships
                echo "<article>";
                echo "<b class='fw-bold h2'>Starships</b>";
                echo '<p class="fs-4">';
                try {
                    $query = "
                    SELECT starship.starship_name
                    FROM people_starships, starship
                    WHERE {$currentPerson['peopleID']}=people_starships.peopleID
                    AND starship.starshipID=people_starships.starshipID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo $row['starship_name'];
                        echo " ● ";
                    }
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo '</p>';
                echo "</article>";

                // Vehicles
                echo "<article>";
                echo "<b class='fw-bold h2'>Vehicles</b>";
                echo '<p class="fs-4">';
                try {
                    $query = "
                    SELECT vehicle.vehicle_name
                    FROM people_vehicles, vehicle
                    WHERE {$currentPerson['peopleID']}=people_vehicles.peopleID
                    AND vehicle.vehicleID=people_vehicles.vehicleID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo $row['vehicle_name'];
                        echo " ● ";
                    }
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo '</p>';
                echo "</article>";

                // Appeared
                echo "<article>";
                echo "<b class='fw-bold h2'>Appeared in</b>";
                echo '<p class="fs-4">';
                try {
                    $query = "
                    SELECT film.filmID, film.film_title
                    FROM film_people, film 
                    WHERE {$currentPerson['peopleID']}=film_people.peopleID
                    AND film.filmID=film_people.filmID";
                    $result = $open_review_s_db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "
                        <a class='link-light link-underline link-underline-opacity-0 link-underline-opacity-100-hover' href='films.php?id={$row['filmID']}'>
                            {$row['film_title']}
                        </a>●";
                    }
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
                echo '</p>';
                echo "</article>";

                echo '</div>';
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