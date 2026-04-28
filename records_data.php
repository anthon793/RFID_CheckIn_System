<?php
include 'connection.php';
include 'app_helpers.php';

require_login();

header('Content-Type: application/json');

$count = 0;
$current_day = '';
$day_count = 0;
$selected_date = $_GET['record_date'] ?? '';
$date_filter = '';

if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date)) {
    $date_filter = $selected_date;
}

if ($date_filter) {
    $statement = $conn->prepare("SELECT * FROM records WHERE DATE(timestamp) = ? ORDER BY timestamp DESC, id DESC");
    $statement->bind_param("s", $date_filter);
    $statement->execute();
    $run_query = $statement->get_result();
} else {
    $query = "SELECT * FROM records ORDER BY timestamp DESC, id DESC";
    $run_query = mysqli_query($conn, $query) or die(mysqli_error($conn));
}

ob_start();

if (mysqli_num_rows($run_query) > 0) {
    while ($row = mysqli_fetch_array($run_query)) {
        $count++;
        $id = $row['id'];
        $name = $row['name'];
        $matricnum = $row['matricnum'];
        $cardid = $row['cardid'];
        $timestamp = $row['timestamp'];
        $record_day = date('Y-m-d', strtotime($timestamp));
        $record_day_label = date('l, F j, Y', strtotime($timestamp));

        if ($record_day !== $current_day) {
            $current_day = $record_day;
            $day_count = 0;
            echo "<tr class='record-day-row'>";
            echo "<td colspan='6' class='font-weight-bold text-muted'>" . e($record_day_label) . "</td>";
            echo "</tr>";
        }

        $day_count++;

        echo "<tr>";
        echo "<td>" . e($day_count) . "</td>";
        echo "<td><strong>" . e($name) . "</strong></td>";
        echo "<td>" . e($matricnum) . "</td>";
        echo "<td><span class='id-badge'>" . e($cardid) . "</span></td>";
        echo "<td>" . e($timestamp) . "</td>";
        echo "<td class='action-cell'>
          <form method='POST' class='js-delete-form d-inline' data-title='Delete record?' data-text='This check-in record will be permanently removed.'>
            " . csrf_field() . "
            <input type='hidden' name='delete_record' value='1'>
            <input type='hidden' name='record_id' value='" . e($id) . "'>
            <button type='submit' class='btn btn-outline-danger btn-sm'>
              <i class='fas fa-trash-alt'></i> Delete
            </button>
          </form>
        </td>";
        echo "</tr>";
    }
} else {
    $empty_message = $date_filter ? "No records found for " . date('l, F j, Y', strtotime($date_filter)) . "." : "No records found.";
    echo "<tr><td colspan='6' class='text-center text-muted'>" . e($empty_message) . "</td></tr>";
}

if (isset($statement)) {
    $statement->close();
}

echo json_encode([
    'html' => ob_get_clean(),
    'count' => $count,
]);
