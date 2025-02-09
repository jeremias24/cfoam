# models/__init__.py

from .base import Base  # Import Base from base.py
from .products import Product  # Use singular 'Product'
from .sales import Sales

__all__ = ['Base', 'Product', 'Sales']  # Add Base to __all__ if desired
