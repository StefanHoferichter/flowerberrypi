import RPi.GPIO as GPIO
import time

GPIO.setmode(GPIO.BCM)
GPIO.setup(27, GPIO.IN)

def cb(channel):
    print("EDGE!")

try:
    GPIO.add_event_detect(27, GPIO.BOTH, callback=cb)
    print("Edge detection WORKS on GPIO 27.")
except Exception as e:
    print("ERROR:", e)

while True:
    time.sleep(1)
