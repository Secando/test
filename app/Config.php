<?php

namespace App;

class Config
{
    private array $config = [];

    public function __construct()
    {
        $this->config = [
            'app_host'=>$_ENV['APP_HOST'],
            'app_email'=>$_ENV['APP_EMAIL'],
            'db' => [
                'host' => $_ENV['DB_HOST'],
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
                'driver' => $_ENV['DB_DRIVER']
            ],
            'twig_assets'=>[
                'path' => __DIR__ . '/../public/cache',
                // Public url base path
                'url_base_path' => 'cache/',
                // Internal cache directory for the assets
                'cache_path' => __DIR__ . '/tmp/twig-assets',
                'cache_name' => 'assets-cache',
                //  Should be set to 1 (enabled) in production
                'minify' => 1,
            ],
            'dsn'=>$_ENV['MAIL_SMTP']
        ];
    }

    public function __get(string $name)
    {
        return $this->config[$name];
    }
}