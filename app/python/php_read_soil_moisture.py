import sys
import time
import board
import busio
import adafruit_ads1x15.ads1115 as ADS
from adafruit_ads1x15.analog_in import AnalogIn

MAX_RETRIES = 3
RETRY_DELAY = 0.5


def error_exit(msg):
    print(f"❌ {msg}", file=sys.stderr)
    sys.exit(1)

# --- Argumente prüfen ---
if len(sys.argv) != 3:
    print("ERROR: python php_read_soil_moisture.py <i2c_address> <channel 0-3>")
    sys.exit(1)

# I2C-Adresse parsen
try:
    i2c_address = int(sys.argv[1])
    if i2c_address not in (72,73,74,75):
        raise ValueError
except ValueError:
    error_exit("ERROR: Invalid I2C-Address. Valid range (72/73/74/75).")
    sys.exit(1)

# Kanalnummer prüfen
try:
    channel_number = int(sys.argv[2])
    if channel_number not in (0, 1, 2, 3):
        raise ValueError
except ValueError:
    print("ERROR: Invalid channel. Use 0, 1, 2 oder 3.")
    sys.exit(1)

# I2C starten
try:
	i2c = busio.I2C(board.SCL, board.SDA)
except Exception as e:
    error_exit(f"ERROR: I2C-Init failed: {e}")

# ADS1115 mit angegebener I2C-Adresse initialisieren
try:
    ads = ADS.ADS1115(i2c, address=i2c_address)
#    ads.mode = ADS.Mode.SINGLE  # Optional: SINGLE = weniger Rauschen
    ads.gain = 1  # z.B. für 4.096V Bereich
    channels = [ADS.P0, ADS.P1, ADS.P2, ADS.P3]
    chan = AnalogIn(ads, channels[channel_number])
    time.sleep(0.05)
    print(f"{chan.voltage:.3f}")
except Exception as e:
    error_exit(f"ERROR: ADS1115 read failed: {e}")
