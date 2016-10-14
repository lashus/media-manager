<?php

namespace spec\MediaManager\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gaufrette\Filesystem;
use MediaManager\Model\ValueObject\LibraryId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LibrarySpec extends ObjectBehavior
{
    function it_is_initializable(Filesystem $fs, LibraryId $id)
    {
        $name = microtime(true);
        $this->beConstructedWith($id, $fs, $name);
    }

    function it_gives_access_to_fields(Filesystem $fs, LibraryId $id)
    {
        $name = microtime(true);
        $this->beConstructedWith($id, $fs, $name);

        $this->name()->shouldReturn($name);
        $this->filesystem()->shouldReturn($fs);
        $this->id()->shouldReturn($id);
        $this->files()->shouldReturnAnInstanceOf(ArrayCollection::class);

        $this->changeFilesystem($fs)->shouldReturnAnInstanceOf($this);
    }

}
