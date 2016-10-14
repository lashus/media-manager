<?php

namespace MediaManager\Factory;

use MediaManager\Model\File;
use MediaManager\Model\Library;

/**
 * File factory class
 * @package MediaManager\Factory
 */
class FileFactory {

    /**
     * It's a static factory - disable constructor
     */
    private function __construct() {
        return false;
    }

    public static function createFactory() {
        return new self();
    }

    /**
     * Creates new file in given library
     *
     * @param Library $library
     * @param $name
     * @param $filename
     * @param $content
     * @return File
     */
    public static function create(Library $library, $name, $filename, $content) {

        $fs = $library->filesystem();
        $fs->write($filename, $content, true); // will overwrite existing file
        $mimetype = $fs->mimeType($filename);
        $size = $fs->size($filename);

        $file = new File($library, $name, $filename, $mimetype, $size);

        return $file;
        
    }
    
}