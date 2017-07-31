#include <stdio.h>
#include <string.h>
#include "freertos/FreeRTOS.h"
#include "freertos/task.h"
#include <driver/adc.h>

#include "esp_deep_sleep.h"
//#include "ble.h"
#include "SimpleBLE.h"
#include "read_data.h"
#include "ulp_adc.h"

#define GPIO_INPUT_IO_TRIGGER     2
#define GPIO_INPUT_IO_RESETCOUNT  13

#define period_time_wake_up_to_estimate_weight 20*1000000

#define Thresholds_Low 50
#define Thresholds_Press 1500
#define GPIO_READ_ADC 34 // shouldn't change because it define in ulp_wakeup

#define storage "storage"
#define arrayName "arrayname"
#define sizeArray 5

void wakeupCause();
void setupPins(int firstPin, int secondPin);
void onPress();
void delay(int msSeconds);
void ulp_process(int threshold_press, int period);
int getWeight();
int getAndSetAvgWeight();
void setup();

void app_main() {
	wakeupCause();
	setupPins(GPIO_INPUT_IO_TRIGGER, GPIO_INPUT_IO_RESETCOUNT);
	esp_deep_sleep_enable_timer_wakeup(period_time_wake_up_to_estimate_weight);
	//ulp_process();
	delay(10000);
	//endAdvertising();
	
	printf("Sleeping......\n");
	esp_deep_sleep_start();
	//startAdvertisingName("Mr Ngo Dat", 10);
	//vTaskDelay(5000 / portTICK_PERIOD_MS);
	//startAdvertisingName("Ahihi", 20);
}

void setup() {
	adc1_config_width(ADC_WIDTH_12Bit);
    adc1_config_channel_atten(ADC1_CHANNEL_6,ADC_ATTEN_0db);
}

void ulp_process(int threshold_press, int period) {
	init_ulp_program(0, 100, 100);
	start_ulp_program();
	ESP_ERROR_CHECK( esp_deep_sleep_enable_ulp_wakeup() );
	
}

int getWeight() {
    int val = adc1_get_voltage(ADC1_CHANNEL_6);
	return val;
}
//algorithm get value sum minus value in position
int getAndSetAvgWeight() {
	int numberArrayCur = getValueWithName(storage, "NumberOfRecord");
	if (numberArrayCur == -1) numberArrayCur = 0;
	
	int sumOfRecord = getValueWithName(storage, "SumOfRecord");
	if (sumOfRecord == -1) sumOfRecord = 0;
	
	int curPosition = getValueWithName(storage, "CurrentPosition");
	if (curPosition == -1) curPosition = 0;
	
	int weight = getWeight();
	int avgWeight = weight;
	if (numberArrayCur < sizeArray) {
		sumOfRecord += weight;
		curPosition = numberArrayCur;
		numberArrayCur++;
		setValueWithName(storage, "NumberOfRecord", numberArrayCur);
		avgWeight = sumOfRecord/numberArrayCur;
		
	} else {
		sumOfRecord += weight;
		curPosition++;
		if (curPosition >= sizeArray) curPosition = 0;
		sumOfRecord -= getValueAt(storage, arrayName, curPosition);
		avgWeight = sumOfRecord / sizeArray;
	}
	
	storeValueAt(storage,arrayName,weight,curPosition);
	setValueWithName(storage, "SumOfRecord", sumOfRecord);
	setValueWithName(storage, "CurrentPosition", curPosition);

	return avgWeight;
}


void delay(int msSeconds) {
	vTaskDelay(msSeconds / portTICK_PERIOD_MS);
}

void onPress() {
	int serial = getValueWithName(storage, "serial");
	if (serial == -1) serial = 0;	
	serial++;
	int weight = getAndSetAvgWeight();
	setValueWithName(storage, "serial", serial);
	
	//init ulp_wakeup
	ulp_process(Thresholds_Press + weight, 100);
	
	char bufferSerial[snprintf(NULL, 0, "%d", serial) + 1];
	sprintf(bufferSerial, "%d", serial);
	char bufferWeight[snprintf(NULL, 0, "%d", weight) + 1];
	sprintf(bufferWeight, "%d", weight);
	
	char adv_data[26];
	strcpy(adv_data, bufferSerial);
	strcat(adv_data, " ");
	strcat(adv_data, bufferWeight);
	beginAdvertising(adv_data);
	//delay(5000);
	//printf("Deep sleep starting ...\n");
	//esp_deep_sleep_start();
}

void wakeupCause() {
	esp_deep_sleep_wakeup_cause_t cause = esp_deep_sleep_get_wakeup_cause();
    if (cause == ESP_DEEP_SLEEP_WAKEUP_ULP) {
		printf("Deep sleep ULP wakeup\n");
		printf("Value = %d\n", getValueResultADC());
		onPress();
    } else if (cause == ESP_DEEP_SLEEP_WAKEUP_TIMER){
        //init_ulp_program();
		printf("Wakeup from timer\n");
		int avgWeight = getAndSetAvgWeight();
		printf("Estimate Weight : %d \n", avgWeight);
		ulp_process(Thresholds_Press + avgWeight, 100);
		
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
    } else {
		//init_ulp_program();
		printf("Wake up from something I don't know.ahihi (RESET)\n");
		int avgWeight = getAndSetAvgWeight();
		ulp_process(Thresholds_Press + avgWeight, 100);
		beginAdvertising("Mr Dat hihi");
	}
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