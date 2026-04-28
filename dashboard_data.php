<?php
include('connection.php');
include('app_helpers.php');

require_login();

header('Content-Type: application/json');

function dashboard_count_query($conn, $sql)
{
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    return (int) ($row['total'] ?? 0);
}

$total_records = dashboard_count_query($conn, "SELECT COUNT(*) AS total FROM records");
$today_records = dashboard_count_query($conn, "SELECT COUNT(*) AS total FROM records WHERE DATE(timestamp) = CURDATE()");
$unique_cards = dashboard_count_query($conn, "SELECT COUNT(DISTINCT cardid) AS total FROM records");
$total_users = dashboard_count_query($conn, "SELECT COUNT(*) AS total FROM users");

$latest_result = mysqli_query($conn, "SELECT timestamp FROM records ORDER BY timestamp DESC, id DESC LIMIT 1") or die(mysqli_error($conn));
$latest_record = mysqli_fetch_assoc($latest_result);
$last_checkin = $latest_record ? date('M j, Y g:i A', strtotime($latest_record['timestamp'])) : 'No records yet';

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

for ($i = 6; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-{$i} days"));
    $total = $trend_map[$day] ?? 0;
    $weekly_total += $total;
    $max_trend = max($max_trend, $total);
    $trend_days[] = [
        'date' => $day,
        'label' => date('D', strtotime($day)),
        'total' => $total,
    ];
}

$recent_records_result = mysqli_query($conn, "SELECT name, matricnum, cardid, timestamp FROM records ORDER BY timestamp DESC, id DESC LIMIT 6") or die(mysqli_error($conn));
$recent_records = [];

while ($record = mysqli_fetch_assoc($recent_records_result)) {
    $recent_records[] = [
        'name' => $record['name'],
        'matricnum' => $record['matricnum'],
        'cardid' => $record['cardid'],
        'time' => date('M j, Y g:i A', strtotime($record['timestamp'])),
    ];
}

echo json_encode([
    'stats' => [
        'totalRecords' => $total_records,
        'todayRecords' => $today_records,
        'uniqueCards' => $unique_cards,
        'totalUsers' => $total_users,
    ],
    'trend' => [
        'weeklyTotal' => $weekly_total,
        'maxTrend' => $max_trend,
        'showEmptyTrend' => $weekly_total === 0,
        'lastCheckin' => $last_checkin,
        'days' => $trend_days,
    ],
    'recentRecords' => $recent_records,
]);
