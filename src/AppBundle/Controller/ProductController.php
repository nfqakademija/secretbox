<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package AppBundle\Controller
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/list", name="app.product")
     */
    public function indexAction(Request $request)
    {
    }
}
