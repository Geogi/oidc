from pathlib import Path

from flask import Flask
from flask_cors import CORS

from oidcserverpy.api.v1 import v1_bp
from oidcserverpy.auth import oidc

parent = Path(__file__).parent / '..'
app = Flask(__name__, static_folder=None)
app.config.from_json(parent / 'config.json')
app.config.from_json(parent / 'config_private.json')
app.config['OIDC_CLIENT_SECRETS'] = parent / 'client_secrets.json'
app.register_blueprint(v1_bp, url_prefix='/v1')
CORS(app)
oidc.init_app(app)

if __name__ == '__main__':
    app.run()
