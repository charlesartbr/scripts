import csv

rows = dict()
status = { '=>': 'delivery', '==': 'deferred', '**': 'failed' }

with open('main.log') as log_file:

    for log in log_file:

        row = log.split(' ')

        # message arrival
        if row[3] == '<=':
        
            if row[4] != '<>':
                rows[row[2]] = { 'date': row[0] + ' ' + row[1], 'from': row[4], 'to': '', 'status': '', 'info': '' }

        # delivery
        elif row[3] == '=>' or row[3] == '==' or row[3] == '**':
        
            if row[2] in rows:

                rows[row[2]]['date'] = row[0] + ' ' + row[1]
                rows[row[2]]['status'] = status[row[3]]
                rows[row[2]]['to'] = row[4].strip(':')
                
                info_col = 5 if row[3] == '**' else 4
                
                if row[3] == '**':
                    rows[row[2]]['info'] += ' / ' + ' '.join(row[5:])[:-1]
                else:
                    rows[row[2]]['info'] = ' '.join(row[7:])[:-1]

count = 0

with open('exim-log-parser.csv', 'w', newline='') as f:
    w = csv.writer(f, delimiter=';')
    w.writerow({ 'date': '', 'from': '', 'to': '', 'status': '', 'info': '' })
    for row in sorted(rows.values(), key=lambda item: item['date']):
        count += 1
        w.writerow(row.values())
   
print('%s registros ' % count)
