<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Wallet Management.
 * @Route("/wallet")
 */
class BlockchainWalletController extends BlockchainBaseController
{

    /**
     * Get the balance of the whole wallet.
     * @Route("/balance", name="wallet_balance")
     */
    public function balance()
    {
        return new JsonResponse($this->blockchain->Wallet->getBalance());
    }
}
