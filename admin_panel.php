<!-- admin_panel.php -->
<?php
$pageTitle = 'Admin Panel - Attendance System';
include 'template/header.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}
?>

<h1 class="text-2xl font-bold mb-6">Admin Panel</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Add New User</h2>
        <form id="addUserForm">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-6">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                <select id="role" name="role" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Add User</button>
        </form>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Generate Report</h2>
        <form id="generateReportForm">
            <div class="mb-4">
                <label for="startDate" class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                <input type="date" id="startDate" name="startDate" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-6">
                <label for="endDate" class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                <input type="date" id="endDate" name="endDate" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">Generate Report</button>
        </form>
    </div>
</div>

<div class="mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">User List</h2>
    <div id="userList"></div>
</div>

<script>
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch('includes/add_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User added successfully!');
                e.target.reset();
                loadUserList();
            } else {
                alert('Error adding user. Please try again.');
            }
        });
    });
    
    document.getElementById('generateReportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch('includes/generate_report.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display or download the report
                console.log(data.report);
                alert('Report generated successfully!');
            } else {
                alert('Error generating report. Please try again.');
            }
        });
    });
    
    function loadUserList() {
        fetch('includes/get_user_list.php')
        .then(response => response.json())
        .then(data => {
            const userListHtml = data.users.map(user => `
                <div class="mb-2 p-2 border rounded">
                    <p><strong>${user.name}</strong> (${user.username})</p>
                    <p>Email: ${user.email}</p>
                    <p>Role: ${user.role}</p>
                </div>
            `).join('');
            document.getElementById('userList').innerHTML = userListHtml;
        });
    }
    
    // Initial load
    loadUserList();
</script>

<?php include 'template/footer.php'; ?>