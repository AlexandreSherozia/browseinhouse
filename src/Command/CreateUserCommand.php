<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 12/09/2018
 * Time: 21:34
 */

namespace App\Command;


use App\Entity\User;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateUserCommand
 * @package App\Command
 */
class CreateUserCommand extends Command
{
    private $em;

    /**
     * CreateUserCommand constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }


    protected function configure()
    {
        //parent::configure(); // TODO: Change the autogenerated stub
        $this
            ->setName('user:promote:admin')
            ->setDescription('adds a role admin to defined user')
            ->setHelp('bla bla bla')
            ->addArgument('pseudo', InputArgument::REQUIRED, 'The pseudo of user')
            ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //parent::execute($input, $output); // TODO: Change the autogenerated stub

        $userPseudo = $input->getArgument('pseudo');
        $userToElevate = $this->em->getRepository(User::class)->findOneBy(['pseudo' => $userPseudo]);

        if ($userToElevate) {
            $userToElevate->addRole('ROLE_ADMIN');
            $this->em->persist($userToElevate);
            $this->em->flush();


            $output->writeln([
                '======================================================',
                'Great, ' . $userPseudo,
                'has been succesfully promoted ADMIN',
                '======================================================'
                ]);
        } else {
            $output->writeln([
                "====================================",
                "Attention, $userPseudo doesn't exist",
                "===================================="

            ]);
        }
    }

}