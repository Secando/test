<?php


use App\Config;
use App\Csrf;
use App\Twig\OrderStatusExtension;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Odan\Twig\TwigAssetsExtension;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;

return [
    App::class                              => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $addMiddlewares = require CONFIG_PATH . '/middlewares.php';
        $router         = require CONFIG_PATH . '/routes.php';

        $app = AppFactory::create();



        $router($app);

        $addMiddlewares($app);

        return $app;
    },
    Config::class => new Config(),
    \Doctrine\ORM\EntityManager::class => function (Config $config) {
        return new EntityManager(
            DriverManager::getConnection(
                $config->db,
                ORMSetup::createAttributeMetadataConfiguration([ENTITY_PATH], 'true')
            ),
            ORMSetup::createAttributeMetadataConfiguration([ENTITY_PATH], 'true')

        );
    },
    \Slim\Views\Twig::class=> function(Config $config,\Psr\Container\ContainerInterface $container){
        $twig = Twig::create(VIEW_PATH, []);
        $twig->addExtension(new OrderStatusExtension);
        return $twig;
    },
    \Symfony\Component\Mailer\MailerInterface::class=> function(Config $config){
     return new \App\CustomMailer($config->dsn);
    },
    \League\Flysystem\Filesystem::class=> function(){
        $adapter = new League\Flysystem\Local\LocalFilesystemAdapter(__DIR__.'/../../public/assets/images');
        return new League\Flysystem\Filesystem($adapter);
    },
    ResponseFactoryInterface::class         => fn(App $app) => $app->getResponseFactory(),
    'csrf'                                  => fn(ResponseFactoryInterface $responseFactory, Csrf $csrf) => new Guard(
        $responseFactory, failureHandler: $csrf->failureHandler(), persistentTokenMode: true
    ),

    ];