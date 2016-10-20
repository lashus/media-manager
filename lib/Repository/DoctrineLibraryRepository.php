<?php

namespace MediaManager\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use MediaManager\Model\File;
use MediaManager\Model\Library;
use MediaManager\Model\LibraryInterface;
use MediaManager\Model\LibraryRepositoryInterface;
use MediaManager\Model\ValueObject\FileUuid;
use MediaManager\Model\ValueObject\LibraryId;
use MediaManager\Model\ValueObject\LibraryUuid;
use Psr\Log\InvalidArgumentException;

/**
 * Class DoctrineLibraryRepository
 * @package MediaManager\Repository
 */
class DoctrineLibraryRepository extends EntityRepository implements LibraryRepositoryInterface {

    protected $em;
    protected $fileClass;
    protected $libraryClass;

    /**
     * DoctrineLibraryRepository constructor.
     *
     * @param EntityManager $em
     * @param $libraryClass
     * @param $fileClass
     */
    public function __construct(EntityManager $em, $classMetadata = null, $libraryClass= Library::class, $fileClass = File::class) {

        parent::__construct($em, $classMetadata);

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
     * @param \MediaManager\Model\LibraryInterface $library
     * @return mixed
     */
    public function store(LibraryInterface $library)
    {
        $this->em->persist($library);
        $this->em->flush();

        return true;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->em->getRepository($this->libraryClass)->findAll();
    }

    /**
     * @param \MediaManager\Model\LibraryInterface $library
     * @return mixed
     */
    public function getLibraryFiles(LibraryInterface $library)
    {
        return $this->em->getRepository($this->fileClass)->findBy(array('library'=>$library));
    }

    /**
     * @param $name
     * @return mixed
     */
    public function findLibraryByName($name)
    {
        return $this->em->getRepository($this->libraryClass)->findBy(array('name'=>$name));
    }

    /**
     * Returns next id - only usable for integer strategy
     * 
     * @return LibraryId
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNextId($strategy = LibraryId::class) {

        switch($strategy) {
            case LibraryId::class:
                $qb = $this->em->getRepository($this->libraryClass)->createQueryBuilder('a');
                $qb->select('a.id')
                    ->setMaxResults(1)
                    ->orderBy('a.id', 'DESC');

                $result = $qb->getQuery()->getArrayResult();

                if($result) {
                    $id = (string)$result[0]['id'];
                    return LibraryId::fromString($id+1);
                } else {
                    return LibraryId::generate();
                }
            break;

            case LibraryUuid::class:
                return LibraryUuid::generate();
            break;

            default:
                throw new InvalidArgumentException('Strategy not found.');
        }
    }

    /**
     * @param \MediaManager\Model\ValueObject\FileUuid $id
     * @return mixed|null|object
     */
    public function getLibraryFile(FileUuid $id) {
        return $this->em->getRepository($this->fileClass)->find($id);
    }


    /**
     * Returns library with given id
     * @param LibraryInterface $id
     * @return mixed
     */
    public function getById(LibraryInterface $id)
    {
        return $this->em->getRepository($this->libraryClass)->find($id);
    }
}