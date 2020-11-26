<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
// Fixtures allow you to create fictitious data, fake data in BDD
// We create here a loop in order to create 10 articles in BDD
// To be able to insert articles in BDD, we must go through the Article entity which reflects the SQL table
        for($i=1; $i <= 10; $i++)
        {
            //For each loop turn, we create an empty Article object
            $article = new Article;

            //ON informs all the setters of the Article entity
            $article->setTitle("Titre de l'article n°$i")
                    ->setContent("<p>Contenu de l'article n°$i</p>")
                    ->setImage("https://picsum.photos/200/300")
                    ->setCreatedAt(new \DateTime());

                    //ObjetManager is used to handle the lines in the DB (INSERT, UPDATE, DELETE)
                    //persist (): used to prepare insert requests
            $manager->persist($article);        
        }
        $manager->flush();
    }
}
