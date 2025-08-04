import sys
import time
import board
import busio
import adafruit_ads1x15.ads1115 as ADS
from adafruit_ads1x15.analog_in import AnalogIn

# --- Argumente prüfen ---
if len(sys.argv) != 3:
    print("❌ Verwendung: python3 read_ads_channel.py <i2c_address> <Kanalnummer 0-3>")
    sys.exit(1)

# I2C-Adresse parsen
try:
    i2c_address = int(sys.argv[1])
    if i2c_address not in (72,73):
        raise ValueError
except ValueError:
    print("❌ Ungültige I2C-Adresse. Beispiel: 0x48")
    sys.exit(1)

# Kanalnummer prüfen
try:
    channel_number = int(sys.argv[2])
    if channel_number not in (0, 1, 2, 3):
        raise ValueError
except ValueError:
    print("❌ Ungültiger Kanal. Bitte 0, 1, 2 oder 3 angeben.")
    sys.exit(1)

# I2C starten
i2c = busio.I2C(board.SCL, board.SDA)

# ADS1115 mit angegebener I2C-Adresse initialisieren
ads = ADS.ADS1115(i2c, address=i2c_address)

# Kanal auswählen
channels = [ADS.P0, ADS.P1, ADS.P2, ADS.P3]
chan = AnalogIn(ads, channels[channel_number])

# Wert ausgeben
print(f"{chan.voltage:.3f}")
