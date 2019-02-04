# Copyright (c) 2014-2015, Erica Ehrhardt
# Copyright (c) 2016, Patrick Uiterwijk <patrick@puiterwijk.org>
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are met:
#
# * Redistributions of source code must retain the above copyright notice, this
#   list of conditions and the following disclaimer.
#
# * Redistributions in binary form must reproduce the above copyright notice,
#   this list of conditions and the following disclaimer in the documentation
#   and/or other materials provided with the distribution.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
# AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
# DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
# SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
# CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
# OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
# OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
import json
from base64 import b64decode
from functools import wraps
from flask import abort
from flask_oidc import OpenIDConnect


class OidcPlus(OpenIDConnect):
    def require_keycloak_role(self, client, role):
        """
        Function to check for a KeyCloak client role in JWT access token.
        This is intended to be replaced with a more generic 'require this value
        in token or claims' system, at which point backwards compatibility will
        be added.
        .. versionadded:: 1.5.0
        """

        def wrapper(view_func):
            @wraps(view_func)
            def decorated(*args, **kwargs):
                pre, tkn, post = self.get_access_token().split('.')
                access_token = json.loads(b64decode(tkn))
                if role in access_token['resource_access'][client]['roles']:
                    return view_func(*args, **kwargs)
                else:
                    return abort(403)

            return decorated

        return wrapper
