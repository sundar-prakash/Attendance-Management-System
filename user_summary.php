<?php
$pageTitle = 'User Summary - Attendance System';
include_once 'template/header.php'; // Ensure header is included only once
include_once 'includes/functions.php'; // Ensure functions.php is included only once

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo 'No user ID provided.';
    exit();
}

$user_id = $_GET['id'];
$user_details = get_user_details($user_id);

// Handle case where user details are not found
if (empty($user_details)) {
    echo 'User not found.';
    exit();
}

// Pagination settings
$itemsPerPage = 10; // Number of entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Fetch attendance data with pagination
$attendance = get_user_attendance($user_id, $itemsPerPage, $offset);
$totalEntries = get_total_attendance_entries($user_id);
$totalPages = ceil($totalEntries / $itemsPerPage);
?>

<h1 class="text-2xl font-bold mb-6">User Summary: <?php echo htmlspecialchars($user_details['name']); ?></h1>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">User Details</h2>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user_details['username'] ?? 'N/A'); ?></p>
    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user_details['name'] ?? 'N/A'); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user_details['email'] ?? 'N/A'); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars($user_details['role'] ?? 'N/A'); ?></p>
</div>

<div class="bg-white p-6 rounded-lg shadow-md mt-6">
    <h2 class="text-xl font-semibold mb-4">Attendance Summary</h2>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700">
                <th class="border p-2">Date</th>
                <th class="border p-2">Check-in Time</th>
                <th class="border p-2">Check-out Time</th>
                <th class="border p-2">Duration</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendance as $entry): ?>
                <tr>
                    <td class="border p-2"><?php echo htmlspecialchars($entry['date']); ?></td>
                    <td class="border p-2"><?php echo htmlspecialchars($entry['checkin_time']); ?></td>
                    <td class="border p-2"><?php echo htmlspecialchars($entry['checkout_time']); ?></td>
                    <td class="border p-2"><?php echo htmlspecialchars($entry['duration']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Pagination controls -->
    <div class="mt-4">
        <?php if ($page > 1): ?>
            <a href="?id=<?php echo urlencode($user_id); ?>&page=<?php echo $page - 1; ?>" class="bg-gray-300 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-400">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?id=<?php echo urlencode($user_id); ?>&page=<?php echo $i; ?>" class="bg-gray-300 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-400 <?php if ($i == $page) echo 'font-bold'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?id=<?php echo urlencode($user_id); ?>&page=<?php echo $page + 1; ?>" class="bg-gray-300 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-400">Next</a>
        <?php endif; ?>
    </div>

    <a href="includes/generate_user_attendance.php?id=<?php echo urlencode($user_id); ?>" class="mt-4 inline-block bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">Download Summary as Excel</a>
</div>

<?php include 'template/footer.php'; ?>
