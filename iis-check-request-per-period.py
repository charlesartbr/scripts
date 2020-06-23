import glob
import codecs

for file in glob.glob('*/*2006*', recursive = True):

    total = 0
    r = 0

    f = codecs.open(file, "r", "utf-8")

    for line in f:

        total += 1

        if "2020-06" in line:
            r += 1

    f.close()

    if r > 1000:
        print(file + ": total=" + str(total) + ' - r=' + str(r) + ' %=' + ("%.2f" % (r / total * 100)))
