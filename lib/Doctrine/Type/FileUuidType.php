<?php

namespace MediaManager\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use MediaManager\Model\ValueObject\FileUuid;
use Ramsey\Uuid\Doctrine\UuidType;

/**
 * Class FileUuidType
 * @package MediaManager\Doctrine\Type
 */
final class FileUuidType extends UuidType
{
    const NAME = 'file_uuid';

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
        if ($value instanceof FileUuid) {
            return $value;
        }
        try {
            $value = FileUuid::fromString($value);
        } catch (\Exception $ex) {

            throw ConversionException::conversionFailed($value, self::NAME);
        }
        return $value;
    }
    /**
     * {@inheritdoc}
     *
     * @param FileUuid|null $value
     * @param AbstractPlatform $platform
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        if ($value instanceof FileUuid) {
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