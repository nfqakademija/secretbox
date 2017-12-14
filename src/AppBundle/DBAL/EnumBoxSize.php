<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.12.12
 * Time: 21.20
 */

namespace AppBundle\DBAL;

class EnumBoxSize extends EnumType
{
    protected $name = 'enum_box_size';
    protected $values = array('small', 'medium', 'big');
}
