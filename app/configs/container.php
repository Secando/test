<?php

$container = new \DI\ContainerBuilder();

$container->addDefinitions(require __DIR__.'/container_bindings.php');
return $container->build();