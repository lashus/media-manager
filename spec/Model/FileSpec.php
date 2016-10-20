<?php

namespace spec\MediaManager\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gaufrette\Filesystem;
use MediaManager\Model\Library;
use MediaManager\Model\ValueObject\FileUuid;
use MediaManager\Model\ValueObject\LibraryId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
    function it_is_initializable(Library $library)
    {
        $id = FileUuid::generate();
        $name = microtime(true);
        $this->beConstructedWith($id, $library, $name, $name);
    }

    function it_gives_access_to_fields(Library $library)
    {
        $name = microtime(true);
        $id = FileUuid::generate();
        $this->beConstructedWith($id, $library, $name, $name);

        $this->id()->shouldReturn($id);
        $this->name()->shouldReturn($name);
        $this->filename()->shouldReturn($name);
        $this->library()->shouldReturn($library);
        $this->size()->shouldReturn(0);
        $this->mimetype()->shouldReturn(null);
    }

}
