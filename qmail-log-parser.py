from __future__ import unicode_literals
from binascii import a2b_hex
import sys
import os
import datetime
import struct
import glob
import csv

def tai64n2date(tai64n):
    seconds, nanoseconds = struct.unpack(b">QI", a2b_hex(tai64n[1:]))
    seconds -= (2 ** 62) + 10
    epoch = seconds + (nanoseconds / 1000000000.0)
    date = datetime.datetime.fromtimestamp(epoch)
    return date.isoformat().replace('T', ' ')[:19]

rows = dict()

if os.path.isfile('current'):
    os.rename('current', 'current.s')

for file in glob.glob('*.s'):

    print(file)

    with open(file) as log_file:

        for log in log_file:

            row = log.split(' ')

            if "info msg" in log:
            
                rows[row[3][:-1]] = { 'delivery': 0, 'date': '', 'from': row[7][1:-1], 'to': '', 'status': '', 'info': '' }
            
            elif "delivery" in log:

                if "starting delivery" in log and "to remote" in log:

                    if row[5] in rows:
                        rows[row[5]]['delivery'] = row[3][:-1]
                        rows[row[5]]['to'] = row[-1][:-1]

                else:
                        
                    for msg, r in rows.items(): 
                    
                        if r['delivery'] == row[2][:-1]:

                            rows[msg]['date'] = tai64n2date(row[0])
                            rows[msg]['status'] = row[3][:-1]
                            rows[msg]['info'] = ' '.join(row[4:])[:-2]

count = 0

with open('qmail-log-parser.csv', 'w', newline='') as f:
    w = csv.writer(f, delimiter=';')
    w.writerow({ 'delivery': 0, 'date': '', 'from': '', 'to': '', 'status': '', 'info': '' })
    for row in rows.values():
        if row['delivery'] != 0:
            count += 1
            w.writerow(row.values())
    
print('%s registros ' % count)
