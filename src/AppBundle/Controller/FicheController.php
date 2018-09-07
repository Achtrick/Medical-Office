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

class FicheController extends Controller
{


    /**
     * @Route("/Cabinet/Administration/Gestionfiche", name="Gestionfiche")
     */
    public function GestionficheAction()
    {
       
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM fos_user where fos_user.cin <> :c;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('c', '');
        $statement->execute();
        $users = $statement->fetchAll();

        return $this->render('Cabinet/fiche/gestionfiche.html.twig' , array(
        'users'=>$users
        ));
    }


    /**
     * @Route("/Cabinet/Administration/Gestionfiche/search", name="searchfiche")
     */
    public function SearchficheAction()
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
                return $this->redirectToRoute('Gestionfiche');
        }

		return $this->render('Cabinet/fiche/gestionfiche.html.twig', array(
    	'users' => $users));
    }

    /**
     * @Route("/Cabinet/Administration/Gestionfiche/voir/{id}", name="voirfiche")
     */
    public function VoirficheAction($id)
    {

        $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);

        $c = $user->getCin();

        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM mutuelle where mutuelle.cin = :c;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('c', $c);
        $statement->execute();
        $result = $statement->fetchAll();

        if ($result <> null) {
            foreach ($result as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $mutuelle=$value;
                }
            }
        }else{
                $mutuelle=null;
        }
        

        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM consultation where consultation.cin = :c;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('c', $c);
        $statement->execute();
        $consultations = $statement->fetchAll();



        return $this->render('Cabinet/fiche/voirfiche.html.twig' , array(
        'user'=>$user, 'mutuelle' => $mutuelle, 'consultations'=>$consultations
        ));

    }
    

    /**
     * @Route("/voirmafiche", name="voirmafiche")
     */
    public function VoirmaficheAction()
    {
        $c = $this->getUser()->getCin();
        $id = $this->getUser()->getId();

        $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM mutuelle where mutuelle.cin = :c;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('c', $c);
        $statement->execute();
        $result = $statement->fetchAll();

        if ($result == null) {
            $mutuelle = null;
        }else{

        foreach ($result as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $mutuelle=$value;
            }
        }
    }
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM consultation where consultation.cin = :c;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('c', $c);
        $statement->execute();
        $consultations = $statement->fetchAll();



        return $this->render('Cabinet/fiche/voirmafiche.html.twig' , array(
        'user'=>$user, 'mutuelle' => $mutuelle, 'consultations'=>$consultations
        ));

    }
}
?>