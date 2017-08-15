#include <stdio.h>
#include <string.h>
#include "freertos/FreeRTOS.h"
#include "freertos/task.h"
#include <driver/adc.h>

#include "esp_deep_sleep.h"
#include "esp_system.h"
//#include "ble.h"
#include "SimpleBLE.h"
#include "read_data.h"
#include "ulp_adc.h"
#include <sys/time.h>

#include "driver/rtc_io.h"

#define GPIO_INPUT_IO_TRIGGER     2
#define GPIO_INPUT_IO_RESETCOUNT  13

#define period_time_wake_up_to_estimate_weight 5*1000000

#define THRESHOLDS_TOP_UP_NUMBER 10
#define THRESHOLDS_LOW 50
#define THRESHOLDS_PRESS 700
#define GPIO_READ_ADC 34 // shouldn't change because it define in ulp_wakeup
#define THRESHOLDS_OF_WEIGHT_SMALL 100

#define storage "storage"
#define arrayName "arrayname"
#define sizeArray 5

#define GAP 300
#define ESTIMATE_COUNT 200

#define WEIGHT_OF_BOTTLE 1300
#define NUMBER_ACCEPTED_TAKE_AWAY 4
#define NUMBER_DETERMINE_NEED_TOP_UP 3
#define NUMBER_TIME_GROW_HEIGH 

//#define LEVEL_TIME[5] [50, 165, 350, 1750, 10500]
const int LEVEL_TIME[] = {50, 165, 350, 1750, 10500};
#define LEVEL_TIME1 50   //9seconds
#define LEVEL_TIME2 165  //30seconds
#define LEVEL_TIME3 350  //1 minutes
#define LEVEL_TIME4 1750 //5 minutes
#define LEVEL_TIME5 10500//30 minutes

void wakeupCause();
void setupPins(int firstPin, int secondPin);
void onPress();
void delay(int msSeconds);
void ulp_process(int threshold_press, int period, int time);
int getWeight();
void storeWeight(int weight);
void storeWeightToArray(int weight);
int getWeightFromArray();
void setup();
void showArray();
void onChangeGoHeigh(int value, bool fromWakeup);
void advertising(int serial, int weight, int count,int needTopup);
void getStableWeight(int* weight, int* press_count) ;
void predictTopped();
void wakeUpByPeriod();
unsigned long millis();
void predictTakeAway();
void noticeTopUp();
bool inTimeAdvertising(unsigned long miliseconds, int threshold);
void toppedUp();


void app_main() {
	setup();
	setupPins(GPIO_INPUT_IO_TRIGGER, GPIO_INPUT_IO_RESETCOUNT);
	wakeupCause();
	//printf("Enable timer wake up!\n");
	//esp_deep_sleep_enable_timer_wakeup(period_time_wake_up_to_estimate_weight);
	printf("Sleeping......\n");
	esp_deep_sleep_start();
}

void setup() {
	adc1_config_width(ADC_WIDTH_12Bit);
    adc1_config_channel_atten(ADC1_CHANNEL_6,ADC_ATTEN_11db);
}

bool inTimeAdvertising(unsigned long miliseconds, int threshold) {
	unsigned long start = millis();
	unsigned long end = millis();
	while(1) {
		int weight = adc1_get_voltage(ADC1_CHANNEL_6);
		if (weight > threshold) {
			delay(800);
			onPress();
			return true;
		}
		end = millis();
		if (end - start >= miliseconds) return false;
	}
}

void wakeupCause() {
	esp_deep_sleep_wakeup_cause_t cause = esp_deep_sleep_get_wakeup_cause();
    if (cause == ESP_DEEP_SLEEP_WAKEUP_ULP) {
		int counter = getCounter();
		if (counter >= LEVEL_TIME5) {
			printf("Wake up from ULP with TIMER\n");
			wakeUpByPeriod();
		} else {
			printf("Wake up from ULP with User affect\n");
			printf("Value of press = %d\n", getValueResultADC());
			//printf("Counter = %d \n", getCounter());
			onPress();
		}	
    } else if (cause == ESP_DEEP_SLEEP_WAKEUP_EXT1) {
        uint64_t wakeup_pin_mask = esp_deep_sleep_get_ext1_wakeup_status();
        if (wakeup_pin_mask != 0) {
            int pin = __builtin_ffsll(wakeup_pin_mask) - 1;
            printf("Wake up from GPIO %d\n", pin);
            if (pin == GPIO_INPUT_IO_TRIGGER) {
					printf("Wake up from Trigger\n");
            } else if (pin == GPIO_INPUT_IO_RESETCOUNT) {
					printf("Wake up from reset count\n");
            }
        } else {
            printf("Wake up from GPIO\n");
        }
		esp_restart();
    } else {
		printf("Wake up from RESET\n");

		int weight, press_count;
		//while(1) {
		getStableWeight(&weight, &press_count);
		printf("Weight read from REST %d \n", weight);
		//	delay(100);
		//}
		//storeWeightToArray(weight);
	
		storeValueWithName(storage, "LastWeight", weight);
		int press = weight + THRESHOLDS_PRESS;
		ulp_process(press, 100000, LEVEL_TIME[1]);
	}
}

