import win32com.client
import glob
import os

outlook = win32com.client.Dispatch("Outlook.Application").GetNamespace("MAPI")

path = os.path.dirname(os.path.abspath(__file__))

files = glob.glob('msgs/*.msg')

def get(str):
    return str.split(':')[1].lstrip().rstrip()

print ('INSERT INTO `orcamentos` (`nome`, `telefone`, `email`, `estado`, `cidade`, `toneladas`, `cultivos`, `mensagem`, `ip`, `data`) VALUES')

msgs = []

for file in files:

    msg = outlook.OpenSharedItem(path + '\\' + file)

    lines = [line.lstrip().rstrip() for line in msg.Body.split('\n')]

    nome = get(lines[2])
    telefone = get(lines[3])
    email = get(lines[4])
    estado = get(lines[5])
    cidade = get(lines[6])
    toneladas = get(lines[7])
    cultivos = get(lines[8])

    last = lines[11] if 'Mensagem' in lines[9] else lines[10]
    last = last.split('-')
    dh = last[0].split(' as ')
    d = dh[0].split('/')

    data = d[2] + '-' + d[1] + '-' + d[0] + ' ' + dh[1].lstrip().rstrip()
    ip = last[1].lstrip().rstrip()

    msgs.append([nome, telefone, email, estado, cidade, toneladas, cultivos, ip, data])

for s in sorted(msgs, key=lambda x: x[8]):

    print("('" + "','".join(s) + "'),")

del outlook, msg
