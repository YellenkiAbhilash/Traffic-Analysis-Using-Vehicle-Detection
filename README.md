## 🚗 Vehicle Detection & Traffic Alert System
# Overview
This is a real-time vehicle detection and alert system built with Flask, OpenCV, and YOLOv8. The system captures video from two regions (e.g., Region A via webcam and Region B via video file), performs object detection to count vehicles, and displays a live stream via a web interface. It includes a simple vehicle tracking module and integrates with PHP scripts for emergency and traffic alerts.

# Features
- Real-time vehicle detection using YOLOv8
- Vehicle tracking with ID assignment
- Live video stream for two regions (Region A & B)
- Counts displayed on the stream
- Simple HTTP-based front end
- Integration with alert and emergency PHP back-end

# How to Run
1) Ensure coco.txt and b.mp4 are in the project directory.

2) Start the Flask server:
   ```
    python app.py
   ```
3) Start the front-end
   
4) open browser http://localhost:3000/


