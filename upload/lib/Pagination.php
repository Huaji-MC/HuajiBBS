<?php
// +----------------------------------------------------------------------
// | HuajiBBS
// +----------------------------------------------------------------------
// | Copyright © 2022 HuajiMC. All rights reserved.
// +----------------------------------------------------------------------
// | License: GNU General Public License 3.0
// +----------------------------------------------------------------------
// | Author: 滑稽mc（HuajiMC） <HuajiMC@126.com>
// +----------------------------------------------------------------------

namespace lib;

class Pagination {
	private $totalPages; //总页数
	private $currentPage; //当前页码
	private $url; //点击页码跳转url（变量 {{page}} 自动替换为页面）
	
	public function create($totalRows,$pageRows,$currentPage,$url) {
		$this->totalPage = ceil($totalRows / $pageRows) ?: 1; //没有记录时默认 1 页
		$this->currentPage = $currentPage <= 0 ? 1 : $currentPage; //当前页码必须为正整数
		$this->url = $url;
		
		$html = '';
		$html .= '<ul class="pagination justify-content-center">';
		$html .= $this->previous();
		if($currentPage > 7) {
			$html .= $this->page(1);
			$html .= $this->ellipsis();
			for($i = $this->currentPage - 5; $i <= $this->currentPage - 1; $i ++) {
				$html .= $this->page($i);
			}
		} else {
			for($i = 1; $i < $this->currentPage; $i ++) {
				$html .= $this->page($i);
			}
		}
		$html .= $this->page($this->currentPage);
		if($currentPage + 6 < $this->totalPage) {
			for($i = $this->currentPage + 1; $i <= $this->currentPage + 5; $i ++) {
				$html .= $this->page($i);
			}
			$html .= $this->ellipsis();
			$html .= $this->page($this->totalPage);
		} else {
			for($i = $this->currentPage + 1; $i <= $this->totalPage; $i ++) {
				$html .= $this->page($i);
			}
		}
		$html .= $this->next();
		$html .= '</ul>';
		
		return $html;
	}
	
	private function previous() {
		$page = $this->currentPage - 1;
		return '<li class="page-item ' . ($this->currentPage <= 1 ? 'disabled' : '') . '"><a class="page-link" href="' . $this->url($page) . '">上一页</a></li>';
	}
	
	private function next() {
		$page = $this->currentPage + 1;
		return '<li class="page-item ' . ($this->currentPage >= $this->totalPage ? 'disabled' : '') . '"><a class="page-link" href="' . $this->url($page) . '">下一页</a></li>';
	}
	
	private function page($page) {
		return '<li class="page-item ' . ($page == $this->currentPage ? 'active' : '') . '"><a class="page-link" href="' . $this->url($page) . '">' . $page . '</a></li>';
	}
	
	private function ellipsis() {
		return '<li class="page-item disabled"><a class="page-link" href="#">…</a></li>';
	}
	
	private function url($page) {
		return str_replace('{{page}}',$page,$this->url);
	}
}