<?php

namespace MediaManager\Repository;

use Doctrine\ORM\EntityManagerInterface;
use MediaManager\Model\Library;
use MediaManager\Model\LibraryRepositoryInterface;
use MediaManager\Model\ValueObject\LibraryId;

/**
 * Class DoctrineLibraryRepository
 * @package MediaManager\Repository
 */
class DoctrineLibraryRepository implements LibraryRepositoryInterface {

    protected $em;
    protected $fileClass;
    protected $libraryClass;

    /**
     * DoctrineLibraryRepository constructor.
     *
     * @param EntityManagerInterface $em
     * @param $libraryClass
     * @param $fileClass
     */
    public function __construct(EntityManagerInterface $em, $libraryClass, $fileClass) {
        $this->em = $em;
        $this->libraryClass = $libraryClass;
        $this->fileClass = $fileClass;
    }

    /**
     * @param LibraryId $id
     * @return mixed
     */
    public function get(LibraryId $id)
    {
        return $this->em->getRepository($this->libraryClass)->find($id);
    }

    /**
     * @param \MediaManager\Model\Library $library
     * @return mixed
     */
    public function store(Library $library)
    {
        $this->em->persist($library);
        $this->em->flush();

        return ;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $this->em->getRepository($this->libraryClass)->findAll();
    }

    /**
     * @param \MediaManager\Model\Library $library
     * @return mixed
     */
    public function getLibraryFiles(Library $library)
    {
        $this->em->getRepository($this->fileClass)->findBy(array('library'=>$library));
    }

    /**
     * @param $name
     * @return mixed
     */
    public function findLibraryByName($name)
    {
        $this->em->getRepository($this->fileClass)->findBy(array('name'=>$name));
    }


}