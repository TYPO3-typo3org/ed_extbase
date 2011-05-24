<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Nikola Stojiljkovic <nikola.stojiljkovic(at)essentialdots.com>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * This ViewHelper renders a Pagination of objects.
 *
 * = Examples =
 *
 * {namespace e=Tx_EdExtbase_ViewHelpers}
 * <code title="required arguments">
 * <e:widget.paginate objects="{10: blogs}" as="paginatedBlogs">
 *   // use {paginatedBlogs} as you used {blogs} before, most certainly inside
 *   // a <e:for> loop.
 * </e:widget.paginate>
 * </code>
 *
 * <code title="full configuration">
 * <e:widget.paginate objects="{10: blogs}" as="paginatedBlogs" configuration="{itemsPerPage: 5, insertAbove: 1, insertBelow: 0}">
 *   // use {paginatedBlogs} as you used {blogs} before, most certainly inside
 *   // a <e:for> loop.
 * </e:widget.paginate>
 * </code>
 *
 * = Performance characteristics =
 *
 * In the above examples, it looks like {blogs} contains all Blog objects, thus
 * you might wonder if all objects were fetched from the database.
 * However, the blogs are NOT fetched from the database until you actually use them,
 * so the paginate ViewHelper will adjust the query sent to the database and receive
 * only the small subset of objects.
 * So, there is no negative performance overhead in using the Paginate Widget.
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 */
class Tx_EdExtbase_ViewHelpers_Widget_PaginateViewHelper extends Tx_Fluid_Core_Widget_AbstractWidgetViewHelper {

	/**
	 * @var Tx_EdExtbase_ViewHelpers_Widget_Controller_PaginateController
	 */
	protected $controller;

	/**
	 * @param Tx_EdExtbase_ViewHelpers_Widget_Controller_PaginateController $controller
	 * @return void
	 */
	public function injectController(Tx_EdExtbase_ViewHelpers_Widget_Controller_PaginateController $controller) {
		$this->controller = $controller;
	}

	/**
	 *
	 * @param mixed $objects
	 * @param string $as
	 * @param array $configuration
	 * @return string
	 */
	public function render($objects, $as, array $configuration = array('itemsPerPage' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE, 'pagerTemplate' => FALSE)) {
		return $this->initiateSubRequest();
	}
}

?>