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
    <!-- Add New User Form -->
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
    
    <!-- Generate Report Form -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Generate Overall Report</h2>
        <form id="generateReportForm">
            <div class="mb-4">
                <label for="startDate" class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                <input type="date" id="startDate" name="startDate" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-6">
                <label for="endDate" class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                <input type="date" id="endDate" name="endDate" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <a href="#" id="generateReportLink" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 text-center block">Generate Report</a>
        </form>
    </div>
</div>

<!-- User List -->
<div class="mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">User List</h2>
    <!-- Search Bar -->
<div class="mt-8">
    <div class="mb-4">
        <input type="text" id="searchInput" class="w-full px-3 py-2 border rounded-lg" placeholder="Search by username or full name">
    </div>
</div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200 text-gray-700">
                <th class="border p-2">Username</th>
                <th class="border p-2">Full Name</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Role</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody id="userList"></tbody>
    </table>
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

    // Update the Generate Report link dynamically based on the date inputs
    document.getElementById('generateReportForm').addEventListener('input', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const generateReportLink = document.getElementById('generateReportLink');
        generateReportLink.href = `includes/generate_overall_report.php?startDate=${encodeURIComponent(startDate)}&endDate=${encodeURIComponent(endDate)}`;
    });

    function loadUserList() {
        fetch('includes/get_user_list.php')
        .then(response => response.json())
        .then(data => {
            const userListHtml = data.users.map(user => `
                <tr>
                    <td class="border p-2">${user.username}</td>
                    <td class="border p-2">${user.name}</td>
                    <td class="border p-2">${user.email}</td>
                    <td class="border p-2">${user.role}</td>
                    <td class="border p-2 text-center">
                     <a href="user_summary.php?id=${user.id}" class="bg-blue-500 text-white py-1 px-2 rounded-lg hover:bg-blue-600">View Summary</a>
                        <button onclick="updateUser('${user.id}')" class="bg-yellow-500 text-white py-1 px-2 rounded-lg hover:bg-yellow-600">Update</button>
                        <button onclick="deleteUser('${user.id}')" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-600">Delete</button>
                    </td>
                </tr>
            `).join('');
            document.getElementById('userList').innerHTML = userListHtml;
        });
    }
    
    function updateUser(userId) {
        window.location.href = `update_user.php?id=${userId}`;
    }
    
    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch('includes/delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('User deleted successfully!');
                    loadUserList();
                } else {
                    alert('Error deleting user. Please try again.');
                }
            });
        }
    }
    document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.trim();
    fetchUserList(query);
});

function fetchUserList(query = '') {
    fetch('includes/get_user_list.php?search=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            const userListHtml = data.users.map(user => `
                <tr>
                    <td class="border p-2">${user.username}</td>
                    <td class="border p-2">${user.name}</td>
                    <td class="border p-2">${user.email}</td>
                    <td class="border p-2">${user.role}</td>
                    <td class="border p-2 text-center">
                        <a href="user_summary.php?id=${user.id}" class="bg-blue-500 text-white py-1 px-2 rounded-lg hover:bg-blue-600">View Summary</a>
                        <button onclick="updateUser('${user.id}')" class="bg-yellow-500 text-white py-1 px-2 rounded-lg hover:bg-yellow-600">Update</button>
                        <button onclick="deleteUser('${user.id}')" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-600">Delete</button>
                    </td>
                </tr>
            `).join('');
            document.getElementById('userList').innerHTML = userListHtml;
        });
}

// Initial load
fetchUserList();
    
    // Initial load
    loadUserList();
</script>

<?php include 'template/footer.php'; ?>
