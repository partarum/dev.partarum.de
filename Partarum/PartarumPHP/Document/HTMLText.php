<?php
namespace Partarum\PartarumPHP\Document {

    use \DOMText as DOMText;

    class HTMLText {

        public function __construct($text, $element)
        {
            //print_r(gettype($text));


                 $element->appendChild(new DOMText($text));


        }
    }
}

/*
 *  Hinzufügen von Elementen wie <i> und <span> und </br> usw.......Textelemente!!!
 */