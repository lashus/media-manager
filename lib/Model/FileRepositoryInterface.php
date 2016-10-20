<?php

namespace MediaManager\Model;

use MediaManager\Model\File;
use MediaManager\Model\ValueObject\FileId;
use MediaManager\Model\ValueObject\FileUuid;

/**
 * Interface for library repository
 * @package MediaManager\Model
 */
interface FileRepositoryInterface {

    /**
     * Returns library by given id (VO)
     *
     * @param FileUuid $id
     * @return mixed
     */
    public function get(FileUuid $id);

    /**
     * Store a file in persistence store
     *
     * @param FileInterface $library
     * @return mixed
     */
    public function store(FileInterface $library);

    /**
     * Get all files
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get a file by given name
     *
     * @param $name
     * @return mixed
     */
    public function findFileByName($name);

}