<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Star Wars Database - People</title>
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
    <img class="img-fluid proj-img-width-15 mx-auto d-block mt-4" src="img/star_wars_logo.png" id="main-logo" alt="Star Wars logo"/>

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

    <h1 class="text-white text-center proj-font-jedi"><?= $header; ?></h1>

    <?php
    if (!isset($_GET['id'])) {
        try {
            echo '<div class="row row-cols-auto proj-font-jedi">';
            $query = "SELECT peopleID, people_name, image_url FROM people";
            $result = $open_review_s_db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <div class='col text-center position-relative mb-4 grow proj-overflow bg-black mx-auto border border-4 border-warning rounded-5' style='height: 300px; width: 300px'>
                    <a class='glow stretched-link link-underline link-underline-opacity-0 fs-4' href='people.php?id={$row['peopleID']}'>
                        {$row['people_name']}
                    </a>
                    <br>";
                if ($row['image_url'] != null) {
                    $new_url = str_replace('/revision/latest', '', $row['image_url']);
                    echo "<img class='img-fluid' alt='{$row['people_name']}' src='{$new_url}'/>";
                }
                echo "</div>";
            }
            echo '</div>';
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    } else {
        try {
            echo "<div class='container-fluid clearfix p-4 proj-bg-deep-grey text-white rounded-5'>";

            // Get current people tuple
            $query = "SELECT * FROM people WHERE peopleID={$_GET['id']}";
            $result = $open_review_s_db->query($query);
            $currentPerson = $result->fetch(PDO::FETCH_ASSOC);

            // Right panel
            echo '<div class="float-sm-end bg-white p-2 rounded-3 text-white ms-3" style="width: 20rem;">';

            // Person image
            $new_url = str_replace('/revision/latest', '', $currentPerson['image_url']);
            echo "<img class='mx-auto d-block rounded-2' data-bs-toggle='modal' data-bs-target='#imgModal' alt='{$currentPerson['people_name']}' width='100%' src='{$new_url}'/>";
            echo "<br/>";


            //<!-- Modal -->
            echo "
            <div class='text-black modal fade' id='imgModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
              <div class='modal-dialog modal-xl'>
                <div class='modal-content'>
                  <div class='modal-header'>
                    <h1 class='modal-title fs-5 text-capitalize' id='exampleModalLabel'>{$currentPerson['people_name']}</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                  </div>
                  <div class='modal-body'>
                    <img class='img-fluid d-block mx-auto' src='{$new_url}'/>
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
            $query = "
                SELECT planet_name
                FROM planet
                WHERE planet.planetID={$currentPerson['people_homeworld_id']}";
            $result = $open_review_s_db->query($query);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            echo $row['planet_name'];
            echo "<br>";
            echo '</div>';

            // Species
            echo '<div class="text-capitalize mb-2 p-2 proj-bg-deep-grey rounded-2">';
            echo "<b>Species</b>";
            echo "<br>";
            $query = "
                SELECT species_name
                FROM species
                WHERE species.speciesID={$currentPerson['people_species_id']}";
            $result = $open_review_s_db->query($query);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            echo $row['species_name'];
            echo "<br>";
            echo '</div>';

        
            echo '</div>';

            // Description
            echo "<b class='proj-font-jedi h4'>Description</b>";
            echo '<p></p>';

            // Starships
            echo "<b class='proj-font-jedi h4'>Starships</b>";
            echo '<p>';
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
            echo '</p>';

            // Vehicles
            echo "<b class='proj-font-jedi h4'>vehicles</b>";
            echo '<p>';
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
            echo '</p>';

            // Appeared
            echo "<b class='proj-font-jedi h4'>Appeared in</b>";
            echo '<p>';
            $query = "
                SELECT film.film_title
                FROM film_people, film 
                WHERE {$currentPerson['peopleID']}=film_people.peopleID
                AND film.filmID=film_people.filmID";
            $result = $open_review_s_db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo $row['film_title'];
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
</html>