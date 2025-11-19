#include <gpiod.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <time.h>

#define CONSUMER "433sender"

// Präzise Wartefunktion (Microsekunden)
static void sleep_us(long microseconds)
{
    struct timespec ts;
    ts.tv_sec = microseconds / 1000000;
    ts.tv_nsec = (microseconds % 1000000) * 1000;
    nanosleep(&ts, NULL);
}

// Ein einzelnes Bit senden (1 oder 0)
static void sendBit(struct gpiod_line *line, int bit, int pulse_len)
{
    if (bit == 1) {
        gpiod_line_set_value(line, 1);
        sleep_us(pulse_len * 3);
        gpiod_line_set_value(line, 0);
        sleep_us(pulse_len);
    } else {
        gpiod_line_set_value(line, 1);
        sleep_us(pulse_len);
        gpiod_line_set_value(line, 0);
        sleep_us(pulse_len * 3);
    }
}

// Vollständigen 24-Bit-Code senden
static void sendFrame(struct gpiod_line *line, unsigned long code, int pulse_len)
{
    for (int repeat = 0; repeat < 5; repeat++) {
        for (int i = 23; i >= 0; i--) {
            int bit = (code >> i) & 1;
            sendBit(line, bit, pulse_len);
        }
        // Sync-Pulse
        gpiod_line_set_value(line, 1);
        sleep_us(pulse_len);
        gpiod_line_set_value(line, 0);
        sleep_us(pulse_len * 31);
    }
}

// Code mit mehreren Try-Pulse-Längen senden
void sendCode(struct gpiod_line *line, unsigned long code)
{
    int pulse_variants[] = {310, 340, 370};
    int count = sizeof(pulse_variants) / sizeof(pulse_variants[0]);

    for (int i = 0; i < count; i++) {
        int p = pulse_variants[i];
        printf("➡ Sende mit Puls %d µs...\n", p);
        sendFrame(line, code, p);
    }
}

int main(int argc, char *argv[])
{
    if (argc != 3) {
        printf("Usage: %s <GPIO> <CODE>\n", argv[0]);
        return 1;
    }

    int pin = atoi(argv[1]);
    unsigned long code = strtoul(argv[2], NULL, 10);

    printf("=== 433 MHz Sender (Pi 5) ===\n");
    printf("GPIO: %d\n", pin);
    printf("Code: %lu\n\n", code);

    struct gpiod_chip *chip = gpiod_chip_open_by_number(0);
    if (!chip) {
        perror("gpiod_chip_open_by_number");
        return 1;
    }

    struct gpiod_line *line = gpiod_chip_get_line(chip, pin);
    if (!line) {
        perror("gpiod_chip_get_line");
        return 1;
    }

    if (gpiod_line_request_output(line, CONSUMER, 0) < 0) {
        perror("gpiod_line_request_output");
        return 1;
    }

    sendCode(line, code);

    gpiod_line_release(line);
    gpiod_chip_close(chip);

    printf("\n✔ Fertig.\n");
    return 0;
}
