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
 * Ndab base entity manager
 *
 * @author  Jan Skrasek
 */
abstract class Manager extends Nette\Object
{
	/** @var Nette\Dabase\Connection */
	protected $connection;

	/** @var string */
	protected $tableName;

	/** @var string */
	protected $rowClass;



	/**
	 * Manager constructor.
	 * @param  Nette\Database\Connection $connection
	 * @param  string
	 * @param  string
	 */
	public function __construct(Nette\Database\Connection $connection, $tableName = NULL, $rowClass = NULL)
	{
		$this->connection = $connection;
		if ($tableName) {
			$this->tableName = $tableName;
		}
		if ($rowClass) {
			$this->rowClass = $rowClass;
		}

		if (empty($this->tableName)) {
			throw new Nette\InvalidStateException('Undefined tableName property in ' . $this->getReflection()->name);
		}
	}



	/**
	 * Creates entity with data.
	 * @param  array      entity data
	 * @param  Selection  parent selection
	 * @return Table\ActiveRow
	 */
	public function initEntity(array $data, Table\Selection $selection)
	{
		$class = $selection->getRowClass();
		if (!$class) {
			$class = $this->rowClass;
		}
		if (!$class) {
			 $class = '\Ndab\Entity';
		}
		return new $class($data, $selection);
	}



	/**
	 * Finds all data
	 * @param  array  $conds
	 * @return Selection
	 */
	public function findAll($conds = array())
	{
		return $this->table()->where($conds);
	}



	/**
	 * Returns table selection.
	 * @return Selection
	 */
	final protected function table()
	{
		return new Selection($this->connection, $this->tableName, $this);
	}

}
