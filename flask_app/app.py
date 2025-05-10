import cv2
import pandas as pd
import time
from flask import Flask, Response, render_template_string
from ultralytics import YOLO
from tracker import Tracker
from flask_cors import CORS
# Initialize Flask app
app = Flask(__name__)
CORS(app)
# Load the YOLO model
model = YOLO('yolov8s.pt')

# Load class names
with open("coco.txt", "r") as my_file:
    data = my_file.read()
    class_list = data.split("\n")

# Initialize Tracker
tracker = Tracker()

# Video capture for Region A
cap_a = cv2.VideoCapture(0)
# Video capture for Region B
cap_b = cv2.VideoCapture("b.mp4")

# Global variable for vehicle count
current_vehicle_count_a = 0
current_vehicle_count_b = 0

def generate_frames(cap, region):
    global current_vehicle_count_a, current_vehicle_count_b
    count = 0

    while True:
        ret, frame = cap.read()
        if not ret:
            cap.set(cv2.CAP_PROP_POS_FRAMES, 0)  # Loop the video
            continue

        count += 1
        if count % 3 != 0:  # Skip frames to reduce processing load
            continue

        frame = cv2.resize(frame, (1020, 500))

        # Perform inference with YOLO
        results = model.predict(frame)
        a = results[0].boxes.data
        px = pd.DataFrame(a).astype("float")

        # Reset count for the current frame
        current_vehicle_count = 0
        bbox_list = []

        # Process detection results
        for index, row in px.iterrows():
            x1, y1, x2, y2, d = int(row[0]), int(row[1]), int(row[2]), int(row[3]), int(row[5])
            c = class_list[d]

            if 'car' in c:
                bbox_list.append([x1, y1, x2, y2])  # Append bounding box coordinates
                current_vehicle_count += 1  # Count vehicles

        # Update bounding boxes for tracking
        bbox_id = tracker.update(bbox_list)

        # Draw bounding boxes and vehicle count on the frame
        for bbox in bbox_id:
            x3, y3, x4, y4, id = bbox
            cv2.rectangle(frame, (x3, y3), (x4, y4), (0, 0, 255), 2)

        # Update vehicle count based on region
        if region == "a":
            current_vehicle_count_a = current_vehicle_count
        else:
            current_vehicle_count_b = current_vehicle_count

        # Display the vehicle count
        cv2.putText(frame, f'Vehicle Count: {current_vehicle_count}', (60, 40), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)

        # Convert frame to JPEG for streaming
        ret, buffer = cv2.imencode('.jpg', frame)
        frame = buffer.tobytes()

        # Send vehicle count data as a custom HTTP header
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n'
               b'X-Vehicle-Count: ' + str(current_vehicle_count).encode() + b'\r\n\r\n' + frame + b'\r\n')

@app.route('/video_feed_a')
def video_feed_a():
    return Response(generate_frames(cap_a, "a"), mimetype='multipart/x-mixed-replace; boundary=frame')

@app.route('/video_feed_b')
def video_feed_b():
    return Response(generate_frames(cap_b, "b"), mimetype='multipart/x-mixed-replace; boundary=frame')

# HTML template to display the video stream
html_template = '''
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Detection Stream</title>
</head>
<body>
    <h1>Vehicle Detection Video Stream</h1>
    <h2>Region A</h2>
    <img src="/video_feed_a" width="1020" height="500">
    <h2>Region B</h2>
    <img src="/video_feed_b" width="1020" height="500">
</body>
</html>
'''

# Flask route for HTML template
@app.route('/')
def index():
    return render_template_string(html_template)

if __name__ == "__main__":
    app.run(use_reloader=False)
