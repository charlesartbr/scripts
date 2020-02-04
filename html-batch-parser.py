import requests
from bs4 import BeautifulSoup

url = 'http://domain.com.br/pg/'


for i in range(1, 10):

    r = requests.get(url + str(i))

    if r.status_code == 200:

        html = BeautifulSoup(r.text, 'html.parser')
        artigo = html.body.find('div', attrs={ 'id': 'somid' })

        titulo = artigo.find('h2').text
        descricao = artigo.find('h6').text
        img = artigo.find('div', attrs={ 'class': 'someclass' }).find('img')['src']
        texto = artigo.find_all('p')

        print(i, titulo, descricao, img, texto[0])

    break
