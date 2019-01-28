from setuptools import setup, find_packages

setup(
    name='oidcserverpy',
    version='1.0.0',
    description='OpenID Connect Test Server in Python',
    author='Paul Blouët',
    author_email='paul.blouet@ajeel.fr',
    packages=find_packages(),
    install_requires=['flask', 'flask-cors', 'flask-oidc', 'python-dotenv'],
    package_data={'': ['../api/api.yaml']},
    include_package_data=True,
)
