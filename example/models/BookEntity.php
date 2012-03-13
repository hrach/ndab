<?php

/**
 * This file is part of the Ndab
 *
 * Copyright (c) 2012 Jan Skrasek (http://jan.skrasek.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */



class BookEntity extends Ndab\Entity
{

	public function getName()
	{
		return $this->title;
	}

	public function getTags()
	{
		return $this->getSubRelation('book_tag:tag');
	}

	public function getSortedTags()
	{
		return $this->getSubRelation('book_tag:tag', callback(function($related) {
			$related->order('tag.name ASC');
		}));
	}

}
