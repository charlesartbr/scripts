import sys, os, urllib.request, json

apiEndpoint = 'https://eucatex.api.aatb.com.br/catalog/'

# lista de categorias
print('SELECIONE A CATEGORIA:')

with urllib.request.urlopen(apiEndpoint + 'category?language=pt') as url:

    data = json.loads(url.read().decode())

    for category in data:
        print(category['categoryId'], '-', category['name'])


# seleciona a categoria
id = int(input())

category = next(filter(lambda x: x['categoryId'] == id, data))

print('\nINICIANDO CAPTURA DA CATEGORIA:', category['name'])


# cria o arquivo
if os.path.isdir('eucatex-export-texts') == False:
    os.mkdir('eucatex-export-texts')

f = open('eucatex-export-texts/' + category['name'] + '.html', 'w+')


# iterage nas categorias recursivamente exibindo os produtos
def print_products(categories):

    for category in categories:
        print(category['categoryId'], '-', category['name'])

        if 'subCategories' in category:
            print_products(category['subCategories'])


# retorna a lista de produtos
with urllib.request.urlopen(apiEndpoint + 'category?language=pt&products=true&categoryId=' + str(id)) as url:

    data = json.loads(url.read().decode())

    print_products(data)


# sai do programa
sys.exit()