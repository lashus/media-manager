<?php

namespace MediaManager\Doctrine\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;
use MediaManager\Model\ValueObject\LibraryId;

/**
 * Class LibraryIdType
 * @package MediaManager\Doctrine\Type
 */
final class LibraryIdType  extends IntegerType
{
    const NAME = 'library_id';

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
        if ($value instanceof LibraryId) {
            return $value;
        }
        try {
            $value = LibraryId::fromString($value);
        } catch (\Exception $ex) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }
        return $value;
    }
    /**
     * {@inheritdoc}
     *
     * @param LibraryId|null $value
     * @param AbstractPlatform $platform
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        if ($value instanceof LibraryId) {
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