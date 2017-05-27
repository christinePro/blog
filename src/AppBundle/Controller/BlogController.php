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

    /**
     * @Route("/admin/blog/{id}/", name="blog_show_comment")
     */
     public function createCommentAction(Blog $blog_id)

     {
       $blog = $this->getBlog($blog_id);
       $comment = new Comment();
       $comment->setBlog($blog);
       $form = $this->createForm(CommentType::class, $comment);
       $form->handleRequest($request);

       if ($form->isValid()) {
         // TODO: Persist the comment entity

         return $this->redirect($this->generateUrl('blog_bundle_blog_show', array(
           'id' => $comment->getBlog()->getId())) .
           '#comment-' . $comment->getId()
         );
       }
       return $this->render('BlogBundleBlogBundle:Comment:create.html.twig', array(
         'comment' => $comment,
         'form' => $form->createView()
       ));

     }

     protected function getBlog($blog_id)
     {
       $em = $this->getDoctrine()
       ->getManager();

       $blog = $em->getRepository('BlogBundleBlogBundle:Blog')->find($blog_id);

       if (!$blog) {
         throw $this->createNotFoundException('Unable to find Blog post.');
       }

       return $blog;
     }

}
