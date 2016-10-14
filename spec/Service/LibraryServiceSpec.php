<?php

namespace spec\MediaManager\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Gaufrette\Filesystem;
use MediaManager\Factory\FileFactory;
use MediaManager\Model\File;
use MediaManager\Model\Library;
use MediaManager\Model\LibraryRepositoryInterface;
use MediaManager\Model\ValueObject\LibraryId;
use MediaManager\Model\ValueObject\LibraryUuid;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LibraryServiceSpec extends ObjectBehavior
{
    function it_is_initializable(FileFactory $fileFactory, LibraryRepositoryInterface $libraryRepository, $strategy = LibraryId::class)
    {
        $this->beConstructedWith($fileFactory, $libraryRepository, $strategy);
        $this->shouldHaveType('MediaManager\\Service\\LibraryService');
    }

    function it_should_find_file_by_name(FileFactory $fileFactory, LibraryRepositoryInterface $libraryRepository, Filesystem $fs, File $file)
    {
        $strategy = LibraryId::class;
        $this->beConstructedWith($fileFactory, $libraryRepository, $strategy);
        $this->shouldHaveType('MediaManager\\Service\\LibraryService');

        $name = microtime(true);
        $library = $this->createLibrary($name, $fs)->shouldReturnAnInstanceOf(Library::class);
        $this->findFileByName($library, $file->name())->shouldReturnAnInstanceOf(ArrayCollection::class);
    }

    function it_should_add_file_to_library(FileFactory $fileFactory, LibraryRepositoryInterface $libraryRepository, Filesystem $fs, File $file) {

        $strategy = LibraryId::class;
        $this->beConstructedWith($fileFactory, $libraryRepository, $strategy);
        $this->shouldHaveType('MediaManager\\Service\\LibraryService');

        $name = microtime(true);
        $library = $this->createLibrary($name, $fs)->shouldReturnAnInstanceOf(Library::class);
        $this->addFileToLibrary($file, $library)->shouldReturnAnInstanceOf(Library::class);

    }

    function it_should_remove_file_from_library(FileFactory $fileFactory, LibraryRepositoryInterface $libraryRepository, Filesystem $fs, File $file) {

        $strategy = LibraryId::class;
        $this->beConstructedWith($fileFactory, $libraryRepository, $strategy);
        $this->shouldHaveType('MediaManager\\Service\\LibraryService');

        $name = microtime(true);
        $library = $this->createLibrary($name, $fs)->shouldReturnAnInstanceOf(Library::class);
        $this->removeFileFromLibrary($file, $library)->shouldReturnAnInstanceOf(Library::class);

    }

    function it_should_not_generate_id(FileFactory $fileFactory, LibraryRepositoryInterface $libraryRepository)
    {
        $strategy = Library::class;
        $this->beConstructedWith($fileFactory, $libraryRepository, $strategy);
        $this->shouldHaveType('MediaManager\\Service\\LibraryService');
        $this->shouldThrow(\InvalidArgumentException::class)->duringGenerateId($strategy);
    }

    function it_should_generate_id(FileFactory $fileFactory, LibraryRepositoryInterface $libraryRepository)
    {
        $strategy = LibraryId::class;
        $this->beConstructedWith($fileFactory, $libraryRepository, $strategy);
        $this->shouldHaveType('MediaManager\\Service\\LibraryService');
        $this->generateId($strategy)->shouldReturnAnInstanceOf($strategy);
    }

    function it_should_generate_uuid(FileFactory $fileFactory, LibraryRepositoryInterface $libraryRepository)
    {
        $strategy = LibraryUuid::class;
        $this->beConstructedWith($fileFactory, $libraryRepository, $strategy);
        $this->shouldHaveType('MediaManager\\Service\\LibraryService');
        $this->generateId($strategy)->shouldReturnAnInstanceOf($strategy);
    }
}
