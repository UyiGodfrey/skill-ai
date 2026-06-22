<?php

require 'sidebar.php';

$file = '../logs/reviews.json';

$reviews = [];

if(file_exists($file)){

    $json = file_get_contents($file);

    $reviews = json_decode($json, true);

    if(!is_array($reviews)){
        $reviews = [];
    }
}

?>

<div id="main">

    <div class="page-heading">

        <h3>Student Reviews</h3>

        <section class="section">

            <div class="card">

                <div class="card-body">

<?php

if(empty($reviews)){

?>

<div class="alert alert-warning">

    No reviews found

</div>

<?php

}else{

?>

                    <table class="table table-striped">

                        <thead>

                            <tr>

                                <th>Student ID</th>

                                <th>Rating</th>

                                <th>Review</th>

                                <th>Date</th>

                            </tr>

                        </thead>

                        <tbody>

<?php

foreach(array_reverse($reviews) as $review){

?>

<tr>

    <td>
        <?php echo $review['student_id']; ?>
    </td>

    <td>

        <?php

        for($i = 1; $i <= $review['rating']; $i++){

            echo "⭐";
        }

        ?>

    </td>

    <td>
        <?php echo htmlspecialchars($review['review']); ?>
    </td>

    <td>
        <?php echo $review['date']; ?>
    </td>

</tr>

<?php } ?>

                        </tbody>

                    </table>

<?php } ?>

                </div>

            </div>

        </section>

    </div>

</div>