void onPress() {
	int serial = getValueWithName(storage, "serial");
	int count = getValueWithName(storage, "Count");
	int needTopup = getValueWithName(storage, "needTopup");
	int press_plus = 0;
	if (serial == -1) serial = 0; serial++;
	if (count == -1) count = 0; count++;
	if (needTopup == -1) needTopup = 0;
	int weight;
	printf("Getting stable weight on wake up period\n");
	getStableWeight(&weight, &press_plus);
	
	if (weight < 150) {
		needTopup = 1;
	} else if (weight > 300 && needTopup == 1) {
		printf("Detected Topped Up, Set Count = 0\n");
		needTopup = 0;
		count = 0;
	}

	if (press_plus > 0) {
		printf("Detect press on get Stable weight \n");
		serial += press_plus;
		count += press_plus;
	}
	
	storeValueWithName(storage, "Count", count);
	storeValueWithName(storage, "serial", serial);
	storeValueWithName(storage, "LastWeight", weight);
	storeValueWithName(storage, "needTopup", needTopup);
	
	int press = weight + THRESHOLDS_PRESS;
	advertising(serial, weight, count, needTopup);
	
	if (!inTimeAdvertising(10000, press)) {
		ulp_process(press, 100000, LEVEL_TIME[1]);
	}
}


void wakeUpByPeriod() {
	int serial = getValueWithName(storage, "serial");
	int count = getValueWithName(storage, "Count");
	int needTopup = 0;
	int press_plus = 0;
	if (serial == -1) serial = 0;
	if (count == -1) count = 0;
	int weight;
	printf("Getting stable weight on wake up period\n");
	getStableWeight(&weight, &press_plus);

	if (weight < 150) {
		needTopup = 1;
	} else if (weight > 300 && needTopup == 1) {
		printf("Detected Topped Up, Set Count = 0\n");
		needTopup = 0;
		count = 0;
	}
	
	if (press_plus > 0) {
		printf("Detect press on get Stable weight \n");
		serial += press_plus;
		count += press_plus;
	}
	storeValueWithName(storage, "Count", count);
	storeValueWithName(storage, "serial", serial);
	storeValueWithName(storage, "LastWeight", weight);
	storeValueWithName(storage, "needTopup", needTopup);
	int press = weight + THRESHOLDS_PRESS;
	advertising(serial, weight, count, needTopup);
	if (!inTimeAdvertising(10000, press)) {
		ulp_process(press, 100000, LEVEL_TIME[1]);
	}
}

void ulp_process(int threshold_press, int period, int time) {
	if (threshold_press > 3000)
		threshold_press = 3000;
	printf("Set wake up press : %d \n",threshold_press);
	init_ulp_program(0, threshold_press, period);
	start_ulp_program(LEVEL_TIME[4] - time);
	ESP_ERROR_CHECK( esp_deep_sleep_enable_ulp_wakeup() );
}

void showArray() {
	int store[sizeArray];
	getArrayValues(storage, arrayName, store, sizeArray);
	int i;
	for(i = 0; i < sizeArray; i++) {
		printf("Value at %d is %d \n", i, store[i]);
	}
	int numberArrayCur = getValueWithName(storage, "NumberOfRecord");
	if (numberArrayCur == -1) numberArrayCur = 0;
	
	int sumOfRecord = getValueWithName(storage, "SumOfRecord");
	if (sumOfRecord == -1) sumOfRecord = 0;
	
	int curPosition = getValueWithName(storage, "CurrentPosition");
	if (curPosition == -1) curPosition = 0;
	printf("NumberOfRecord %d, SumOfRecord %d, CurrentPosition %d \n", numberArrayCur, sumOfRecord, curPosition);
}

