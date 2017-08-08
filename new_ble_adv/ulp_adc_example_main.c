#include "freertos/FreeRTOS.h"
#include "freertos/task.h"

#include <stdio.h>
#include <string.h>
#include "esp_deep_sleep.h"
#include "nvs.h"
#include "nvs_flash.h"
#include "soc/rtc_cntl_reg.h"
#include "soc/sens_reg.h"
#include "driver/gpio.h"
#include "driver/rtc_io.h"
#include "driver/adc.h"
#include "driver/dac.h"
#include "esp32/ulp.h"
#include "ulp_main.h"

#define THRESHOLDS_PRESS 1000

extern const uint8_t ulp_main_bin_start[] asm("_binary_ulp_main_bin_start");
extern const uint8_t ulp_main_bin_end[]   asm("_binary_ulp_main_bin_end");

/* This function is called once after power-on reset, to load ULP program into
 * RTC memory and configure the ADC.
 */
static void init_ulp_program(int threshold);

/* This function is called every time before going into deep sleep.
 * It starts the ULP program and resets measurement counter.
 */
static void start_ulp_program();

void wakeupCause();
void setupPinReadAnalog();
void delay(int msSeconds);

void app_main()
{
	setupPinReadAnalog();
    wakeupCause();
    start_ulp_program();
    ESP_ERROR_CHECK( esp_deep_sleep_enable_ulp_wakeup() );
	esp_deep_sleep_enable_timer_wakeup(1000000 * 5);
	printf("Entering deep sleep\n\n");
    esp_deep_sleep_start();
}

void delay(int msSeconds) {
	vTaskDelay(msSeconds / portTICK_PERIOD_MS);
}

void setupPinReadAnalog() {
	adc1_config_width(ADC_WIDTH_12Bit);
    adc1_config_channel_atten(ADC1_CHANNEL_6,ADC_ATTEN_0db);
}

int getWeight() {
	//return 500;
	int numberOfCount = 5;
	int i;
	int sum = 0;
	for(i = 0; i < numberOfCount; i++) {
		sum += adc1_get_voltage(ADC1_CHANNEL_7);
		sum += ulp_last_result;
		delay(500);
	}
	return sum / numberOfCount;
}

void wakeupCause() {
	esp_deep_sleep_wakeup_cause_t cause = esp_deep_sleep_get_wakeup_cause();
    if (cause == ESP_DEEP_SLEEP_WAKEUP_ULP) {
		printf("ULP coprocessor wakeup\n");
		int weight = getWeight();
		int press = weight + THRESHOLDS_PRESS;
		printf("Weight : %d", weight);
		init_ulp_program(10);
      /*  printf("ULP did %d measurements since last reset\n", ulp_sample_counter & UINT16_MAX);
        printf("Thresholds:  low=%d  high=%d\n", ulp_low_thr, ulp_high_thr);
        ulp_last_result &= UINT16_MAX;
        printf("Value=%d was %s threshold\n", ulp_last_result,
                ulp_last_result < ulp_low_thr ? "below" : "above"); */
    } else {
		if (cause == ESP_DEEP_SLEEP_WAKEUP_TIMER){
			printf("Wakeup from timer\n");
			int weight = getWeight();
			int press = weight + THRESHOLDS_PRESS;
			printf("Weight : %d", weight);
			init_ulp_program(10);
			
		} else {
			printf("Wake up from something I don't know.ahihi\n");
			//int weight = getWeight();
			//int press = weight + THRESHOLDS_PRESS;
			//printf("Weight : %d", weight);
			init_ulp_program(0);
		}
		
	}
}

static void init_ulp_program(int threshold)
{
    esp_err_t err = ulp_load_binary(0, ulp_main_bin_start,
            (ulp_main_bin_end - ulp_main_bin_start) / sizeof(uint32_t));
    ESP_ERROR_CHECK(err);

    /* Configure ADC channel */
    /* Note: when changing channel here, also change 'adc_channel' constant
       in adc.S */
    adc1_config_channel_atten(ADC1_CHANNEL_6, ADC_ATTEN_11db);
    adc1_config_width(ADC_WIDTH_12Bit);
    adc1_ulp_enable();
	delay(1000);
    /* Set low and high thresholds, approx. 1.35V - 1.75V*/
    ulp_low_thr = 0;
    ulp_high_thr = threshold;

    /* Set ULP wake up period to 100ms */
    ulp_set_wakeup_period(0, 100000);
}

static void start_ulp_program()
{
    /* Reset sample counter */
    ulp_sample_counter = 0;

    /* Start the program */
    esp_err_t err = ulp_run((&ulp_entry - RTC_SLOW_MEM) / sizeof(uint32_t));
    ESP_ERROR_CHECK(err);
}
