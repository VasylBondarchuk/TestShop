<?php
namespace app\core;

class DataMapper
{
    public static function mapDataToObject(array $data, $object): void
    {
        foreach ($data as $key => $value) {
            $propertyName = self::convertColumnNameToPropertyName($key);
            $setterMethod = self::createSetterMethod($propertyName);
            if (method_exists($object, $setterMethod)) {
                $object->$setterMethod($value);
            }
        }        
    }

    private static function createSetterMethod(string $propertyName): string
    {
        return 'set' . ucfirst($propertyName);
    }

    private static function convertColumnNameToPropertyName(string $columnName): string
    {
        // Convert snake_case column names to camelCase property names
        return lcfirst(str_replace('_', '', ucwords($columnName, '_')));
    }
}

