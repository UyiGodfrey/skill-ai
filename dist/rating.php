<?php

require 'sidebar_student.php';

$student_id = $_SESSION['roll'];

?>

<div id="main">

    <div class="page-heading">

        <h3>Rate Hostel System</h3>

        <section class="section">

            <div class="card">

                <div class="card-body">

<?php

if(isset($_GET['success'])){

?>

<div class="alert alert-success">

    Review submitted successfully

</div>

<?php } ?>

                    <form method="POST"
                          action="save_rating.php">

                        <input type="hidden"
                               name="student_id"
                               value="<?php echo $student_id; ?>">


                        <div class="mb-3">

                            <label class="form-label">
                                Rating
                            </label>

                            <select name="rating"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Select Rating
                                </option>

                                <option value="5">
                                    ⭐⭐⭐⭐⭐ Excellent
                                </option>

                                <option value="4">
                                    ⭐⭐⭐⭐ Very Good
                                </option>

                                <option value="3">
                                    ⭐⭐⭐ Good
                                </option>

                                <option value="2">
                                    ⭐⭐ Fair
                                </option>

                                <option value="1">
                                    ⭐ Poor
                                </option>

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Review
                            </label>

                            <textarea
                                name="review"
                                class="form-control"
                                rows="5"
                                required></textarea>

                        </div>


                        <button type="submit"
                                class="btn btn-primary">

                            Submit Review

                        </button>

                    </form>

                </div>

            </div>

        </section>

    </div>

</div>