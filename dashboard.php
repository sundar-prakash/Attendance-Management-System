<?php
$pageTitle = 'Dashboard - Attendance System';
include 'template/header.php';
include 'includes/helpers/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_details = get_user_name($user_id);
?>

<h1 class="text-2xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($user_details['name']); ?></h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Today's Attendance</h2>
        <p><?php echo 'Current timezone: ' . date_default_timezone_get() . '<br>';
echo 'Current time: ' . date('Y-m-d H:i:s'); ?></p>
        <div id="attendanceStatus"></div>
        <button id="checkInBtn" class="mt-4 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">Check In</button>
        <button id="checkOutBtn" class="mt-4 ml-4 bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">Check Out</button>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">This Week's Summary</h2>
        <canvas id="weeklySummaryChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.getElementById('checkInBtn').addEventListener('click', function() {
        recordAttendance('check_in');
    });
    
    document.getElementById('checkOutBtn').addEventListener('click', function() {
        recordAttendance('check_out');
    });

    function recordAttendance(type) {
        fetch('includes/attendance/attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'type=' + type
        })
        // .then(response => response.json())
        .then(data => {
            if (data.ok) {
                alert(type === 'check_in' ? 'Checked in successfully!' : 'Checked out successfully!');
                updateAttendanceStatus();  // Update status immediately
            } else {
                alert('Error recording attendance: ' + data.message);
            }
        });
    }
    
    function updateAttendanceStatus() {
        fetch('includes/attendance/get_attendance_status.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('attendanceStatus').innerHTML = `
                <p>Check-in time: ${data.checkInTime || 'Not checked in'}</p>
                <p>Check-out time: ${data.checkOutTime || 'Not checked out'}</p>
            `;
            updateButtonStates(data);
        });
    }
    
    function updateButtonStates(data) {
        const checkInBtn = document.getElementById('checkInBtn');
        const checkOutBtn = document.getElementById('checkOutBtn');

        if (data.checkInTime) {
            checkInBtn.disabled = true;
            checkInBtn.classList.remove('bg-green-500', 'hover:bg-green-600');
            checkInBtn.classList.add('bg-gray-500', 'cursor-not-allowed');
        } else {
            checkInBtn.disabled = false;
            checkInBtn.classList.remove('bg-gray-500', 'cursor-not-allowed');
            checkInBtn.classList.add('bg-green-500', 'hover:bg-green-600');
        }

        if (data.checkOutTime) {
            checkOutBtn.disabled = true;
            checkOutBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
            checkOutBtn.classList.add('bg-gray-500', 'cursor-not-allowed');
        } else {
            checkOutBtn.disabled = !data.checkInTime;
            checkOutBtn.classList.remove('bg-gray-500', 'cursor-not-allowed');
            checkOutBtn.classList.add('bg-red-500', 'hover:bg-red-600');
        }
    }

    function loadWeeklySummary() {
    fetch('includes/attendance/get_weekly_summary.php')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('weeklySummaryChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.dates,
                datasets: [{
                    label: 'Check-in Duration (minutes)',
                    data: data.durations,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Day of the Week'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Duration (minutes)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
}

// Initial load
updateAttendanceStatus();
loadWeeklySummary();

</script>

<?php include 'template/footer.php'; ?>
