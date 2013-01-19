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

	/** @var Nette\Database\Table\IReflection */
	protected $databaseReflection;

	/** @var string */
	protected $tableName;

	/** @var string */
	protected $primaryColumn;

	/** @var Settings */
	protected $settings;



	/**
	 * Manager constructor.
	 * @param  Nette\Database\Connection $connection
	 * @param  string
	 * @param  string
	 */
	public function __construct(Nette\Database\Connection $connection, Settings $settings, $tableName = NULL)
	{
		$this->connection = $connection;
		$this->settings   = $settings;
		if ($tableName) {
			$this->tableName = $tableName;
		}

		if (empty($this->tableName)) {
			throw new Nette\InvalidStateException('Undefined tableName property in ' . $this->getReflection()->name);
		}

		$this->databaseReflection = $connection->table($this->tableName)->getDatabaseReflection();
		$this->primaryColumn = $this->databaseReflection->getPrimary($this->tableName);
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
		if (!$class && isset($this->settings->tables->{$selection->getTable()})) {
			$class = $this->settings->tables->{$selection->getTable()};
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
	public function create($values)
	{
		$entity = $this->table()->insert($values);
		return $this->get($entity[$this->primaryColumn]);
	}



	/**
	 * Updates entry
	 * @param  mixed $values
	 * @return Entity
	 */
	public function update($values)
	{
		if (!isset($values[$this->primaryColumn]))
			throw new Nette\InvalidArgumentException('Missing primary value');

		$primaryValue = $values[$this->primaryColumn];
		unset($values[$this->primaryColumn]);
		$this->table()->where($this->primaryColumn, $primaryValue)->update($values);
		return $this->get($primaryValue);
	}



	/**
	 * Deletes entry
	 * @param  Entity|mixed  Entity instance or primary value
	 * @return book
	 */
	public function delete($entity)
	{
		if ($entity instanceof Entity)
			$primaryValue = $entity[$this->primaryColumn];
		else
			$primaryValue = $entity;

		return $this->table()->where($this->primaryColumn, $primaryValue)->delete() > 0;
	}



	/**
	 * Returns table selection.
	 * @return Selection
	 */
	final protected function table()
	{
		return new Selection($this->connection, $this->tableName, $this);
	}



	/**
	 * @return Nette\Database\Table\IReflection
	 */
	public function getDatabaseReflection()
	{
		return $this->databaseReflection;
	}

}
