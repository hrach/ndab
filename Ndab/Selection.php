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
 * Ndab selection
 *
 * @author  Jan Skrasek
 */
class Selection extends Table\Selection
{
	/** @var Manager */
	protected $manager;



	/**
	 * Selection constructor.
	 * @param  string
	 * @param  Nette\Database\Connection
	 * @param  Manager
	 */
	public function __construct(Nette\Database\Connection $connection, $table, Manager $manager)
	{
		parent::__construct($connection, $table);
		$this->manager = $manager;
	}



	/**
	 * @return  Manager
	 */
	public function getManager()
	{
		return $this->manager;
	}



	protected function createRow(array $row)
	{
		return $this->manager->initEntity($row, $this);
	}



	protected function createSelectionInstance($table = NULL)
	{
		return new Selection($this->connection, $table ?: $this->table);
	}



	protected function createGroupedSelectionInstance($table, $column, $active)
	{
		return new GroupedSelection($this, $table, $column, $active);
	}

}
