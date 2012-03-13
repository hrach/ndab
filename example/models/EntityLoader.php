<?php

/**
 * This file is part of the Ndab
 *
 * Copyright (c) 2012 Jan Skrasek (http://jan.skrasek.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

use Nette\Database\Table;




class EntityLoader implements Ndab\IEntityLoader
{

	public function getEntityClassName(Ndab\Manager $manager, Table\Selection $selection, array $data)
	{
		if ($selection->name === 'book') {
			return 'BookEntity';
		}
	}

}
