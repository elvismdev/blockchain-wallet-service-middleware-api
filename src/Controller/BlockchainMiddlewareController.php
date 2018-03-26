<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class BlockchainMiddlewareController extends Controller
{
    /**
     * @Route("/blockchain/middleware", name="blockchain_middleware")
     */
    public function index()
    {
    	$blockchain = $this->container->get('ami_blockchain.blockchain');
    	$blockchain->Wallet->credentials('wallet-id-1', 'password-1');

        $data = $blockchain->Wallet->getBalance();

        return new JsonResponse($data);
    }
}
