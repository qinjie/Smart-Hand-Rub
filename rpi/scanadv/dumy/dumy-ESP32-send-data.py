import time
import os

path = os.path.dirname(os.path.realpath(__file__))
pathData = os.path.join(path, "../data")

def ensure_dir(file_path):
   directory = os.path.dirname(file_path)
   if not os.path.exists(directory):
	os.makedirs(directory)

def generate_ESP32_data():
    timestamp = "%.0f" % round(time.time())
    serial = "%.0f" % round(time.time() % 100000)
    file_path = os.path.join(pathData, timestamp + '.txt')
    ensure_dir(file_path)
    storeFile = open(os.path.join(pathData, timestamp + '.txt'),'a+')
    storeFile.write('30:ae:a4:01:57:66' + ' ' + serial + ' ' + '90 20 1 ' + timestamp  +'\r\n')
    storeFile.write('30:ae:a4:00:c7:8e' + ' ' + serial + ' ' + '80 30 1 ' + timestamp  +'\r\n')
    #storeFile.write('30:ae:a4:01:57:66 \r\n')
    #data.clear()
    storeFile.close()

if __name__ == "__main__":
    generate_ESP32_data()
