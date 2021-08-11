<?php

namespace srag\CQRS\Aggregate;

use DateTimeZone;
use DateTimeImmutable;
use JsonSerializable;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AbstractValueObject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class AbstractValueObject implements JsonSerializable
{
    const VAR_CLASSNAME = "avo_class_name";

    /**
     * Compares ValueObjects to each other returns true if they are the same
     */
    public function equals(AbstractValueObject $other) : bool
    {
        return $this->jsonSerialize() === $other->jsonSerialize();
    }

    /**
     * Compares if two nullable ValueObjects are equal and returns true if they are
     */
    public static function isNullableEqual(?AbstractValueObject $first, ?AbstractValueObject $second) : bool
    {
        return (is_null($first) === is_null($second)) && (is_null($first) || $first->equals($second));
    }

    public static function isNullableArrayEqual(?array $first, ?array $second) : bool
    {
        if (is_null($first) !== is_null($second)) {
            return false;
        }

        if (is_null($first) && is_null($second)) {
            return true;
        }

        if (count($first) !== count($second)) {
            return false;
        }

        for($i = 0; $i < count($first); $i += 1) {
            if (! $first[$i]->equals($second[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $json = [];
        $vars = get_object_vars($this);
        foreach ($vars as $key => $var) {
            $json[$key] = $this->sleep($var);
        }
        $json[self::VAR_CLASSNAME] = get_called_class();
        return $json;
    }

    protected function sleep($field_value)
    {
        if ($field_value instanceof AbstractValueObject) {
            return $field_value->jsonSerialize();
        }
        else if ($field_value instanceof Uuid) {
            return $field_value->toString();
        }

        return $field_value;
    }

    public function serialize() : string
    {
        return json_encode($this->jsonSerialize());
    }

    public static function deserialize(?string $data)
    {
        if ($data === null) {
            return null;
        }

        $data_array = json_decode($data, true);

        if ($data_array === null) {
            return null;
        }

        return self::createFromArray($data_array);
    }

    public static function createFromArray(?array $data)
    {
        if (is_null($data)) {
            return null;
        }

        if (array_key_exists(self::VAR_CLASSNAME, $data)) {
            /** @var AbstractValueObject $object */
            $object = new $data[self::VAR_CLASSNAME]();

            foreach ($data as $key => $value) {
                if (!($key === self::VAR_CLASSNAME)) {
                    if (is_array($value)) {
                        $object->$key = self::createFromArray($value);
                    }
                    else {
                        $object->$key = static::deserializeValue($key, $value);
                    }
                }
            }

            return $object;
        } else {
            if (array_key_exists('date', $data) &&
                array_key_exists('timezone_type', $data) &&
                array_key_exists('timezone', $data)) {
                return new DateTimeImmutable(
                    $data['date'],
                    new DateTimeZone($data['timezone'])
                );
            }

            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = self::createFromArray($value);
                }
            }

            return $data;
        }
    }

    protected static function deserializeValue(string $key, $value)
    {
        //virtual method
        return $value;
    }
}
