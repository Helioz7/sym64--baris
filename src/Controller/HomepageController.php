<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    // Route pour la page d'accueil
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    // Route pour la page À propos
    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    // Route pour le blog
    #[Route('/blog', name: 'app_blog')]
    public function blog(): Response
    {
        return $this->render('blog.html.twig');
    }

    // Route pour les détails du blog
    #[Route('/blog/{id}', name: 'app_blog_details')]
    public function blogDetails(int $id): Response
    {
        return $this->render('blog-details.html.twig', [
            'id' => $id,
        ]);
    }

    // Route pour la page Contact
    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }

    // Route pour le portfolio
    #[Route('/portfolio', name: 'app_portfolio')]
    public function portfolio(): Response
    {
        return $this->render('portfolio.html.twig');
    }

    // Route pour les détails du portfolio
    #[Route('/portfolio/{id}', name: 'app_portfolio_details')]
    public function portfolioDetails(int $id): Response
    {
        return $this->render('portfolio-details.html.twig', [
            'id' => $id,
        ]);
    }

    // Route pour la page des Services
    #[Route('/services', name: 'app_services')]
    public function services(): Response
    {
        return $this->render('services.html.twig');
    }

    // Route pour l'équipe
    #[Route('/team', name: 'app_team')]
    public function team(): Response
    {
        return $this->render('team.html.twig');
    }
}
