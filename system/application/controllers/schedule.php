<?php

class Schedule extends Controller {

	public function __construct () {
		parent::Controller ();
		$this->load->model ( 'mschedule' );
		$this->load->model ( 'mwallpaper' );
	}

	public function index () {
		$schedules = $this->mschedule->get_schedules ();
		$added = 0;
		$schedules_count = 0;

		if ( $schedules != FALSE ) {
			foreach ( $schedules as $schedule ) {
				$schedules_count++;
				if ( $schedule->last_run + ( 3600 * $schedule->interval ) < now () ) {
					$schedule_wallpapers = $this->mschedule->get_scheduled_wallpapers ( $schedule->id, $schedule->amount );
					if ( $schedule_wallpapers != FALSE ) {
						$this->mschedule->update_last_run ( $schedule->id );
						foreach ( $schedule_wallpapers as $schedule_wallpaper ) {
							$added++;
							$this->mwallpaper->activate ( $schedule_wallpaper->wallpaper_id );
							$this->mschedule->delete_scheduled_wallpaper ( $schedule_wallpaper->wallpaper_id );
						}
					}
				}

				if ( $this->mschedule->schedule_finished ( $schedule->id ) ) {
					$this->mschedule->delete_schedule ( $schedule->id );
				}
			}
		}

		die ( "Added $added wallpapers in $schedules_count scheduled programs" );
	}

}