<?php
namespace Base\Dg;

class Table{
	protected $rowCount;
	protected $dataSet;
	protected $headers;
	protected $htmlOptions;
	protected $paging;
	protected $rowInPage;

	protected $rows;
	protected $buttons;
	protected $options;

	/**
	 * @return the $options
	 */
	public function getOptions() {
		return $this->options;
	}

	public function getOption($name) {
		return isset($this->options[$name])? $this->options[$name]: false;
	}

	/**
	 * @param field_type $options
	 */
	public function setOptions($options) {
		$this->options = $options;
	}

	/**
	 * @return the $buttons
	 */
	public function getButtons() {
		return $this->buttons;
	}

	/**
	 * @param field_type $buttons
	 */
	public function setButtons($buttons) {
		$this->buttons = $buttons;
	}

	/**
	 * @return the $rowInPage
	 */
	public function getRowInPage() {
		return $this->rowInPage;
	}

	/**
	 * @param field_type $rowInPage
	 */
	public function setRowInPage($rowInPage) {
		$this->rowInPage = $rowInPage;
	}

	/**
	 * @return the $rowCount
	 */
	public function getRowCount() {
		return $this->rowCount;
	}

	/**
	 * @param field_type $rowCount
	 */
	public function setRowCount($rowCount) {
		$this->rowCount = $rowCount;
	}

	/**
	 * @return the $dataSet
	 */
	public function getDataSet() {
		return $this->dataSet;
	}

	/**
	 * @param field_type $dataSet
	 */
	public function setDataSet($dataSet) {
		$this->dataSet = $dataSet;
	}

	/**
	 * @return the $headers
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * @param Ambigous <unknown, multitype:multitype:unknown  > $headers
	 */
	public function setHeaders($headers) {
		$this->headers = $headers;
	}

	/**
	 * @return the $htmlOptions
	 */
	public function getHtmlOptions() {
		return $this->htmlOptions;
	}

	/**
	 * @param field_type $htmlOptions
	 */
	public function setHtmlOptions($htmlOptions) {
		$this->htmlOptions = $htmlOptions;
	}

	/**
	 * @return the $paging
	 */
	public function getPaging() {
		return $this->paging;
	}

	/**
	 * @param field_type $paging
	 */
	public function setPaging($paging) {
		$this->paging = $paging;
	}

	/**
	 * @return the $rows
	 */
	public function getRows() {
		return $this->rows;
	}

	/**
	 * @param field_type $rows
	 */
	public function setRows($rows) {
		$this->rows = $rows;
	}

	public function __construct($options = NULL) {
		if(is_array($options)) {
			if (isset($options['data'])) {
				$this->setDataSet($options['data']);
			}
			if (isset($options['rowCount'])) {
				$this->setRowCount($options['rowCount']);
			}
			if (isset($options['headers'])) {
				$this->setHeaders($options['headers']);
			}
			if (isset($options['htmlOptions'])) {
				$this->setHtmlOptions($options['htmlOptions']);
			}
			if (isset($options['paging'])) {
				$this->setPaging($options['paging']);
			}
			if (isset($options['rowInPage'])) {
				$this->setRowInPage($options['rowInPage']);
			}
			if (isset($options['buttons'])) {
				$this->setButtons($options['buttons']);
			}
			$this->setOptions($options);
		}
	}
	/**
	 * prepare data
	 */
	protected function init() {
		$headerArr = array();
		foreach ($this->headers as $header) {
			$headerArr[] = array(
				'label' => $header,
			);
		}
		$this->setHeaders($headerArr);
	}

