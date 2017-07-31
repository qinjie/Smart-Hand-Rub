#include <stdio.h>
#include "freertos/FreeRTOS.h"
#include "freertos/task.h"
#include "esp_system.h"
#include "esp_partition.h"
#include "nvs_flash.h"
#include "nvs.h"
#include "string.h"

#define STORAGE "storage"

//return a array of arrayName with storage
void getArrayValues(char* storage,char* arrayName,int* values, int n) {
	esp_err_t err = nvs_flash_init();
	if (err == ESP_ERR_NVS_NO_FREE_PAGES) {
        const esp_partition_t* nvs_partition = esp_partition_find_first(
                ESP_PARTITION_TYPE_DATA, ESP_PARTITION_SUBTYPE_DATA_NVS, NULL);
        ESP_ERROR_CHECK( esp_partition_erase_range(nvs_partition, 0, nvs_partition->size) );
        err = nvs_flash_init();
    }
	ESP_ERROR_CHECK( err );
	nvs_handle array_handle;
	err = nvs_open(storage, NVS_READWRITE, &array_handle);
	
	//int values[n];
	
	if (err != ESP_OK) {
        printf("Error (%d) opening NVS handle!\n", err);
    } else {
		int i = 0;
		char nameElement[15];
		for( i = 0; i < n; i++) {
			char number[snprintf(NULL, 0, "%d", i) + 1];
			sprintf(number, "%d", i);
			strcpy(nameElement, arrayName);
			strcat(nameElement, number);
			err = nvs_get_i32(array_handle, nameElement, &values[i]);
			switch (err) {
				case ESP_OK:
					//printf("Read Counter successfull\n");
					break;
				case ESP_ERR_NVS_NOT_FOUND:
					printf("The value is not initialized yet!\n");
					values[i] = -1;
					break;
				default :
					printf("Error (%d) reading!\n", err);
			}
		}
		nvs_close(array_handle);
	}
}

//Get a value of Array at position Pos
int getValueAt(char* storage,char* arrayName, int pos) {
	esp_err_t err = nvs_flash_init();
	if (err == ESP_ERR_NVS_NO_FREE_PAGES) {
        const esp_partition_t* nvs_partition = esp_partition_find_first(
                ESP_PARTITION_TYPE_DATA, ESP_PARTITION_SUBTYPE_DATA_NVS, NULL);
        ESP_ERROR_CHECK( esp_partition_erase_range(nvs_partition, 0, nvs_partition->size) );
        err = nvs_flash_init();
    }
	ESP_ERROR_CHECK( err );
	nvs_handle array_handle;
	err = nvs_open(storage, NVS_READWRITE, &array_handle);
	
	int value = -1;
	
	if (err != ESP_OK) {
        printf("Error (%d) opening NVS handle!\n", err);
    } else {
		char nameElement[15];
		char number[snprintf(NULL, 0, "%d", pos) + 1];
		sprintf(number, "%d", pos);
		strcpy(nameElement, arrayName);
		strcat(nameElement, number);
		err = nvs_get_i32(array_handle, nameElement, &value);
			switch (err) {
				case ESP_OK:
					//printf("Read Counter successfull\n");
					break;
				case ESP_ERR_NVS_NOT_FOUND:
					printf("The value is not initialized yet!\n");
					value = -1;
					break;
				default :
					printf("Error (%d) reading!\n", err);
			}
		nvs_close(array_handle);
	}
	
	return value;
}

//Get a value of name
int getValueWithName(char* storage,char* valueName) {
	esp_err_t err = nvs_flash_init();
	if (err == ESP_ERR_NVS_NO_FREE_PAGES) {
        const esp_partition_t* nvs_partition = esp_partition_find_first(
                ESP_PARTITION_TYPE_DATA, ESP_PARTITION_SUBTYPE_DATA_NVS, NULL);
        ESP_ERROR_CHECK( esp_partition_erase_range(nvs_partition, 0, nvs_partition->size) );
        err = nvs_flash_init();
    }
	ESP_ERROR_CHECK( err );
	nvs_handle value_handle;
	err = nvs_open(storage, NVS_READWRITE, &value_handle);
	int value = -1;
	if (err != ESP_OK) {
        printf("Error (%d) opening NVS handle!\n", err);
    } else {
		err = nvs_get_i32(value_handle, valueName, &value);
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
		nvs_close(value_handle);
	}
	
	return value;
}

//Store Value of Array at position Pos
void storeValueAt(char* storage,char* arrayName,int value, int pos) {
	esp_err_t err = nvs_flash_init();
	if (err == ESP_ERR_NVS_NO_FREE_PAGES) {
        const esp_partition_t* nvs_partition = esp_partition_find_first(
                ESP_PARTITION_TYPE_DATA, ESP_PARTITION_SUBTYPE_DATA_NVS, NULL);
        ESP_ERROR_CHECK( esp_partition_erase_range(nvs_partition, 0, nvs_partition->size) );
        err = nvs_flash_init();
    }
	ESP_ERROR_CHECK( err );
	nvs_handle array_handle;
	err = nvs_open(storage, NVS_READWRITE, &array_handle);
	
	if (err != ESP_OK) {
        printf("Error (%d) opening NVS handle!\n", err);
    } else {
		char nameElement[15];
		char number[snprintf(NULL, 0, "%d", pos) + 1];
		sprintf(number, "%d", pos);
		strcpy(nameElement, arrayName);
		strcat(nameElement, number);
		nvs_set_i32(array_handle, nameElement, value);
		nvs_commit(array_handle);
		nvs_close(array_handle);
	}
}
//Store Value with valueName
void setValueWithName(char* storage,char* valueName,int value) {
	esp_err_t err = nvs_flash_init();
	if (err == ESP_ERR_NVS_NO_FREE_PAGES) {
        const esp_partition_t* nvs_partition = esp_partition_find_first(
                ESP_PARTITION_TYPE_DATA, ESP_PARTITION_SUBTYPE_DATA_NVS, NULL);
        ESP_ERROR_CHECK( esp_partition_erase_range(nvs_partition, 0, nvs_partition->size) );
        err = nvs_flash_init();
    }
	ESP_ERROR_CHECK( err );
	nvs_handle value_handle;
	err = nvs_open(storage, NVS_READWRITE, &value_handle);
	
	if (err != ESP_OK) {
        printf("Error (%d) opening NVS handle!\n", err);
    } else {
		err = nvs_set_i32(value_handle, valueName, value);
        err = nvs_commit(value_handle);
		nvs_close(value_handle);
	}
}



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
    err = nvs_open(STORAGE, NVS_READWRITE, &my_handle);
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
    err = nvs_open(STORAGE, NVS_READWRITE, &my_handle);
    if (err != ESP_OK) {
        printf("Error (%d) opening NVS handle!\n", err);
    } else {
        err = nvs_set_i32(my_handle, "serial", serial);
        err = nvs_set_i32(my_handle, "counter", count);
        err = nvs_commit(my_handle);
		nvs_close(my_handle);
    }
}