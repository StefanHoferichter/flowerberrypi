#!/usr/bin/env python3
import sys
import time
import pigpio

PULSE_LENGTH = 185  # exakt wie in 433Utils
REPEAT = 20         # wie oft der Code gesendet wird
BITS = 24           # codesend sendet immer 24 Bits
DEFAULT_GPIO = 17

def send_code(pi, gpio_pin, code):
    pi.set_mode(gpio_pin, pigpio.OUTPUT)
    pi.wave_clear()
    
    # Wandelt Zahl in Binärformat mit 24 Bit
    binary = format(code, f'0{BITS}b')
    wf = []

    for bit in binary:
        if bit == '0':
            # Bit 0: short HIGH, long LOW
            wf.append(pigpio.pulse(1 << gpio_pin, 0, PULSE_LENGTH))
            wf.append(pigpio.pulse(0, 1 << gpio_pin, PULSE_LENGTH * 3))
        else:
            # Bit 1: long HIGH, short LOW
            wf.append(pigpio.pulse(1 << gpio_pin, 0, PULSE_LENGTH * 3))
            wf.append(pigpio.pulse(0, 1 << gpio_pin, PULSE_LENGTH))

    # Sync pulse: HIGH für 1 * PULSE, dann LOW für 31 * PULSE
    wf.append(pigpio.pulse(1 << gpio_pin, 0, PULSE_LENGTH))
    wf.append(pigpio.pulse(0, 1 << gpio_pin, PULSE_LENGTH * 31))

    pi.wave_add_generic(wf)
    wave_id = pi.wave_create()

    if wave_id >= 0:
        for _ in range(REPEAT):
            pi.wave_send_once(wave_id)
            while pi.wave_tx_busy():
                time.sleep(0.01)
        pi.wave_delete(wave_id)
    else:
        print("ERROR: could not create wave.")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("ERROR: python3 codesend_compatible.py <CODE> [GPIO_PIN]")
        sys.exit(1)

    code = int(sys.argv[1])
    gpio_pin = int(sys.argv[2]) if len(sys.argv) > 2 else DEFAULT_GPIO

    pi = pigpio.pi()
    if not pi.connected:
        print("❌ pigpiod nicht verbunden. Starte ihn mit: sudo pigpiod")
        sys.exit(1)

    print(f"📡 Sende Code {code} über GPIO {gpio_pin}")
    send_code(pi, gpio_pin, code)
    pi.stop()
