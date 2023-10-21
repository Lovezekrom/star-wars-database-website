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
        echo 'success';
    } catch (PDOException $e) {
        die($e->getMessage());
    }