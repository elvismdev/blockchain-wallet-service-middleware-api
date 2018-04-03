<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BlockchainApiCreateUserCommand extends Command
{
    protected static $defaultName = 'blockchain-api:create-user';

    /**
     * @var User
     */
    private $userManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserRepository $userManager, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->userManager      = $userManager;
        $this->entityManager    = $em;
        $this->encoder          = $encoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->setDescription('Adds an application user for JWT authentication.')
        ->addArgument('username', InputArgument::REQUIRED, 'API Username')
        ->addArgument('password', InputArgument::REQUIRED, 'API Password')
        ->addArgument('appname', InputArgument::REQUIRED, 'Application Name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io         = new SymfonyStyle($input, $output);
        $username   = $input->getArgument('username');
        $password   = $input->getArgument('password');
        $appname    = $input->getArgument('appname');
        
        // Check if user already exists in DB. If it doesn't, create new record.
        if ($user = $this->userManager->findOneByUsername($username)) {
            $io->note(sprintf('Username already exists: %s', $username));
        } else {
            $user = new User();
            $user->setUsername($username);
            $user->setApplicationName($appname);
            $encoded = $this->encoder->encodePassword($user, $password);
            $user->setPassword($encoded);
            // Tells Doctrine I want to save this user (no queries yet).
            $this->entityManager->persist($user);
            // Record/update user in DB.
            $this->entityManager->flush();

            $io->success('User ' . $user->getUsername() . ' successfully generated!');
        }
        
    }
}
