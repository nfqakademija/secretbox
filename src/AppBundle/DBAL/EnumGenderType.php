<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.12.7
 * Time: 10.27
 */

namespace AppBundle\DBAL;

class EnumGenderType extends EnumType
{
    protected $name = 'enum_gender';
    protected $values = array('unisex', 'male', 'female');
}
