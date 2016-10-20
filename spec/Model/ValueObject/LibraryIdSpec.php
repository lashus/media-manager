<?php

namespace spec\MediaManager\Model\ValueObject;

use MediaManager\Model\ValueObject\LibraryId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LibraryIdSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('abc');
        $this->shouldHaveType('MediaManager\\Model\\ValueObject\\LibraryId');
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();

        $this->beConstructedWith(2);
        $this->shouldNotThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_can_generate()
    {
        $this->beConstructedWith(1);
        $this->generate()->shouldReturnAnInstanceOf(LibraryId::class);
    }

    function it_can_be_created_from_string()
    {
        $this->beConstructedWith(1);
        $this->fromString(1)->shouldReturnAnInstanceOf(LibraryId::class);
    }

    function it_can_be_converted_to_string()
    {
        $this->beConstructedWith(1);
        $this->toString()->shouldReturn("1");
        $this->__toString()->shouldReturn("1");

        $other = new LibraryId(1);
        $this->sameValueAs($other)->shouldReturn(true);

        $other = new LibraryId(3);
        $this->sameValueAs($other)->shouldReturn(false);
    }

}
