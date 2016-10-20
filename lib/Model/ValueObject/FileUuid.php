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
 * Identifier for a File - uuid by Ramsey
 */
class FileUuid implements FileIdInterface
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @param string $fileId
     * @return FileUuid
     */
    public static function fromString($fileId)
    {
        return new self(Uuid::fromString($fileId));
    }

    /**
     * @return FileUuid
     */
    public static function generate()
    {
        return new self(Uuid::uuid4());
    }


    /**
     * Always provide a string representation of the FileUuid to construct the VO
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
        return $this->uuid->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param FileIdInterface $other
     * @return bool
     */
    public function sameValueAs(FileIdInterface $other)
    {
        return $this->toString() === $other->toString();
    }

}