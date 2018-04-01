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

    /**
     * Get wallet ID.
     * @Route("/id", name="wallet_id")
     */
    public function id()
    {
        return new JsonResponse($this->blockchain->Wallet->getIdentifier());
    }

    /**
     * Get single address balance.
     * @Route("/balance/{addr}", name="wallet_addr_balance")
     */
    public function addrBalance($addr)
    {
        return new JsonResponse($this->blockchain->Wallet->getAddressBalance($addr));
    }

    /**
     * Get wallet addresses.
     * @Route("/addresses", name="wallet_addresses")
     */
    public function addresses()
    {
        return new JsonResponse($this->blockchain->Wallet->getAddresses());
    }
}
