import os
import numpy as np
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.callbacks import ReduceLROnPlateau
from app.utils.data_preprocessing import preprocess_data
from config.config import Config

def create_new_model():
    model = keras.Sequential([
        keras.layers.Dense(64, activation='relu', input_shape=(3,)),
        keras.layers.Dense(32, activation='relu'),
        keras.layers.Dense(1)
    ])
    optimizer = Adam(learning_rate=0.001, clipnorm=1.0)
    model.compile(optimizer=optimizer, loss='mse')
    return model

def load_or_create_model():
    if os.path.exists(Config.MODEL_PATH):
        return keras.models.load_model(Config.MODEL_PATH)
    return create_new_model()

def train_model(X, y):
    X_preprocessed, y_preprocessed = preprocess_data(X, y)
    model = create_new_model()
    reduce_lr = ReduceLROnPlateau(monitor='loss', factor=0.2, patience=5, min_lr=0.00001)
    history = model.fit(X_preprocessed, y_preprocessed, epochs=100, batch_size=32, callbacks=[reduce_lr], verbose=1)
    
    if not np.isnan(history.history['loss'][-1]):
        model.save(Config.MODEL_PATH)
        return True
    return False

def predict(model, input_data):
    return model.predict(input_data)