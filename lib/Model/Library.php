<?php

namespace MediaManager\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gaufrette\Filesystem;
use MediaManager\Model\ValueObject\LibraryIdInterface;

class Library implements LibraryInterface {

    /**
     * @var LibraryIdInterface
     */
    protected $id;
    protected $created;
    protected $name;
    protected $filesystem;
    protected $files;

    /**
     * File constructor.
     * @param LibraryIdInterface $id
     * @param Filesystem $filesystem
     * @param $name
     */
    public function __construct(LibraryIdInterface $id, Filesystem $filesystem, $name) {

        $this->id = $id;
        $this->created = time();
        $this->name = $name;
        $this->filesystem = $filesystem;
        $this->files = new ArrayCollection();

    }

    /**
     * @return LibraryIdInterface
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
     * @return string
     */
    public function name() {
        return $this->name;
    }

    /**
     * @return Filesystem
     */
    public function filesystem() {
        return $this->filesystem;
    }

    /**
     * @return ArrayCollection
     */
    public function files() {
        return $this->files;
    }

    /**
     * Change the filesystem to new one.
     * @param Filesystem $filesystem
     * @return Library
     */
    public function changeFilesystem(Filesystem $filesystem) {
        $this->filesystem = $filesystem;

        return $this;
    }

}