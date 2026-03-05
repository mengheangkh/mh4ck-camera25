<?php
$mapFile = 'map.txt';
$latestMapFile = 'latest_map.txt';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Captured Location</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        .location-info {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            word-wrap: break-word;
        }
        .map-link {
            background: #4CAF50;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .map-link:hover {
            background: #45a049;
        }
        pre {
            background: rgba(0,0,0,0.3);
            padding: 15px;
            border-radius: 10px;
            overflow: auto;
            color: #00ff00;
            font-family: 'Courier New', monospace;
        }
        .latest-link {
            background: #ff9800;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .latest-link a {
            color: white;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📍 Captured GPS Location</h1>
        
        <?php if (file_exists($latestMapFile)): 
            $latestLink = file_get_contents($latestMapFile);
        ?>
        <div class="latest-link">
            <strong>🔗 Latest Map Link:</strong><br>
            <a href="<?php echo htmlspecialchars($latestLink); ?>" target="_blank">
                <?php echo htmlspecialchars($latestLink); ?>
            </a>
        </div>
        <?php endif; ?>
        
        <div class="location-info">
            <h3>📋 All Captured Locations:</h3>
            <pre><?php 
                if (file_exists($mapFile)) {
                    echo htmlspecialchars(file_get_contents($mapFile));
                } else {
                    echo "No locations captured yet.";
                }
            ?></pre>
        </div>
        
        <?php if (file_exists($latestMapFile)): 
            // ទាញរយៈទទឹង និងរយៈបណ្តោយពី Link
            $latestLink = file_get_contents($latestMapFile);
            preg_match('/q=([0-9.-]+),([0-9.-]+)/', $latestLink, $matches);
            if (isset($matches[1]) && isset($matches[2])):
                $lat = $matches[1];
                $lng = $matches[2];
            ?>
            <h3>🗺️ Location on Map:</h3>
            <iframe 
                width="100%" 
                height="400" 
                frameborder="0" 
                style="border:0; border-radius: 10px;" 
                src="https://www.openstreetmap.org/export/embed.html?bbox=<?php echo $lng-0.01; ?>%2C<?php echo $lat-0.01; ?>%2C<?php echo $lng+0.01; ?>%2C<?php echo $lat+0.01; ?>&amp;layer=mapnik&amp;marker=<?php echo $lat; ?>%2C<?php echo $lng; ?>" 
                allowfullscreen>
            </iframe>
            
            <!-- Link ទៅកាន់ Google Maps -->
            <a href="<?php echo htmlspecialchars($latestLink); ?>" target="_blank" class="map-link">
                🗺️ បើកក្នុង Google Maps
            </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>