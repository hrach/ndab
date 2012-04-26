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
 * Ndab Grouped selection
 *
 * @author  Jan Skrasek
 */
class GroupedSelection extends Table\GroupedSelection
{

	protected function createRow(array $row)
	{
		return $this->refTable->manager->initEntity($row, $this);
	}



	protected function createSelectionInstance($table = NULL)
	{
		return new Selection($this->connection, $table ?: $this->table, $this->refTable->manager);
	}



	protected function createGroupedSelectionInstance($table, $column, $active)
	{
		return new GroupedSelection($this, $table, $column, $active);
	}

}
