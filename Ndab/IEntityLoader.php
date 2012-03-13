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

use Nette,
	Nette\Database\Table;



/**
 * Ndab Entity loader, resolves entity class names
 *
 * @author  Jan Skrasek
 */
interface IEntityLoader
{

	/**
	 * Returns entity class name base on Manager/Selection/selected data
	 * @return  string
	 */
	function getEntityClassName(Manager $manager, Table\Selection $selection, array $data);

}
