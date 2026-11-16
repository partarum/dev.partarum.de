<?php
declare(strict_types=1);
/**
 *          Copyright 2020 © Alexander Bombis. All rights reserved.
 *          Developed by Alexander Bombis.
 *          Email: email@alexander-bombis.de
 *
 *          The following code was created based on the template for the website http://cordes-software.de.
 *          This may also be used in full by the person or business
 *          representing the domain "cordes-software.de"
 *          and also modified for their use.
 */
namespace Partarum\PartarumPHP\Traits;

trait FeatTemplateHelper {

    protected function setListOfLinks(iterable $array, string | false $language = false): void {

        foreach ($array as $value) {
            if (!is_object($value)) {
                continue;
            }

            if (empty($value->display)) {
                $href = isset($value->href) ? (string)$value->href : "";
                $navOne = "<li><a href='{$href}' ";

                $props = get_object_vars($value);
                if (count($props) > 2) {
                    foreach ($props as $nextAttr => $nextAttrValue) {
                        if (($nextAttr !== "href") && ($nextAttr !== "text") && is_scalar($nextAttrValue)) {
                            $navOne .= $nextAttr . "='" . $nextAttrValue . "'";
                        }
                    }
                }
                $navOne .= " >";

                if (isset($value->text) && is_string($value->text)) {
                    $navOne .= $value->text;
                } elseif ($language !== false && isset($value->text) && is_object($value->text) && isset($value->text->{$language})) {
                    $navOne .= (string)$value->text->{$language};
                }

                $navOne .= "</a></li>";

                echo $navOne;
            }
        }
    }

    protected function setHeadline(string | object $text, int | string $size = 5, string $language = "de"): void {

        $textValue = $this->setLanguage($text, $language);

        echo "<h5>" . $textValue . "</h5>";
    }

    protected function setLanguage(string | object $item, string $language = "de"): string {

        if (is_string($item)) {
            return $item;
        }

        return isset($item->{$language}) ? (string)$item->{$language} : "";
    }
}