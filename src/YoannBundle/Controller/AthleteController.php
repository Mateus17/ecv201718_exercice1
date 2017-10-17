<?php

namespace YoannBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use YoannBundle\Entity\Athlete;
use YoannBundle\Entity\Discipline;
use YoannBundle\Entity\Pays;

/**
 * @Route("/athlete")
 */
class AthleteController extends Controller
{
  /**
   * Liste tous les athlètes
   *
   * @Route("/list", name="athlete_list")
   * @Template()
   */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $session = $this->get('session');

    $athlete = new Athlete();
    $form = $this->createFormBuilder($athlete)
      ->add('nom', TextType::class, array('label'=>'athlete.nom'))
      ->add('prenom', TextType::class, array('label'=>'athlete.prenom'))
      ->add('photo', FileType::class, array('label'=>'athlete.photo'))
      ->add('date_naissance', BirthdayType::class, array('label' => 'athlete.birthday'))
      ->add('pays', EntityType::class, array(
        'label' => 'athlete.pays',
        'class'    => 'YoannBundle:Pays',
        'multiple' => false,
        'expanded' => false
      ))
      ->add('discipline', EntityType::class, array(
        'label' => 'athlete.discipline',
        'class'    => 'YoannBundle:Discipline',
        'multiple' => false,
        'expanded' => false
      ))
      ->add('save', SubmitType::class, array('label'=>'save'))
      ->getForm();

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
      // Je récupère la photo
      $file = $athlete->getPhoto();
      // Je renomme le fichier
      $fileName = md5(uniqid()).'.'.$file->guessExtension();

      // Je le déplace dans le répertoire d'upload (Cf entité Athlete)
      $file->move(
        $athlete->getUploadDir(),
        $fileName
      );

      $athlete->setPhoto($fileName);

      $em->persist($athlete);
      $em->flush();

      $session->getFlashBag()->add('success', 'athlete.added');
    }

    $list_athletes = $em->getRepository('YoannBundle:Athlete')->findAll();

    return array(
      'athletes' => $list_athletes,
      'form' => $form->createView()
    );
  }

  /**
   * Modification d'un athlètes
   *
   * @Route("/edit/{id}", name="athlete_edit")
   * @Template()
   */
  public function editAction(Request $request, $id)
  {
    if($id){
      // Si je reçois un id, j'essaie de modifier l'athlètes

      $em = $this->getDoctrine()->getManager();
      $session = $this->get('session');

      $athlete = $em->getRepository('YoannBundle:Athlete')->findOneById($id);

      if(!empty($athlete)){

        $old_file = $athlete->getPhoto();

        $form = $this->createFormBuilder($athlete)
          ->add('nom', TextType::class, array('label'=>'athlete.nom'))
          ->add('prenom', TextType::class, array('label'=>'athlete.prenom'))
          ->add('photo', FileType::class, array('label'=>'athlete.photo', 'data_class'=>null))
          ->add('date_naissance', BirthdayType::class, array('label' => 'athlete.birthday'))
          ->add('pays', EntityType::class, array(
            'label' => 'athlete.pays',
            'class'    => 'YoannBundle:Pays',
            'multiple' => false,
            'expanded' => false
          ))
          ->add('discipline', EntityType::class, array(
            'label' => 'athlete.discipline',
            'class'    => 'YoannBundle:Discipline',
            'multiple' => false,
            'expanded' => false
          ))
          ->add('save', SubmitType::class, array('label'=>'edit'))
          ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          // Je récupère la photo
          $file = $athlete->getPhoto();
          // Je renomme le fichier
          $fileName = md5(uniqid()).'.'.$file->guessExtension();

          // Je le déplace dans le répertoire d'upload (Cf entité Athlete)
          $file->move(
            $athlete->getUploadDir(),
            $fileName
          );

          $athlete->setPhoto($fileName);
          $athlete->deletePhoto($old_file);

          $em->persist($athlete);
          $em->flush();

          $session->getFlashBag()->add('success', 'athlete.edited');

          return $this->redirectToRoute('athlete_list');
        }

        return array(
          'form' => $form->createView()
        );
      }
      else{
        return $this->redirectToRoute('athlete_list');
      }
    }
    else{
      return $this->redirectToRoute('athlete_list');
    }
  }

  /**
   * Supprime un athlète
   *
   * @Route("/delete/{id}", name="athlete_delete")
   */
  public function delete(Request $request, $id=false){

    if($id){
      // Si je reçois un id, j'essaie de supprimer l'athlète
      $em = $this->getDoctrine()->getManager();
      $session = $this->get('session');

      $athlete = $em->getRepository('YoannBundle:Athlete')->findOneById($id);

      if(!empty($athlete)){
        $em->remove($athlete);
        $em->flush();

        $session->getFlashBag()->add('success', 'athlete.deleted');
      }
      else{
        $session->getFlashBag()->add('error', 'athlete.unknown');
      }

    }

    // Quoi qu'il arrive je redirige vers la liste des athlètes
    return $this->redirectToRoute('athlete_list');
  }
}
