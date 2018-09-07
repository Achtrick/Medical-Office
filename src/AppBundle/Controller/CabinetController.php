<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

}
?>
