import os
import joblib
import numpy as np
import cv2
from flask import Flask, Blueprint, request, jsonify, render_template
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import StandardScaler
from sklearn.pipeline import make_pipeline
from sklearn.exceptions import NotFittedError
from werkzeug.utils import secure_filename

# Initialize Flask app
app = Flask(__name__)
bp = Blueprint('main', __name__)

# Paths - Adjusted for Your Container Structure
BASE_DIR = "/app/app"
MODEL_DIR = os.path.join(BASE_DIR, "models")
MODEL_PATH = os.path.join(MODEL_DIR, "defect_classifier.pkl")
TRAINING_DATA_PATH = os.path.join(MODEL_DIR, "training_samples")
UPLOAD_FOLDER = os.path.join(BASE_DIR, "uploads")

# Ensure directories exist
os.makedirs(MODEL_DIR, exist_ok=True)
os.makedirs(TRAINING_DATA_PATH, exist_ok=True)
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Allowed file extensions
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'bmp', 'webp'}

# **Fix: Define extract_features BEFORE using it in train_model**
def extract_features(original_img, edges):
    """Extracts features from an image for defect classification."""
    hsv = cv2.cvtColor(original_img, cv2.COLOR_BGR2HSV)
    avg_saturation = np.mean(hsv[:, :, 1])
    avg_brightness = np.mean(hsv[:, :, 2])
    contrast = np.std(cv2.cvtColor(original_img, cv2.COLOR_BGR2GRAY))
    edge_density = np.sum(edges) / edges.size
    return np.array([avg_saturation, avg_brightness, contrast, edge_density]).reshape(1, -1)

def train_model():
    """Train defect detection model properly."""
    training_data, labels = [], []

    if not os.listdir(TRAINING_DATA_PATH):  # Ensure training images exist
        print("⚠ No training images found! Skipping training.")
        return make_pipeline(StandardScaler(), RandomForestClassifier(n_estimators=100))  # Return untrained model

    label_mapping = {}  # Dictionary to store label-to-int mapping

    for filename in os.listdir(TRAINING_DATA_PATH):
        img_path = os.path.join(TRAINING_DATA_PATH, filename)
        img = cv2.imread(img_path)
        if img is None:
            print(f"❌ Warning: Failed to load {filename}. Skipping this file.")
            continue  # Skip this image

        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        edges = cv2.Canny(gray, 50, 150)

        features = extract_features(img, edges)
        label = filename.split("_")[0]  # Extract label from filename

        # **Fix: Convert labels to numeric IDs**
        if label not in label_mapping:
            label_mapping[label] = len(label_mapping) + 1  # Assign an ID to each unique label
        labels.append(label_mapping[label])
        
        training_data.append(features.flatten())

    if len(training_data) == 0:  # **Fix: Prevent crash if no valid images are loaded**
        print("❌ No valid training images found! Skipping training.")
        return make_pipeline(StandardScaler(), RandomForestClassifier(n_estimators=100))  # Return untrained model

    training_data = np.array(training_data)
    labels = np.array(labels)

    # **Fix: Train StandardScaler before RandomForest**
    scaler = StandardScaler()
    training_data = scaler.fit_transform(training_data)  # Ensure StandardScaler is fitted

    # Train and save the model
    model = make_pipeline(scaler, RandomForestClassifier(n_estimators=100))
    model.fit(training_data, labels)
    joblib.dump(model, MODEL_PATH)
    print("✅ Model trained successfully and saved!")

    return model

def load_or_train_model():
    """Load trained model or train a new one if missing."""
    global defect_model  # Ensure it's global

    if os.path.exists(MODEL_PATH):
        print("✅ Loading trained model...")
        defect_model = joblib.load(MODEL_PATH)
    else:
        print("⚠ No trained model found. Training now...")
        defect_model = train_model()

    return defect_model

# Ensure model is trained before app starts
defect_model = load_or_train_model()

def allowed_file(filename):
    """Check if uploaded file has an allowed extension."""
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@bp.route('/')
def index():
    """Render the file upload page."""
    return render_template('upload.html')

@bp.route('/analysis', methods=['POST'])
def upload_files():
    """Handles file upload and defect analysis."""
    if not request.files:
        return jsonify({"error": "No files received"}), 400

    uploaded_files = request.files.to_dict(flat=False)
    results = []
    defects = {}

    for key, files in uploaded_files.items():
        for file in files:
            if file.filename == '':
                continue
            if file and allowed_file(file.filename):
                filename = secure_filename(file.filename)
                filePath = os.path.join(app.config['UPLOAD_FOLDER'], filename)
                file.save(filePath)

                # Process image and analyze defects
                analysis = process_image(filePath)

                # **Fix: Remove zero-count defects from results**
                non_zero_defects = {k: v for k, v in analysis["defectPredictions"].items() if v > 0}

                results.append({
                    "originalImage": filePath,
                    "processedImage": analysis["processedImagePath"],
                    "defectPredictions": non_zero_defects,
                    "detectedDefectsImage": analysis["detectedDefectsImagePath"]
                })

                # **Fix: Aggregate non-zero defects**
                for defect, count in non_zero_defects.items():
                    defects[defect] = defects.get(defect, 0) + count

    return jsonify({ "imagesResults": results, "defects": defects }), 200

