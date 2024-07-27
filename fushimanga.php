<?php
// application.php



use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
$app = require __DIR__. '/bootstrap.php';
$container = $app->getContainer();
$application = new Application();


// ... register Commands
$application->add(new \App\Commands\CreateAdminUserCommand($container->get(\Doctrine\ORM\EntityManager::class)));
$application->run();

