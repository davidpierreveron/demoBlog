<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\form\ArticleType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManager;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response
    {
     //We call the Repository class of the Article class
     //A Repository class only allows you to select data in BDD
      //  $repo= $this->getDoctrine()->getRepository(Article::class);
        // dump($repo);


        
   //finAll () is a method from the ArticleRepository class and allows you to select an entire SQL table (SELECT * FROM)

        $articles = $repo->findAll();
        dump($articles);

        return $this->render('blog/index.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/", name="home");
     */
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue sur le blog Symfony',
            'age' => 25
        ]);
    }

      /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager)
    {
        //dump($request);
        // if($request->request->count() > 0)
        // {
        //     $article = new Article;
        //     $article->setTitle($request->request->get('title'))
        //             ->setContent($request->request->get('content'))
        //             ->setImage($request->request->get('image'))
        //             ->setCreatedAt(new \DateTime());

        //     $manager->persist($article);
        //     $manager->flush();

        //     return $this->redirectToRoute('blog_show',[
        //         'id' => $article->getId()
        //     ]);


        // }

        if(!$article)
        {
            $article = new Article;
        }

        // $article = new Article;

        // $form = $this->createFormBuilder($article)
        //             ->add('title')
        //             ->add('content')
        //             ->add('image')
        //             ->getForm();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        dump($request);

        if($form->isSubmitted() && $form->isValid())
        {
        if(!$article->getId())
        {
            $article->setCreatedAt(new \DateTime());
        }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show',[

                'id' => $article->getId()

            ]);
        }

        return $this->render('blog/create.html.twig',[
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
            
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $manager): Response
    {
       // $repo = $this->getDoctrine()->getRepository(Article::class);
        // $article = $repo->find($id);
       

        $comment = new Comment;

        dump($request);

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        if($formComment->isSubmitted() && $formComment->isValid())
        {
            $comment->setCreatedAt((new \DateTime));
            $comment->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', "Le commentaire a bien été posté !");

            return $this->redirectToRoute('blog_show',[
                'id' => $article->getId()
            ]);
        }




        return $this->render('blog/show.html.twig',[
        'article' => $article,
        'formComment' => $formComment->createView()
        ]);
    }
    /* 
   symfony understands that there is an article passed and that in the route there is an ID, so it will look for the correct article with the correct identifier.
    all this thanks to symfony's ParamConverter, basically it sees that we need an article and also an ID, it will look for the article with the identifier and send it to the show () function
    So we have much shorter functions !!
     */

  
}
