<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RDV;
use AppBundle\Entity\User;
use AppBundle\Entity\Mutuelle;
use AppBundle\Entity\Consultation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class MutuelleController extends Controller
{    


    /**
     * @Route("/Cabinet/Administration/Gestionmutuelle", name="Gestionmutuelle")
     */
    public function GestionmutuelleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM fos_user where fos_user.cin <> :c;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('c', '');
        $statement->execute();
        $users = $statement->fetchAll();

        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM mutuelle ;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $mutuelles = $statement->fetchAll();

        return $this->render('Cabinet/mutuelle/gestionmutuelle.html.twig' , array(
        'users'=>$users, 'mutuelles' => $mutuelles
        ));
    }


    /**
     * @Route("/Cabinet/Administration/Gestionmutuelle/search", name="searchmutuelle")
     */
    public function SearchmutuelleAction()
    {
        if ( ! empty($_POST['search'])){
        $data = $_POST['search'];
        }

        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM fos_user where fos_user.cin = :c;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('c', $data);
        $statement->execute();
        $users = $statement->fetchAll();

        if ($users == null) {
            $this->addFlash(
                'notice',
                'Aucun résultat trouvé !'
                );
                return $this->redirectToRoute('Gestionmutuelle');
        }else{
            $this->addFlash(
                'notice',
                'Résultat pour votre recherche'
                );
        }


        return $this->render('Cabinet/mutuelle/gestionmutuelle.html.twig', array(
        'users' => $users));
    }

    /**
     * @Route("/Cabinet/Administration/Gestionmutuelle/créer/{id}", name="créermutuelle")
     */
    public function CréermutuelleAction($id, Request $request)
    {
        $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);

        $mutuelle = new Mutuelle;
        $form = $this->createFormBuilder($mutuelle)
        ->add('caisse',ChoiceType::class, array('label' => 'Caisse', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'), 'choices' => array('CNSS' => 'CNSS' ,'CNAM' => 'CNAM', 'CNRPS' => 'CNRPS'),'choices_as_values' => true,'multiple'=>false,'expanded'=>false))
        ->add('valide', DateType::class, array('label' => 'Valide de','input' => 'string', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('a', DateType::class, array('label' => 'à','input' => 'string', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Identificateur', TextType::class, array('attr' => array('placeholder' => '123456789123', 'class' => 'form-control' , 'style' => 'margin-bottom:15px')))            
        ->add('submit', SubmitType::class, array('label' => 'Valider', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
        ->getForm();
              
        $form ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
                
        $prenom = $user->getPrenom();
        $nom = $user->getNom();
        $cin = $user->getCin();
        $caisse = $form['caisse']->getData();
        $identificateur = $form['Identificateur']->getData();
        $valide = $form['valide']->getData();
        $a = $form['a']->getData();

        $mutuelle->setPrenom($prenom);
        $mutuelle->setNom($nom);
        $mutuelle->setCin($cin);
        $mutuelle->setCaisse($caisse);
        $mutuelle->setValide($valide);
        $mutuelle->setA($a);
        $mutuelle->setIdentificateur($identificateur);
        $user->setM('YES');

        $em = $this -> getDoctrine() -> getManager();
        $em->persist($mutuelle);
        $em->flush();

        $this->addFlash(
        'notice',
        'Mutuelle crée avec succès'
        );
        return $this->redirectToRoute('Gestionmutuelle');
        }
        return $this->render('Cabinet/mutuelle/créermutuelle.html.twig', array(
        'form' => $form->createView()
        ));
        }

    /**
     * @Route("/Cabinet/Administration/Gestionmutuelle/edit/{id}", name="editmutuelle")
     */
    public function EditmutuelleAction($id, Request $request)
    {
        $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);

        $c = $user -> getCin();


        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM mutuelle where mutuelle.cin = :c ;';
        $statement = $em->getConnection()->prepare($RAW_QUERY); 
        $statement->bindValue('c', $c);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach ($result as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $idm = $value['id'];
            }
        }

        $mutuelle = $this->getDoctrine()
        ->getRepository('AppBundle:Mutuelle')
        ->find($idm);

        $mutuelle->setPrenom($mutuelle->getPrenom());
        $mutuelle->setNom($mutuelle->getNom());
        $mutuelle->setCin($mutuelle->getCin());
        $mutuelle->setValide($mutuelle->getValide());
        $mutuelle->setA($mutuelle->getA());
        $mutuelle->setIdentificateur($mutuelle->getIdentificateur());

        $form = $this->createFormBuilder($mutuelle)
        ->add('caisse',ChoiceType::class, array('label' => 'Caisse', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'), 'choices' => array('CNSS' => 'CNSS' ,'CNAM' => 'CNAM', 'CNRPS' => 'CNRPS'),'choices_as_values' => true,'multiple'=>false,'expanded'=>false))
        ->add('valide', DateType::class, array('label' => 'Valide de','input' => 'string', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('a', DateType::class, array('label' => 'à','input' => 'string', 'widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Identificateur', TextType::class, array('attr' => array('placeholder' => '123456789123', 'class' => 'form-control' , 'style' => 'margin-bottom:15px')))            
        ->add('submit', SubmitType::class, array('label' => 'Valider', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
        ->getForm();
              
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
                
        $prenom = $user->getPrenom();
        $nom = $user->getNom();
        $cin = $user->getCin();
        $caisse = $form['caisse']->getData();
        $identificateur = $form['Identificateur']->getData();
        $valide = $form['valide']->getData();
        $a = $form['a']->getData();

        $mutuelle->setPrenom($prenom);
        $mutuelle->setNom($nom);
        $mutuelle->setCin($cin);
        $mutuelle->setCaisse($caisse);
        $mutuelle->setValide($valide);
        $mutuelle->setA($a);
        $mutuelle->setIdentificateur($identificateur);

        $em = $this -> getDoctrine() -> getManager();
        $em->persist($mutuelle);
        $em->flush();

        $this->addFlash(
        'notice',
        'Mutuelle modifié avec succès'
        );
        return $this->redirectToRoute('Gestionmutuelle');
        }
        return $this->render('Cabinet/mutuelle/editmutuelle.html.twig', array(
        'form' => $form->createView()
        ));
        }

    /**
     * @Route("/Cabinet/Administration/Gestionmutuelle/delete/{id}", name="deletemutuelle")
     */
    public function DeletemutuelleAction($id, Request $request)
    {
        $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);

        $user->setM($user->getM());
        $user->setM('NO');
        $em = $this -> getDoctrine() -> getManager();
        $em->persist($user);
        $em->flush();

        $c = $user -> getCin();

        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM mutuelle where mutuelle.cin = :c ;';
        $statement = $em->getConnection()->prepare($RAW_QUERY); 
        $statement->bindValue('c', $c);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach ($result as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $idm = $value['id'];
            }
        }

       $em = $this -> getDoctrine() -> getManager();
       $mutuelle = $em -> getRepository('AppBundle:Mutuelle')->find($idm);
       $em->remove($mutuelle);
       $em->flush();

      $this->addFlash(
      'notice',
      'Mutuelle été supprimer avec succès'
      );
      return $this->redirectToRoute('Gestionmutuelle');

    }
}
?>