<?php

class WB_meminfo {

        private $info = array ();

        function __construct ()
        {
                $this->obj = &get_instance ();
                $this->info = $this->build_query ();
        }

        function build_query ()
        {
        	if ( ! get_my_session_id () ) return FALSE;
        	$meminfo_loaded = $this->obj->session->userdata ( 'meminfo_loaded' );

        	if ( ! $meminfo_loaded ) {
        		$query = $this->obj->db->query 
			(
				'SELECT 
					* 
				FROM 
					' . DBPREFIX . 'users 
				WHERE 
					ID = ' . qstr ( get_my_session_id () ) 
			);

	                $this->obj->session->set_userdata ( array ( 'meminfo_loaded' => serialize ( $query->result_array () ) ) );
	                return $query->result_array ();
        	}
        	
        	return unserialize ( $meminfo_loaded );
        }

        function get ( $key )
        {
                if ( is_array ( $this->info [ 0 ] ) )
                {
                        if ( isset ( $this->info [ 0 ] [ $key ] ) )
                        {
                                return $this->info [ 0 ] [ $key ];
                        }
                }

                return FALSE;
        }
}
//	END