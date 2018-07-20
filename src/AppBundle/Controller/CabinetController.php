<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RDV;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CabinetController extends Controller
{
    /**
     * @Route("/", name="Home")
     */
    public function listAction()
    {

    $user = $securityContext = $this->container->get('security.authorization_checker');
    if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

        $userRole = $this->getUser()->getRoles();        
        if (in_array("ROLE_ADMIN", $userRole)) {
            return $this->redirect("/admin/");
        } elseif (in_array("ROLE_ASSISTANT", $userRole)) {
            return $this->redirect("/Cabinet/Administration");
        } elseif (in_array("ROLE_DOCTOR", $userRole)) {
            return $this->redirect("/Cabinet/Administration");
        }
    } else {
    	return $this->render('Cabinet/home.html.twig' , array());
  }
  return $this->render('Cabinet/home.html.twig' , array());
}



    /**
     * @Route("/Cabinet/Administration", name="Cabinetadmin")
     */
    public function ShowAction()
    {
        return $this->render('Cabinet/CabinetAdmin.html.twig' , array());
    }

    /**
     * @Route("/Cabinet/Administration/Gestionrdv", name="Gestionrdv")
     */
    public function GestionrdvAction()
    {

        $rdvs = $this->getDoctrine()
        ->getRepository('AppBundle:RDV')
        ->findAll();

        return $this->render('Cabinet/gestionrdv.html.twig' , array(
        'rdvs'=>$rdvs
        ));
    }

     /**
     * @Route("/Cabinet/Administration/Gestionrdv/edit/{id}", name="editrdv")
     */
    public function EditrdvAction($id, Request $request)
    {
        $rdv = $this->getDoctrine()->getRepository('AppBundle:RDV')->find($id);

                $rdv->setPrenom($rdv->getPrenom());
                $rdv->setNom($rdv->getNom());
                $rdv->setCin($rdv->getCin());
                $rdv->setNumTel($rdv->getNumTel());
                $rdv->setDateRdv($rdv->getDateRdv());
                $rdv->setSeance($rdv->getSeance());

        $form = $this->createFormBuilder($rdv)
              ->add('prenom', TextType::class, array('attr' => array('class' => 'form-control' , 'disabled' => 'true' , 'style' => 'margin-bottom:15px')))
              ->add('nom', TextType::class, array('attr' => array('class' => 'form-control' , 'disabled' => 'true' , 'style' => 'margin-bottom:15px'))) 
              ->add('cin', TextType::class, array('attr' => array('class' => 'form-control', 'disabled' => 'true' , 'style' => 'margin-bottom:15px'))) 
              ->add('num_tel', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Numéro de téléphone' , 'disabled' => 'true' , 'style' => 'margin-bottom:15px')))
              ->add('date_rdv', DateType::class, array('input' => 'string', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
              ->add('seance',ChoiceType::class, array('label' => 'Séance', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'), 'choices' => array('Séance 1' => 'S1' ,'Séance 2' => 'S2','Séance 3' => 'S3','Séance 4 ' => 'S4','Séance 5' => 'S5','Séance 6' => 'S6','Séance 7' => 'S7','Séance 8' => 'S8'),'choices_as_values' => true,'multiple'=>false,'expanded'=>false))
              ->add('submit', SubmitType::class, array('label' => 'Valider', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
              ->getForm();
              
              $form ->handleRequest($request);

              if($form->isSubmitted() && $form->isValid()){

                $prenom = $form['prenom']->getData();
                $nom = $form['nom']->getData();
                $cin = $form['cin']->getData();
                $num_tel = $form['num_tel']->getData();
                $date_rdv = $form['date_rdv']->getData();
                $seance = $form['seance']->getData();

                $em = $this -> getDoctrine() -> getManager();
                $rdv = $em -> getRepository('AppBundle:RDV')->find($id);

                $em1 = $this->getDoctrine()->getManager();
                $RAW_QUERY1 = 'SELECT r_d_v.seance FROM r_d_v WHERE r_d_v.date_rdv = :d ;';
                $statement1 = $em1->getConnection()->prepare($RAW_QUERY1);
                $statement1->bindValue('d', $date_rdv);
                $statement1->execute();
                $result1 = $statement1->fetchAll();

                $sv = true ;

                foreach ($result1 as $key => $value) {
                if (in_array($seance, $value)){
                    $sv = false ;
                }
            }

                if ($sv == false) {
                    $this->addFlash(
                    'notice',
                    'Cette séance est déjas réserver !'
                    );
                }else{

                $rdv->setPrenom($prenom);
                $rdv->setNom($nom);
                $rdv->setCin($cin);
                $rdv->setNumTel($num_tel);
                $rdv->setDateRdv($date_rdv);
                $rdv->setSeance($seance);

               
                $em->flush();
                $this->addFlash(
                   'notice',
                   'Rendez-vous modifié avec succès'
                );

                return $this->redirectToRoute('Gestionrdv');

            }
        }  

        return $this->render('Cabinet/editrdv.html.twig' , array(
        'rdv'=>$rdv,
        'form'=>$form->createView()
        ));
    }


     /**
     * @Route("/Cabinet/Administration/Gestionrdv/delete/{id}", name="deleterdv")
     */
    public function removeAction($id)
    {
      $em = $this -> getDoctrine() -> getManager();
      $rdv = $em -> getRepository('AppBundle:RDV')->find($id);
      $em->remove($rdv);
      $em->flush();
      $this->addFlash(
      'notice',
      'Rendez-vous été supprimer avec succès'
      );
      return $this->redirectToRoute('Gestionrdv');      
    }

    /**
     * @Route("/Cabinet/Administration/Gestionrdv/créer", name="créerrdv")
     */
    public function CréerrdvAction(Request $request)
    {

            $rdv = new RDV;
            $form = $this->createFormBuilder($rdv)
              ->add('prenom', TextType::class, array('attr' => array('class' => 'form-control' , 'style' => 'margin-bottom:15px')))
              ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'))) 
              ->add('cin', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'))) 
              ->add('num_tel', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
              ->add('date_rdv', DateType::class, array('input' => 'string', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
              ->add('seance',ChoiceType::class, array('label' => 'Séance', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'), 'choices' => array('Séance 1' => 'S1' ,'Séance 2' => 'S2','Séance 3' => 'S3','Séance 4 ' => 'S4','Séance 5' => 'S5','Séance 6' => 'S6','Séance 7' => 'S7','Séance 8' => 'S8'),'choices_as_values' => true,'multiple'=>false,'expanded'=>false))
              ->add('submit', SubmitType::class, array('label' => 'Valider', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
              ->getForm();
              
              $form ->handleRequest($request);

              if($form->isSubmitted() && $form->isValid()){
                
                $prenom = $form['prenom']->getData();
                $nom = $form['nom']->getData();
                $cin = $form['cin']->getData();
                $num_tel = $form['num_tel']->getData();
                $date_rdv = $form['date_rdv']->getData();
                $seance = $form['seance']->getData();

                $em1 = $this->getDoctrine()->getManager();
                $RAW_QUERY1 = 'SELECT r_d_v.seance FROM r_d_v WHERE r_d_v.date_rdv = :d ;';
                $statement1 = $em1->getConnection()->prepare($RAW_QUERY1);
                $statement1->bindValue('d', $date_rdv);
                $statement1->execute();
                $result1 = $statement1->fetchAll();

                $sv = true ;

                foreach ($result1 as $key => $value) {
                if (in_array($seance, $value)){
                    $sv = false ;
                }
            }

                if ($sv == false) {
                    $this->addFlash(
                    'notice',
                    'Cette séance est déjas réserver !'
                    );
                }else{

                $rdv->setPrenom($prenom);
                $rdv->setNom($nom);
                $rdv->setCin($cin);
                $rdv->setNumTel($num_tel);
                $rdv->setDateRdv($date_rdv);
                $rdv->setSeance($seance);

                $em = $this -> getDoctrine() -> getManager();
                $em->persist($rdv);
                $em->flush();

                $this->addFlash(
                'notice',
                'Rendez-vous crée avec succès'
                );
                return $this->redirectToRoute('Gestionrdv');
                }

        return $this->render('Cabinet/créerrdv.html.twig', array(
            'form' => $form->createView()
             ));
        }
                return $this->render('Cabinet/créerrdv.html.twig', array(
            'form' => $form->createView()
             ));
    }

    /**
     * @Route("/Cabinet/Administration/Gestionmutuelle", name="Gestionmutuelle")
     */
    public function GestionmutuelleAction()
    {
        return $this->render('Cabinet/gestionmutuelle.html.twig' , array());
    }

       /**
     * @Route("/Cabinet/Administration/Gestionfiche", name="Gestionfiche")
     */
    public function GestionficheAction()
    {
        return $this->render('Cabinet/gestionfiche.html.twig' , array());
    }
    
    /**
     * @Route("/Cabinet/Administration/Gestionconsultation", name="Cabinetconsultation")
     */
    public function GestionconsultationAction()
    {
        return $this->render('Cabinet/gestionconsultation.html.twig' , array());
    }         


    /**
     * @Route("/PrendreRDV", name="Prendrerdv")
     */
    public function RDVAction(Request $request)
    {
            $cin = $this->getUser()->getCin();
            $prenom = $this->getUser()->getPrenom();
            $nom = $this->getUser()->getNom();
            $form['prenom'] = $prenom;
            $form['nom'] = $nom;
            $form['cin'] = $cin;

                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY = 'SELECT * FROM r_d_v where r_d_v.cin = :c ;';
                $statement = $em->getConnection()->prepare($RAW_QUERY); 
                $statement->bindValue('c', $cin);
                $statement->execute();
                $result = $statement->fetchAll();
                                        
                if ($result <> null) {
                foreach ($result as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $d = $value1['date_rdv'];
                        $s = $value1['seance'];
                            }
                        }
                    return $this->render('Cabinet/HAVERDV.html.twig', array('value1' => $value1));
                }else{

            $rdv = new RDV;
            $form = $this->createFormBuilder($rdv)
              ->add('prenom', TextType::class, array('attr' => array('class' => 'form-control' , 'placeholder' => $prenom , 'disabled' => 'true' , 'style' => 'margin-bottom:15px')))
              ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => $nom , 'disabled' => 'true' , 'style' => 'margin-bottom:15px'))) 
              ->add('cin', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => $cin ,'disabled' => 'true' , 'style' => 'margin-bottom:15px'))) 
              ->add('num_tel', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Numéro de téléphone' , 'style' => 'margin-bottom:15px')))
              ->add('date_rdv', DateType::class, array('input' => 'string', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
              ->add('seance',ChoiceType::class, array('label' => 'Séance', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'), 'choices' => array('Séance 1' => 'S1' ,'Séance 2' => 'S2','Séance 3' => 'S3','Séance 4 ' => 'S4','Séance 5' => 'S5','Séance 6' => 'S6','Séance 7' => 'S7','Séance 8' => 'S8'),'choices_as_values' => true,'multiple'=>false,'expanded'=>false))
              ->add('submit', SubmitType::class, array('label' => 'Valider', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
              ->getForm();
              
              $form ->handleRequest($request);

              if($form->isSubmitted() && $form->isValid()){
                
                $num_tel = $form['num_tel']->getData();
                $date_rdv = $form['date_rdv']->getData();
                $seance = $form['seance']->getData();

                $em1 = $this->getDoctrine()->getManager();
                $RAW_QUERY1 = 'SELECT r_d_v.seance FROM r_d_v WHERE r_d_v.date_rdv = :d ;';
                $statement1 = $em1->getConnection()->prepare($RAW_QUERY1);
                $statement1->bindValue('d', $date_rdv);
                $statement1->execute();
                $result1 = $statement1->fetchAll();

                $sv = true ;

                foreach ($result1 as $key => $value) {
                if (in_array($seance, $value)){
                    $sv = false ;
                }
            }

                if ($sv == false) {
                    $this->addFlash(
                    'notice',
                    'Cette séance est déjas réserver !'
                    );
                }else{

                $rdv->setPrenom($prenom);
                $rdv->setNom($nom);
                $rdv->setCin($cin);
                $rdv->setNumTel($num_tel);
                $rdv->setDateRdv($date_rdv);
                $rdv->setSeance($seance);

                $em = $this -> getDoctrine() -> getManager();
                $em->persist($rdv);
                $em->flush();   

                return $this->redirectToRoute('Prendrerdv');
                }

        return $this->render('Cabinet/RDV.html.twig', array(
            'form' => $form->createView()
             ));
            }    
        }
            return $this->render('Cabinet/RDV.html.twig', array(
            'form' => $form->createView()
             ));
    }
}
?>
