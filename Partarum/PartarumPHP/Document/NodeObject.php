<?php
namespace Partarum\PartarumPHP\Document {

	use Partarum\PartarumPHP\Traits\Document\FeatHTMLAttributes;

	class NodeObject
	{
		use FeatHTMLAttributes;

		public $element;

		public $event;

		public $text;

		public function __construct($el = NULL)
		{

			($el !== NULL) && $this->element = $el;
		}

	}
}