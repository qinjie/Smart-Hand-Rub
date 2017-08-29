from AWSIoTPythonSDK.MQTTLib import AWSIoTMQTTClient
import sys
import time
import random
import logging
import argparse
import os
import logging
import json
#CustomMQTT message callback
def customCallback(client, userdata, message):
	print "Received new message :"
	print message.payload + '\n-------------\n'

path = os.path.dirname(os.path.realpath(__file__))

logging.basicConfig(filename=os.path.join(path,"mqtt.log"),level=logging.DEBUG)

host = 'aflolh6t6129n.iot.ap-southeast-1.amazonaws.com'
rootCAPath = os.path.join(path,'root-CA.crt')
certificatePath = os.path.join(path,'MrDat.cert.pem')
privateKeyPath = os.path.join(path,'MrDat.private.key')

clientId = 'basicPubSub'
topic = 'RPi/today'
useWebSocket = True

#Init AWSIoTMQTTClient
myAWSIoTMQTTClient = None

if useWebSocket:
	print "Use WebSocket"
	myAWSIoTMQTTClient = AWSIoTMQTTClient(clientId, useWebsocket = True)
	myAWSIoTMQTTClient.configureEndpoint(host, 443)
	myAWSIoTMQTTClient.configureCredentials(rootCAPath)
else:
	myAWSIoTMQTTClient = AWSIoTMQTTClient(clientId)
	myAWSIoTMQTTClient.configureEndpoint(host, 8883)
	myAWSIoTMQTTClient.configureCredentials(rootCAPath, privateKeyPath, certificatePath)

# AWSIOTMQTTClient connection configuration
myAWSIoTMQTTClient.configureAutoReconnectBackoffTime(1,32,20)
myAWSIoTMQTTClient.configureOfflinePublishQueueing(-1) #Infinite pffline Publish queueing
myAWSIoTMQTTClient.configureDrainingFrequency(2) #Draining : 2Hz
myAWSIoTMQTTClient.configureConnectDisconnectTimeout(10) # 10 sec
myAWSIoTMQTTClient.configureMQTTOperationTimeout(5)
# Connect and subscribe to AWS IoT
#print 'connecting.....'
try:
    myAWSIoTMQTTClient.connect()
    myAWSIoTMQTTClient.subscribe(topic, 1, customCallback)
    time.sleep(2)
    print "Connected to server"
    logging.info("Connected to server")
except:
    print "Cann't connect to server"
    logging.error("Cann't connect to server")
    sys.exit(0)
#publish to the same topic in a loop forever

def getMAC(interface):
  # Return the MAC address of interface
  try:
    str = open('/sys/class/net/' + interface + '/address').read()
  except:
    str = "00:00:00:00:00:00"
  return str[0:17]

def ensure_dir(directory):
    if not os.path.exists(directory):
        os.makedirs(directory)
pathData = os.path.join(path, '../scanadv/data')
ensure_dir(pathData)
listFiles = os.listdir(pathData)

for fileName in listFiles:
    pathFile = os.path.join(pathData, fileName)    
    if os.path.isfile(pathFile):
	with open(pathFile) as f:
	    content = f.readlines()
	    rows = []
	    if content:
	    	for line in content:
		    line = line.strip()
		    words = line.split(" ")
		    row = {}
		    if len(words) == 6:
			row["RpiAddress"] = getMAC('eth0')
		       	row["EspAddress"] = words[0]
		   	row["SerialCount"] = words[1]
			row["Weight"] = words[2]
		    	row["CurrentCount"] = words[3]
			row["NeedTopUp"] = words[4]
		    	row["Timestamp"] = words[5]
	            	rows.append(row)
		    else:
		    	print "Format wrong of line"
			logging.error("Format wrong of line")
			break;
            	jsonContent =  json.dumps(rows)
	    
		if myAWSIoTMQTTClient.publish(topic, ''.join(jsonContent), 1):
            	     #logging.info("Sent data in file :'" + fileName + " to server")
		     print ("Sent data in file :'" + fileName + "' to server")
		     print ("Data :" + jsonContent)
		     logging.info("Sent data in file :'" + fileName + "' to server")
		     os.remove(pathFile)
		else:
		     print "Cann't publish data"
 		     logging.error("Cann't publish data")
'''if os.path.isfile(pathData):
    print "Data privious didn't send"
    with open(pathData) as f:
	content = f.readlines()
	print content
	if content:
		myAWSIoTMQTTClient.publish(topic, ''.join(content), 1)
	#if data sent successful
	os.remove(pathData)
else :
    pathNewData = os.path.join(path, "../scanadv/storedata.txt")
    if os.path.isfile(pathNewData):
	os.rename(pathNewData, pathData)
    	if os.path.isfile(pathData):
	    print "Have new data"
	    with open(pathData) as f:
           	content = f.readlines()
	    	content = [x.strip() for x in content]
		if content:
			myAWSIoTMQTTClient.publish(topic,''.join(content), 1)
            	print content
	    #if data sent sucessful
	    os.remove(pathData)
    else:
	print "No data"'''
#while True:
#	print 'Running...'
#	myAWSIoTMQTTClient.publish(topic, "Temperature : " + str(25 + random.uniform(0,2)), 1)
#	time.sleep(10)