void getStableWeight(int* weight, int* press_count) {
	int lastWeight = getValueWithName(storage, "LastWeight");
	if (lastWeight == -1) {
		lastWeight = 0;
	}
	bool stable = false;
	//Advoid infinite
	int numberOfLoop = 0;
	//float divation = 1.5;
	(*press_count) = 0;
	int gap_minmax = GAP;
	while(!stable) {
		numberOfLoop++;
		int numberOfCount = 50;
		int min = 4096;
		int max = -1;
		int i;
		int sum = 0;
		for(i = 0; i < numberOfCount; i++) {
			int value = adc1_get_voltage(ADC1_CHANNEL_6);
			if (value > max) max = value;
			if (value < min) min = value;
			sum += value;
			delay(2000/numberOfCount);
		}
		if (abs(max - min) <= gap_minmax) {
			(*weight) = sum / numberOfCount;
			stable = true;
		} else {
			if (max > lastWeight + THRESHOLDS_PRESS) {
				(*press_count)++;
			} else {
				if (numberOfLoop >= 3) {
					numberOfLoop = 0;
					gap_minmax+= 100;
				}
			}
		}
	}
	//if (*press_count == 3) (*press_count)--;
	//return weight;
}

int getWeight() {
	int numberOfCount = 10;
	int i;
	int sum = 0;
	for(i = 0; i < numberOfCount; i++) {
		sum += adc1_get_voltage(ADC1_CHANNEL_6);
		delay(50);
	}
	return sum / numberOfCount;
}

//algorithm get value sum minus value in position
void storeWeightToArray(int weight) {
	int numberArrayCur = getValueWithName(storage, "NumberOfRecord");
	if (numberArrayCur == -1) numberArrayCur = 0;
	
	int curPosition = getValueWithName(storage, "CurrentPosition");
	if (curPosition == -1) curPosition = 0;
	
	if (numberArrayCur < sizeArray) {
		curPosition = numberArrayCur;
		numberArrayCur++;
		storeValueWithName(storage, "NumberOfRecord", numberArrayCur);
	} else {
		curPosition++;
		if (curPosition >= sizeArray) curPosition = 0;
	}
	storeValueAt(storage,arrayName,weight,curPosition);
	storeValueWithName(storage, "CurrentPosition", curPosition);
}

int getWeightFromArray() {
	//int numberArrayCur = getValueWithName(storage, "NumberOfRecord");
	int curPosition = getValueWithName(storage, "CurrentPosition");
	if (curPosition != -1) {
		return getValueAt(storage, arrayName, curPosition);
	} else {
		return -1;
	}
}


void delay(int msSeconds) {
	vTaskDelay(msSeconds / portTICK_PERIOD_MS);
}

void advertising(int serial, int weight, int count, int needTopup) {
	char bufferSerial[snprintf(NULL, 0, "%d", serial) + 1];
	sprintf(bufferSerial, "%d", serial);
	char bufferWeight[snprintf(NULL, 0, "%d", weight) + 1];
	sprintf(bufferWeight, "%d", weight);
	char bufferCount[snprintf(NULL, 0, "%d", count) + 1];
	sprintf(bufferCount, "%d", count);
	char bufferNeedTopUp[snprintf(NULL, 0, "%d", needTopup) + 1];
	sprintf(bufferNeedTopUp, "%d", needTopup);
	char adv_data[26];
	strcpy(adv_data, bufferSerial);
	strcat(adv_data, " ");
	strcat(adv_data, bufferWeight);
	strcat(adv_data, " ");
	strcat(adv_data, bufferCount);
	strcat(adv_data, " ");
	strcat(adv_data, bufferNeedTopUp);
	/*if (topup) {
		strcat(adv_data, "1");
	} else {
		strcat(adv_data, "0");
	} */
	printf("Begin advertising : \"%s\"\n",adv_data);
	beginAdvertising(adv_data);
}

unsigned long millis() {
	return xTaskGetTickCount() * portTICK_PERIOD_MS;
}

void setupPins(int firstPin, int secondPin) {
    esp_deep_sleep_pd_config(ESP_PD_DOMAIN_RTC_PERIPH, ESP_PD_OPTION_ON);
    const int ext_wakeup_pin_1 = firstPin;
    const int ext_wakeup_pin_2 = secondPin;
    //pinMode(GPIO_INPUT_IO_TRIGGER, INPUT_PULLDOWN);
    //pinMode(GPIO_INPUT_IO_RESETCOUNT, INPUT_PULLDOWN);
    const uint64_t ext_wakeup_pin_1_mask = 1LL << ext_wakeup_pin_1;
    const uint64_t ext_wakeup_pin_2_mask = 1LL << ext_wakeup_pin_2;
    esp_deep_sleep_enable_ext1_wakeup(ext_wakeup_pin_1_mask | ext_wakeup_pin_2_mask, ESP_EXT1_WAKEUP_ANY_HIGH);
}