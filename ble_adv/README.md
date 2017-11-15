# ULP ADC WAKE-UP

This example demonstrates how to use the ULP coprocessor to poll ADC in deep sleep.

ULP program periodically measures the input voltage on GPIO34. The voltage is compared to two thresholds. If the voltage is less than the low threshold, or higher than the high threshold, ULP wakes up the system.

Average current drawn by the ESP32 in this example with the default configuration (10Hz measurement period, 4x averaging) is 80 uA.

## Example output

Below is the output from this example. GPIO15 is pulled down to ground to supress output from ROM bootloader.

```
Wake up from RESET
Play sound
Weight read from REST 0

Wake up from ULP with User affect
Value of press = 3281
Getting stable weight on wake up period
Begin advertising : "2025 0 101 1"
Set wake up press : 700
Sleeping......

Wake up from ULP with User affect
Value of press = 968
Getting stable weight on wake up period
Begin advertising : "2026 0 102 1"
....
```
##Install
    Follow instrucments on this esp-idf link:
    http://esp-idf.readthedocs.io/en/latest/get-started/index.html
    
##Technique : http://esp-idf.readthedocs.io/en/latest/api-reference/system/sleep_modes.html