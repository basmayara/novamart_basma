<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;   // ← updated

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product_details', requirements: ['id' => '\d+'])]
    public function details(int $id): Response    // ← typed as int
    {
        return $this->render('product/product_details.html.twig', [
            'id' => $id,                          
        ]);
    }
}