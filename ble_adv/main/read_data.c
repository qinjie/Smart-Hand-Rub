#include <stdio.h>
#include "freertos/FreeRTOS.h"
#include "freertos/task.h"
#include "esp_system.h"
#include "esp_partition.h"
#include "nvs_flash.h"
#include "nvs.h"

void readCountFlash(int* serial, int* count) {
    esp_err_t err = nvs_flash_init();
     if (err == ESP_ERR_NVS_NO_FREE_PAGES) {
        const esp_partition_t* nvs_partition = esp_partition_find_first(
                ESP_PARTITION_TYPE_DATA, ESP_PARTITION_SUBTYPE_DATA_NVS, NULL);
        ESP_ERROR_CHECK( esp_partition_erase_range(nvs_partition, 0, nvs_partition->size) );
        err = nvs_flash_init();
    }
    ESP_ERROR_CHECK( err );

    nvs_handle my_handle;
    err = nvs_open("storage", NVS_READWRITE, &my_handle);
    if (err != ESP_OK) {
        printf("Error (%d) opening NVS handle!\n", err);
    } else {
      
        err = nvs_get_i32(my_handle, "serial", serial);
        
        switch (err) {
            case ESP_OK:
                //printf("Read Serial successfull\n");
                break;
            case ESP_ERR_NVS_NOT_FOUND:
                printf("The value is not initialized yet!\n");
                break;
            default :
                printf("Error (%d) reading!\n", err);
        }
        err = nvs_get_i32(my_handle, "counter", count);
        switch (err) {
            case ESP_OK:
                //printf("Read Counter successfull\n");
                break;
            case ESP_ERR_NVS_NOT_FOUND:
                printf("The value is not initialized yet!\n");
                break;
            default :
                printf("Error (%d) reading!\n", err);
        }
        nvs_close(my_handle);
    }
}

void writeDataFlash(int serial, int count) {
    esp_err_t err = nvs_flash_init();
     if (err == ESP_ERR_NVS_NO_FREE_PAGES) {
        const esp_partition_t* nvs_partition = esp_partition_find_first(
                ESP_PARTITION_TYPE_DATA, ESP_PARTITION_SUBTYPE_DATA_NVS, NULL);
        ESP_ERROR_CHECK( esp_partition_erase_range(nvs_partition, 0, nvs_partition->size) );
        err = nvs_flash_init();
    }
    ESP_ERROR_CHECK( err );
    nvs_handle my_handle;
    err = nvs_open("storage", NVS_READWRITE, &my_handle);
    if (err != ESP_OK) {
        printf("Error (%d) opening NVS handle!\n", err);
    } else {
        err = nvs_set_i32(my_handle, "serial", serial);
        err = nvs_set_i32(my_handle, "counter", count);
        err = nvs_commit(my_handle);
        nvs_close(my_handle);
    }
}