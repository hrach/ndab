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
	/** @var Nette\Database\Connection */
	protected $connection;

	/** @var string */
	protected $tableName;

	/** @var string */
	protected $rowClass;

	/** @var string */
	protected $primaryColumn;



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

		$this->primaryColumn = $this->connection->getDatabaseReflection()->getPrimary($this->tableName);
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
	 * Returns all rows filtered by $conds
	 * @param  array  $conds
	 * @return Selection
	 */
	public function getAll($conds = array())
	{
		return $this->table()->where($conds);
	}



	/**
	 * Returns row identified by $privaryValue
	 * @param  mixed  $privaryValue
	 * @return Entity
	 */
	public function get($privaryValue)
	{
		return $this->table()->get($privaryValue);
	}



	/**
	 * Inserts data into table
	 * @param  mixed $values
	 * @return Entity
	 */
	public function insert($values)
	{
		return $this->table()->insert($values);
	}



	/**
	 * Updates data
	 * @param  mixed $values
	 * @return Entity
	 */
	public function update($values)
	{
		if (!isset($values[$this->primaryColumn]))
			throw new Nette\InvalidArgumentException('Missing privary value');

		$primaryValue = $values[$this->primaryColumn];
		unset($values[$this->primaryColumn]);
		$this->table()->where($this->primaryColumn, $primaryValue)->update($values);
		return $this->get($primaryValue);
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