	public function __toString() {
		$this->init();
		$tableHtml = '';
		if(count($this->getButtons())) {
			$btnHtml = "<span class='btn-group'>";
			foreach ($this->getButtons() as $btn) {
				$btnHtml .= "<a class='btn {$btn['class']}' href='{$btn['href']}'><i class='{$btn['iClass']}'></i> {$btn['text']}</a>";
			}
			$btnHtml .= "</span>";
			$tableHtml .= $btnHtml;
		}
		$tableHtml .= "<table ";
		$class = 'dg table table-bordered table-hover table-condensed';
		$optionStr = '';
		if($this->htmlOptions) {
			if(isset($this->htmlOptions['class'])) {
				$class .= ' '.$this->htmlOptions['class'];
				unset($this->htmlOptions['class']);
			}
			foreach ($this->htmlOptions as $key => $value) {
				$optionStr .= "$key='$value' ";
			}
		}
		$optionStr .= " class='$class'";
		$tableHtml .= $optionStr . " >";
		// generate Header
		if (count($this->headers)) {
			$tableHtml .= "<thead>";
			foreach ($this->headers as $header) {
				$tableHtml .= "<th";
				if(isset($header['class'])) {
					$tableHtml .= " class='{$header['class']}'";
				}
                if(isset($header['style'])) {
                    $tableHtml .= " style='{$header['style']}'";
                }
				if(isset($header['htmlOptions'])) {
					foreach ($header['htmlOptions'] as $option => $value) {
						$tableHtml .= " $option='$value' ";
					}
				}
				$tableHtml .= ">";
				$tableHtml .= $header['label'];
				$tableHtml .= "</th>";
			}
			$tableHtml .= "</thead>";
		}
		// generate body
		if (count($this->rows)) {
			$tableHtml .= "<tbody>";
			$index = 0;
			foreach ($this->rows as $row) {
				$tableHtml .= $this->addRow($row, $index++);
			}
			$tableHtml .= "</tbody>";
		}
		$tableHtml .= "</table>";

		if($this->getPaging()) {
			
			//$tableHtml = "<div class='dg-container'>".$this->addPaginator(true).$tableHtml.$this->addPaginator()."</div>";
			$tableHtml = "<div class='dg-container'>".$tableHtml.$this->addPaginator()."</div>";
		} else {
			$tableHtml = "<div class='dg-container'>".$tableHtml."</div>";
		}
		return $tableHtml;
	}

	private function addRow($row, $index) {
        $rowClass = $index % 2 == 0 ? 'even' : 'odd';
        $first = reset($row);
        if (isset($first['parentClass'])) $rowClass .= ' '.$first['parentClass'];
		$rowHtml = "<tr class='$rowClass'>";
		foreach ($row as $cell) {
			$rowHtml .= $this->addCell($cell);
		}
		$rowHtml .= "</tr>";
		return $rowHtml;
	}

