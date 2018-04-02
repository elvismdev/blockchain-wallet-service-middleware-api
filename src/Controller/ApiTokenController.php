<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiTokenController extends Controller
{
    /**
     * @Route("/login_check", name="login_check", methods={"POST"})
     */
    public function getNewToken(Request $request)
    {

    	$token = $this->get('lexik_jwt_authentication.encoder')
    	->encode([
    		'username' 	=> 'elvismdev',
    		// 'exp'		=> time() + 3600
    	]);

    	return new JsonResponse([
    		'token' => $token
    	]);
    }
}
