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
	public function __construct($table, Nette\Database\Connection $connection, Manager $manager)
	{
		parent::__construct($table, $connection);
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



	/********************* references *********************/



	public function getReferencedTable($table, $column, $checkReferenceNewKeys = FALSE)
	{
		$referenced = & $this->referenced[$table][$column];
		if ($referenced === NULL || $checkReferenceNewKeys || $this->checkReferenceNewKeys) {
			$keys = array();
			foreach ($this->rows as $row) {
				if ($row[$column] === NULL)
					continue;

				$key = $row[$column] instanceof Table\ActiveRow ? $row[$column]->getPrimary() : $row[$column];
				$keys[$key] = TRUE;
			}

			if ($referenced !== NULL && $keys === array_keys($this->rows)) {
				$this->checkReferenceNewKeys = FALSE;
				return $referenced;
			}

			if ($keys) {
				$referenced = new Selection($table, $this->connection, $this->manager);
				$referenced->where($table . '.' . $referenced->primary, array_keys($keys));
			} else {
				$referenced = array();
			}
		}

		return $referenced;
	}



	public function getReferencingTable($table, $column, $active = NULL)
	{
		$referencing = new GroupedSelection($table, $this, $column, $active);
		$referencing->where("$table.$column", array_keys((array) $this->rows)); // (array) - is NULL after insert
		return $referencing;
	}

}
