from sqlalchemy import Column, Integer, Float, Boolean
from app.database import db

class Product(db.Model):
    __tablename__ = 'products'

    id = Column(Integer, primary_key=True)
    size = Column(Float)
    category_id = Column(Integer)
    is_active = Column(Boolean)