<?php
require 'sidebar.php';
?>

<?php
require 'config.inc.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$hostels = mysqli_query($conn, "SELECT * FROM hostel");

// ───────────────────────────────────────────────
// DELETE
// ───────────────────────────────────────────────
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $del = mysqli_query($conn, "DELETE FROM room WHERE Room_id = $delete_id");
    if ($del) {
        echo "<script>alert('Room deleted successfully.'); window.location='add_room.php';</script>";
    } else {
        echo "<script>alert('Error deleting room: " . mysqli_error($conn) . "');</script>";
    }
}

// ───────────────────────────────────────────────
// EDIT – load existing data into form
// ───────────────────────────────────────────────
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id   = intval($_GET['edit']);
    $edit_res  = mysqli_query($conn, "SELECT * FROM room WHERE Room_id = $edit_id");
    $edit_data = mysqli_fetch_assoc($edit_res);
}

// ───────────────────────────────────────────────
// ADD / UPDATE on form submit
// ───────────────────────────────────────────────
if (isset($_POST['submit'])) {

    $room_no   = mysqli_real_escape_string($conn, $_POST['room_no']);
    $temp      = mysqli_real_escape_string($conn, $_POST['temp']);
    $floor     = intval($_POST['floor']);
    $price     = mysqli_real_escape_string($conn, $_POST['price']);
    $type      = mysqli_real_escape_string($conn, $_POST['type']);
    $hostel_id = intval($_POST['hostel_id']);
    $edit_id   = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;

    // ── Duplicate check (exclude current record when editing) ──
    $dup_sql = "SELECT * FROM room
                WHERE Room_No = '$room_no'
                AND   Hostel_id = '$hostel_id'";
    if ($edit_id > 0) {
        $dup_sql .= " AND Room_id != $edit_id";
    }
    $dup_result = mysqli_query($conn, $dup_sql);

    if (mysqli_num_rows($dup_result) > 0) {
        echo "<script>alert('Room number already exists in this hostel. Please use a different room number.');</script>";

    } elseif ($edit_id > 0) {
        // ── UPDATE ──
        $upd = "UPDATE room
                SET Room_No    = '$room_no',
                    Hostel_id  = '$hostel_id',
                    temp       = '$temp',
                    floor      = $floor,
                    price      = '$price',
                    type       = '$type'
                WHERE Room_id  = $edit_id";
        if (mysqli_query($conn, $upd)) {
            echo "<script>alert('Room updated successfully.'); window.location='add_room.php';</script>";
        } else {
            echo "<script>alert('Error updating room: " . mysqli_error($conn) . "');</script>";
        }

    } else {
        // ── INSERT ──
        $ins = "INSERT INTO room (Hostel_id, Room_No, Allocated, type, temp, price, floor)
                VALUES ('$hostel_id', '$room_no', 0, '$type', '$temp', '$price', $floor)";
        if (mysqli_query($conn, $ins)) {
            echo "<script>alert('Room added successfully.'); window.location='add_room.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3><?= $edit_data ? 'Edit Building' : 'Add a Building' ?></h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Building</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">

                                        <form method="post" class="form form-horizontal">
                                            <!-- Hidden field carries Room_id when editing -->
                                            <?php if ($edit_data): ?>
                                                <input type="hidden" name="edit_id" value="<?= $edit_data['Room_id'] ?>">
                                            <?php endif; ?>

                                            <div class="form-body">
                                                <div class="row">

                                                    <!-- Building No -->
                                                    <div class="col-md-4">
                                                        <label>Building Name / No.</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="room_no"
                                                            placeholder="Building No."
                                                            value="<?= $edit_data ? htmlspecialchars($edit_data['Room_No']) : '' ?>"
                                                            required>
                                                    </div>

                                                    <!-- Select Hostel -->
                                                    <div class="col-md-4">
                                                        <label>Select Hostel</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <?php
                                                        // Re-query hostels since pointer may be exhausted
                                                        $hostels = mysqli_query($conn, "SELECT * FROM hostel");
                                                        ?>
                                                        <select name="hostel_id" class="form-control" required>
                                                            <option value="">-- Select Hostel --</option>
                                                            <?php while ($row = mysqli_fetch_assoc($hostels)): ?>
                                                                <option value="<?= $row['Hostel_id'] ?>"
                                                                    <?= ($edit_data && $edit_data['Hostel_id'] == $row['Hostel_id']) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($row['Hostel_name']) ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>

                                                    <!-- Temperature -->
                                                    <div class="col-md-4">
                                                        <label>Building Temperature</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="temp"
                                                            placeholder="Room Temp."
                                                            value="<?= $edit_data ? htmlspecialchars($edit_data['temp']) : '' ?>">
                                                    </div>

                                                    <!-- Floor -->
                                                    <div class="col-md-4">
                                                        <label>Building Floor</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="number" class="form-control" name="floor"
                                                            placeholder="Floor"
                                                            value="<?= $edit_data ? intval($edit_data['floor']) : '' ?>"
                                                            required>
                                                    </div>

                                                    <!-- Price -->
                                                    <div class="col-md-4">
                                                        <label>Building Price</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="price"
                                                            placeholder="Price"
                                                            value="<?= $edit_data ? htmlspecialchars($edit_data['price']) : '' ?>">
                                                    </div>

                                                    <!-- Type -->
                                                    <div class="col-md-4">
                                                        <label>Building Type</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <select class="form-control" name="type">
                                                            <?php
                                                            $types = ['executive' => 'Executive', 'conducive' => 'Conducive', 'normal' => 'Normal'];
                                                            foreach ($types as $val => $label):
                                                                $sel = ($edit_data && $edit_data['type'] === $val) ? 'selected' : '';
                                                            ?>
                                                                <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <!-- Buttons -->
                                                    <div class="col-sm-12 d-flex justify-content-end mt-2">
                                                        <button name="submit" type="submit" class="btn btn-primary me-1 mb-1">
                                                            <?= $edit_data ? 'Update' : 'Save' ?>
                                                        </button>
                                                        <?php if ($edit_data): ?>
                                                            <a href="add_room.php" class="btn btn-secondary me-1 mb-1">Cancel</a>
                                                        <?php endif; ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div><!-- /.page-heading -->

            <!-- Building List Table -->
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <h3>Building List</h3>

                        <?php
                        $result = mysqli_query($conn, "
                            SELECT r.*, h.Hostel_name
                            FROM room r
                            LEFT JOIN hostel h ON r.Hostel_id = h.Hostel_id
                        ");
                        ?>

                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Building No</th>
                                    <th>Hostel</th>
                                    <th>Type</th>
                                    <th>Temp</th>
                                    <th>Price</th>
                                    <th>Floor</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <?php $status = $row['Allocated'] == 1 ? 'Occupied' : 'Available'; ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['Room_No']) ?></td>
                                            <td><?= htmlspecialchars($row['Hostel_name'] ?? '—') ?></td>
                                            <td><?= htmlspecialchars($row['type']) ?></td>
                                            <td><?= htmlspecialchars($row['temp']) ?></td>
                                            <td><?= htmlspecialchars($row['price']) ?></td>
                                            <td><?= intval($row['floor']) ?></td>
                                            <td>
                                                <span class="badge <?= $row['Allocated'] == 1 ? 'bg-danger' : 'bg-success' ?>">
                                                    <?= $status ?>
                                                </span>
                                            </td>
                                            <td>
                                                <!-- Edit -->
                                                <a href="add_room.php?edit=<?= $row['Room_id'] ?>"
                                                   class="btn btn-sm btn-warning me-1">
                                                    <i class="bi bi-pencil-fill"></i> Edit
                                                </a>
                                                <!-- Delete -->
                                                <a href="add_room.php?delete=<?= $row['Room_id'] ?>"
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Delete this room? This cannot be undone.')">
                                                    <i class="bi bi-trash-fill"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="8">No Rooms Found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </section>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2026 &copy;</p>
                    </div>
                    <div class="float-end">
                        <p>Developed by <a href="#">Bolnan Bukar</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

    <?php if ($edit_data): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formSection = document.getElementById('basic-horizontal-layouts');
            if (formSection) {
                formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
