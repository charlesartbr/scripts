import codecs
import csv
from PIL import Image
from collections import defaultdict

sizes = defaultdict(list)

sizes['Imprensa'] = (335, 1414)
sizes['Executivo'] = (364, 1252)

with codecs.open('mailing.csv', 'r', 'utf-8') as csv_file:

	csv_reader = csv.reader(csv_file, delimiter=';')
	
	for row in csv_reader:

		img = row[2].strip() + ' - ' + row[1].strip().replace('/', '-') + '.png'
	
		qrcode = Image.open('qrcode/' + img)
		
		bg = Image.open(row[0] + '.png')
		
		new_img = Image.new('RGB', bg.size)
		
		new_img.paste(bg, (0, 0))
		new_img.paste(qrcode, sizes[row[0]])
		
		new_img.save('convites/' + img)
		
		print(row[2].strip() + ' - ' + row[1].strip())
    
print('pronto...')
