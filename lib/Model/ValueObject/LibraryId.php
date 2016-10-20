<?php
/**
 * Created by PhpStorm.
 * User: agorny
 * Date: 14.10.16
 * Time: 09:39
 */

namespace MediaManager\Model\ValueObject;
use Webmozart\Assert\Assert;

/**
 * Identifier for a Library - simple id integer
 */
class LibraryId implements LibraryIdInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @param string $libraryId
     * @return LibraryId
     */
    public static function fromString($libraryId)
    {
        return new self((int)$libraryId);
    }

    /**
     * @return LibraryId
     */
    public static function generate()
    {
        return new self(1);
    }
    
    /**
     * Always provide a string representation of the LibraryId to construct the VO
     *
     * @param integer $id
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($id)
    {
        Assert::integer($id);
        
        $this->id = (int)$id;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->toString();
    }

    /**
     * @param LibraryIdInterface $other
     * @return bool
     */
    public function sameValueAs(LibraryIdInterface $other)
    {
        return $this->toString() === $other->toString();
    }

}