from flask import Flask
from app.database import db
from app.routes.main import bp

def create_app():
    app = Flask(__name__)
    app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:password@cfoam-mysql:3306/cfoam'
    app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

    db.init_app(app)

    app.register_blueprint(bp)

    return app

if __name__ == '__main__':
    app = create_app()
    app.run(host='0.0.0.0', port=5000)