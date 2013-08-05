<?php
namespace Application\MainBundle\Doctrine\DBAL\Types;

class PriorityType extends EnumType
{
    const LOW       = 'low';
    const MEDIUM    = 'medium';
    const IMPORTANT = 'important';
    const CRUCIAL   = 'crucial';

    protected $name = 'priority';
    protected $default = self::MEDIUM;
    protected $values = array(
        self::LOW       ,
        self::MEDIUM    ,
        self::IMPORTANT ,
        self::CRUCIAL   ,
    );

    public static function getValues()
    {
        return self::getType('priority')->values;
    }

    public static function getDefault()
    {
        return self::getType('priority')->default;
    }
}