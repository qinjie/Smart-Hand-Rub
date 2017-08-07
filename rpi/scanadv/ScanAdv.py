from configparser import ConfigParser
import json
import bluepy
import threading
import time
import sys
import os

DefaultDelegate = bluepy.btle.DefaultDelegate

BTNAME='Complete Local Name'

path = os.path.dirname(os.path.realpath(__file__))
pathDev = os.path.join(path, "DevAddress.ini")

cfg = ConfigParser()
cfg.read(pathDev)
pathStoreDev = path;
pathData = os.path.join(path, "data")
data = {}

numberToStore = 2;

def isNumber(value):
    try:
	int(value)
	return True
    except ValueError:
	return False

def checkAndstore():
    global data
    if len(data) < numberToStore:
	return
    timestamp = "%.0f" % round(time.time()*1000)
    storeFile = open(os.path.join(pathData, timestamp + '.txt'),'a+')
    for keys, values in data.items():
    	storeFile.write(keys[0] + ' ' + keys[1] + ' '+ values[0] + ' ' + values[1]+
				' ' + keys[2] + ' ' + values[2]+'\r\n')
    data.clear()
    storeFile.close()
    

def isValidData(data):
    values = data.split(' ')
    if len(values) != 4:
	return False
    if not isNumber(values[0]) or not isNumber(values[1]) or not isNumber(values[2]) or not isNumber(values[3]):
	return False
    return True

def paireddevicefactory( dev ):
    # get the device name to decide which type of device to create
    devdata = {}
    for (adtype, desc, value) in dev.getScanData():
        devdata[desc]=value
    if BTNAME not in devdata.keys():
        devdata[BTNAME] = 'Unknown!'
    list = json.loads(cfg.get("Devices","Addresses"))

    global data
    if dev.addr in list:
	strName = devdata[BTNAME]
	#strName = " ".join(strName.split())
	if not isValidData(strName):
	    print ("Data : '"+ strName+"' not validate")
	    return False
	else:
	    values = strName.split(' ')
	    timestamp = "%.0f" % round(time.time()*1000)
	    data[(dev.addr, values[0], values[3])] = (values[1],values[2],timestamp)
	    checkAndstore()
	print "Send Data : ", devdata[BTNAME], " From " , dev.addr, "To Server"
    else:    
    	print "Found : ",dev.addr,", Value : ",devdata[BTNAME]
    return None
	
class ScanDelegate(DefaultDelegate):
    def __init__(self):
        DefaultDelegate.__init__(self)
        print "Init Delegate successful!"
    def handleDiscovery(self, dev, isNewDev, isNewData):
        if isNewDev or isNewData:
	    paireddevicefactory(dev)

#prepair bluetooth
def prepairbluetooth():
    os.system('sudo hciconfig hci0 down')
    os.system('sudo hciconfig hci0 up')

if __name__ == "__main__":
    prepairbluetooth()
    scandelegate = ScanDelegate()
    scanner = bluepy.btle.Scanner().withDelegate(scandelegate)
    while True:
    	scanner.scan(timeout=10)
#    storeFile = open(path + '/storedata.txt','a+')
#    storeFile.write('ScanAdv.py is running\r\n')
#    storeFile.close()
