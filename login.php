<?php
$pageTitle = 'Login - Attendance System';
include 'template/header.php';
include 'includes/db.php';         // Include database connection
include 'includes/user/auth.php';       // Include authentication functions
include 'includes/helpers/location_validation.php'; // Include validation functions

// Initialize debug variables
$debug_latitude = isset($_POST['latitude']) ? htmlspecialchars($_POST['latitude']) : null;
$debug_longitude = isset($_POST['longitude']) ? htmlspecialchars($_POST['longitude']) : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Optional: Check if location data is provided
    $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;

    // Verify credentials
    if (verify_credentials($username, $password)) {
        if (is_admin($username)) {
            $_SESSION['user_id'] = get_user_id($username);
            $_SESSION['is_admin'] = is_admin($username);
            header('Location: dashboard.php');
            exit();
        } else {
            // Check if location is provided and valid
            if ($latitude && $longitude && check_location($latitude, $longitude)) {
                $_SESSION['user_id'] = get_user_id($username);
                $_SESSION['is_admin'] = is_admin($username);
                header('Location: dashboard.php');
                exit();
            } else {
                $error = $latitude && $longitude ? 
                    "You must be within 500 meters of the office." : 
                    "Please enable location services.";
            }
        }
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
        <?php if (isset($error)): ?>
            <p class="text-red-500 mb-4"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <!-- Debugging output for latitude and longitude -->
        <?php if ($debug_latitude && $debug_longitude): ?>
            <p>Your Location - Latitude: <?php echo $debug_latitude; ?>, Longitude: <?php echo $debug_longitude; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST" id="loginForm">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-red-400  text-white py-2 rounded-lg hover:bg-[#f00000] ">Login</button>
        </form>
        <div class="mt-4 text-center">
            <a href="password_reset_request.php" class="text-blue-500 hover:underline">Forgot Password?</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                console.log(position.coords.latitude);
                console.log(position.coords.longitude);
                var latitudeInput = document.createElement('input');
                latitudeInput.type = 'hidden';
                latitudeInput.name = 'latitude';
                latitudeInput.value = position.coords.latitude;
                
                var longitudeInput = document.createElement('input');
                longitudeInput.type = 'hidden';
                longitudeInput.name = 'longitude';
                longitudeInput.value = position.coords.longitude;
                
                e.target.appendChild(latitudeInput);
                e.target.appendChild(longitudeInput);
                
                e.target.submit();
            }, function() {
                alert('Unable to get your location. Please enable location services.');
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });
</script>

<?php include 'template/footer.php'; ?>
