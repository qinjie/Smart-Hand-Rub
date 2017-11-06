import os
import json
import logging
import datetime

from sqs_sender import sqs_sender

URL = 'https://sqs.ap-southeast-1.amazonaws.com/498107424281/SmartHandRub_NodePresses'
path = os.path.dirname(os.path.realpath(__file__))

logging.basicConfig(filename=os.path.join(path,"sqs.log"),level=logging.INFO)

def ensure_dir(directory):
    if not os.path.exists(directory):
        os.makedirs(directory)

def getMAC(interface):
  # Return the MAC address of interface
  try:
    str = open('/sys/class/net/' + interface + '/address').read()
  except:
    str = "00:00:00:00:00:00"
  return str[0:17]

def send_data_sqs():
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
			    break
		    jsonContent =  json.dumps(rows)
		    
		    result = sqs_sender(URL, jsonContent)
		    if result:
			print ("Sent data in file :'" + fileName + "' to server")
		     	print ("Data :" + jsonContent)
			logging.info(str(datetime.datetime.now()) + " Sent data in file :'" + fileName + "' to server")
			os.remove(pathFile)   
		    else:
			logging.info(str(datetime.datetime.now()) + " Sent data in file :'" + fileName + "'failed.")
			print "Cann't push data to sqs"

if __name__ == '__main__':
    send_data_sqs()
