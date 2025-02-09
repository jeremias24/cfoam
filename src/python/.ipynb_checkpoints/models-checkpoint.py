from sqlalchemy import Column, Integer, Float, Boolean, String
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class Product(Base):
    __tablename__ = "products"

    id = Column(Integer, primary_key=True, index=True)
    price = Column(Float)
    stock_quantity = Column(Integer)
    category_id = Column(Integer)
    is_active = Column(Boolean)

class Sales(Base):
    __tablename__ = "sales"

    id = Column(Integer, primary_key=True, index=True)
    product_id = Column(Integer)
    quantity = Column(Integer)