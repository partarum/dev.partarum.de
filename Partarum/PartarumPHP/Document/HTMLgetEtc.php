<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use Partarum\PartarumPHP\Config\GlobalConfig;
    use Partarum\PartarumPHP\Filesystem\FilesystemGenerator;
    use Partarum\PartarumPHP\Traits\Document\FeatHTMLgetEtc;
    use stdClass;

    class HTMLgetEtc {

        use FeatHTMLgetEtc;

        public const HTML_PATH = "/Partarum/etc/";

        public const DASHBOARD_PATH = "/Partarum/src/php/classes/DASHBOARD/surface/html";

        public const DS = DIRECTORY_SEPARATOR;

        public ?string $extension = null;

        public array $templateExtension = [
            "txt",
            "plain",
            "html",
            "js"
        ];

        public HTMLObject $etc;

        public object $templatePaths;

        public array $mainTags = [
            "head" => "head",
            "header" => "1#header",
            "main" => "2#main",
            "footer" => "3#footer"
        ];

        public array $paths = [
            "head" => "surface\\html\\head",
            "header" => "surface\\html\\body\\wrapper\\1#header",
            "main" => "surface\\html\\body\\wrapper\\2#main",
            "footer" => "surface\\html\\body\\wrapper\\3#footer"
        ];

        public array $pattern = [];

        public ?FilesystemGenerator $iterator = null;

        public string $theme;

        public function __construct(string $theme) {

            $this->theme = $theme;
            $this->etc = new HTMLObject();
            $this->templatePaths = new stdClass();
            $this->setPattern();
            $this->getIterator();
            $this->getEtc();
        }

        public function getEtc(): void {

            if ($this->iterator === null) {
                return;
            }

            $wwwRoot = str_replace("/", "\\", $this->resolveRootPath());

            $this->etc = new HTMLObject();
            $this->templatePaths = new stdClass();

            $pattern = $this->pattern;

            if ($this->theme !== "theOffice") {
                $filterDOMPathNeedle = $wwwRoot . "\\Partarum\\etc\\" . $this->theme . "\\";
            } else {
                $filterDOMPathNeedle = $wwwRoot . "\\Partarum\\src\\php\\classes\\DASHBOARD\\";
            }

            $directory = $this->iterator->getFile();

            foreach ($directory as $file) {

                $fileName = $file->getBasename();
                $fileExtension = $file->getExtension();

                if (!in_array($fileExtension, $this->templateExtension, true)) {

                    $path = $file->getPath();

                    $tagName = $file->getBasename(".php");
                    $elementName = (string)preg_replace("![^a-z]!", "", $tagName);

                    $filePath = $file->getPathname();

                    if (is_file($filePath)) {
                        include_once $filePath;
                    }

                    $filterNamespaceNeedle = (GlobalConfig::$theme !== "theOffice")
                        ? $wwwRoot . "\\Partarum\\"
                        : $wwwRoot . "\\Partarum\\src\\php\\classes\\";

                    $filePathWithBackslash = str_replace("/", "\\", str_replace(["1#", "2#", "3#"], "", $filePath));
                    $filterDOMPath = str_replace($filterDOMPathNeedle, "", $filePathWithBackslash);

                    $domPath = str_replace($fileName, $tagName, $filterDOMPath);
                    $slashPosParentPath = strrpos($domPath, "\\");
                    $domParentPath = $slashPosParentPath !== false ? substr($domPath, 0, $slashPosParentPath) : $domPath;

                    $slashPos = strrpos($domParentPath, "\\");
                    $domParentParentPath = $slashPos !== false ? substr($domParentPath, 0, $slashPos) : $domParentPath;

                    $filterNamespacePath = str_replace($filterNamespaceNeedle, "", $filePathWithBackslash);
                    $namespacePath = str_replace($fileName, $tagName, $filterNamespacePath);

                    foreach ($pattern as $key => $patt) {

                        if (preg_match($patt, $path)) {

                            $newPath = $key;

                            $objectLevelOnePos = strrpos($filePath, $this->mainTags[$key]);
                            $objectLevelOne = $objectLevelOnePos !== false ? substr($filePath, $objectLevelOnePos) : $filePath;
                            $objectLevelOnePath = (string)preg_replace("![0-9]#!", "", $objectLevelOne);

                            $this->etc->$newPath->$objectLevelOnePath = new HTMLetcElementInfo();
                            $this->etc->$newPath->$objectLevelOnePath->tagName = $elementName;
                            $this->etc->$newPath->$objectLevelOnePath->domPath = $domPath;
                            $this->etc->$newPath->$objectLevelOnePath->domParentPath = $domParentPath;
                            $this->etc->$newPath->$objectLevelOnePath->domParentParentPath = $domParentParentPath;
                            $this->etc->$newPath->$objectLevelOnePath->namespacePath = $namespacePath;
                        }
                    }
                } else {

                    $filterPropPath = str_replace("/", "\\", $file->getPath());
                    $propPath = str_replace($filterDOMPathNeedle, "", $filterPropPath);
                    $textFilePath = $file->getPathname();
                    $prop = (string)preg_replace("/[0-9]#/", "", $propPath);

                    $this->templatePaths->$prop = $textFilePath;
                    $this->extension = $fileExtension;
                }
            }
        }
    }
}