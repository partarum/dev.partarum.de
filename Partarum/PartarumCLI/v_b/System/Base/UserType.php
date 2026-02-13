<?php
namespace Partarum\PartarumCLI\v_b\System\Base {

    enum UserType : int{

        case CHECK = 0x0001;
        case PHP = 0x0002;
        case ROOT = 0x0004;
        case SHELL = 0x0008;
    }
}