<!-- dashboard.php -->
<?php
$pageTitle = 'Dashboard - Attendance System';
include 'template/header.php';
include 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_details = get_user_details($user_id);
$attendance = get_attendance($user_id);
?>

<h1 class="text-2xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($user_details['name']); ?></h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Today's Attendance</h2>
        <div id="attendanceStatus"></div>
        <button id="checkInBtn" class="mt-4 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">Check In</button>
        <button id="checkOutBtn" class="mt-4 ml-4 bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">Check Out</button>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">This Week's Summary</h2>
        <div id="weeklySummary"></div>
    </div>
</div>

<script>
    // JavaScript to handle check-in and check-out actions
    document.getElementById('checkInBtn').addEventListener('click', function() {
        recordAttendance('check_in');
    });
    
    document.getElementById('checkOutBtn').addEventListener('click', function() {
        recordAttendance('check_out');
    });
    
    function recordAttendance(type) {
        fetch('includes/attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'type=' + type
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(type === 'check_in' ? 'Checked in successfully!' : 'Checked out successfully!');
                updateAttendanceStatus();
            } else {
                alert('Error recording attendance. Please try again.');
            }
        });
    }
    
    function updateAttendanceStatus() {
        fetch('includes/get_attendance_status.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('attendanceStatus').innerHTML = `
                <p>Check-in time: ${data.checkInTime || 'Not checked in'}</p>
                <p>Check-out time: ${data.checkOutTime || 'Not checked out'}</p>
            `;
        });
    }
    
    // Initial load
    updateAttendanceStatus();
</script>

<?php include 'template/footer.php'; ?>