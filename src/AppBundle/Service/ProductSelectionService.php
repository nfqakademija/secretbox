<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.11.18
 * Time: 10.56
 */

namespace AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class ProductSelectionService
{
    private $em;
    private $container;
    private $facebook;
    private $hoursToRevealSecret;


    /**
     * ProductSelectionService constructor.
     *
     * @param EntityManager $em
     * @param ContainerInterface $container
     * @param Session $session
     * @param FacebookInfoService $facebook
     */
    public function __construct(EntityManager $em, ContainerInterface $container, FacebookInfoService $facebook)
    {
        $this->em = $em;
        $this->container = $container;
        $this->facebook =  $facebook;
        $this->hoursToRevealSecret = $container->getParameter('secret_reveal_time') * 24;
    }

    /**
     * @param integer $userId
     * @param integer $daysToRevealSecret
     *
     * @return null|array
     */
    private function getUserUnusedProducts($userId)
    {
        $validOrderDate = new \DateTime();
        $validOrderDate->modify('-' . $this->hoursToRevealSecret . ' hours');
        $revealedProducts = $this->em->getRepository(Order::class)->getUserRevealedProducts(
            $userId,
            $validOrderDate
        );

        $validProductDate = new \DateTime();
        $validProductDate->modify('+' . $this->hoursToRevealSecret . ' hours');
        $newProducts = $this->em->getRepository(Product::class)->getUniqueProducts($revealedProducts, $validProductDate);

        if (empty($newProducts)) {
            return null;
        } else {
            return $newProducts;
        }
    }

    public function selectProperProduct($userId)
    {
        $unusedProducts = $this->getUserUnusedProducts($userId);

        if ($unusedProducts) {
            $userLikes = $this->facebook->getUserDataByReference('likes');
            $userEvents = $this->facebook->getUserDataByReference('events?fields=name');

            $matchInLikes = $this->matchesInArrays($unusedProducts, $userLikes, 'name');
            $matchInEvents = $this->matchesInArrays($unusedProducts, $userEvents, 'name');
            $productsMatch = array_merge($matchInLikes, $matchInEvents);


            if (!empty($productsMatch)) {
                $personInfo = $this->facebook->getPersonInfo('gender,birthday');
                $age = $this->getUserAge($personInfo['birthday'])->y;
                $gender = $personInfo['gender'];
                $productsByGender = [];

                foreach ($productsMatch as $product) {
                    if (($product['gender'] == "unisex") || ($product['gender'] == $gender)) {
                        array_push($productsByGender, $product);
                    }
                }

                if (!empty($productsByGender)) {
                    $productsByAge = [];
                    foreach ($productsByGender as $product) {
                        if (($age > $product['ageRange']['min']) && ($age < $product['ageRange']['max'])) {
                            array_push($productsByAge, $product);
                        }
                    }

                    if (!empty($productsByAge)) {
                        $perfectMatch = $productsByAge[array_rand($productsByAge, 1)];

                        return $perfectMatch['id'];
                    } else {
                        $productByGender = $productsByGender[array_rand($productsByGender, 1)];

                        return $productByGender['id'];
                    }
                } else {
                    $productsByAge = [];
                    foreach ($productsMatch as $product) {
                        if (($age > $product['ageRange']['min']) && ($age < $product['ageRange']['max'])) {
                            array_push($productsByAge, $product);
                        }
                    }

                    if (!empty($productsByAge)) {
                        $productByAge = $productsByAge[array_rand($productsByAge, 1)];

                        return $productByAge['id'];
                    }
                }

                $productMatch = $productsMatch[array_rand($productsMatch, 1)];

                return $productMatch['id'];
            } else {
                $unusedProduct = $unusedProducts[array_rand($unusedProducts, 1)];

                return $unusedProduct['id'];
            }
        } else {
            return null;
        }
    }

    private function matchesInArrays(array $first, array $second, $fieldName)
    {
        $matches = [];
        foreach ($first as $item) {
            if (in_array($item[$fieldName], array_column($second, $fieldName))) {
                array_push($matches, $item);
            }
        }

        return $matches;
    }


    /**
     * @param \DateTime $birthDay
     *
     * @return bool|\DateInterval
     */
    private function getUserAge($birthDay)
    {
        $today = new \DateTime();
        $age = $today->diff($birthDay);
        return $age;
    }
}
