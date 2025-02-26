#include <SPI.h>
#include <MFRC522.h>

#define SS_PIN 10
#define RST_PIN 9

MFRC522 rfid(SS_PIN, RST_PIN);
String rfidTag;

void setup() {
    Serial.begin(9600);
    SPI.begin();
    rfid.PCD_Init();
}

void loop() {
    if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial()) {
        return;
    }

    rfidTag = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
        rfidTag += String(rfid.uid.uidByte[i], HEX);
    }

    Serial.println(rfidTag); // Envia o ID para o PHP
    delay(1000);
}
