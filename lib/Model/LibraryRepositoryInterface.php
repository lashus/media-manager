<?php

namespace MediaManager\Model;

use MediaManager\Model\Library;
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
     * @param Library $library
     * @return mixed
     */
    public function store(Library $library);

    /**
     * Get all libraries
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get all files belonging to library
     *
     * @param Library $library
     * @return mixed
     */
    public function getLibraryFiles(Library $library);

    /**
     * Get a library by given name
     *
     * @param $name
     * @return mixed
     */
    public function findLibraryByName($name);

}