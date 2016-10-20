<?php

namespace MediaManager\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use MediaManager\Model\ValueObject\LibraryUuid;
use Ramsey\Uuid\Doctrine\UuidType;

/**
 * Class LibraryUuidType
 * @package MediaManager\Doctrine\Type
 */
final class LibraryUuidType extends UuidType
{
    const NAME = 'library_uuid';

    /**
     * {@inheritdoc}
     *
     * @param string|null $value
     * @param AbstractPlatform $platform
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }
        if ($value instanceof LibraryUuid) {
            return $value;
        }
        try {
            $value = LibraryUuid::fromString($value);
        } catch (\Exception $ex) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }
        return $value;
    }
    /**
     * {@inheritdoc}
     *
     * @param LibraryUuid|null $value
     * @param AbstractPlatform $platform
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        if ($value instanceof LibraryUuid) {
            return $value->toString();
        }
        throw ConversionException::conversionFailed($value, self::NAME);
    }
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }
}