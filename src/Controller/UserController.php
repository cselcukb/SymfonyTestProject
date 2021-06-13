<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
	/**
	 * @Route("/dashboard", name="webapp_dashboard")
	 */
	public function dashboardAction(Request $request){
		$user = $this->getUser();
		return $this->render('users/dashboard.html.twig', array(
//			'last_username' => $lastUsername,
//			'error' => $error
		));
	}
}