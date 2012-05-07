<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Zenbu Solspace Tag formatting extension
 * =========================================
 * Formats Solspace's Tag Fieldtypes in Zenbu
 * @version 1.0.0 
 * @author 	Nicolas Bottari
 * ------------------------------ 
 * 
 * *** IMPORTANT NOTES ***
 * I (Nicolas Bottari) am not responsible for any
 * damage, data loss, etc caused directly or indirectly by the use of this extension. 
 *
 * REQUIRES Zenbu module:
 * @link	http://nicolasbottari.com/eecms/zenbu/
 * @see		http://nicolasbottari.com/eecms/docs/zenbu/
 *
 * Solspace Tag Module: 
 * @link 	http://www.solspace.com/software/detail/tag/
 * 
 */
class Zenbu_tag_formatting_ext {
	
	var $name				= 'Zenbu Solspace Tag formatting extension';
	var $addon_short_name 	= 'zenbu_tag_formatting';
	var $version 			= '1.0.0';
	var $description		= 'Formats Solspace\'s Tag Fieldtypes in Zenbu';
	var $settings_exist		= 'y';
	var $docs_url			= 'http://nicolasbottari.com/expressionengine_cms/docs/zenbu_solspace_tag_formatting';
	var $settings        	= array();

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	function Zenbu_tag_formatting_ext($settings='')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
		$this->EE->lang->loadfile('zenbu_tag_formatting');
	}
	
	/**
	*	=============================================
	*	Extension Hook zenbu_modify_field_cell_data
	*	=============================================
	*	Modify custom field cell data before output & display in Zenbu
	* 	@param string 	$field_data 	The current data to be displayed in the Zenbu column cell
	* 	@param array 	$info_data 	  	An array of the current entry_id, field_id, and an array
	* 	                       			of field information (ids, fieldtype, name...)
	* 	@return string 	$field_data 	The modified data to be displayed in the Zenbu column cell 
	*/
	function zenbu_modify_field_cell_data($field_data, $info_data)
	{

		// Check if field is a Solspace Tag field.
		// If not, just return the content as-is.
		if($info_data['fieldtype'][$info_data['current_field_id']] == 'tag')
		{
			$output = '';
			$field_data = explode("\n", $field_data);

			// We're storing data in a session variable to avoid
			// making this query multiple times for each Zenbu row
			if( ! isset($this->EE->session->cache['zenbu_tag_formatting']))
			{
				$query = $this->EE->db->query("SELECT settings FROM exp_extensions WHERE class = '".__CLASS__."'");
				
				if($query->num_rows() > 0)
				{
					foreach($query->result_array() as $result)
					{
						$this->EE->session->cache['zenbu_tag_formatting'] = unserialize($result['settings']);
					}
				}
			}
			
			// If we have settings, format the content based on the setting
			if(isset($this->EE->session->cache['zenbu_tag_formatting']['separator']))
			{
				
				switch($this->EE->session->cache['zenbu_tag_formatting']['separator'])
				{
					case 'br':
						foreach($field_data as $key => $val)
						{
							$output .= $val . BR;
						}
						return $output;
					break;
					case 'comma':
						foreach($field_data as $key => $val)
						{
							$output .= $val . ', ';
						}
						return substr($output, 0, -2);
					break;
				}

			} else {
				
				// If we have no settings, just return the data cell content
				return $field_data;

			}

		} else {

			return $field_data;

		}
	}

	
	/**
	 * Settings Form
	 *
	 * @param	Array	Settings
	 * @return 	void
	 */
	function settings_form()
	{	
		$this->EE->load->helper('form');
		
		// Retrieve extension settings from the database 
		$query = $this->EE->db->query("SELECT settings FROM exp_extensions WHERE class = '".__CLASS__."'");
		$vars = array();
		
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result)
			{
				$settings = unserialize($result['settings']);
			}
		}

		$separator = isset($settings['separator']) ? $settings['separator'] : '';
		
		$options = array(
				'comma' => $this->EE->lang->line('comma'),
				'br'	=> $this->EE->lang->line('linebreak'),
			);
				
		$vars['settings'] = array(
			'separator'	=> form_dropdown('separator', $options, $separator),
			);
		
		// Load settings into our own view
		return $this->EE->load->view('ext_settings', $vars, TRUE);			
	}
	
	
	/**
	* Save Settings
	*
	* This function provides a little extra processing and validation 
	* than the generic settings form.
	*
	* @return void
	*/
	function save_settings()
	{
		if (empty($_POST))
		{
			show_error($this->EE->lang->line('unauthorized_access'));
		}
		
		unset($_POST['submit']);
		
		$settings = $_POST;
		
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update('extensions', array('settings' => serialize($settings)));
		
		$this->EE->session->set_flashdata(
			'message_success',
		 	$this->EE->lang->line('preferences_updated')
		);
	}



	function activate_extension() {
	
			$data[] = array(
			    'class'      => __CLASS__,
			    'method'    => "zenbu_modify_field_cell_data",
			    'hook'      => "zenbu_modify_field_cell_data",
			    'settings'    => serialize(array()),
			    'priority'    => 10,
			    'version'    => $this->version,
			    'enabled'    => "y"
			  );
		 
		      	
	      // insert in database
	      foreach($data as $key => $data) {
	      $this->EE->db->insert('exp_extensions', $data);
	      }
	  }
	
	
	  function disable_extension() {
	
	      $this->EE->db->where('class', __CLASS__);
	      $this->EE->db->delete('exp_extensions');
	  } 
	  
	  /**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
		
		if ($current < $this->version)
		{
			// Update to version 1.0
		}
		
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update(
					'extensions', 
					array('version' => $this->version)
		);
	}

  
  

}
// END CLASS