<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blockchain\Blockchain;

abstract class BlockchainBaseController extends Controller
{
    /**
     * @var Blockchain
     */
    protected $blockchain;


    public function __construct( array $walletCreds, Blockchain $blockchain )
    {
    	$this->blockchain = $blockchain;
    	$this->blockchain->Wallet->credentials($walletCreds['id'], $walletCreds['main_password'], $walletCreds['second_password']);
    }
}
