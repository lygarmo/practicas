<?php

namespace App\Controller;

use App\Entity\Libro;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LibroController extends AbstractController
{
    #[Route('/libro', name: 'app_libro')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Obtener todos los libros desde la base de datos
        $libros = $entityManager->getRepository(Libro::class)->findAll();

        // Pasar los libros a la plantilla
        return $this->render('libro/index.html.twig', [
            'libros' => $libros,
        ]);
    }
}
