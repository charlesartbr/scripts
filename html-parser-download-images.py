import requests
from bs4 import BeautifulSoup

with open('scripts.sql', 'w') as sql:

	url = 'http://domain.com.br'

	r = requests.get(url)

	html = BeautifulSoup(r.text, 'html.parser')
	items = html.body.find('div', attrs={ 'id': 'someid' }).find_all('a')

	x = 0
	
	for item in items:

		x = x + 1
		t = item.get('data-title').split('|')[0].strip()
		u = url + item.get('href')
		
		r = requests.get(u)
		
		with open('galeria/' + str(x) + '.jpg', 'wb') as img:
			img.write(r.content)
	
		sql.write("INSERT Galeria (GaleriaId, Titulo) VALUES(" + str(x) + ", '" + t + "')")

		print((t, u))
