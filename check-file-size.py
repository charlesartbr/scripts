import glob
import os

limit = 1024 * 50000 # 50 Mb

for file in glob.glob('**', recursive = True):

    size = os.path.getsize(file)

    if size > limit:

        print(file, size)
