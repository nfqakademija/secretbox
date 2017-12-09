<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.12.7
 * Time: 10.56
 */

namespace AppBundle\DBAL;

class EnumDeliveryType extends EnumType
{
    protected $name = 'enum_delivery_type';
    protected $values = array('home', 'parcel_machine');
}
