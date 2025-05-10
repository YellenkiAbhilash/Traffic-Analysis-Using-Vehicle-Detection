<!DOCTYPE html>
<html>
<head>
    <title>Search and Graph Application</title>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head>
<body>
    <h1>Search the area</h1>
    <form id="search-form">
        <input type="text" id="region-input" placeholder="Enter region">
        <button type="submit">Search</button>
    </form>
    <div id="video-container" style="margin-top: 20px;">
        <h2>Video Stream</h2>
        <img id="video-stream" src="" width="1020" height="500" style="display:none;">
    </div>
    <div id="graph"></div>
    <button onclick="redirectToLogin()">Agent</button>

    <script>
        document.getElementById('search-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            
            const regionInput = document.getElementById('region-input').value.trim().toLowerCase();
            const videoStream = document.getElementById('video-stream');

            // Determine the video source based on the input
            if (regionInput.replace(/\s+/g, '') === 'regiona') {
                videoStream.src = 'http://localhost:5000/video_feed_a'; // Update with your Region A video feed URL
                videoStream.style.display = 'block'; // Show the video element
            } else if (regionInput.replace(/\s+/g, '') === 'regionb') {
                videoStream.src = 'http://localhost:5000/video_feed_b'; // Update with your Region B video feed URL
                videoStream.style.display = 'block'; // Show the video element
            } else {
                alert('Region not found. Please enter a valid region.');
                videoStream.style.display = 'none'; // Hide the video element
            }
        });

        function redirectToLogin() {
            window.location.href = 'login_form.php';
        }
    </script>
</body>
</html>
