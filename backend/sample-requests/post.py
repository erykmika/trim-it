import requests as req
from hostaddr import HOSTADDR

def trimUrl(url: str) -> str:
    """ Shorten the URL, get its hash for requesting the original form """
    response = req.post(HOSTADDR + 'hash', json={'url' : url})
    return response.json()['hash']
