<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Utils\DataCurator;

/**
 * Wallet Management.
 * @Route("/wallet")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
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
     * @param string $addr
     * @Route("/balance/{addr}", name="wallet_addr_balance")
     */
    public function addrBalance($addr)
    {
        return new JsonResponse($this->blockchain->Wallet->getAddressBalance($addr));
    }

    /**
     * Get list of the active addresses within the wallet.
     * @Route("/addresses", name="wallet_addresses")
     */
    public function addresses()
    {
        return new JsonResponse($this->blockchain->Wallet->getAddresses());
    }

    /**
     * Get a new Bitcoin address, with an optional label, less than 255 characters in length.
     * @param string $label
     * @Route("/new_address/{label}", name="wallet_new_address", defaults={"label"=null})
     */
    public function newAddress($label)
    {
        return new JsonResponse($this->blockchain->Wallet->getNewAddress($label));
    }

    /**
     * Archives an address.
     * @param string $addr
     * @Route("/arch_address/{addr}", name="wallet_arch_address")
     */
    public function archAddress($addr)
    {
        return new JsonResponse($this->blockchain->Wallet->archiveAddress($addr));
    }

    /**
     * Unarchive an address.
     * @param string $addr
     * @Route("/unarch_address/{addr}", name="wallet_unarch_address")
     */
    public function unarchAddress($addr)
    {
        return new JsonResponse($this->blockchain->Wallet->unarchiveAddress($addr));
    }

    /**
     * Send Bitcoin to a single recipient address.
     * @Route("/send", name="wallet_send", methods={"POST"})
     */
    public function send(Request $request)
    {
        return new JsonResponse($this->blockchain->Wallet->send(
            $request->get('to_address'), 
            $request->get('amount'), 
            $request->get('from_address'), 
            $request->get('fee')
        ));
    }

    /**
     * Send a multi-recipient transaction to many addresses at once.
     * @Route("/send_to_many", name="wallet_send_to_many", methods={"POST"})
     */
    public function sendToMany(Request $request)
    {
        $recipients = DataCurator::recipientsToArray(
            $request->get('recipients')
        );

        return new JsonResponse($this->blockchain->Wallet->sendMany(
            $recipients,
            $request->get('from_address'), 
            $request->get('fee')
        ));
    }

}
