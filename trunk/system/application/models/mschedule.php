<?php

class Mschedule extends Model {

	function add_schedule ( $amount, $interval ) {
		$data [ 'amount' ] = $amount;
		$data [ 'interval' ] = $interval;
		$data [ 'last_run' ] = now ();
		$this->db->insert ( DBPREFIX . 'schedule', escape_arr ( $data ) );
		return $this->db->call_function ( 'insert_id' );
	}

	function add_scheduled_wallpaper ( $schedule_id, $wallpaper_id ) {
		$data [ 'schedule_id' ] = $schedule_id;
		$data [ 'wallpaper_id' ] = $wallpaper_id;
		$this->db->insert ( DBPREFIX . 'scheduled_wallpapers', escape_arr ( $data ) );
	}

	function get_schedules () {
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'schedule' );
		return ( $query->num_rows () ) ? $query->result () : FALSE;
	}

	function get_scheduled_wallpapers ( $schedule_id, $limit ) {
		$query = $this->db->get_where ( DBPREFIX . 'scheduled_wallpapers', array ( 'schedule_id' => $schedule_id ), $limit );
		return ( $query->num_rows () ) ? $query->result () : FALSE;
	}
	
	function schedule_finished ( $schedule_id ) {
		$query = $this->db->query ( 'SELECT s.id FROM ' . DBPREFIX . 'schedule s INNER JOIN ' . DBPREFIX . 'scheduled_wallpapers w ON(w.schedule_id=s.id) GROUP BY s.id LIMIT 0, 1' );
		return ( $query->num_rows () ) ? FALSE : TRUE;
	}
	
	function update_last_run ( $schedule_id ) {
		$data [ 'last_run' ] = now ();
		$this->db->where ( 'id', $schedule_id );
		$this->db->update ( DBPREFIX . 'schedule', $data ); 
	}
	
	function delete_schedule ( $schedule_id ) {
		$this->db->where ( 'id', $schedule_id );
		$this->db->delete ( DBPREFIX . 'schedule' ); 
	}
	
	function delete_scheduled_wallpaper ( $wallpaper_id ) {
		$this->db->where ( 'wallpaper_id', $wallpaper_id );
		$this->db->delete ( DBPREFIX . 'scheduled_wallpapers' ); 
	}

}