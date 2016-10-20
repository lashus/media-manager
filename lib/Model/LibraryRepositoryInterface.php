<?php

namespace MediaManager\Model;

use MediaManager\Model\Library;
use MediaManager\Model\ValueObject\FileUuid;
use MediaManager\Model\ValueObject\LibraryId;

/**
 * Interface for library repository
 * @package MediaManager\Model
 */
interface LibraryRepositoryInterface {

    /**
     * Returns library by given id (VO)
     *
     * @param LibraryId $id
     * @return mixed
     */
    public function get(LibraryId $id);

    /**
     * Store a library in persistence store
     *
     * @param LibraryInterface $library
     * @return mixed
     */
    public function store(LibraryInterface $library);

    /**
     * Get all libraries
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get all files belonging to library
     *
     * @param LibraryInterface $library
     * @return mixed
     */
    public function getLibraryFiles(LibraryInterface $library);

    /**
     * Get a library by given name
     *
     * @param $name
     * @return mixed
     */
    public function findLibraryByName($name);

    /**
     * Returns next id (for integer)
     *
     * @param $strategy
     * @return mixed
     */
    public function getNextId($strategy);


    /**
     * Returns library with given id
     * @param $id
     * @return mixed
     */
    public function getById(LibraryInterface $id);

    /**
     * Returns library file specified by id
     *
     * @param $id
     * @return mixed
     */
    public function getLibraryFile(FileUuid $id);

}