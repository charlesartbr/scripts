import sys, urllib.request, json

apiEndpoint = 'https://eucatex.api.aatb.com.br/catalog/'

print('Categoria: ')

with urllib.request.urlopen(apiEndpoint + 'category?language=pt') as url:

    data = json.loads(url.read().decode().replace('<strong>', '').replace('</strong>', ''))

    for category in data:
        print(category['categoryId'], '-', category['name'])

sys.exit()
