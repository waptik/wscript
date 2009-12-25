<?php

class Pagination {

	public $limit = 12; // how many per page

	public $select_what = '*'; // what to select

	public $add_query = '';

	public $otherParams = '';

	/* customize links */
	public $next_r = '&#9658;';

	public $previous_r = '&#9668;';

	public $thequery = FALSE;

	public $is_ajax = FALSE;

	public $link_id = '';

	function __construct () {
		$this->first_r = strtoupper ( Lang ( 'first' ) );
		$this->last_r = strtoupper ( Lang ( 'last' ) );
	}

	function getQuery ( $return_q = FALSE ) {
		$CI = & get_instance ();
		
		if ( $this->thequery ) {
			$query = $CI->db->query ( $this->thequery );
		}
		else {
			$query = $CI->db->query ( 'SELECT
                                        SQL_CALC_FOUND_ROWS
                                        ' . $this->select_what . '
                                FROM
                                        ' . $this->the_table . '
                                ' . $this->add_query . '
                                LIMIT ' . $this->start . ', ' . $this->limit );
		}
		
		$this->nbItems = $CI->db->call_function ( 'result', $CI->db->call_function ( 'query', 'SELECT FOUND_ROWS() AS nbr' ), 0, 'nbr' );
		
		if ( $return_q == FALSE ) {
			return $this->nbItems;
		}
		else {
			return $query;
		}
	}

	function remove_double_slashes ( $string ) {
		return preg_replace ( "/([^:])\/\/+/", "\\1/", $string );
	}

	function paginate () {
		$nbItems = $this->nbItems;
		
		if ( $nbItems <= $this->limit ) {
			return;
		}
		else {
			$allPages = ceil ( $nbItems / $this->limit );
			
			$currentPage = floor ( $this->start / $this->limit ) + 1;
			
			$pagination = "";
			if ( $allPages > 9 ) {
				$maxPages = ( $allPages > 7 ) ? 7 : $allPages;
				
				if ( $allPages > 7 ) {
					if ( $currentPage >= 1 && $currentPage <= $allPages ) {
						$pagination .= ( $currentPage > 4 ) ? "...&nbsp;" : " ";
						
						$minPages = ( $currentPage > 4 ) ? $currentPage : 5;
						$maxPages = ( $currentPage < $allPages - 4 ) ? $currentPage : $allPages - 4;
						
						for ( $i = $minPages - 4; $i < $maxPages + 4; $i ++ ) {
							if ( $this->is_ajax ) {
								if ( $i == $currentPage ) {
									$pagination .= "<a href=\"javascript:void(0);\" class=\"current\">" . $i . "</a>";
								}
								else {
									$pagination .= "<a href=\"javascript:void(0)\" onclick=\"ajax_paginate('" . $this->filePath . "/" . ( ( $i - 1 ) * $this->limit ) . $this->otherParams . "/" . print_unique_id () . "','" . $this->link_id . "')\">" . $i . "</a>";
								}
							}
							else {
								if ( $i == $currentPage ) {
									$pagination .= "<a href=\"javascript:void(0);\" class=\"current\">" . $i . "</a>";
								}
								else {
									$pagination .= "<a href=\"" . $this->remove_double_slashes ( $this->filePath . "/" . ( ( $i - 1 ) * $this->limit ) . $this->otherParams ) . "\">" . $i . "</a>";
								}
							}
						}
						$pagination .= ( $currentPage < $allPages - 4 ) ? "...&nbsp;" : "";
					}
					else {
						$pagination .= "...&nbsp;";
					}
				}
			}
			else {
				for ( $i = 1; $i < $allPages + 1; $i ++ ) {
					if ( $this->is_ajax ) {
						if ( $i == $currentPage ) {
							$pagination .= "<a href=\"javascript:void(0);\" class=\"current\">" . $i . "</a>";
						}
						else {
							$pagination .= "<a href=\"javascript:void(0)\" onclick=\"ajax_paginate('" . $this->filePath . "/" . ( ( $i - 1 ) * $this->limit ) . $this->otherParams . "/" . print_unique_id () . "','" . $this->link_id . "')\">" . $i . "</a>";
						}
					}
					else {
						if ( $i == $currentPage ) {
							$pagination .= "<a href=\"javascript:void(0);\" class=\"current\">" . $i . "</a>";
						}
						else {
							$pagination .= "<a href=\"" . $this->remove_double_slashes ( $this->filePath . "/" . ( ( $i - 1 ) * $this->limit ) . $this->otherParams ) . "\">" . $i . "</a>";
						}
					}
				}
			}
			
			if ( $currentPage > 1 ) {
				if ( $this->is_ajax ) {
					$pagination = "<a href=\"javascript:void(0)\" onclick=\"ajax_paginate('" . $this->filePath . "/0" . $this->otherParams . "/" . print_unique_id () . "','" . $this->link_id . "')\">" . $this->first_r . "</a><a href=\"javascript:void(0)\" onclick=\"ajax_paginate('" . $this->filePath . "/" . ( ( $currentPage - 2 ) * $this->limit ) . $this->otherParams . "/" . print_unique_id () . "','" . $this->link_id . "')\">" . $this->previous_r . "</a> " . $pagination;
				}
				else {
					$pagination = "<a href=\"" . $this->remove_double_slashes ( $this->filePath . "/0" . $this->otherParams ) . "\">" . $this->first_r . "</a><a href=\"" . $this->remove_double_slashes ( $this->filePath . "/" . ( ( $currentPage - 2 ) * $this->limit ) . $this->otherParams ) . "\">" . $this->previous_r . "</a> " . $pagination;
				}
			}
			if ( $currentPage < $allPages ) {
				if ( $this->is_ajax ) {
					$pagination .= "<a href=\"javascript:void(0)\" onclick=\"ajax_paginate('" . $this->filePath . "/" . ( $currentPage * $this->limit ) . $this->otherParams . "/" . print_unique_id () . "','" . $this->link_id . "')\">" . $this->next_r . "</a><a href=\"javascript:void(0)\" onclick=\"ajax_paginate('" . $this->filePath . "/" . ( ( $allPages - 1 ) * $this->limit ) . $this->otherParams . "/" . print_unique_id () . "','" . $this->link_id . "')\">" . $this->last_r . "</a>";
				}
				else {
					$pagination .= "<a href=\"" . $this->remove_double_slashes ( $this->filePath . "/" . ( $currentPage * $this->limit ) . $this->otherParams ) . "\">" . $this->next_r . "</a><a href=\"" . $this->remove_double_slashes ( $this->filePath . "/" . ( ( $allPages - 1 ) * $this->limit ) . $this->otherParams ) . "\">" . $this->last_r . "</a>";
				}
			}
			
			return "\n\t\t<div class=\"clear\"></div>\n\t\t<div class=\"pagination\">" . $this->remove_double_slashes ( $pagination ) . "\n\t\t</div>";
		}
	}

	function __destruct () {
		unset ( $this );
	}
}
//*END of pagination class