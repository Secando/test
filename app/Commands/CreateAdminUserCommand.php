<?php


namespace App\Commands;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

// the name of the command is what users type after "php bin/console"

#[AsCommand(name: 'create-admin-user')]
class CreateAdminUserCommand extends Command
{
    public function __construct(private EntityManager $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $adminUser = new User();
            $adminUser->setEmail($_ENV['ADMIN_EMAIL']);
            $adminUser->setName($_ENV['ADMIN_NAME']);
            $adminUser->setPassword(password_hash($_ENV['ADMIN_PASS'], PASSWORD_BCRYPT));
            $adminUser->setVerificatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $adminUser->setRole(\App\Enums\UserRole::ADMIN->value);
            $this->em->persist($adminUser);
            $this->em->flush();
        }catch (\Exception $e){
            return Command::FAILURE;
        }
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }
        return Command::SUCCESS;
    }
}