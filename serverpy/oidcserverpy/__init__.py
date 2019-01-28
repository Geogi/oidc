from flask import Flask
from flask_cors import CORS

from oidcserverpy.api.v1 import v1_bp
from oidcserverpy.auth import oidc

app = Flask(__name__, static_folder=None)
app.register_blueprint(v1_bp, url_prefix='/v1')
CORS(app)
oidc.init_app(app)

if __name__ == '__main__':
    app.run()
