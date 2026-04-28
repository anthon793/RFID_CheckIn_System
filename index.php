<?php
include('connection.php');
include('app_helpers.php');

require_login();

function count_query($conn, $sql)
{
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    return (int) ($row['total'] ?? 0);
}

$total_records = count_query($conn, "SELECT COUNT(*) AS total FROM records");
$today_records = count_query($conn, "SELECT COUNT(*) AS total FROM records WHERE DATE(timestamp) = CURDATE()");
$unique_cards = count_query($conn, "SELECT COUNT(DISTINCT cardid) AS total FROM records");
$total_users = count_query($conn, "SELECT COUNT(*) AS total FROM users");

$latest_result = mysqli_query($conn, "SELECT timestamp FROM records ORDER BY timestamp DESC, id DESC LIMIT 1") or die(mysqli_error($conn));
$latest_record = mysqli_fetch_assoc($latest_result);
$last_checkin = $latest_record ? date('M j, Y g:i A', strtotime($latest_record['timestamp'])) : 'No records yet';

$recent_records = mysqli_query($conn, "SELECT name, matricnum, cardid, timestamp FROM records ORDER BY timestamp DESC, id DESC LIMIT 6") or die(mysqli_error($conn));
$daily_records = mysqli_query($conn, "
    SELECT DATE(timestamp) AS record_day, COUNT(*) AS total
    FROM records
    WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(timestamp)
    ORDER BY record_day ASC
") or die(mysqli_error($conn));

$trend_map = [];
while ($row = mysqli_fetch_assoc($daily_records)) {
    $trend_map[$row['record_day']] = (int) $row['total'];
}

$trend_days = [];
$max_trend = 1;
$weekly_total = 0;
$zero_days = 0;
for ($i = 6; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-{$i} days"));
    $total = $trend_map[$day] ?? 0;
    $weekly_total += $total;
    if ($total === 0) {
        $zero_days++;
    }
    $max_trend = max($max_trend, $total);
    $trend_days[] = [
        'date' => $day,
        'label' => date('D', strtotime($day)),
        'total' => $total,
    ];
}
$show_empty_trend = $weekly_total === 0;

$chart_width = 520;
$chart_height = 120;
$chart_top = 28;
$chart_bottom = 108;
$chart_points = [];
$chart_area_points = [];

foreach ($trend_days as $index => $day) {
    $x = count($trend_days) > 0 ? round((($index + 0.5) / count($trend_days)) * $chart_width, 2) : 0;
    $y = $max_trend > 0 ? round($chart_bottom - (($day['total'] / $max_trend) * ($chart_bottom - $chart_top)), 2) : $chart_bottom;
    $chart_points[] = $x . ',' . $y;
}

if (!empty($chart_points)) {
    $chart_area_points = array_merge(['0,' . $chart_bottom], $chart_points, [$chart_width . ',' . $chart_bottom]);
}

include('header.php');
include('dashboard.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-8">
          <h1 class="m-0 font-weight-bold">Dashboard Overview</h1>
          <p class="text-muted mb-0">Welcome back, <?php echo e($_SESSION['username'] ?? 'Admin'); ?>. Here is what is happening today.</p>
        </div>
        <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
          <a href="records.php" class="btn btn-primary top-action-btn">
            <i class="fas fa-table"></i> View Records
          </a>
          <a href="adduser.php" class="btn btn-outline-secondary top-action-btn ml-1">
            <i class="fas fa-user-plus"></i> Add User
          </a>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-3 col-md-6">
          <div class="card stat-card">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stat-label">Total check-ins</div>
                  <div class="stat-value mt-2" id="stat-total-records"><?php echo e($total_records); ?></div>
                </div>
                <div class="stat-icon stat-icon-primary">
                  <i class="fas fa-fingerprint"></i>
                </div>
              </div>
              <p class="text-muted mb-0 mt-3">All captured RFID entries</p>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="card stat-card">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stat-label">Today</div>
                  <div class="stat-value mt-2" id="stat-today-records"><?php echo e($today_records); ?></div>
                </div>
                <div class="stat-icon stat-icon-primary">
                  <i class="fas fa-clock"></i>
                </div>
              </div>
              <p class="text-muted mb-0 mt-3">Check-ins recorded today</p>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="card stat-card">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stat-label">Unique cards</div>
                  <div class="stat-value mt-2" id="stat-unique-cards"><?php echo e($unique_cards); ?></div>
                </div>
                <div class="stat-icon stat-icon-primary">
                  <i class="fas fa-tags"></i>
                </div>
              </div>
              <p class="text-muted mb-0 mt-3">Different RFID cards seen</p>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6">
          <div class="card stat-card">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stat-label">Admin users</div>
                  <div class="stat-value mt-2" id="stat-total-users"><?php echo e($total_users); ?></div>
                </div>
                <div class="stat-icon stat-icon-primary">
                  <i class="fas fa-user-shield"></i>
                </div>
              </div>
              <p class="text-muted mb-0 mt-3">Dashboard accounts</p>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header p-4">
              <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start">
                <div>
                  <h3 class="font-weight-bold mb-1">Weekly Check-In Activity</h3>
                  <p class="text-muted mb-0">Rolling 7-day activity window</p>
                </div>
                <div class="trend-summary mt-3 mt-md-0 text-md-right">
                  <strong id="trend-weekly-total"><?php echo e($weekly_total); ?></strong> check-ins in the last 7 days<br>
                  <span><i class="fas fa-clock mr-1"></i> Last check-in: <span id="trend-last-checkin"><?php echo e($last_checkin); ?></span></span>
                </div>
              </div>
            </div>
            <div class="card-body p-4" id="trend-chart-body">
              <?php if ($show_empty_trend): ?>
                <div class="trend-empty">
                  <div>
                    <i class="fas fa-calendar-minus mb-2 d-block"></i>
                    No check-in activity this period
                  </div>
                </div>
              <?php else: ?>
                <div class="line-chart-wrap">
                  <svg class="line-chart" viewBox="0 0 520 140" role="img" aria-label="Weekly check-in activity line chart">
                    <line class="chart-grid" x1="0" y1="12" x2="520" y2="12"></line>
                    <line class="chart-grid" x1="0" y1="60" x2="520" y2="60"></line>
                    <line class="chart-grid" x1="0" y1="108" x2="520" y2="108"></line>
                    <polygon class="chart-area" points="<?php echo e(implode(' ', $chart_area_points)); ?>"></polygon>
                    <polyline class="chart-line" points="<?php echo e(implode(' ', $chart_points)); ?>"></polyline>
                    <?php foreach ($trend_days as $index => $day): ?>
                      <?php
                        [$cx, $cy] = explode(',', $chart_points[$index]);
                        $point_class = $day['total'] === 0 ? 'chart-point chart-point-muted' : 'chart-point';
                      ?>
                      <circle class="<?php echo e($point_class); ?>" cx="<?php echo e($cx); ?>" cy="<?php echo e($cy); ?>" r="5">
                        <title><?php echo e($day['label'] . ': ' . $day['total'] . ' check-ins'); ?></title>
                      </circle>
                      <?php $label_y = max(14, ((float) $cy) - 14); ?>
                      <text x="<?php echo e($cx); ?>" y="<?php echo e($label_y); ?>" text-anchor="middle" fill="#475569" font-size="13" font-weight="800"><?php echo e($day['total']); ?></text>
                    <?php endforeach; ?>
                  </svg>
                  <div class="chart-labels">
                    <?php foreach ($trend_days as $day): ?>
                      <span><?php echo e($day['label']); ?></span>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-header p-4">
              <h3 class="card-title font-weight-bold">Quick Actions</h3>
            </div>
            <div class="card-body p-4">
              <a href="records.php?record_date=<?php echo e(date('Y-m-d')); ?>" class="action-button mb-2">
                <i class="fas fa-calendar-check text-success"></i>
                <span>Review today’s records</span>
              </a>
              <a href="records.php" class="action-button mb-2">
                <i class="fas fa-search text-primary"></i>
                <span>Search all check-ins</span>
              </a>
              <a href="users.php" class="action-button mb-2">
                <i class="fas fa-user-cog text-info"></i>
                <span>Manage users</span>
              </a>
              <a href="profile.php" class="action-button">
                <i class="fas fa-shield-alt text-warning"></i>
                <span>Update profile</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header p-4">
              <h3 class="card-title font-weight-bold">Recent Check-ins</h3>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table app-table mb-0">
                <thead>
                  <tr>
                    <th class="text-center">S/N</th>
                    <th class="text-center">Student</th>
                    <th class="text-center">Matric Number</th>
                    <th class="text-center">Card ID</th>
                    <th class="text-center">Time</th>
                  </tr>
                </thead>
                <tbody id="recent-records-body">
                  <?php if (mysqli_num_rows($recent_records) > 0): ?>
                    <?php $recent_count = 0; ?>
                    <?php while ($record = mysqli_fetch_assoc($recent_records)): ?>
                      <?php $recent_count++; ?>
                      <tr>
                        <td class="text-center"><?php echo e($recent_count); ?></td>
                        <td class="text-center"><strong><?php echo e($record['name']); ?></strong></td>
                        <td class="text-center"><?php echo e($record['matricnum']); ?></td>
                        <td class="text-center"><span class="id-badge"><?php echo e($record['cardid']); ?></span></td>
                        <td class="text-center"><?php echo e(date('M j, Y g:i A', strtotime($record['timestamp']))); ?></td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center text-muted">No check-ins have been recorded yet.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<footer class="main-footer">
  <strong>Copyright &copy;</strong>
  All rights reserved.
</footer>

<aside class="control-sidebar control-sidebar-dark"></aside>

<script>
  const DASHBOARD_REFRESH_MS = 5000;

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function renderTrendChart(trend) {
    const chartBody = document.getElementById('trend-chart-body');
    if (!chartBody) return;

    document.getElementById('trend-weekly-total').textContent = trend.weeklyTotal;
    document.getElementById('trend-last-checkin').textContent = trend.lastCheckin;

    if (trend.showEmptyTrend) {
      chartBody.innerHTML = `
        <div class="trend-empty">
          <div>
            <i class="fas fa-calendar-minus mb-2 d-block"></i>
            No check-in activity this period
          </div>
        </div>
      `;
      return;
    }

    const width = 520;
    const top = 28;
    const bottom = 108;
    const maxTrend = Math.max(1, Number(trend.maxTrend || 1));
    const days = trend.days || [];
    const points = days.map((day, index) => {
      const x = days.length ? ((index + 0.5) / days.length) * width : 0;
      const y = bottom - ((Number(day.total || 0) / maxTrend) * (bottom - top));
      return { x, y, day };
    });

    const pointList = points.map((point) => `${point.x.toFixed(2)},${point.y.toFixed(2)}`).join(' ');
    const areaList = [`0,${bottom}`, pointList, `${width},${bottom}`].join(' ');
    const circles = points.map((point) => {
      const total = Number(point.day.total || 0);
      const pointClass = total === 0 ? 'chart-point chart-point-muted' : 'chart-point';
      const labelY = Math.max(14, point.y - 14);
      return `
        <circle class="${pointClass}" cx="${point.x.toFixed(2)}" cy="${point.y.toFixed(2)}" r="5">
          <title>${escapeHtml(point.day.label)}: ${escapeHtml(total)} check-ins</title>
        </circle>
        <text x="${point.x.toFixed(2)}" y="${labelY.toFixed(2)}" text-anchor="middle" fill="#475569" font-size="13" font-weight="800">${escapeHtml(total)}</text>
      `;
    }).join('');
    const labels = days.map((day) => `<span>${escapeHtml(day.label)}</span>`).join('');

    chartBody.innerHTML = `
      <div class="line-chart-wrap">
        <svg class="line-chart" viewBox="0 0 520 140" role="img" aria-label="Weekly check-in activity line chart">
          <line class="chart-grid" x1="0" y1="12" x2="520" y2="12"></line>
          <line class="chart-grid" x1="0" y1="60" x2="520" y2="60"></line>
          <line class="chart-grid" x1="0" y1="108" x2="520" y2="108"></line>
          <polygon class="chart-area" points="${areaList}"></polygon>
          <polyline class="chart-line" points="${pointList}"></polyline>
          ${circles}
        </svg>
        <div class="chart-labels">${labels}</div>
      </div>
    `;
  }

  function renderRecentRecords(records) {
    const tbody = document.getElementById('recent-records-body');
    if (!tbody) return;

    if (!records.length) {
      tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No check-ins have been recorded yet.</td></tr>';
      return;
    }

    tbody.innerHTML = records.map((record, index) => `
      <tr>
        <td class="text-center">${index + 1}</td>
        <td class="text-center"><strong>${escapeHtml(record.name)}</strong></td>
        <td class="text-center">${escapeHtml(record.matricnum)}</td>
        <td class="text-center"><span class="id-badge">${escapeHtml(record.cardid)}</span></td>
        <td class="text-center">${escapeHtml(record.time)}</td>
      </tr>
    `).join('');

    if (window.applyTableSearch) {
      const searchInput = document.querySelector('.js-table-search');
      window.applyTableSearch(searchInput ? searchInput.value : '');
    }
  }

  async function refreshDashboard() {
    if (document.hidden) return;

    try {
      const response = await fetch('dashboard_data.php', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        cache: 'no-store'
      });

      if (!response.ok) return;

      const data = await response.json();

      document.getElementById('stat-total-records').textContent = data.stats.totalRecords;
      document.getElementById('stat-today-records').textContent = data.stats.todayRecords;
      document.getElementById('stat-unique-cards').textContent = data.stats.uniqueCards;
      document.getElementById('stat-total-users').textContent = data.stats.totalUsers;

      renderTrendChart(data.trend);
      renderRecentRecords(data.recentRecords);
    } catch (error) {
      console.warn('Dashboard refresh failed', error);
    }
  }

  setInterval(refreshDashboard, DASHBOARD_REFRESH_MS);
  document.addEventListener('visibilitychange', refreshDashboard);
</script>

<?php
include("footer.php");
?>
