import sys, os, urllib.request, json, re, codecs

# endpoint da API
apiEndpoint = 'https://eucatex.api.aatb.com.br/catalog/'


# remove HTML de uma string
def strip_html(text):
    return re.sub('<[^<]+?>', '', text)


# lista de categorias
print('SELECIONE A CATEGORIA:')

with urllib.request.urlopen(apiEndpoint + 'category?language=pt') as url:

    data = json.loads(url.read().decode())

    for category in data:
        print(category['categoryId'], '-', strip_html(category['name']))


# seleciona a categoria
id = int(input())

category = next(filter(lambda x: x['categoryId'] == id, data))

print('\nINICIANDO CAPTURA DA CATEGORIA:', category['name'])


# cria o arquivo
if os.path.isdir('eucatex-export-texts') == False:
    os.mkdir('eucatex-export-texts')

f = codecs.open('eucatex-export-texts/' + strip_html(category['name']) + '.html', 'w+', "utf-8")
f.write('<html><body>')

# iterage nas categorias recursivamente exibindo os produtos
def print_products(categories, dept = 0):

    dept += 1

    for category in categories:

        f.write('<h' + str(dept) + '>' + category['name'] + '</h' + str(dept) + '>')

        print('\nCategoria:', strip_html(category['name']))

        # recursivo nas subcategorias
        if 'subCategories' in category:
            print_products(category['subCategories'], dept)

        # lista os produtos
        if 'products' in category:
            for product in category['products']:
                print('Produto:', strip_html(product['name']))
                f.write('<h' + str(dept + 1) + '>' + product['name'] + '</h' + str(dept + 1) + '>')


# retorna a lista de produtos
with urllib.request.urlopen(apiEndpoint + 'category?language=pt&products=true&categoryId=' + str(id)) as url:

    data = json.loads(url.read().decode())

    print_products(data)

# fecha o arquivo
f.write('</body></html>')
f.close()

# sai do programa
sys.exit()