<?php
/**
 * Created by PhpStorm.
 * User: agorny
 * Date: 14.10.16
 * Time: 10:43
 */

namespace MediaManager\Model\ValueObject;

interface LibraryIdInterface
{
    public function sameValueAs(LibraryIdInterface $other);
    public function toString();
    public static function fromString($libraryId);
    public static function generate();
}