<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use AppBundle\Model\Blog;
use AppBundle\Entity\Blog;
use AppBundle\Form\Type\BlogType;

class DefaultController extends Controller
{



    /**
        * @Route("/", name="homepage")
        */
        public function homepageAction()
        {
          $em = $this->get('doctrine.orm.entity_manager');
          $repository = $em->getRepository(Blog::class);
          $blogs = $repository->findAllForList();
          return $this ->render('blog/homepage.html.twig',[
              'blogs' => $blogs,
          ]);
        }


        /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('default/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }



}
