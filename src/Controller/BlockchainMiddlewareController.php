<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Blockchain\Blockchain;

class BlockchainMiddlewareController extends Controller
{
    /**
     * @var Blockchain
     */
    private $blockchain;


    public function __construct( array $walletCreds, Blockchain $blockchain )
    {
        $this->blockchain = $blockchain;
        $this->blockchain->Wallet->credentials($walletCreds['id'], $walletCreds['main_password'], $walletCreds['second_password']);
    }

    /**
     * Get the balance of the whole wallet.
     * @Route("/balance", name="balance")
     */
    public function balance()
    {
        return new JsonResponse($this->blockchain->Wallet->getBalance());
    }
}
