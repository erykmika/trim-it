from get import getUrl
from post import trimUrl

""" Trim or retrieve URLs. Perform GET/POST requests. """
while True:
    print('1. Retrieve a URL')
    print('2. Trim a URL')
    choice = input("1/2: ").strip()
    if choice == '1':
        try:
            hash = input('Specify the hash: ').strip()
            print('URL: ' + getUrl(hash))
        except Exception as e:
            print('Could not find the URL ')
    elif choice == '2':
        try:
            url = input('URL to be trimmed: ').strip()
            print('Hash: ' + trimUrl(url))
        except Exception as e:
            print('Could not trim the URL ')
    else:
        break
