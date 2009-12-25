<?php
//die ( 'Cron job finished in ' . get_friendly_time_elapsed ( now () - $this->start ) );

class WS_Cron {

	public $limit = 1000;

	public function __construct () {
		$this->o = &get_instance ();
	}

	function start ()
	{
		if ( ! $this->__start () ) {
			$this->__finish ();
			return TRUE;
		}
		return FALSE;
	}

	private function __start ()
        {
        	if ( ! SAFE_MODE ) {
			@ini_set ( "max_execution_time", 0 );
                        @ini_set ( 'memory_limit', '240M' );
		}

		$this->o->load->model ( 'mwallpaper' );
		$this->o->load->model ( 'msearch_queries' );

		$query = $this->o->db->query
                (
                        'SELECT
                                w.ID,
                                w.hits,
                                w.downloads,
                                w.rating,
                                COUNT(distinct h.ip) AS wall_hits,
                                COUNT(distinct d.ip) AS wall_downloads,
                                SUM(v.vote_value)/COUNT(distinct v.visitor_ip) as wall_rating
                        FROM
                                ' . DBPREFIX . 'wallpapers w
                        LEFT JOIN
                                ' . DBPREFIX . 'hits h
                                ON
                                        (
                                               h.item_id = w.ID
                                        )
                        LEFT JOIN
                                ' . DBPREFIX . 'downloads d
                                ON
                                        (
                                               d.item_id = w.ID
                                        )
                        LEFT JOIN
                                ' . DBPREFIX . 'votes v
                                ON
                                        (
                                               v.item_id = w.ID
                                        )
                        GROUP BY
                                w.ID
                        HAVING
                        		wall_hits <> w.hits
                        	OR
                        		wall_downloads <> w.downloads
                        	OR
                        		wall_rating <> w.rating
                        LIMIT 0, ' . qstr ( $this->limit )
                );

                if ( ! $query->num_rows () ) {
                	return FALSE;
                }

		foreach ( $query->result () as $row )
		{
			$data = array
			(
					'hits'		=> $row->wall_hits,
					'downloads'	=> get_wallpaper_downloads ( $row->ID ),
					'rating'	=> round ( ( float ) $row->rating, 2 )
			);

			$this->o->db->where ( 'ID', $row->ID );
			$this->o->db->update ( DBPREFIX . 'wallpapers', escape_arr ( $data ) );
		}
		
		return TRUE;
        }

        private function __finish () {
        	include_once APPPATH . "config/tables.php";

		foreach ( $database_tables as $table ) {
			$this->o->db->query ( 'OPTIMIZE TABLE ' . DBPREFIX . $table );
		}

		$this->o->msearch_queries->perform_maintenance ();
		global_reset_categories ();
		update_colors ();
		update_tags ();
		
		return TRUE;
        }

}