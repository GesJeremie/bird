<?php
/**
 * Bird - a simple PHP routing system
 *
 * @author      Jeremie Ges <bonjour@gesjeremie.fr>
 * @copyright   2012 Jeremie Ges
 * @version     1.0
 * @example 	/
 *
 * DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 *
 * Everyone is permitted to copy and distribute verbatim or modified
 * copies of this license document, and changing it is allowed as long
 * as the name is changed.
 *
 * DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 * TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
 *
 * 0. You just DO WHAT THE FUCK YOU WANT TO.
 *
 */

class Bird {

	/**
	* @var mixed The global pattern request (Ex. view/zombie/:id)
	*/
	private $_request = FALSE;

	/**
	* @var array Contain all params of your request (Ex. array('id' => 5))
	*/
	private $_request_params = array();

	/**
	* @var string The request method used (Ex. POST/GET)
	*/
	private $_request_method = FALSE;

	/**
	* @var string Base Regex rule used to match segment (Ex. :id BECOME [a-zA-Z0-9_-]*)
	*/
	private $_base_rule = '[a-zA-Z0-9_-]*';

	/**
	* @var string When callback executed, become TRUE, helpful for is_404()
	*/
	private $_found_pattern = FALSE;

	/**
	 * Constructor
	 *
	 */
	public function __construct() {

		// Bird system avoid $_GET system 
		// So we must guess $_GET params to recreate them 
		$created = $this->_create_get_params();

		// Check if exists request
		if (isset($_SERVER['REDIRECT_QUERY_STRING'])) {

			// Replace the GET var of .htaccess used for "redirect"
			$request = str_replace('request=', '', $_SERVER['REDIRECT_QUERY_STRING']);

			$request = trim($request, '/');

			// Inject into class var
			$this->_request = $request;
		} 

		// We don't want this $_GET
		unset($_GET['request']);

		// The request method used
		$this->_request_method = $_SERVER['REQUEST_METHOD'];



	}

	/**
     * Get value of segment position in the request
     * 
     * @access public
     * @param int The position
     * @return mixed (string/value)
     */
	public function segment($pos) {

		$segments = explode('/', $this->_request);

		if (isset($segments[$pos])) {

			return $segments[$pos];

		} else {

			return FALSE;

		}
	}

	/**
     * Get method
     * 
     * @access public
     * @param string The pattern to test
     * @param mixed function/string The callback to execute
     * @return void
     */
	public function get($pattern, $callback) {

		if ($this->_request_method == 'GET') {

			$this->run($pattern, $callback);

		}

	}

	/**
     * Post method
     * 
     * @access public
     * @param string The pattern to test
     * @param mixed function/string The callback to execute
     * @return void
     */
	public function post($pattern, $callback) {

		if ($this->_request_method == 'POST') {

			$this->run($pattern, $callback);

		}

	}

	/**
     * Put method
     * 
     * @access public
     * @param string The pattern to test
     * @param mixed function/string The callback to execute
     * @return void
     */
	public function put($pattern, $callback) {

		if ($this->_request_method == 'PUT') {

			$this->run($pattern, $callback);

		}

	}

	/**
     * Delete method
     * 
     * @access public
     * @param string The pattern to test
     * @param mixed function/string The callback to execute
     * @return void
     */
	public function delete($pattern, $callback) {

		if ($this->_request_method == 'DELETE') {

			$this->run($pattern, $callback);

		}

	}

	/**
     * Method for POST/GET/DELETE/PUT 
     * 
     * @access public
     * @param string The pattern to test
     * @param mixed function/string The callback to execute
     * @return void
     */
	public function any($pattern, $callback) {

		$this->run($pattern, $callback);

	}



	/**
     * Check if no route found
     * 
     * @access public
     * @return bool
     */
	public function is_404() {

		if ($this->_found_pattern === TRUE) {

			return FALSE;

		} else {

			return TRUE;

		}

	}

	/* ----------------------------------------------------------------------- */

    /**
     * Re-create all $_GET params
     * @return mixed (string/false)
     */
	private function _create_get_params() {

		// Method called by the construct

		// The request URI
		$request_uri = $_SERVER['REQUEST_URI'];

		// Find where start $_GET params (just after "?" char)
		$pos_ask = strpos($request_uri, '?');

		// Have we got $_GET params ?
		if ($pos_ask !== FALSE) {

			// +1 -> eat the "?" char
			$params = substr($request_uri, $pos_ask+1);

			// Convert in array type
			$params = explode('&', $params);

			// Loop all params and add in $_GET
			foreach ($params as $param) {

				if (strpos($param, '=') !== FALSE) { 

					list($key, $value) = explode('=', $param);
					$_GET[$key] = $value;

				}

			}

			// Return the string params
			return substr($request_uri, $pos_ask);


		} else {

			return FALSE;

		}

	}

	/**
     * Generic method to check route
     * 
     * @access private
     * @param string The pattern to test
     * @param mixed function/string The callback to execute
     * @return bool
     */
	private function run($pattern, $callback) {

		$pattern = $this->_parse($pattern);

		preg_match_all('#^' . $pattern . '$#', $this->_request, $matches);

		if (! empty($matches[0])) {

			// Execute callback
			call_user_func_array($callback, $this->_request_params);

			$this->_found_pattern = TRUE;
			return TRUE;

		} else {

			return FALSE;

		}

	}

	
	/**
     * Parse pattern to find all "vars"
     * 
     * @access public
     * @param string The pattern to parse
     * @return string
     */
	private function _parse($pattern) {

		// Remove "/" char at the start and at the end of string
		$pattern = trim($pattern, '/');

		// Create array
		$segments = explode('/', $pattern);

		// Loop all segments
		foreach ($segments as $key => $segment) {

			if (! empty($segment)) {

				// It's a var !
				if ($segment[0] == ':') {

					// Add new params
					$this->_request_params[] = $this->segment($key);

					// Replace var by base REGEX pattern
					$segments[$key] = str_replace($segment, $this->_base_rule, $segment);

				}

			}

		}

		// Re concat segment
		$segments = implode('/', $segments);

		// Return segments
		return $segments;
	}


}

?>