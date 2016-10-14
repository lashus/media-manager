<?php

namespace MediaManager\Service;

use Gaufrette\Filesystem;
use MediaManager\Factory\FileFactory;
use MediaManager\Model\File;
use MediaManager\Model\Library;
use MediaManager\Model\LibraryRepositoryInterface;
use MediaManager\Model\ValueObject\LibraryId;
use MediaManager\Model\ValueObject\LibraryIdInterface;

/**
 * LibraryService responsible for library operations.
 *
 * @package MediaManager\Domain\Application\Container
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
     * LibraryService constructor.
     * @param FileFactory $fileFactory - File factory for creating files
     * @param LibraryRepositoryInterface $libraryRepository - Library repository for managing persistence layer
     * @param $strategy - Strategy for generting Ids for libraries
     */
    public function __construct(FileFactory $fileFactory, LibraryRepositoryInterface $libraryRepository, $strategy = LibraryId::class)
    {
        $this->fileFactory = $fileFactory;
        $this->libraryRepository = $libraryRepository;
        $this->strategy = $strategy;
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


}