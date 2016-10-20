<?php
/**
 * Created by PhpStorm.
 * User: agorny
 * Date: 14.10.16
 * Time: 09:39
 */

namespace MediaManager\Model\ValueObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Identifier for a Library - uuid by Ramsey
 */
class LibraryUuid implements LibraryIdInterface
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @param string $libraryId
     * @return LibraryId
     */
    public static function fromString($libraryId)
    {
        return new self(Uuid::fromString($libraryId));
    }

    /**
     * @return LibraryId
     */
    public static function generate()
    {
        return new self(Uuid::uuid4());
    }


    /**
     * Always provide a string representation of the LibraryId to construct the VO
     *
     * @param UuidInterface $uuid
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return (string)$this->uuid->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->toString();
    }

    /**
     * @param LibraryId $other
     * @return bool
     */
    public function sameValueAs(LibraryIdInterface $other)
    {
        return $this->toString() === $other->toString();
    }

}