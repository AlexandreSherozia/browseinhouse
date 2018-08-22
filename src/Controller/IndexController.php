<?php

namespace App\Controller;

use App\Service\AdvertManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index(AdvertManager $advertManager)
    {
        $lastAdverts = $advertManager->getFiveLastAdverts();
        $categories = $advertManager->getAllCategories();

        return $this->render('index/index.html.twig', [
            'lastAdverts' => $lastAdverts,
            'categories' => $categories
        ]);
    }
}
