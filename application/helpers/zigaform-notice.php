<?php

if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}

class Zigaform_b_notice {

	private $tables = array();
	private $suffix = 'd-M-Y_H-i-s';

	/**
	 * Constructor
	 *
	 * @mvc Controller
	 */
	public function __construct() {
	 
	}


	/**
	 * Adding admin notice
	 */
	public function notice_rated() {

			$data              = get_option( 'zigaform_f_notice_1', array() );
			$data['time']      = time();
			$data['dismissed'] = true;
			$data['rated']     = true;

			update_option( 'zigaform_f_notice_1', $data );
			die();
	}


	/**
	 * Adding admin notice
	 */
	public function notice_add() {
		return;

	}


	/**
	 * Dismiss notice
	 */
	public function notice_dismiss() {

			$data              = get_option( 'zigaform_f_notice_1', array() );
			$data['time']      = time();
			$data['dismissed'] = true;
			$data['rated']     = false;

			update_option( 'zigaform_f_notice_1', $data );
			die();
	}


	/**
	 * When user is on zigaform admin page, display footer text that asks them to rate us.
	 */
	public function notice_footer( $text ) {
		return '';
	}

}

new Zigaform_b_notice();
