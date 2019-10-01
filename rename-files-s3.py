import boto3
import re

s3 = boto3.resource('s3')

bucket_name = 'bucket-name'

bucket = s3.Bucket(bucket_name)

for file in bucket.objects.filter(Prefix = '/'):

    if len(re.findall('/', file.key)) == 2:

        obj = bucket.Object(file.key.replace('somestring', 'newstring'))
        obj.copy({ 'Bucket': bucket_name, 'Key': file.key })

        s3.Object(bucket_name, file.key).delete()
        
        print(file.key)
