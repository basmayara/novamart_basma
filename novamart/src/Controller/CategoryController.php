<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function browse(): Response
    {
        return $this->render('category/browse_categories.html.twig');
    }

    #[Route('/category/{id}', name: 'app_products_by_category')]
    public function productsByCategory($id): Response
    {
        return $this->render('category/products_by_category.html.twig');
    }
}