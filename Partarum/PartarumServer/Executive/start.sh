#!/bin/bash
#
# Brief description of your script
# Copyright 2023 bombi

function main() {

    pwd >> pwd.txt

    #denon run --allow-net --allow-run --allow-read --allow-write ./Partarum/PartarumServer/server.js
    #which screen

    cd Partarum/PartarumServer/Executive || exit

    ls >> d.txt

    pup --upgrade

    #echo "Start Screen-Session"
    #screen -dmS executive_Start_index
    #echo "List Screen-Sessions"
    #screen -list
    #echo "add stuff to Screen-Session"
    #screen -S executive_Start_index -X stuff "pup \n"
    #bash screen -S executive_Start_index -X stuff "denon run --allow-net --allow-run --allow-read --allow-write ./index.js"
}

main "$@"