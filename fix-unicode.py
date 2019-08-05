import codecs

html = open('teste2.html', "r")
f = codecs.open('teste3.html', "w+", "utf-8")

for line in html:
	f.write(line)
	
f.close()
html.close()
