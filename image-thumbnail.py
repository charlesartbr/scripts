import os
from PIL import Image

for i in range(1, 261):

	path = 'galeria/' + str(i) + '.jpg'
	
	if os.path.isfile(path):
	
	   img = Image.open(path)
	   
	   img.thumbnail((320, 240))
	   img.save('galeria/' + str(i) + '-thumb.jpg')
	   
	   print(path)
