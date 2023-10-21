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

    <h1 class="text-white text-center proj-font-main fw-bold text-break mt-5">Submit New Film</h1>

    <form class="row gx-5" action="process_film.php" method="post">
        <div class="col-md-4 col-xs-12 mb-3">
            <label for="inputTitle" class="form-label text-white">Film title</label>
            <input type="text" class="form-control" id="inputTitle" name="inputTitle" placeholder="Enter film's title" required>
        </div>

        <div class="col-md-4 col-xs-12 mb-3">
            <label for="inputFirstName" class="form-label text-white">Director first name</label>
            <input type="text" class="form-control" id="inputFirstName" name="inputFirstName" placeholder="Enter director's first name" required>
        </div>

        <div class="col-md-4 col-xs-12 mb-3">
            <label for="inputLastName" class="form-label text-white">Director last name</label>
            <input type="text" class="form-control" id="inputLastName" name="inputLastName" placeholder="Enter director's last name" required>
        </div>

        <div class="col-md-12 col-xs-12 mb-3">
            <label for="inputOpening" class="form-label text-white">Opening crawl</label>
            <textarea class="form-control" id="inputOpening" name="inputOpening" rows="5" placeholder="Enter the opening crawl" required></textarea>
        </div>

        <div class="col-md-4 col-xs-12 mb-3">
            <label for="inputEpisodeNum" class="form-label text-white">Episode number</label>
            <input type="number" class="form-control" id="inputEpisodeNum" name="inputEpisodeNum" placeholder="Enter the episode number" required>
        </div>

        <div class="col-md-4 col-xs-12 mb-3">
            <label for="inputDate" class="form-label text-white">Release date</label>
            <input type="date" class="form-control" id="inputDate" name="inputDate" required>
        </div>

        <div class="col-md-4 col-xs-12 mb-3">
            <label for="inputImageLink" class="form-label text-white">Film poster URL</label>
            <input type="url" class="form-control" id="inputImageLink" name="inputImageLink" placeholder="Enter poster URL" required>
        </div>

        <div class="col-12 col-md-12 mt-3">
            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
        </div>
    </form>
</div
</body>
</html>