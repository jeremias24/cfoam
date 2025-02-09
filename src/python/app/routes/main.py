from flask import Blueprint, request, jsonify, current_app, render_template
from sqlalchemy import func
from app.models.products import Product
from app.models.sales import Sales
from app.services.model_service import load_or_create_model, train_model, predict
from app.database import db
import numpy as np
import cv2  # OpenCV import
import os
from werkzeug.utils import secure_filename
import logging as log

bp = Blueprint('main', __name__)

# Set up logging
log.basicConfig(level=log.DEBUG)

# Load or create model
model = load_or_create_model()

# Allowed file extensions
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'bmp', 'webp'}

# Helper function to check allowed file extensions
def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@bp.route('/')
def index():
    return render_template('upload.html')

@bp.route('/get', methods=['GET'])
def test():
    return jsonify({"message": "Connected successfully to Python!"}), 200

@bp.route('/analysis', methods=['POST'])
def upload_files():
    if not request.files:
        return jsonify({"error": "No files received"}), 400

    uploaded_files = request.files.to_dict(flat=False)
    results = []
    defects = {
        "scratches": 0,
        "dents": 0
    }  # Initialize defects object

    upload_folder = current_app.config['UPLOAD_FOLDER']
    os.makedirs(upload_folder, exist_ok=True)

    for key, files in uploaded_files.items():
        for file in files:
            if file.filename == '':
                continue

            if file and allowed_file(file.filename):
                filename = secure_filename(file.filename)
                filePath = os.path.join(upload_folder, filename)  # Camel Case
                file.save(filePath)

                # Process the image using OpenCV
                analysis = process_image(filePath)

                results.append({
                    "originalImage": filePath,
                    "processedImage": analysis["processedImagePath"],  # Updated Key
                    "scratchCount": analysis["scratchCount"],
                    "dentCount": analysis["dentCount"],  # New Key
                    "detectedScratchImage": analysis["detectedScratchImagePath"]  # Updated Key
                })

                # Update the defects object
                defects["scratches"] += analysis["scratchCount"]
                defects["dents"] += analysis["dentCount"]

    return jsonify({
        "imagesResults": results,
        "defects": defects
    }), 200

def process_image(image_path):
    # Read the image
    img = cv2.imread(image_path)

    # Convert to grayscale
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # Perform edge detection
    edges = cv2.Canny(gray, 50, 150)

    # Save the processed image
    upload_folder = current_app.config['UPLOAD_FOLDER']
    processedPath = os.path.join(upload_folder, 'processed_' + os.path.basename(image_path))  # Camel Case
    cv2.imwrite(processedPath, edges)

    # Analyze defects
    analysis = analyze_defects(processedPath)

    # Add processed image path to the analysis result
    analysis["processedImagePath"] = processedPath  # Updated Key
    return analysis

def analyze_defects(processed_image_path):
    # Load the processed image (edge-detected)
    edges = cv2.imread(processed_image_path, cv2.IMREAD_GRAYSCALE)

    # Find contours from the edge-detected image
    contours, _ = cv2.findContours(edges, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    scratchContours = []  # Camel Case
    dentContours = []  # Camel Case

    for contour in contours:
        # Calculate the bounding box of the contour
        x, y, w, h = cv2.boundingRect(contour)
        aspectRatio = float(w) / h if h != 0 else 0  # Camel Case

        # Define thresholds for identifying scratches and dents
        if aspectRatio > 5 and w > 50:  # Long and narrow contours (scratches)
            scratchContours.append(contour)
        elif aspectRatio <= 5 and w > 20:  # Wide and short contours (dents)
            dentContours.append(contour)

    # Create a copy of the processed image to visualize the results
    resultImage = cv2.cvtColor(edges, cv2.COLOR_GRAY2BGR)  # Camel Case
    cv2.drawContours(resultImage, scratchContours, -1, (0, 0, 255), 2)  # Red for scratches
    cv2.drawContours(resultImage, dentContours, -1, (255, 0, 0), 2)  # Blue for dents

    # Save the result image with detected defects highlighted
    detectedScratchImagePath = processed_image_path.replace("processed_", "detected_scratch_")  # Camel Case
    cv2.imwrite(detectedScratchImagePath, resultImage)

    # Return analysis results
    return {
        "scratchCount": len(scratchContours),
        "dentCount": len(dentContours),  # Count of dents
        "detectedScratchImagePath": detectedScratchImagePath  # Updated Key
    }

@bp.route('/verify-token', methods=['POST'])
def verify_token():
    # Example endpoint logic
    data = request.json
    return jsonify({"message": "Token verified successfully", "data": data}), 200
