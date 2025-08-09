<?php
namespace Jet_Tabs\Endpoints;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Endpoint_Base class
 */
abstract class Base {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	abstract function get_name();

	/**
	 * API callback
	 * @return void
	 */
	abstract function callback( $request );

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELTE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'GET';
	}

	/**
	 * Check user access to current end-popint
	 * 
	 * By default set to admin capabilities. 
	 * For any other - should be rewritten in the endpoint itself
	 *
	 * @return string|bool
	 */
	public function permission_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get query param. Regex with query parameters
	 *
	 * Example:
	 *
	 * (?P<id>[\d]+)/(?P<meta_key>[\w-]+)
	 *
	 * @return string
	 */
	public function get_query_params() {
		return '';
	}

	/**
	 * Returns arguments config
	 *
	 * Example:
	 *
	 * 	array(
	 * 		array(
	 * 			'type' => array(
	 * 			'default'  => '',
	 * 			'required' => false,
	 * 		),
	 * 	)
	 *
	 * @return array
	 */
	public function get_args() {
		return array();
	}

}
