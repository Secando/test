<?php

use Doctrine\ORM\EntityManager;

$container = require __DIR__.'/../bootstrap.php';


$em = $container->get(\Doctrine\ORM\EntityManager::class);

/**
 * @var  EntityManager $em
 */

$container->get(\App\Services\EmailService::class)->sendQueueMail();
