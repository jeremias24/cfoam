from sqlalchemy import Column, Integer, Float
from app.database import db

class Sales(db.Model):
    __tablename__ = 'sales'

    id = Column(Integer, primary_key=True)
    product_id = Column(Integer)
    quantity = Column(Float)