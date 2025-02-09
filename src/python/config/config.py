import os

class Config:
    SECRET_KEY = os.environ.get('SECRET_KEY') or 'you-will-never-guess'
    DATABASE_URL = os.environ.get('DATABASE_URL') or "mysql+pymysql://root:password@cfoam-mysql:3306/cfoam"
    SQLALCHEMY_TRACK_MODIFICATIONS = False
    MODEL_PATH = './models/product_sales_model.keras'