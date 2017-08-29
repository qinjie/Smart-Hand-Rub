import requests
import json
import os
path = os.path.dirname(os.path.realpath(__file__))
pathDev = os.path.join(path, "../scanadv/DevAddress.ini")

def ensure_dir(file_path):
   directory = os.path.dirname(file_path)
   if not os.path.exists(directory):
        os.makedirs(directory)

def getListESP():
    url = "http://13.228.113.29/SmartHandRub-Web/backend/web/node/list/"
    r = requests.get(url)
    objectJson = r.json() 
    dataJson = json.dumps(objectJson)
    data = json.loads(dataJson)
    
    if not os.path.isfile(pathDev):
	return
    contents = "[Devices]\r\n Addresses = [";    
    
    storeFile = open(pathDev, 'w')    

    for i in range(len(data)  - 1):
        if len(data[i]['serial']) == 17:
	    contents += '\"'+data[i]['serial']+'\",'
	else:
	    print "Wrong Mac :" + data[0]['serial']
    if len(data[len(data) - 1]['serial']) == 17:
 	contents += '\"'+data[len(data)-1]['serial']+'\"'
    contents += "]\r\n"
    print "Store : " + contents  
    storeFile.write(contents)
    storeFile.close()
 
if __name__ == "__main__":
    getListESP();
