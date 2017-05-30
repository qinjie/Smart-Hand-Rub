import requests
import json
import sys
import datetime

import getSerial

def getDataFromUart() :
    data = ""
    return data

def postDataToUart(data) :
    a = 1

if __name__ == '__main__' :
    url = 'http://128.199.93.67/SmartHandRub-Web/api/web/index.php/v1/gateway/enroll'
    serial_number = '0000000038a3eddd1'
    # serial_number = getSerial.getserial()
    headers = {'Serial': serial_number}
    get_response = requests.post(url=url, headers=headers)
    result = json.loads(get_response.text)
    try :
        token = result["token"]
        gateway_id = result["id"]
    except Exception :
        print("This device doesn't register!")
        exit()

    while (1) :
        data = getDataFromUart()
        if data == 'AtPoweup':
            currentTime = datetime.datetime.time()
            postDataToUart('OK' + currentTime)
            nodeGenerate = getDataFromUart()
            url = 'http://128.199.93.67/SmartHandRub-Web/api/web/index.php/v1/gateway/new'
            headers = {'Token': token}
            post_data = {
                "serial": nodeGenerate,
                "gateway_id" : gateway_id,
                "initial_weight" : 500
            }
            get_response = requests.post(url=url, data=post_data, headers=headers)
            while (get_response.text == "Serial existed") :
                text = 'NoDidConflict'
                postDataToUart(text)
                nodeGenerate = getDataFromUart()
                url = 'http://128.199.93.67/SmartHandRub-Web/api/web/index.php/v1/gateway/new'
                headers = {'Token': token}
                post_data = {
                    "serial": nodeGenerate,
                    "gateway_id": gateway_id,
                    "initial_weight": 500
                }
                get_response = requests.post(url=url, data=post_data, headers=headers)
            result = json.loads(get_response.text)
            node_id = result["id"]
            while (1):
                data = getDataFromUart()
                press = -1
                weight = -1
                url = 'http://128.199.93.67/SmartHandRub-Web/api/web/index.php/v1/gateway/press'
                headers = {'Token': token}
                post_data = {
                    "node_id": node_id,
                    "press": press
                }
                requests.post(url=url, data=post_data, headers=headers)

                url = 'http://128.199.93.67/SmartHandRub-Web/api/web/index.php/v1/gateway/weight'
                headers = {'Token': token}
                post_data = {
                    "node_id": node_id,
                    "weight": weight
                }
                requests.post(url=url, data=post_data, headers=headers)



