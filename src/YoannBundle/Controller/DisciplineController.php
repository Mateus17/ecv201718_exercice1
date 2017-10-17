<?php

namespace YoannBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use YoannBundle\Entity\Discipline;

/**
 * @Route("/discipline")
 */
class DisciplineController extends Controller
{
  /**
   * Liste toutes les disciplines
   *
   * @Route("/list", name="discipline_list")
   * @Template()
   */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $session = $this->get('session');

    $discipline = new Discipline();
    $form = $this->createFormBuilder($discipline)
      ->add('nom', TextType::class, array('label'=>'discipline.nom'))
      ->add('save', SubmitType::class, array('label'=>'save'))
      ->getForm();

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
      $em->persist($discipline);
      $em->flush();

      $session->getFlashBag()->add('success', 'discipline.added');
    }

    $list_discipline = $em->getRepository('YoannBundle:Discipline')->findAll();

    return array(
      'disciplines' => $list_discipline,
      'form' => $form->createView()
    );
  }

  /**
   * Modification d'une discipline
   *
   * @Route("/edit/{id}", name="discipline_edit")
   * @Template()
   */
  public function editAction(Request $request, $id)
  {
    if($id){
      // Si je reçois un id, j'essaie de modifier la discipline

      $em = $this->getDoctrine()->getManager();
      $session = $this->get('session');

      $discipline = $em->getRepository('YoannBundle:Discipline')->findOneById($id);

      if(!empty($discipline)){

        $form = $this->createFormBuilder($discipline)
          ->add('nom', TextType::class, array('label'=>'discipline.nom'))
          ->add('save', SubmitType::class, array('label'=>'edit'))
          ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          $em->persist($discipline);
          $em->flush();

          $session->getFlashBag()->add('success', 'discipline.edited');

          return $this->redirectToRoute('discipline_list');
        }

        return array(
          'form' => $form->createView()
        );
      }
      else{
        return $this->redirectToRoute('discipline_list');
      }
    }
    else{
      return $this->redirectToRoute('discipline_list');
    }
  }

  /**
   * Supprime une discipline
   *
   * @Route("/delete/{id}", name="discipline_delete")
   */
  public function delete(Request $request, $id=false){

    if($id){
      // Si je reçois un id, j'essaie de supprimer la discipline
      $em = $this->getDoctrine()->getManager();
      $session = $this->get('session');

      $discipline = $em->getRepository('YoannBundle:Discipline')->findOneById($id);

      if(!empty($discipline)){
        $em->remove($discipline);
        $em->flush();

        $session->getFlashBag()->add('success', 'discipline.deleted');
      }
      else{
        $session->getFlashBag()->add('error', 'discipline.unknown');
      }

    }

    // Quoi qu'il arrive je redirige vers la liste des disciplines
    return $this->redirectToRoute('discipline_list');
  }

}
