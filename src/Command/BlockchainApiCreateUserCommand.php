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
use Symfony\Component\Console\Question\Question;

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
        // ->addArgument('username', InputArgument::REQUIRED, 'API Username')
        // ->addArgument('password', InputArgument::REQUIRED, 'API Password')
        // ->addArgument('appname', InputArgument::REQUIRED, 'Application Name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io         = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        // $username   = $input->getArgument('username');
        // $password   = $input->getArgument('password');
        $question = new Question('Application Name: ');
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('Application name cannot be empty');
            }

            return $value;
        });
        $appname = $helper->ask($input, $output, $question);

        // $appname    = $input->getArgument('appname');
        $username   = md5( $appname );
        $password   = hash( 'sha256', $username . time() );
        
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

            $io->table(['API Username', 'API Password'], [[$username, $password]]);
            $io->note('Keep in a safe place your credentials, you won\'t see them again.');
            $io->success('API user successfully generated!');

        }
        
    }
}
