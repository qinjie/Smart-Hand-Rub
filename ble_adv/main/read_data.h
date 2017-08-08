
void readCountFlash(int* serial, int* count);

void writeDataFlash(int serial, int count);

void getArrayValues(char* storage,char* arrayName,int* values, int n);

int getValueAt(char* storage,char* arrayName,int pos);

void storeValueAt(char* storage,char* arrayName, int value, int pos);

int getValueWithName(char* storage,char* valueName);

void storeValueWithName(char* storage,char* valueName,int value);

void getValuesOfProgram(char* storage, char* name1, char* name2, char* name3, char* name4, char* name5 
										,int* value1, int* value2, int* value3, int* value4, int* value5);
										
void storeValuesOfProgram(char* storage, char* name1, char* name2, char* name3, char* name4, char* name5 
										,int value1, int value2, int value3, int value4, int value5);