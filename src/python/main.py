from flask import Flask
from app.database import db
from app.routes.main import bp
from flask_cors import CORS

def create_app():
    app = Flask(__name__)
    app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:password@cfoam-mysql:3306/cfoam'
    app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
    # Set upload folder configuration
    app.config['UPLOAD_FOLDER'] = './static/images/defects'
    CORS(app, resources={r"/*": {"origins": "http://127.0.0.1:3000"}})

    db.init_app(app)

    app.register_blueprint(bp)

    return app

if __name__ == '__main__':
    app = create_app()
    app.run(host='0.0.0.0', port=5000)