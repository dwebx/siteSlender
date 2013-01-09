<?php
/**
 * includes/parity.php
 * 
 * class file for parity
 * 
 * @package   siteSlender
 * @subpackage Core
 * @author    Torsten Albrecht <t.albrecht@dwebx.de>
 * @version   1.0.0
 * @access    public
 * @require   siteSlender v1.0.2
 * @copyright 2011
 */

class Parity {

	/**
	 * parity flag for (list) entries to easier set CSS classes for styling
	 * 
	 * @var string 
	 * @access public
	 */
	public $parity;
	
	/**
	 * list for possible states of parity flag
	 * 
	 * These states will be directly used to write the CSS classes into HTML elements
	 * 
	 * This array can be overwritten via config.php to customize framework vars
	 * 
	 * @var array 
	 * @access private
	 */
	private $arrParity = array ( "even", "odd" );


	/**
	 * function __construct
	 * 
	 * creates an instance of Parity and set current parity flag to 'odd'
	 * 
	 * @access public
	 */
	public function __construct() {
		
		if ( $GLOBALS['config']['css_parity_list'] && is_array($GLOBALS['config']['css_parity_list']) )
		{
			$this->arrParity = $GLOBALS['config']['css_parity_list'];
		}

		$this->parity = $this->arrParity[1];
	}


	/**
	 * function get
	 * 
	 * switch and return the current state of the parity flag.
	 * 
	 * During creation an instance of Parity, the parity flag was set to "odd"
	 * so the first call of get() will return the value "even"
	 * 
	 * @access public
	 * @return string 
	 */
	public function get() {
		
		if ( $this->parity === $this->arrParity[0] )
		{
			$this->parity = $this->arrParity[0];
		}
		else
		{
			$this->parity = $this->arrParity[1];
		}

		return $this->parity;
	}


	/**
	 * function set
	 * 
	 * manually set the parity flag
	 * 
	 * The function negates the assigned value because get() switches 
	 * against the previous state of the flag. 
	 * This is done for a user-friendly usage of set() 
	 *   -> set(0) initial state to get "even"
	 *   -> set(1) the other state to get "odd"
	 * 
	 * @access public
	 * @param type $bolParity 
	 */
	public function set( $bolParity = 0 ) {

		$this->parity = $this->arrParity[ !$bolParity ]; // negate
	}


	/**
	 * function reset
	 * 
	 * reset the current state of the parity flag (default "even")
	 * 
	 * @access public
	 */
	public function reset() {

		$this->set();
	}


	/**
	 * function __destruct
	 * 
	 * @access public
	 */
	public function __destruct() {

		$this->parity = "";
	}
}

?>