	private function addCell($cell) {
		$cellHtml = "<td";
		if(isset($cell['class'])) {
			$cellHtml .= " class='{$cell['class']}' ";
		}
		if(isset($cell['title'])) {
			$cellHtml .= " title='{$cell['title']}' ";
		}
		if(isset($cell['htmlOptions'])) {
			foreach ($cell['htmlOptions'] as $option => $value) {
				$cellHtml .= " $option='$value' ";
			}
		}
		$cellHtml .= ">";

		$cellContent = "";
		if(isset($cell['tag'])) {
			if(isset($cell['elementClass'])) {
				$cellValue = "<{$cell['tag']} class='{$cell['elementClass']}'>{$cell['value']}</{$cell['tag']}>";
			} else {
				$cellValue = "<{$cell['tag']}>{$cell['value']}</{$cell['tag']}>";
			}
		} else {
			$cellValue = $cell['value'];
		}
		switch ($cell['type']) {
			case "text":
				$cellContent = $cellValue;
				break;
			case "link":
				$href = isset($cell['href'])?$cell['href']:'#';
				$cellContent = "<a href='$href' target='".(isset($cell['target'])?$cell['target']:'_self')."'>{$cellValue}</a>";
				break;
			case "action":
				$href = isset($cell['href'])?$cell['href']:'#';
				$cellContent = "<a class='action' href='#' lnk='$href'>{$cellValue}</a>";
				break;
            case "actionBtn":
                $href = isset($cell['href'])?$cell['href']:'#';
                $cellContent = "<a onclick='".(isset($cell['onclick'])?$cell['onclick']:'')."' class='action btn ".(isset($cell['cls'])?$cell['cls']:'')."' href='#' lnk='$href'>{$cellValue}</a>";
                break;
			case "prp":
				$href = isset($cell['href'])?$cell['href']:'#';
				$aClass = isset($cell['elementClass'])? $cell['elementClass']: '';
				$cellContent = "<a href='$href' rel='prp'><i class='$aClass'></i>{$cellValue}</a>";
				break;
			case "btn":
				$href = isset($cell['href'])?$cell['href']:'#';
				$aClass = isset($cell['elementClass'])? $cell['elementClass']: '';
				$cellContent = "<a href='#' class='btn btn-danger btn-block' lnk='{$cell['href']}'><i class='$aClass'></i>{$cellValue}</a>";
				break;
			default:
				$cellContent = $cellValue;
				break;
		}
		$cellHtml .= $cellContent;
		$cellHtml .= "</td>";
		return $cellHtml;
	}

//	private function addPaginator($header = false) {
//		$pagingHtml = "<div class='paginator'>";
//		$paging = $this->getPaging();
//		$currentPage = $paging[0];
//		$numPerPage = $paging[1];
//		if($header) {
//			// get the header buttons
//		}
//
//		$startNumber = ($currentPage - 1) * $numPerPage + 1;
//		$endNumber  = $this->getRowInPage() + $startNumber - 1;
//
//		$rowCount = $this->getRowCount();
//		$lastPage = ceil($rowCount/$numPerPage);
//
//		$pagingHtml .= "<span><i>". $rowCount ." bản ghi / $lastPage trang</i></span>";
//		if($currentPage > 1 && $lastPage > 1) {
//			$pagingHtml .= "<a href='?page=1' title='Trang đầu'><span class='fPage icon-fast-backward'><i></i></span></a>";
//		}
//		if($startNumber > 1) {
//			$previousPage = $currentPage - 1;
//			$pagingHtml .= "<a href='?page=$previousPage' title='Trang trước'><span class='prPage icon-backward'><i></i></span></a>";
//		}
//
//		$pagingHtml .= "<span class='curPage btn-danger btn btn-small'>"."<i class=''><strong>$currentPage</strong></i></span>";
//
//		if($endNumber < $rowCount) {
//			$nextPage = $currentPage + 1;
//			$pagingHtml .= "<a href='?page=$nextPage' title='Trang sau'><span class='nxtPage icon-forward'><i></i></span></a>";
//		}
//		if($numPerPage  < $rowCount && $currentPage < $lastPage) {
//			$pagingHtml .= "<a href='?page=$lastPage' title='Trang cuối'><span class='lastPage  icon-fast-forward'><i></i></span></a>";
//		}
//
//		$pagingHtml .= "</div>";
//		return $pagingHtml;
//	}
    private function addPaginator($header = false) {
        $pagingHtml = "<div class='dataTables_paginate paging_bootstrap pagination'><ul>";
        $paging = $this->getPaging();
        $currentPage = $paging[0];
        $numPerPage = $paging[1];
        if($header) {
            // get the header buttons
        }

        $startNumber = ($currentPage - 1) * $numPerPage + 1;
        $endNumber  = $this->getRowInPage() + $startNumber - 1;

        $rowCount = $this->getRowCount();
        $lastPage = ceil($rowCount/$numPerPage);

        if($startNumber > 1) {
            $previousPage = $currentPage - 1;
            $pagingHtml .= "<li><a href='?page=$previousPage' title='Trang trước'>← <span>Prev</span></a></li>";
        }
        else{
            $pagingHtml .= "<li class='prev disabled'><a>← <span>Prev</span></a></li>";
        }

        $pagingHtml .= "<li><a>"."$currentPage</a></li>";

        if($endNumber < $rowCount) {
            $nextPage = $currentPage + 1;
            $pagingHtml .= "<li><a href='?page=$nextPage' title='Trang sau'><span>Next</span> → </a></li>";
        }
        else{
            $pagingHtml .= "<li class='next disabled'><a title='Trang sau'><span>Next</span> → </a></li>";
        }

        $pagingHtml .= "</ul></div>";
        return $pagingHtml;
    }
}