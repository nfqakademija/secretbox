<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.12.7
 * Time: 10.44
 */

namespace AppBundle\DBAL;

class EnumOrderStatusType extends EnumType
{
    protected $name = 'enum_order_status';
    protected $values = array('new', 'delivered', 'revealed');
}
