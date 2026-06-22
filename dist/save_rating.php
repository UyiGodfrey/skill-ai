<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $student_id = $_POST['student_id'];

    $rating = $_POST['rating'];

    $review = $_POST['review'];


    $file = '../logs/reviews.json';


    // Read Existing Reviews
    $reviews = [];

    if(file_exists($file)){

        $json = file_get_contents($file);

        $reviews = json_decode($json, true);

        if(!is_array($reviews)){
            $reviews = [];
        }
    }


    // Add New Review
    $reviews[] = [

        'student_id' => $student_id,

        'rating' => $rating,

        'review' => $review,

        'date' => date('Y-m-d H:i:s')
    ];


    // Save Back To File
    file_put_contents(
        $file,
        json_encode($reviews, JSON_PRETTY_PRINT)
    );


    header("Location: rating.php?success=1");

    exit();
}
?>