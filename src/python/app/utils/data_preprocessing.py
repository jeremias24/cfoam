import numpy as np

def preprocess_data(X, y):
    mask = np.isfinite(X).all(axis=1) & np.isfinite(y)
    X_clean = X[mask]
    y_clean = y[mask]
    
    X_mean = np.mean(X_clean, axis=0)
    X_std = np.std(X_clean, axis=0)
    X_normalized = (X_clean - X_mean) / (X_std + 1e-8)
    
    return X_normalized, y_clean