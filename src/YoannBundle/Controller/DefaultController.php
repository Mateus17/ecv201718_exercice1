<?php

namespace YoannBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
  /**
   * @Route("/", name="home")
   */
  public function indexAction()
  {
    return $this->render('YoannBundle:Default:index.html.twig');
  }
}
