<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form validation for UK Postcodes
 * 
 * Check that its a valid postcode
 * @author James Mills <james@koodoocreative.co.uk>
 * @version 1.0
 * @package FriendsSavingMoney
 */

class MY_Form_validation extends CI_Form_validation
{

    function __construct()
    {
        parent::__construct();  
        log_message('debug', '*** Hello from MY_Form_validation ***');
    }

    function valid_postcode($postcode)
    {

        /**
         *
         * UK Postcode validation expression from Wikipedia
         * http://en.wikipedia.org/wiki/Postcodes_in_the_United_Kingdom
         *
         * Note: Remember to strtoupper() your postcode before inserting into database!
         *
         */

        $pattern = "/^(GIR 0AA)|(((A[BL]|B[ABDHLNRSTX]?|C[ABFHMORTVW]|D[ADEGHLNTY]|E[HNX]?|F[KY]|G[LUY]?|H[ADGPRSUX]|I[GMPV]|JE|K[ATWY]|L[ADELNSU]?|M[EKL]?|N[EGNPRW]?|O[LX]|P[AEHLOR]|R[GHM]|S[AEGKLMNOPRSTY]?|T[ADFNQRSW]|UB|W[ADFNRSV]|YO|ZE)[1-9]?[0-9]|((E|N|NW|SE|SW|W)1|EC[1-4]|WC[12])[A-HJKMNPR-Y]|(SW|W)([2-9]|[1-9][0-9])|EC[1-9][0-9]) [0-9][ABD-HJLNP-UW-Z]{2})$/";


        if (preg_match($pattern, strtoupper($postcode)))
    {
            return TRUE;
        } 
        else
        {
            $this->set_message('valid_postcode', 'Please enter a valid postcode');
            return FALSE;
        }
    }
	function greater_than_or_equalto($field1,$field2){
		$CI =& get_instance();
		$CI->form_validation->set_message('greater_than_or_equalto', 'The %s should be greater than or equal to %s.');
		if($field1>=$field2){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function less_than_or_equalto($x,$y){
		$CI =& get_instance();
		$CI->form_validation->set_message('less_than_or_equalto', 'The %s should be less than or equal to %s.');
		return ($x < $y) ? TRUE : FALSE;
	}
	function unique($str, $field)
	{
		$CI =& get_instance();
		list($table, $column) = explode('.', $field, 2);

		$CI->form_validation->set_message('unique', 'The %s that you requested is unavailable.');

		$query = $CI->db->query("SELECT COUNT(*) AS dupe FROM $table WHERE $column = '$str'");
		$row = $query->row();
		return ($row->dupe > 0) ? FALSE : TRUE;
	}
	function is_valid_date($date) {
		$CI =& get_instance();
		list($day, $month, $year) = explode("/", $date);
		if (checkdate($month, $day, $year)) {
			return TRUE;
		} else {
			$CI -> form_validation -> set_message('is_valid_date', 'This is not a valid date');
			return FALSE;
		}
	}
	
	function is_exists($str, $field)
	{
		$CI =& get_instance();
		list($table, $column) = explode('.', $field, 2);

		$CI->form_validation->set_message('is_exists', 'The %s that you requested is unavailable.');

		$query = $CI->db->query("SELECT COUNT(*) AS dupe FROM $table WHERE $column = '$str'");
		$row = $query->row();
		return ($row->dupe > 0) ? TRUE : FALSE;
	}
	function is_greaterthan_zero($str){
		$CI =& get_instance();
		$CI->form_validation->set_message('is_greaterthan_zero', 'The %s is not greater than zero');
		
		return ($str > 0) ? TRUE : FALSE;
	}
	function is_updateable($str, $field){
		$CI =& get_instance();
		list($table, $column) = explode('.', $field, 2);

		$CI->form_validation->set_message('is_updateable', ' %s does not exists.');

		$query = $CI->db->query("SELECT COUNT(*) AS dupe FROM $table WHERE $column = '$str'");
		$row = $query->row();
		return ($row->dupe > 0) ? TRUE : FALSE;
	}
    function form_date($name = '', $selected = array(), $extra = '') {
        if ( ! is_array($selected))
        {
            $selected = array($selected);
        }

        // If no selected state was submitted we will attempt to set it automatically
        if (count($selected) === 0)
        {
            // If the form name appears in the $_POST array we have a winner!
            if (isset($_POST[$name]))
            {
                $selected = $_POST[$name];
            }
        }

        if ($extra != '') $extra = ' '.$extra;

        $form = '<select name="'.$name.'[year]"'.$extra.">\n";

        $max_year = date('Y');
        for($i = $max_year; $i >= 1900; $i--)
        {
            if(isset($selected['year']))
            {
                $value = $selected['year'];
            }
            else
            {
                $value = '';
            }
            $sel = $value == $i ? ' selected="selected"' : '';
            $form .= '<option value="'.$i.'"'.$sel.'>'.$i."</option>\n";
        }

        $form .= "</select>\n&nbsp;-&nbsp;";

        $form .= '<select name="'.$name.'[month]"'.$extra.">\n";

        for($i = 1; $i <= 12; $i++)
        {
            if(isset($selected['month']))
            {
                $value = $selected['month'];
            }
            else
            {
                $value = '';
            }
            $sel = $value == $i ? ' selected="selected"' : '';
            $form .= '<option value="'.$i.'"'.$sel.'>'.$i."</option>\n";
        }

        $form .= "</select>\n&nbsp;-&nbsp;";

        $form .= '<select name="'.$name.'[day]"'.$extra.">\n";

        for($i = 1; $i <= 31; $i++)
        {
            if(isset($selected['day']))
            {
                $value = $selected['day'];
            }
            else
            {
                $value = '';
            }
            $sel = $value == $i ? ' selected="selected"' : '';
            $form .= '<option value="'.$i.'"'.$sel.'>'.$i."</option>\n";
        }

        $form .= "</select>\n";

        return $form;
    }
	/* newly added */
	function error_array(){
	    if(count($this->_error_array)===0){
	        return FALSE;
	    }else{
	        return $this->_error_array;
	    }
	}

	function get_tae(){
	    return "TAE!";
	 }
}
?>