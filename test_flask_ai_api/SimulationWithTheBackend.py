import cv2
import numpy as np
import joblib
from flask import Flask, request, jsonify
from werkzeug.utils import secure_filename
import os

# --- Preprocessing steps (must match training) ---
def apply_clahe(image):
    lab = cv2.cvtColor(image, cv2.COLOR_BGR2LAB)
    l, a, b = cv2.split(lab)
    clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
    l_clahe = clahe.apply(l)
    lab_clahe = cv2.merge((l_clahe, a, b))
    return cv2.cvtColor(lab_clahe, cv2.COLOR_LAB2BGR)

def segment_leaf(image):
    image = cv2.resize(image, (512, 512))
    image = apply_clahe(image)
    mask = np.zeros(image.shape[:2], np.uint8)
    bgd_model = np.zeros((1, 65), np.float64)
    fgd_model = np.zeros((1, 65), np.float64)
    rect = (50, 50, 450, 450)
    cv2.grabCut(image, mask, rect, bgd_model, fgd_model, 5, cv2.GC_INIT_WITH_RECT)
    mask = np.where((mask == 2) | (mask == 0), 0, 1).astype('uint8')
    return image * mask[:, :, np.newaxis]

def extract_color_histogram(image):
    image_hsv = cv2.cvtColor(image, cv2.COLOR_BGR2HSV)
    hist_features = []
    for i in range(3):
        hist_rgb = cv2.calcHist([image], [i], None, [256], [0, 256])
        hist_hsv = cv2.calcHist([image_hsv], [i], None, [256], [0, 256])
        hist_rgb = cv2.normalize(hist_rgb, hist_rgb).flatten()
        hist_hsv = cv2.normalize(hist_hsv, hist_hsv).flatten()
        hist_features.extend(hist_rgb)
        hist_features.extend(hist_hsv)
    return np.array(hist_features)

def extract_glcm_features(image):
    from skimage.feature import graycomatrix, graycoprops
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    glcm = graycomatrix(gray, distances=[1], angles=[0], levels=256, symmetric=True, normed=True)
    return np.array([
        graycoprops(glcm, 'contrast')[0, 0],
        graycoprops(glcm, 'energy')[0, 0],
        graycoprops(glcm, 'correlation')[0, 0],
        graycoprops(glcm, 'homogeneity')[0, 0]
    ])

def extract_lbp_texture(image):
    from skimage.feature import local_binary_pattern
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    lbp = local_binary_pattern(gray, 8, 1, method='uniform')
    hist, _ = np.histogram(lbp.ravel(), bins=np.arange(0, 11), range=(0, 10))
    return hist.astype("float") / hist.sum()

def combine_features(image):
    return np.hstack([
        extract_color_histogram(image),
        extract_glcm_features(image),
        extract_lbp_texture(image)
    ])

# --- Classifier Function ---
def classifier(image_path):
    # Step 1: Dummy plant type classification
    #plant_type = "grape" # later this will be replaced by a real classifier
    filename = os.path.basename(image_path)
    name_without_extension = os.path.splitext(filename)[0] # split path from extension
    plant_type = name_without_extension.split('_')[0] #get name after split

    # Step 2: Load correct model components for that plant
    bundle_path = f"C:/wamp64/www/MyGradProject/GradProject-master/test_flask_ai_api/Bundle/Pepper_bundle.pkl"

    bundle = joblib.load(bundle_path)
    model = bundle["model"]
    scaler = bundle["scaler"]
    pca = bundle["pca"]
    label_map = bundle["label_map"]
    # Step 3: Load image and apply preprocessing
    image = cv2.imread(image_path)
    if image is None:
        return "Invalid image path."

    segmented = segment_leaf(image)
    features = combine_features(segmented)
    features_scaled = scaler.transform([features])
    features_pca = pca.transform(features_scaled)

    # Step 4: Predict class
    predicted_label = model.predict(features_pca)[0]
    class_name = label_map[predicted_label]

    return class_name

app = Flask(__name__)

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({'error': 'No image provided'}), 400

    file = request.files['image']
    if file.filename == '':
        return jsonify({'error': 'Empty filename'}), 400

    try:
       
        UPLOAD_FOLDER = 'temp_images'
        os.makedirs(UPLOAD_FOLDER, exist_ok=True)

        filename = secure_filename(file.filename)
        filepath = os.path.join(UPLOAD_FOLDER, filename)

        print(f"Saving image to: {filepath}")  # ⬅️ Debug

        file.save(filepath)

        print(f"Running classifier on: {filepath}")  # ⬅️ Debug

        result = classifier(filepath)

        print(f"Classifier result: {result}")  # ⬅️ Debug

        return jsonify({'result': result})

    except Exception as e:
        print(f"Error occurred: {e}")  # ⬅️ Debug error
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)


