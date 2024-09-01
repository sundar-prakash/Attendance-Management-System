<?php
include_once 'includes/config.php';
// Function to check if the user's location is within CHECKIN_RADIUS meters of the office
function check_location($user_lat, $user_lon, $office_lat = OFFICE_LATITUDE , $office_lon = OFFICE_LONGITUDE ) {
    $distance = get_distance_from_lat_lon_in_km($user_lat, $user_lon, $office_lat, $office_lon);
    return $distance <= (CHECKIN_RADIUS/1000); // Distance is in km, 0.5 km = 500 meters
}

// Function to calculate the distance between two coordinates in kilometers
function get_distance_from_lat_lon_in_km($lat1, $lon1, $lat2, $lon2) {
    $R = 6371; // Radius of the earth in km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $d = $R * $c; // Distance in km
    return $d;
}
?>
