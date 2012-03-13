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
 * Ndab base model entity
 *
 * @author  Jan Skrasek
 */
class Entity extends Table\ActiveRow
{

	public function & __get($key)
	{
		$method = "get$key";
		$method[3] = $method[3] & "\xDF";

		if (!$this->__isset($key) && method_exists($this, $method)) {
			$return = $this->$method();
			return $return;
		}

		return parent::__get($key);
	}



	/**
	 * Returns array of subItems fetched from related() call
	 * @param  string  "relatedTable:subItem"
	 * @param  callable  callback for additional related call definition
	 * @return array
	 */
	protected function getSubRelation($selector, $relatedCallback = NULL)
	{
		list($relatedSelector, $subItemSelector) = explode(':', $selector);

		$related = $this->related($relatedSelector);
		if ($relatedCallback) {
			callback($relatedCallback)->invokeArgs(array($related));
		}

		$subItems = array();
		foreach ($related as $subItem) {
			$subItems[] = $subItem->$subItemSelector;
		}

		return $subItems;
	}

}
