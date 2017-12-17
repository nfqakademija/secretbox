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

class ProductSelectionService
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var FacebookInfoService
     */
    private $facebook;
    /**
     * @var int
     */
    private $hoursToRevealSecret;

    /**
     * ProductSelectionService constructor.
     *
     * @param $secret_reveal_time
     * @param EntityManager       $em
     * @param FacebookInfoService $facebook
     */
    public function __construct(
        $secret_reveal_time,
        EntityManager $em,
        FacebookInfoService $facebook
    ) {
        $this->em = $em;
        $this->facebook = $facebook;
        $this->hoursToRevealSecret = $secret_reveal_time * 24;
    }

    /**
     * @param integer $userId
     *
     * @param string $boxSize
     *
     * @return array|null
     */
    private function getUserUnusedProducts($userId, $boxSize)
    {
        $validOrderDate = (new \DateTime())->modify('-' . $this->hoursToRevealSecret . ' hours');
        $revealedProducts = $this->em->getRepository(Order::class)->getUserRevealedProducts(
            $userId,
            $validOrderDate,
            $boxSize
        );


        $validProductDate = new \DateTime();
        $validProductDate->modify('+' . $this->hoursToRevealSecret . ' hours');
        $newProducts = $this->em
            ->getRepository(Product::class)
            ->getUniqueProducts($revealedProducts, $validProductDate, $boxSize);

        return !empty($newProducts) ? $newProducts : null;
    }

    /**
     * @param integer $userId
     *
     * @param string $boxSize
     *
     * @return int|null
     */
    public function selectProperProductId($userId, $boxSize)
    {
        $unusedProducts = $this->getUserUnusedProducts($userId, $boxSize);

        if ($unusedProducts) {
            $userLikes = $this->facebook->getUserDataByReference('likes');
            $userEvents = $this->facebook->getUserDataByReference('events?fields=name');

            $matchInLikes = $this->matchesInArrays($unusedProducts, $userLikes, 'name');
            $matchInEvents = $this->matchesInArrays($unusedProducts, $userEvents, 'name');
            $productsMatch = array_merge($matchInLikes, $matchInEvents);

            //todo JEI BUS LAIKO refactor this huge if block
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
                        if (($age > $product['ageMin']) && ($age < $product['ageMax'])) {
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
                        if (($age > $product['ageMin']) && ($age < $product['ageMax'])) {
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

    /**
     * @param integer $userId
     *
     * @param string $boxSize
     *
     * @return Product|null|object
     */
    public function selectProperProduct($userId, $boxSize)
    {
        $productId = $this->selectProperProductId($userId, $boxSize);
        $suitableProduct = $this->em->getRepository(Product::class)->findOneBy(['id' => $productId]);

        return $suitableProduct;
    }

    /**
     * @param array  $first
     * @param array  $second
     * @param string $fieldName
     *
     * @return array
     */
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
