<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Head Officer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function updateStream() {
            const regionSelector = document.getElementById("region-selector");
            const selectedRegion = regionSelector.value;
            const videoContainer = document.getElementById("video-container");
            
            if (selectedRegion === "region_a") {
                videoContainer.innerHTML = '<h2>Region A - Vehicle Detection</h2><img src="http://localhost:5000/video_feed_a" width="1020" height="500">';
            } else if (selectedRegion === "region_b") {
                videoContainer.innerHTML = '<h2>Region B - Vehicle Detection</h2><img src="http://localhost:5000/video_feed_b" width="1020" height="500">';
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Traffic Head Officer Dashboard</h1>
        <select id="region-selector" onchange="updateStream()">
            <option value="">Select Region</option>
            <option value="region_a">Region A</option>
            <option value="region_b">Region B</option>
        </select>
    </header>
    <main>
        <section id="video-container">
            <h2>Select a region to view the video stream.</h2>
        </section>
    </main>
</body>
</html>