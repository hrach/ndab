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
	/** @var Selection */
	protected $refTable;



	public function __construct($name, Selection $refTable, $column, $active = NULL)
	{
		parent::__construct($name, $refTable, $column, $active);
		$this->refTable = $refTable;
	}



	protected function createRow(array $row)
	{
		return $this->refTable->manager->initEntity($row, $this);
	}



	public function aggregation($function)
	{
		$aggregation = & $this->refTable->aggregation[$function . implode('', $this->where) . implode('', $this->conditions)];
		if ($aggregation === NULL) {
			$aggregation = array();

			$selection = new Selection($this->name, $this->connection);
			$selection->where = $this->where;
			$selection->parameters = $this->parameters;
			$selection->conditions = $this->conditions;

			$selection->select($function);
			$selection->select("{$this->name}.{$this->column}");
			$selection->group("{$this->name}.{$this->column}");

			foreach ($selection as $row) {
				$aggregation[$row[$this->column]] = $row;
			}
		}

		if (isset($aggregation[$this->active])) {
			foreach ($aggregation[$this->active] as $val) {
				return $val;
			}
		}
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
				$referenced = new Selection($table, $this->connection, $this->refTable->manager);
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