def process_image(image_path):
    """Processes image and predicts defects."""
    img = cv2.imread(image_path)

    if img is None:
        print(f"❌ Error: Failed to load image at {image_path}")
        return {"error": "Failed to load image"}

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # **Fix: Lower Canny Edge Detection Thresholds**
    edges = cv2.Canny(gray, 30, 100)

    processedPath = os.path.join(app.config['UPLOAD_FOLDER'], 'processed_' + os.path.basename(image_path))
    cv2.imwrite(processedPath, edges)

    # **Fix: Save debug images**
    cv2.imwrite(image_path.replace(".jpg", "_gray.jpg"), gray)
    cv2.imwrite(image_path.replace(".jpg", "_edges.jpg"), edges)

    print(f"✅ Processed image saved at {processedPath}")

    predictions = analyze_defects(edges)

    detectedDefectsImagePath = processedPath.replace("processed_", "detected_defects_")
    visualize_defects(img, detectedDefectsImagePath, predictions)

    return {
        "processedImagePath": processedPath,
        "defectPredictions": predictions,
        "detectedDefectsImagePath": detectedDefectsImagePath
    }

def predict_defects(features):
    """Predict defects using the trained model."""
    global defect_model  # ✅ Ensure it's declared at the start

    labels = ["scratches", "dents", "colorMismatch", "peeling", "rust", "stains", "oxidation", "overspray"]

    try:
        features = defect_model.named_steps['standardscaler'].transform(features)
        predictions = defect_model.named_steps['randomforestclassifier'].predict(features)

        # **Fix: Remove defects with a count of zero**
        defects = {label: max(int(pred), 0) for label, pred in zip(labels, predictions)}
        return {label: count for label, count in defects.items() if count > 0}
    
    except NotFittedError:
        print("⚠ Model is not trained. Training now...")
        defect_model = train_model()
        predictions = defect_model.named_steps['randomforestclassifier'].predict(features)

        defects = {label: max(int(pred), 0) for label, pred in zip(labels, predictions)}
        return {label: count for label, count in defects.items() if count > 0}

def analyze_defects(edges):
    """Analyzes defects in the processed image using contours."""
    
    # Find contours from the edge-detected image
    contours, _ = cv2.findContours(edges, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    scratchContours = []
    dentContours = []
    peelingContours = []
    rustContours = []
    stainContours = []
    oversprayContours = []

    for contour in contours:
        x, y, w, h = cv2.boundingRect(contour)
        aspect_ratio = float(w) / h if h != 0 else 0

        # **Fix: Adjust thresholds for better defect detection**
        if w > 10 and h > 10:  # Any small detected region
            if aspect_ratio > 3:  # Long and narrow = scratches
                scratchContours.append(contour)
            elif aspect_ratio < 1.5:  # Squarish = dents
                dentContours.append(contour)
            else:  # Large patches
                peelingContours.append(contour)
                rustContours.append(contour)

    # **Fix: Exclude zero-count defects**
    detected_defects = {
        "scratches": len(scratchContours),
        "dents": len(dentContours),
        "peeling": len(peelingContours),
        "rust": len(rustContours),
        "stains": len(stainContours),
        "overspray": len(oversprayContours)
    }

    return {label: count for label, count in detected_defects.items() if count > 0}

def visualize_defects(image, output_path, defects):
    """Draws detected defects on the image."""
    colors = {
        "scratches": (0, 0, 255),      # Red
        "dents": (255, 0, 0),          # Blue
        "peeling": (0, 255, 255),      # Yellow
        "rust": (165, 42, 42),         # Brown
        "stains": (0, 255, 0),         # Green
        "oxidation": (128, 128, 128),  # Gray
        "overspray": (255, 165, 0)     # Orange
    }

    for defect, count in defects.items():
        count = int(count)  # ✅ Convert to integer before checking
        if count > 0:
            cv2.putText(image, f"{defect}: {count}", (10, 50 + 30 * list(defects.keys()).index(defect)),
                        cv2.FONT_HERSHEY_SIMPLEX, 0.8, colors[defect], 2)

    cv2.imwrite(output_path, image)

@bp.route('/train', methods=['POST'])
def manual_train():
    """Manually triggers training."""
    global defect_model
    defect_model = train_model()
    return jsonify({"message": "Model trained successfully"}), 200

# Register blueprint and start the Flask app
app.register_blueprint(bp)

if __name__ == '__main__':
    print("🚀 Starting Flask Application...")
    app.run(host="0.0.0.0", port=5000, debug=True)
