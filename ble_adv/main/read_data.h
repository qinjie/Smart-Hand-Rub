
void readCountFlash(int* serial, int* count);

void writeDataFlash(int serial, int count);

void getArrayValues(char* storage,char* arrayName,int* values, int n);

int getValueAt(char* storage,char* arrayName,int pos);

void storeValueAt(char* storage,char* arrayName, int value, int pos);

int getValueWithName(char* storage,char* valueName);

void setValueWithName(char* storage,char* valueName,int value);

