import requests as req
from hostaddr import HOSTADDR

def getUrl(hash: str) -> str:
    """ Get previously trimmed URL based on its hash """
    response = req.get(HOSTADDR + 'url/' + hash)
    return response.json()['url']
