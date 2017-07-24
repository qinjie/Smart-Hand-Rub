#include <stdio.h>
#include <string.h>
#include "freertos/FreeRTOS.h"
#include "freertos/task.h"

#include "esp_deep_sleep.h"
//#include "ble.h"
#include "SimpleBLE.h"
#include "read_data.h"
#include "ulp_adc.h"

#define GPIO_INPUT_IO_TRIGGER     2
#define GPIO_INPUT_IO_RESETCOUNT  13

void wakeupCause();
void setupPins(int firstPin, int secondPin);
void onPress();
void delay(int msSeconds);

void app_main() {
	wakeupCause();
	setupPins(GPIO_INPUT_IO_TRIGGER, GPIO_INPUT_IO_RESETCOUNT);
	init_ulp_program(0, 100, 100);
	start_ulp_program();
	ESP_ERROR_CHECK( esp_deep_sleep_enable_ulp_wakeup() );
	delay(10000);
	//endAdvertising();
	esp_deep_sleep_start();
	//startAdvertisingName("Mr Ngo Dat", 10);
	//vTaskDelay(5000 / portTICK_PERIOD_MS);
	//startAdvertisingName("Ahihi", 20);
}

void delay(int msSeconds) {
	vTaskDelay(msSeconds / portTICK_PERIOD_MS);
}

void onPress() {
	int serial, count;
	readCountFlash(&serial, &count);
	serial++; count++;
	writeDataFlash(serial, count);
	
	char bufferSerial[snprintf(NULL, 0, "%d", serial) + 1];
	sprintf(bufferSerial, "%d", serial);
	char bufferCount[snprintf(NULL, 0, "%d", count) + 1];
	sprintf(bufferCount, "%d", count);
	
	char adv_data[26];
	strcpy(adv_data, bufferSerial);
	strcat(adv_data, " ");
	strcat(adv_data, bufferCount);
	beginAdvertising(adv_data);
	//delay(5000);
	//printf("Deep sleep starting ...\n");
	//esp_deep_sleep_start();
}

void wakeupCause() {
	esp_deep_sleep_wakeup_cause_t cause = esp_deep_sleep_get_wakeup_cause();
    if (cause == ESP_DEEP_SLEEP_WAKEUP_ULP) {
		printf("Deep sleep wakeup\n");
		onPress();
        /* printf("ULP did %d measurements since last reset\n", ulp_sample_counter & UINT16_MAX);
        printf("Thresholds:  low=%d  high=%d\n", ulp_low_thr, ulp_high_thr);
        ulp_last_result &= UINT16_MAX;
        printf("Value=%d was %s threshold\n", ulp_last_result,
                ulp_last_result < ulp_low_thr ? "below" : "above"); */
        
    } else if (cause == ESP_DEEP_SLEEP_WAKEUP_TIMER){
        //init_ulp_program();
		printf("Wakeup from timer\n"); 
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
		printf("Wake up from something I don't know.ahihi\n");
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