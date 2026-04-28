<?php 
include 'connection.php';
include 'app_helpers.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_record'])) {
  if (!valid_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('error', 'Delete blocked', 'Please refresh the page and try again.');
    header('Location: records.php');
    exit();
  }

  $record_id = filter_input(INPUT_POST, 'record_id', FILTER_VALIDATE_INT);

  if ($record_id) {
    $statement = $conn->prepare('DELETE FROM records WHERE id = ?');
    $statement->bind_param('i', $record_id);
    $statement->execute();

    if ($statement->affected_rows > 0) {
      set_flash('success', 'Record deleted successfully', '', 'delete_success');
    } else {
      set_flash('warning', 'Record not found', 'That record may have already been deleted.');
    }

    $statement->close();
  } else {
    set_flash('error', 'Invalid record', 'The selected record could not be deleted.');
  }

  header('Location: records.php');
  exit();
}

$flash = get_flash();
$count = 0;
$current_day = '';
$day_count = 0;
$selected_date = $_GET['record_date'] ?? '';
$date_filter = '';

if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date)) {
  $date_filter = $selected_date;
} else {
  $selected_date = '';
}

include 'header.php';
?> 
<?php include 'dashboard.php';?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold">Check-In Records</h1>
          <p class="text-muted mb-0">Review and filter RFID check-in activity.</p>
        </div>
        <div class="col-sm-6">
          
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div>
                <h3 class="table-title">Records Table</h3>
                <p class="table-subtitle">Grouped by check-in date</p>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form method="GET" class="form-inline mb-3">
                <div class="form-group mr-2">
                  <label for="record_date" class="mr-2">Filter by date</label>
                  <input type="date" name="record_date" id="record_date" class="form-control" value="<?php echo e($selected_date); ?>">
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                  <i class="fas fa-filter"></i> Filter
                </button>
                <?php if ($date_filter): ?>
                  <a href="records.php" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                  </a>
                <?php endif; ?>
              </form>
              <div class="table-responsive">
              <table id="example2" class="table app-table mb-0">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Student</th>
                    <th>Matric Number</th>
                    <th>Card ID</th>
                    <th>Timestamp</th>
                    <th class="action-cell">Action</th>
                  </tr>
                </thead>
                <tbody id="records-table-body" data-record-date="<?php echo e($date_filter); ?>">
                  <?php
                    if ($date_filter) {
                      $statement = $conn->prepare("SELECT * FROM records WHERE DATE(timestamp) = ? ORDER BY timestamp DESC, id DESC");
                      $statement->bind_param("s", $date_filter);
                      $statement->execute();
                      $run_query = $statement->get_result();
                    } else {
                      $query = "SELECT * FROM records ORDER BY timestamp DESC, id DESC";
                      $run_query = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    }

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
                  ?>
                </tbody>
              </table>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      <!-- /.row -->
        
        <!-- Main row -->
        <div class="row">
          
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <?php if ($flash): ?>
    <script>
      window.appFlash = <?php echo json_encode($flash); ?>;
    </script>
  <?php endif; ?>
  <script>
    const RECORDS_REFRESH_MS = 5000;

    async function refreshRecordsTable() {
      if (document.hidden) return;

      const tbody = document.getElementById('records-table-body');
      if (!tbody) return;

      const recordDate = tbody.dataset.recordDate || '';
      const url = recordDate ? `records_data.php?record_date=${encodeURIComponent(recordDate)}` : 'records_data.php';

      try {
        const response = await fetch(url, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          cache: 'no-store'
        });

        if (!response.ok) return;

        const data = await response.json();
        tbody.innerHTML = data.html;

        if (window.applyTableSearch) {
          const searchInput = document.querySelector('.js-table-search');
          window.applyTableSearch(searchInput ? searchInput.value : '');
        }
      } catch (error) {
        console.warn('Records refresh failed', error);
      }
    }

    setInterval(refreshRecordsTable, RECORDS_REFRESH_MS);
    document.addEventListener('visibilitychange', refreshRecordsTable);
  </script>

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy;</strong>
    All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <?php
  
include("footer.php");

?>
