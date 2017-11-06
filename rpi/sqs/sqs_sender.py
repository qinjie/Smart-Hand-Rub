import requests

URL = 'https://sqs.ap-southeast-1.amazonaws.com/498107424281/SmartHandRub_NodePresses'
MESSAGE = '{ "name": "MrDat", "image1_url": "http1"}'

def sqs_sender(url, message):
    #url = 'https://sqs.ap-southeast-1.amazonaws.com/498107424281/DatntQueue'
    #message = '{ "name": "MrDat", "image1_url": "http1"}'
    try:
    	payload = {'Action': 'SendMessage', 'MessageBody': message, 'Version': '2012-11-05', 'Expires': '2011-10-15T12%3A00%3A00Z', 'AUTHPARAMS': ''}
    	r = requests.post(url, data=payload)
	print r.status_code
	return r.status_code == 200
    except:
	print "Exception occur"
	return False
	
if __name__ == '__main__':
    print sqs_sender(URL, MESSAGE)
