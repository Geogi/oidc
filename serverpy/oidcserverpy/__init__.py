from flask import Flask
from flask_cors import CORS

from oidcserverpy.api.v1 import v1_bp

app = Flask(__name__, static_folder=None)
app.register_blueprint(v1_bp, url_prefix='/v1')
CORS(app)

if __name__ == '__main__':
    app.run()
