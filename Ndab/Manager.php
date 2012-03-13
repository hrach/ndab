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

	/** @var IEntityLoader */
	protected $entityLoader;



	/**
	 * Manager constructor.
	 * @param  Nette\Database\Connection $connection
	 * @param  IEntityLoader $loader
	 */
	public function __construct(Nette\Database\Connection $connection, IEntityLoader $loader)
	{
		$this->connection = $connection;
		$this->entityLoader = $loader;
	}



	/**
	 * Creates entity with data.
	 * @param  array      entity data
	 * @param  Selection  parent selection
	 * @return Table\ActiveRow
	 */
	public function initEntity(array $data, Table\Selection $selection)
	{
		$class = $this->entityLoader->getEntityClassName($this, $selection, $data) ?: '\NDab\Entity';
		return new $class($data, $selection);
	}



	/**
	 * Returns table selection.
	 * @return Selection
	 */
	final protected function table()
	{
		return new Selection($this->name, $this->connection, $this);
	}

}
