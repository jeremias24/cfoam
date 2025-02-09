from flask import Flask, request, jsonify
import tensorflow as tf
import numpy as np
from sqlalchemy import create_engine, func
from sqlalchemy.orm import sessionmaker
from models import Base, Product, Sales

app = Flask(__name__)

# Database setup
DATABASE_URL = "mysql+pymysql://root:password@db_host:cfoam-mysql/cfoam"
engine = create_engine(DATABASE_URL)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base.metadata.create_all(bind=engine)

# Load the trained model
model = tf.keras.models.load_model('/app/product_sales_model.keras')

@app.route('/predict', methods=['POST'])
def predict():
    db = SessionLocal()
    try:
        data = request.json
        product_id = data.get('product_id')
        
        product = db.query(Product).filter(Product.id == product_id).first()
        if not product:
            return jsonify({'error': 'Product not found'}), 404
        
        input_data = np.array([
            product.price,
            product.stock_quantity,
            product.category_id,
            int(product.is_active)
        ]).reshape(1, -1)
        
        prediction = model.predict(input_data)
        
        return jsonify({'prediction': float(prediction[0][0])})
    finally:
        db.close()

@app.route('/train', methods=['POST'])
def train():
    db = SessionLocal()
    try:
        products = db.query(Product).all()
        sales = db.query(Sales.product_id, func.sum(Sales.quantity).label('total_sales')) \
                  .group_by(Sales.product_id).all()
        
        sales_dict = {s.product_id: s.total_sales for s in sales}
        
        X = []
        y = []
        for product in products:
            X.append([
                product.price,
                product.stock_quantity,
                product.category_id,
                int(product.is_active)
            ])
            y.append(sales_dict.get(product.id, 0))
        
        X = np.array(X)
        y = np.array(y)
        
        model.fit(X, y, epochs=100, batch_size=32, verbose=1)
        model.save('/app/product_sales_model.keras')
        
        return jsonify({'message': 'Model trained and saved successfully'})
    finally:
        db.close()

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)