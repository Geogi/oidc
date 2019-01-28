from flask import Blueprint

from oidcserverpy.auth import oidc

v1_bp = Blueprint('v1', __name__)


@v1_bp.route('/public')
def public():
    return '', 204


@v1_bp.route('/authenticated')
@oidc.require_login
def authenticated():
    return '', 204


@v1_bp.route('/authorized')
@oidc.require_login
def authorized():
    return '', 204
