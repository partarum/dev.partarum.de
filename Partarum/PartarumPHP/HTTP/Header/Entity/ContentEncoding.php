<?php
namespace Partarum\PartarumPHP\HTTP\Header\Entity {

    use InvalidArgumentException;
    use Partarum\PartarumPHP\HTTP\Header\Entity;

    class ContentEncoding {

        public const GZIP = "gzip";

        public const COMPRESS = "compress";

        public const DEFLATE = "deflate";

        public const BROTLI = "br";

    }
}