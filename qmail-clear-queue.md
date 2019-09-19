## check the current mail queue
```
/var/qmail/bin/qmail-qstat
messages in queue: 22463
messages in queue but not yet preprocessed: 22
```

## stop the qmail service
```
service qmail stop
```

## execute the following commands one by one
```
find /var/qmail/queue/mess -type f -exec rm {} \;
find /var/qmail/queue/info -type f -exec rm {} \;
find /var/qmail/queue/local -type f -exec rm {} \;
find /var/qmail/queue/intd -type f -exec rm {} \;
find /var/qmail/queue/todo -type f -exec rm {} \;
find /var/qmail/queue/remote -type f -exec rm {} \;
```
or 

## to delete only e-mails in queue with some text
```
grep -H -R /var/qmail/queue -e 'some-text' | cut -d: -f1 | xargs rm
```


## start the qmail service.
```
service qmail start
```

## check the mail queue
```
/var/qmail/bin/qmail-qstat
messages in queue: 0
messages in queue but not yet preprocessed: 0
```
