<?php

/**
 * This file is part of the Ndab
 *
 * Copyright (c) 2012 Jan Skrasek (http://jan.skrasek.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */


require './../../nette/Nette/loader.php';



$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->enableDebugger();
$configurator->createRobotLoader()->addDirectory(__DIR__ . '/../')->register();
$configurator->addConfig(__DIR__ . '/config.neon');


$container = $configurator->createContainer();

$template = $container->nette->templateFactory->__invoke();
$template->setFile(__DIR__ . '/index.latte');

$bookManager = new BookManager($container->nette->database->default, new EntityLoader);
$template->books = $bookManager->getAll();

echo $template->render();
