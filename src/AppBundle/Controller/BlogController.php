<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use AppBundle\Model\Blog;
use AppBundle\Entity\Blog;
use AppBundle\Form\Type\BlogType;

class BlogController extends Controller
{


  /**
   * @Route("/blog", name="blog")
   */
   public function blogPostAction(Request $request)
   {
     $blog = new Blog();
     $form = $this->createForm(BlogType::class, $blog);
     $form-> handleRequest($request);

     if($form->isSubmitted() && $form->isValid()){
       $blog->setPublishAt(new \DateTime());
       $blog->setIsProcessed(false);
       //pour envoyer un mail à l'administrateur: declaration des elemnts
       $em = $this->get('doctrine.orm.entity_manager');
       $em->persist($blog);//persist is used when the object is not yet save in database
       $em->flush();// execute query in database

       //$flash = sprintf('Merci %s de nous avoir contacté sur le sujet %s.',$blog->getTitle();
       //$this->addFlash('sucess',$flash);
       return $this->redirectToRoute('homepage');
       dump($blog);
       //die;
     }

     return $this ->render('blog/blog.html.twig',['form'=> $form-> createView(),]);
   }


   /**
    * @Route("/admin/blog/list", name="blog_list")
    */
   public function listAction()
   {
       $em = $this->get('doctrine.orm.entity_manager');
       $repository = $em->getRepository(Blog::class);

       // $contacts = $repository->findAll();
       $blogs = $repository->findAllForList();

       return $this->render('blog/list.html.twig', [
           'blogs' => $blogs,
       ]);
   }


    /**
     * @Route("/admin/blog/{id}/", name="blog_show")
     */
    public function showAction(Blog $blog)
    {
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/admin/blog/{id}/mark-as-processed", name="blog_mark_as_processed")
     */
    public function markAsProcessedAction(Blog $blog)
    {
        if ($blog->isProcessed()) {
            $this->addFlash('error', 'This blog is already marked as processed.');
        } else {
            $blog->setIsProcessed(true);
            $this->addFlash('success', 'This blog has been marked as processed!');

            $em = $this->get('doctrine.orm.entity_manager');
            $em->flush();
        }

        return $this->redirectToRoute('blog_show', [
            'id' => $blog->getId(),
        ]);
    }

}
