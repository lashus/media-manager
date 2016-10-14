<?php

namespace spec\MediaManager\Model\ValueObject;

use MediaManager\Model\ValueObject\LibraryUuid;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class LibraryUuidSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldThrow('\TypeError')->during('__construct', ['abc']);

        $this->beConstructedWith(Uuid::uuid4());
        $this->shouldNotThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_can_generate()
    {
        $this->beConstructedWith(Uuid::uuid4());
        $this->generate()->shouldReturnAnInstanceOf(LibraryUuid::class);
    }

    function it_can_be_created_from_string()
    {
        $this->beConstructedWith(Uuid::uuid4());
        $this->fromString('1164d3e1-b61a-4a7b-b752-bf9325b3fcfb')->shouldReturnAnInstanceOf(LibraryUuid::class);
    }

    function it_can_be_converted_to_string()
    {
        $this->beConstructedThrough('fromString', ['1164d3e1-b61a-4a7b-b752-bf9325b3fcfb']);
        $this->toString()->shouldReturn('1164d3e1-b61a-4a7b-b752-bf9325b3fcfb');
        $this->__toString()->shouldReturn('1164d3e1-b61a-4a7b-b752-bf9325b3fcfb');

        $other = LibraryUuid::fromString('1164d3e1-b61a-4a7b-b752-bf9325b3fcfc');
        $this->sameValueAs($other)->shouldReturn(false);
    }

}
