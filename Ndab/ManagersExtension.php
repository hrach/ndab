<?php

/**
 * This file is part of the Ndab
 *
 * Copyright (c) 2012 Jan Skrasek (http://jan.skrasek.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Ndab;

use Nette;



class ManagersExtension extends Nette\DI\CompilerExtension
{

    public function loadConfiguration()
    {
        $config = $this->getConfig();
        $builder = $this->getContainerBuilder();

        unset($config['services']);
        isset($config['tables']) ?: $config['tables'] = array();

        $builder->addDefinition($this->prefix('settings'))
        	->setClass('Ndab\Settings')
        	->setFactory('Ndab\Settings::from', array($config));
	}

}
