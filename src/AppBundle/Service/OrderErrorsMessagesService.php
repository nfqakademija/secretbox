<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.12.16
 * Time: 12.02
 */

namespace AppBundle\Service;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class OrderErrorsMessagesService
{
    /**
     * @param ConstraintViolationListInterface $violations
     *
     * @return array
     */
    public function getErrorsList(ConstraintViolationListInterface $violations)
    {
        $messages = [];
        foreach ($violations as $violation) {
            /** @var ConstraintViolation $violation */
            array_push($messages, $violation->getMessage());
        }

        return $messages;
    }
}
