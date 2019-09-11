import qrcode
import codecs
import csv

with codecs.open('mailing.csv', 'r', 'utf-8') as csv_file:

	csv_reader = csv.reader(csv_file, delimiter=';')
	
	for row in csv_reader:

		qr = qrcode.QRCode(
			version=1,
			error_correction=qrcode.constants.ERROR_CORRECT_L,
			box_size=10,
			border=4,
		)
		qr.add_data(row[2].strip() + ' | ' + row[1].strip())
		qr.make(fit=True)

		img = qr.make_image(fill_color="black", back_color="white")
		
		img.save('qrcode/' + row[2].strip() + ' - ' + row[1].replace('/', '-').strip() + '.png')
		
		print(row[2] + ' - ' + row[1])

    
print('pronto...')
