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

class Tx_EdExtbase_ViewHelpers_Widget_Controller_PaginateController extends Tx_Fluid_Core_Widget_AbstractWidgetController {

	/**
	 * @var array
	 */
	protected $configuration = array('itemsPerPage' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE, 'pagerTemplate' => FALSE);

	/**
	 * @var Tx_Extbase_Persistence_QueryResultInterface
	 */
	protected $objects;

	/**
	 * @var integer
	 */
	protected $currentPage = 1;

	/**
	 * @var integer
	 */
	protected $numberOfPages = 1;

	/**
	 * @return void
	 */
	public function initializeAction() {
		$this->objects = $this->widgetConfiguration['objects'];
		$this->configuration = t3lib_div::array_merge_recursive_overrule($this->configuration, $this->widgetConfiguration['configuration'], TRUE);
		$objectsCount = 0;
		foreach ($this->objects as $objects) {
			$objectsCount += count($objects);
		}
		$this->numberOfPages = ceil($objectsCount / (integer)$this->configuration['itemsPerPage']);
	}

	/**
	 * @param integer $currentPage
	 * @return void
	 */
	public function indexAction($currentPage = 1) {
		if ($this->configuration['pagerTemplate']) {
			$this->view->setTemplatePathAndFilename($GLOBALS['TSFE']->tmpl->getFileName($this->configuration['pagerTemplate']));
		}
		
			// set current page
		$this->currentPage = (integer)$currentPage;
		if ($this->currentPage < 1) {
			$this->currentPage = 1;
		} elseif ($this->currentPage > $this->numberOfPages) {
			$this->currentPage = $this->numberOfPages;
		}

			// modify query
		$itemsPerPage = (integer)$this->configuration['itemsPerPage'];

		$paginatedItems = array();
		$previousObjectSetsCount = 0;
		foreach ($this->objects as $objects) {
			if (count($paginatedItems)==$itemsPerPage) {
				break;
			}
			if ($objects instanceof Tx_Extbase_Persistence_QueryResultInterface) {
				$query = $objects->getQuery();
				$query->setLimit($itemsPerPage-count($paginatedItems));

				if ($this->currentPage > 1) {
					if (count($paginatedItems)==0) {
						$query->setOffset((integer)(($itemsPerPage * ($this->currentPage - 1)) - $previousObjectSetsCount));
					}
				}
				
				$modifiedObjects = $query->execute();
				foreach ($modifiedObjects as $obj) {
					$paginatedItems[] = $obj;
				}
			} else {
				// TODO: implement logic for plain arrays
			}
			$previousObjectSetsCount += count($objects);
 		}
		
		$this->view->assign('contentArguments', array(
			$this->widgetConfiguration['as'] => $paginatedItems
		));
		$this->view->assign('configuration', $this->configuration);
		$this->view->assign('pagination', $this->buildPagination());
	}

	/**
	 * Returns an array with the keys "pages", "current", "numberOfPages", "nextPage" & "previousPage"
	 *
	 * @return array
	 */
	protected function buildPagination() {
		$pages = array();
		for ($i = 1; $i <= $this->numberOfPages; $i++) {
			$pages[] = array('number' => $i, 'isCurrent' => ($i === $this->currentPage));
		}
		$pagination = array(
			'pages' => $pages,
			'current' => $this->currentPage,
			'numberOfPages' => $this->numberOfPages,
		);
		if ($this->currentPage < $this->numberOfPages) {
			$pagination['nextPage'] = $this->currentPage + 1;
		}
		if ($this->currentPage > 1) {
			$pagination['previousPage'] = $this->currentPage - 1;
		}
		return $pagination;
	}
}

?>