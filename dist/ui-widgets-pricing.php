<?php
require 'sidebar_student.php';
require 'config.inc.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| AI ROOM RECOMMENDATION
|--------------------------------------------------------------------------
*/

$recommended_room = null;

if(isset($_POST['get_recommendation'])){

    $cgpa       = $_POST['cgpa'];
    $health     = $_POST['health'];
    $cleaning   = $_POST['cleaning'];
    $temperature = $_POST['temperature'];

    // Convert CGPA to level
    if($cgpa >= 4.5){

        $cgpa_level = "excellent";

    }elseif($cgpa >= 3.0){

        $cgpa_level = "average";

    }else{

        $cgpa_level = "low";
    }

    // Floor preference
    if(in_array($health, ['wheelchair','visual'])){

        $floor_preference = "ground";

    }else{

        $floor_preference = "first";
    }

    // AI request data
    $data = [

        "temperature" => $temperature,
        "cleaning" => $cleaning,
        "disability" => $health,
        "cgpa_level" => $cgpa_level,
        "floor_preference" => $floor_preference
    ];

    // YOUR RENDER URL
    $api_url =
    "https://hostel-ai-zpbe.onrender.com/recommend";

    $ch = curl_init($api_url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    $result = json_decode($response, true);

    if(isset($result['recommended_room'])){

        $recommended_room =
            $result['recommended_room'];
    }
}

/*
|--------------------------------------------------------------------------
| APPLY ROOM DIRECTLY
|--------------------------------------------------------------------------
*/

if(isset($_POST['apply_room'])){

    $student_id =
        $_SESSION['roll'];

    $room_id =
        $_POST['room_id'];

    $hostel_id =
        $_POST['hostel_id'];

    $cgpa =
        $_POST['cgpa'];

    $health =
        $_POST['health'];

    $cleaning =
        $_POST['cleaning'];

    // Check active application
    $check = mysqli_query($conn, "
        SELECT *
        FROM application
        WHERE Student_id = '$student_id'
        AND Application_status IN (0,1)
    ");

    if(mysqli_num_rows($check) > 0){

        echo "
        <script>
        alert('You already have an active application');
        </script>
        ";

    }else{

        $insert = "
        INSERT INTO application
        (
            Student_id,
            Hostel_id,
            Room_id,
            Application_status,
            cgpa,
            health,
            cleaning,
            created_at
        )
        VALUES
        (
            '$student_id',
            '$hostel_id',
            '$room_id',
            1,
            '$cgpa',
            '$health',
            '$cleaning',
            NOW()
        )
        ";

        if(mysqli_query($conn, $insert)){

            echo "
            <script>
            alert('Application Submitted Successfully');
            window.location.href='ui-widgets-todolist.php';
            </script>
            ";

        }else{

            echo "
            <script>
            alert('Error Submitting Application');
            </script>
            ";
        }
    }
}

/*
|--------------------------------------------------------------------------
| AVAILABLE ROOMS
|--------------------------------------------------------------------------
*/

$query =
"SELECT * FROM room WHERE Allocated = 0";

$result =
mysqli_query($conn, $query);
?>

<div id="main">

    <header class="mb-3">

        <a href="#"
           class="burger-btn d-block d-xl-none">

            <i class="bi bi-justify fs-3"></i>

        </a>

    </header>

    <div class="page-heading">

        <div class="page-title">

            <div class="row">

                <div class="col-12 col-md-6">

                    <h3>
                        AI Hostel Recommendation
                    </h3>

                    <p class="text-muted">
                        Get intelligent hostel recommendations
                    </p>

                </div>

            </div>

        </div>

        <!-- AI FORM -->

        <div class="card mb-4">

            <div class="card-header">

                <h4>
                    Get AI Recommendation
                </h4>

            </div>

            <div class="card-body">

                <form method="POST">

                    <div class="row">

                        <!-- CGPA -->

                        <div class="col-md-3">

                            <label class="form-label">
                                CGPA
                            </label>

                            <input
                                type="text"
                                name="cgpa"
                                class="form-control"
                                required>

                        </div>

                        <!-- HEALTH -->

                        <div class="col-md-3">

                            <label class="form-label">
                                Disability
                            </label>

                            <select
                                name="health"
                                class="form-control">

                                <option value="none">
                                    None
                                </option>

                                <option value="wheelchair">
                                    Wheelchair
                                </option>

                                <option value="visual">
                                    Visual Impairment
                                </option>

                            </select>

                        </div>

                        <!-- CLEANING -->

                        <div class="col-md-3">

                            <label class="form-label">
                                Cleaning Habit
                            </label>

                            <select
                                name="cleaning"
                                class="form-control">

                                <option value="neat">
                                    Neat
                                </option>

                                <option value="average">
                                    Average
                                </option>

                                <option value="messy">
                                    Messy
                                </option>

                            </select>

                        </div>

                        <!-- TEMPERATURE -->

                        <div class="col-md-3">

                            <label class="form-label">
                                Temperature Preference
                            </label>

                            <select
                                name="temperature"
                                class="form-control">

                                <option value="cold">
                                    Cold
                                </option>

                                <option value="warm">
                                    Warm
                                </option>

                                <option value="moderate">
                                    Moderate
                                </option>

                            </select>

                        </div>

                    </div>

                    <button
                        type="submit"
                        name="get_recommendation"
                        class="btn btn-primary mt-3">

                        Recommend Room

                    </button>

                </form>

            </div>

        </div>

        <!-- SHOW RECOMMENDATION -->

        <?php if(!empty($recommended_room)){ ?>

        <div class="alert alert-success">

            AI Recommended Room:

            <strong>
                <?php echo $recommended_room; ?>
            </strong>

        </div>

        <?php } ?>

        <!-- ROOMS -->

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="row">

                    <?php
                    if(mysqli_num_rows($result) > 0){

                        while(
                            $row = mysqli_fetch_assoc($result)
                        ){

                            $aiRoomNumber = '';

                            if(!empty($recommended_room)){

                                $aiRoomNumber =
                                str_replace(
                                    'Room',
                                    '',
                                    $recommended_room
                                );

                                $aiRoomNumber =
                                trim($aiRoomNumber);
                            }

                            $dbRoomNumber =
                            trim(
                                (string)$row['Room_No']
                            );

                            $isRecommended =
                            (
                                $aiRoomNumber
                                ===
                                $dbRoomNumber
                            );
                    ?>

                    <div class="col-md-4">

                        <div class="card <?php
                        echo $isRecommended
                        ? 'border border-success border-4'
                        : '';
                        ?>">

                            <div class="card-header text-center">

                                <h4>

                                    Room
                                    <?php
                                    echo $row['Room_No'];
                                    ?>

                                </h4>

                                <?php
                                if($isRecommended){
                                ?>

                                <span class="badge bg-success">

                                    AI Recommended

                                </span>

                                <?php } ?>

                            </div>

                            <div class="card-body">

                                <ul class="list-group">

                                    <li class="list-group-item">

                                        Type:
                                        <?php
                                        echo ucfirst(
                                            $row['type']
                                        );
                                        ?>

                                    </li>

                                    <li class="list-group-item">

                                        Temperature:
                                        <?php
                                        echo $row['temp'];
                                        ?>

                                    </li>

                                    <li class="list-group-item">

                                        Floor:
                                        <?php
                                        echo $row['floor'];
                                        ?>

                                    </li>

                                    <li class="list-group-item">

                                        Price:
                                        ₦<?php
                                        echo number_format(
                                            $row['price']
                                        );
                                        ?>

                                    </li>

                                </ul>

                            </div>

                            <!-- APPLY FORM -->

                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="room_id"
                                    value="<?php
                                    echo $row['Room_id'];
                                    ?>">

                                <input
                                    type="hidden"
                                    name="hostel_id"
                                    value="<?php
                                    echo $row['Hostel_id'];
                                    ?>">

                                <input
                                    type="hidden"
                                    name="cgpa"
                                    value="<?php
                                    echo $_POST['cgpa']
                                    ?? '';
                                    ?>">

                                <input
                                    type="hidden"
                                    name="health"
                                    value="<?php
                                    echo $_POST['health']
                                    ?? '';
                                    ?>">

                                <input
                                    type="hidden"
                                    name="cleaning"
                                    value="<?php
                                    echo $_POST['cleaning']
                                    ?? '';
                                    ?>">

                                <div class="card-footer">

                                    <button
                                        type="submit"
                                        name="apply_room"
                                        class="btn btn-block <?php
                                        echo $isRecommended
                                        ? 'btn-success'
                                        : 'btn-primary';
                                        ?>">

                                        <?php
                                        if($isRecommended){

                                            echo
                                            'Apply Recommended Room';

                                        }else{

                                            echo 'Apply';
                                        }
                                        ?>

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                    <?php
                        }
                    }else{

                        echo "
                        <h4 class='text-center'>
                        No Rooms Available
                        </h4>
                        ";
                    }
                    ?>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>