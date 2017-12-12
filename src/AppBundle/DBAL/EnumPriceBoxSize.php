<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.12.12
 * Time: 21.20
 */

namespace AppBundle\DBAL;


class EnumPriceBoxSize extends EnumType
{
    protected $name = 'enum_price_box_size';
    protected $values = array('small', 'medium', 'big');
}