<?php

namespace MediaManager\Model;

use MediaManager\Model\ValueObject\FileIdInterface;

class File implements FileInterface {

    protected $id;
    protected $created;
    protected $library;
    protected $name;
    protected $filename;
    protected $mimetype;
    protected $size;

    /**
     * File constructor.
     * @param FileIdInterface $id
     * @param Library $library
     * @param $name
     * @param $filename
     * @param null $mimetype
     * @param int $size
     */
    public function __construct(FileIdInterface $id, Library $library, $name, $filename, $mimetype = null, $size = 0) {

        $this->id = $id;
        $this->created = time();
        $this->name = $name;
        $this->filename = $filename;
        $this->size = $size;
        $this->mimetype = $mimetype;
        $this->library = $library;

    }

    /**
     * Returns file id
     */
    public function id() {
        return $this->id;
    }

    /**
     * Returns creation date
     * @return int
     */
    public function created() {
        return $this->created;
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