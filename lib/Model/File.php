<?php

namespace MediaManager\Model;

class File {

    protected $library;
    protected $name;
    protected $filename;
    protected $mimetype;
    protected $size;

    /**
     * File constructor.
     * @param Library $library
     * @param $name
     * @param $filename
     * @param null $mimetype
     * @param int $size
     */
    public function __construct(Library $library, $name, $filename, $mimetype = null, $size = 0) {

        $this->name = $name;
        $this->filename = $filename;
        $this->size = $size;
        $this->mimetype = $mimetype;
        $this->library = $library;

    }

    /**
     * Return library that file belongs to
     * @return Library
     */
    public function library() {
        return $this->library;
    }

    /**
     * @return mixed
     */
    public function name() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function filename() {
        return $this->filename;
    }

    /**
     * @return mixed
     */
    public function mimetype() {
        return $this->mimetype;
    }

    /**
     * @return mixed
     */
    public function size() {
        return $this->size;
    }

}