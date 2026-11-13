<?php
namespace Partarum\PartarumConfig {

     enum AutoloadManifest: string {

         case Cache = "Partarum\PartarumPHP\Cache";
         case Config = "Partarum\PartarumPHP\Config";
         case Database = "Partarum\PartarumPHP\Database";
         case Design = "Partarum\PartarumPHP\Design";
         case Document = "Partarum\PartarumPHP\Document";
         case Filesystem = "Partarum\PartarumPHP\Filesystem";
         case HTTP = "Partarum\PartarumPHP\HTTP";
         case Security = "Partarum\PartarumPHP\Security";
         case Service = "Partarum\PartarumPHP\Service";
         case System = "Partarum\PartarumPHP\System";
         case Test = "Partarum\PartarumPHP\Test";
         case Traits = "Partarum\PartarumPHP\Traits";
         case Workspace = "Partarum\PartarumWorkspace";
    }
}