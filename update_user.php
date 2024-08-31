<?php
$pageTitle = 'Update User - Attendance System';
include 'template/header.php';
include 'includes/db.php';

// Check if user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin_panel.php');
    exit();
}

$userId = intval($_GET['id']);

// Fetch user details from the database
$stmt = $conn->prepare("SELECT username, name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $name, $email, $role);
$stmt->fetch();
$stmt->close();

// Redirect if user not found
if (!$username) {
    header('Location: admin_panel.php');
    exit();
}
?>

<h1 class="text-2xl font-bold mb-6">Update User</h1>

<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md mx-auto">
    <form id="updateUserForm">
        <input type="hidden" id="userId" name="userId" value="<?php echo htmlspecialchars($userId); ?>">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div class="mb-6">
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
            <select id="role" name="role" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="user" <?php echo $role === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Update User</button>
    </form>
</div>

<script>
    document.getElementById('updateUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch('includes/update_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User updated successfully!');
                window.location.href = 'admin_panel.php';
            } else {
                alert('Error updating user. Please try again.');
            }
        });
    });
</script>

<?php include 'template/footer.php'; ?>
