<?php
namespace Partarum\PartarumPHP\HTTP\Header\Entity {

    use InvalidArgumentException;
    use Partarum\PartarumPHP\HTTP\Header\Entity;

    class ContentDisposition {

        public const INLINE = "inline";

        public const ATTACHMENT = "attachment";

        public const FORM = "form-data";
    }
}