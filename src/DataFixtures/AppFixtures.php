<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Section;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private SluggerInterface $slugger;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Création des utilisateurs
        // Admin
        $admin = new User();
        $admin->setUsername('admin')
              ->setFullname($faker->name)
              ->setEmail('admin@example.com')
              ->setActivate(true)
              ->setRoles(['ROLE_ADMIN'])
              ->setPassword($this->userPasswordHasher->hashPassword($admin, 'admin'))
              ->setUniqid(uniqid());
        $manager->persist($admin);

        // Rédacteurs
        $redacs = [];
        for ($i = 1; $i <= 5; $i++) {
            $redac = new User();
            $redac->setUsername('redac' . $i)
                  ->setFullname($faker->name)
                  ->setEmail('redac' . $i . '@example.com')
                  ->setActivate(true)
                  ->setRoles(['ROLE_REDAC'])
                  ->setPassword($this->userPasswordHasher->hashPassword($redac, 'redac' . $i))
                  ->setUniqid(uniqid());
            $manager->persist($redac);
            $redacs[] = $redac; // Stocke les rédacteurs pour les utiliser plus tard
        }

        // Utilisateurs
        $users = [];
        for ($i = 1; $i <= 24; $i++) {
            $user = new User();
            $user->setUsername('user' . $i)
                 ->setFullname($faker->name)
                 ->setEmail('user' . $i . '@example.com')
                 ->setActivate($faker->boolean(75)) // 75% d'activation
                 ->setRoles(['ROLE_USER'])
                 ->setPassword($this->userPasswordHasher->hashPassword($user, 'user' . $i))
                 ->setUniqid(uniqid());
            $manager->persist($user);
            $users[] = $user; // Stocke les utilisateurs pour les utiliser plus tard
        }

        // Flush pour s'assurer que les utilisateurs sont persistés avant de les utiliser
        $manager->flush();

        // Création des sections
        $sections = [];
        for ($i = 1; $i <= 6; $i++) {
            $section = new Section();
            $section->setSectionTitle($faker->sentence(3))
                    ->setSectionSlug($this->slugger->slug($section->getSectionTitle())->lower())
                    ->setSectionDetail($faker->paragraph());
            $manager->persist($section);
            $sections[] = $section; // Stocke les sections pour les utiliser plus tard

            // Ajout d'articles à la section
            $articleCount = rand(2, 40); // Nombre d'articles à ajouter à cette section
            for ($j = 0; $j < $articleCount; $j++) {
                $article = new Article();
                
                // Choisir un auteur aléatoire (Admin ou Rédacteur)
                $author = $faker->randomElement([$admin] + $redacs);

                // Assurez-vous de récupérer l'ID de l'auteur
                $article->setUserId($author->getId()); // Passer l'ID de l'auteur

                // Configurer les propriétés de l'article
                $article->setTitle($faker->sentence(5))
                        ->setTitleSlug($this->slugger->slug($article->getTitle())->lower())
                        ->setText($faker->text())
                        ->setArticleDateCreate($faker->dateTimeBetween('-6 months', 'now'))
                        ->setPublished($faker->boolean(75));

                // Date de publication si l'article est publié
                if ($article->isPublished()) {
                    $article->setArticleDatePosted($faker->dateTimeBetween($article->getArticleDateCreate(), 'now'));
                }

                // Ajouter l'article à la section
                $section->addArticle($article);
                $manager->persist($article);
            }
        }

        // Flush les entités
        $manager->flush();
    }
}
