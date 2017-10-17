<?php

namespace YoannBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use YoannBundle\Entity\Pays;

/**
 * @Route("/pays")
 */
class PaysController extends Controller
{
  /**
   * Liste tous les pays
   *
   * @Route("/list", name="pays_list")
   * @Template()
   */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $session = $this->get('session');

    $pays = new Pays();
    $form = $this->createFormBuilder($pays)
      ->add('nom', TextType::class, array('label'=>'pays.nom'))
      ->add('drapeau', FileType::class, array('label'=>'pays.drapeau'))
      ->add('save', SubmitType::class, array('label'=>'save'))
      ->getForm();

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
      // Je récupère le drapeau
      $file = $pays->getDrapeau();
      // Je renomme le fichier
      $fileName = md5(uniqid()).'.'.$file->guessExtension();

      // Je le déplace dans le répertoire d'upload (Cf entité Pays)
      $file->move(
        $pays->getUploadDir(),
        $fileName
      );

      $pays->setDrapeau($fileName);

      $em->persist($pays);
      $em->flush();

      $session->getFlashBag()->add('success', 'pays.added');
    }

    $list_pays = $em->getRepository('YoannBundle:Pays')->findAll();

    return array(
      'pays' => $list_pays,
      'form' => $form->createView()
    );
  }

  /**
   * Modification d'un pays
   *
   * @Route("/edit/{id}", name="pays_edit")
   * @Template()
   */
  public function editAction(Request $request, $id)
  {
    if($id){
      // Si je reçois un id, j'essaie de modifier le pays

      $em = $this->getDoctrine()->getManager();
      $session = $this->get('session');

      $pays = $em->getRepository('YoannBundle:Pays')->findOneById($id);

      if(!empty($pays)){

        $old_file = $pays->getDrapeau();

        $form = $this->createFormBuilder($pays)
          ->add('nom', TextType::class, array('label'=>'pays.nom'))
          ->add('drapeau', FileType::class, array('label'=>'pays.drapeau', 'data_class'=>null))
          ->add('save', SubmitType::class, array('label'=>'edit'))
          ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          // Je récupère le drapeau
          $file = $pays->getDrapeau();
          // Je renomme le fichier
          $fileName = md5(uniqid()).'.'.$file->guessExtension();

          // Je le déplace dans le répertoire d'upload (Cf entité Pays)
          $file->move(
            $pays->getUploadDir(),
            $fileName
          );

          $pays->setDrapeau($fileName);
          $pays->deleteDrapeau($old_file);

          $em->persist($pays);
          $em->flush();

          $session->getFlashBag()->add('success', 'pays.edited');

          return $this->redirectToRoute('pays_list');
        }

        return array(
          'form' => $form->createView()
        );
      }
      else{
        return $this->redirectToRoute('pays_list');
      }
    }
    else{
      return $this->redirectToRoute('pays_list');
    }
  }

  /**
   * Supprime un pays
   *
   * @Route("/delete/{id}", name="pays_delete")
   */
  public function delete(Request $request, $id=false){

    if($id){
      // Si je reçois un id, j'essaie de supprimer le pays
      $em = $this->getDoctrine()->getManager();
      $session = $this->get('session');

      $pays = $em->getRepository('YoannBundle:Pays')->findOneById($id);

      if(!empty($pays)){
        $em->remove($pays);
        $em->flush();

        $session->getFlashBag()->add('success', 'pays.deleted');
      }
      else{
        $session->getFlashBag()->add('error', 'pays.unknown');
      }

    }

    // Quoi qu'il arrive je redirige vers la liste des pays
    return $this->redirectToRoute('pays_list');
  }
}
