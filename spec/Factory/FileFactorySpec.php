<?php

namespace spec\MediaManager\Factory;

use Gaufrette\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;
use MediaManager\Model\File;
use MediaManager\Model\Library;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileFactorySpec extends ObjectBehavior
{
    function it_should_create_file(Library $library, Filesystem $fs, $name, $filename, $content)
    {
        $this
            ->shouldThrow(\Exception::class)
            ->during('__construct', []);

        $library->filesystem()->willReturn($fs);

        $this->beConstructedThrough('createFactory');
        $this::create($library, $name, $filename, $content)->shouldReturnAnInstanceOf(File::class);
    }

}
