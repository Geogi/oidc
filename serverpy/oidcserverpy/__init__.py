from flask import Flask

from oidcserverpy.api.v1 import v1_bp

app = Flask(__name__, static_folder=None)
app.register_blueprint(v1_bp, url_prefix='/v1')

if __name__ == '__main__':
    app.run()
