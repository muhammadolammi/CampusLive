<?php
require_once __DIR__ . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

// Seed initial shuttle if empty
$check = $db->query("SELECT COUNT(*) as total FROM shuttle_locations")->fetch();
if ($check['total'] == 0) {
    $db->exec("INSERT INTO shuttle_locations (shuttle_name, latitude, longitude, speed) VALUES 
        ('Shuttle Line A', 8.4799, 4.5418, 20.5),
        ('Shuttle Line B', 8.4820, 4.5450, 15.0)");
    echo "Seeded initial shuttles.\n";
}

echo "Starting GPS Simulation (Press Ctrl+C to stop)...\n";

while (true) {
    // Generate small random coordinate movements (approx 10-30 meters)
    $latDelta = (mt_rand(-50, 50) / 100000);
    $lngDelta = (mt_rand(-50, 50) / 100000);

    $stmt = $db->prepare("UPDATE shuttle_locations 
                          SET latitude = latitude + :latDelta, 
                              longitude = longitude + :lngDelta, 
                              speed = :speed 
                          WHERE id = 1");
    $stmt->execute([
        ':latDelta' => $latDelta,
        ':lngDelta' => $lngDelta,
        ':speed' => mt_rand(15, 35)
    ]);

    echo "Updated Shuttle 1 location at " . date('H:i:s') . "\n";
    sleep(3); // Update interval matching polling rate
}