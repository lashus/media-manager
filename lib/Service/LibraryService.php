<?php

namespace MediaManager\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Gaufrette\Filesystem;
use MediaManager\Factory\FileFactory;
use MediaManager\Model\File;
use MediaManager\Model\Library;
use MediaManager\Model\LibraryInterface;
use MediaManager\Model\LibraryRepositoryInterface;
use MediaManager\Model\ValueObject\FileUuid;
use MediaManager\Model\ValueObject\LibraryId;
use MediaManager\Model\ValueObject\LibraryIdInterface;

/**
 * LibraryService responsible for library operations.
 *
 * @package MediaManager\Service
 */
class LibraryService {

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var LibraryRepositoryInterface
     */
    protected $libraryRepository;

    /**
     * Allowed filters (keys) for library query
     *
     * @var array
     */
    protected $allowedLibraryFilters = array('name');

    /**
     * Allowed filters (keys) for file query
     *
     * @var array
     */
    protected $allowedFileFilters = array('name', 'size', 'mimetype');

    /**
     * LibraryService constructor.
     * @param FileFactory $fileFactory - File factory for creating files
     * @param LibraryRepositoryInterface $libraryRepository - Library repository for managing persistence layer
     * @param $strategy - Strategy for generting Ids for libraries
     * @param Filesystem $filesystem
     */
    public function __construct(FileFactory $fileFactory, LibraryRepositoryInterface $libraryRepository, $strategy = LibraryId::class, Filesystem $filesystem)
    {
        $this->fileFactory = $fileFactory;
        $this->libraryRepository = $libraryRepository;
        $this->strategy = $strategy;
        $this->filesystem = $filesystem;
    }

    /**
     * Set new allowed filters (library)
     *
     * @param $keys
     * @return $this
     */
    public function setAllowedLibraryFilters($keys) {
        $this->allowedLibraryFilters = $keys;

        return $this;
    }

    /**
     * Set new allowed filters (file)
     *
     * @param $keys
     * @return $this
     */
    public function setAllowedFileFilters($keys) {
        $this->allowedFileFilters = $keys;

        return $this;
    }

    /**
     * Generate ID for given strategy
     * @param string $strategy
     * @return mixed
     */
    public static function generateId($strategy)
    {

        $implements = class_implements($strategy);
        if(!in_array(LibraryIdInterface::class, $implements)) {
            throw new \InvalidArgumentException('Strategy for generating Uuid not supported');
        }

        return $strategy::generate();

    }

    /**
     * Create new library specified by name and filesystem
     * @param $name
     * @param Filesystem $filesystem
     * @return Library
     */
    public function createLibrary($name, Filesystem $filesystem) {

        $id = self::generateId($this->strategy);
        return new Library($id, $filesystem, $name);

    }

    /**
     * Find file by name in a library
     *
     * @param Library $library
     * @param $name
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function findFileByName(Library $library, $name) {

        /**
         * Filter from library files
         */
        return $library->files()->filter(function($item) use ($name) {
            if($item->name() == $name) {
                return true;
            }

            return false;
        });

    }

    /**
     * Add file to specified library
     *
     * @param File $file - File
     * @param Library $library - Library to store a file
     * @return Library
     */
    public function addFileToLibrary(File $file, Library $library) {

        $library->files()->add($file);
        $this->libraryRepository->store($library);

        return $library;

    }

    /**
     * Add file to specified library
     * @param File $file - File to be deleted
     * @param Library $library - Library with a file
     * @param bool $unlink - Delete from disk flag
     * @return Library
     */
    public function removeFileFromLibrary(File $file, Library $library, $unlink = true) {

        $library->files()->removeElement($file);

        if($unlink) {
            $library->filesystem()->delete($file->name());
        }

        return $library;

    }

    /**
     * Returns available libraries
     * @return array|Library[]
     */
    public function getLibraries()
    {
        $data = $this->libraryRepository->getAll();

        return $data;
    }

    /**
     * Returns available libraries
     * @return array|Library[]
     */
    public function getFilteredLibraries($filters)
    {
        $parsedFilters = array();
        foreach($filters as $key=>$filter) {

            if(in_array($key, $this->allowedLibraryFilters)) {
                $filter = str_replace('*', '%', $filter);

                // check if we have range
                if(strpos($filter, '|AND|') !== false) {
                    list($min, $max) = explode('|AND|', $filter);

                    if($key == 'size') {
                        $min = $this->parseFilterValue($min);
                        $max = $this->parseFilterValue($max);
                    }

                    $parsedFilters[$key] = array('type'=>'range', 'min'=>$min, 'max'=>$max);
                } else {
                    $parsedFilters[$key] = array('type'=>'text', 'value'=>$filter);
                }

            }
        }

        $data = $this->libraryRepository->getByParams($parsedFilters);

        return $data;
    }

    /**
     * Returns available libraries
     * @return array|Library[]
     */
    public function getFilteredLibraryFiles(LibraryInterface $library, $filters)
    {
        $parsedFilters = array();
        foreach($filters as $key=>$filter) {

            if(in_array($key, $this->allowedFileFilters)) {
                $filter = str_replace('*', '%', $filter);

                // check if we have range
                if(strpos($filter, '|AND|') !== false) {
                    list($min, $max) = explode('|AND|', $filter);

                    if($key == 'size') {
                        $min = $this->parseFilterValue($min);
                        $max = $this->parseFilterValue($max);
                    }

                    $parsedFilters[$key] = array('type'=>'range', 'min'=>$min, 'max'=>$max);
                } else {
                    $parsedFilters[$key] = array('type'=>'text', 'value'=>$filter);
                }

            }
        }

        return $this->libraryRepository->getLibraryFiles($library, $parsedFilters);
    }

    /**
     * Adds a library identified by name
     * @param $name
     * @param Filesystem $filesystem
     * @return mixed
     */
    public function addLibrary($name, Filesystem $filesystem)
    {
        $library = new Library($this->libraryRepository->getNextId($this->strategy), $filesystem, $name);
        $this->libraryRepository->store($library);

        return true;
    }

    /**
     * Returns library with given id
     * @param LibraryIdInterface $id
     * @return mixed
     */
    public function getById(LibraryIdInterface $id)
    {
        $library = $this->libraryRepository->get($id);
        if($library) {
            $library->changeFilesystem($this->filesystem);
        }

        return $library;
    }

    /**
     * Returns all files associated to given library
     * @param LibraryInterface $library
     * @return ArrayCollection|File[]
     */
    public function getLibraryFiles(LibraryInterface $library)
    {
        return $this->libraryRepository->getLibraryFiles($library);
    }

    /**
     * @param FileUuid $id
     * @return mixed
     */
    public function getLibraryFile(FileUuid $id)
    {
        return $this->libraryRepository->getLibraryFile($id);
    }

    /**
     * Parses filter value, size (G, M, K) to bytes
     * @param string $filter
     * @return float
     */
    protected function parseFilterValue($filter)
    {

        if(strpos($filter, 'G')) {
            $filter = (float)str_replace('G', '', $filter)*1024*1024*1024;
        }
        if(strpos($filter, 'M')) {
            $filter = (float)str_replace('M', '', $filter)*1024*1024;
        }
        if(strpos($filter, 'K')) {
            $filter = (float)str_replace('K', '', $filter)*1024;
        }

        return ceil($filter);
    }


}