from flask import Blueprint

v1_bp = Blueprint('v1', __name__)


@v1_bp.route('/public')
def public():
    return ''


@v1_bp.route('/authenticated')
def authenticated():
    return ''


@v1_bp.route('/authorized')
def authorized():
    return ''
