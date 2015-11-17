<?PHP
/**
 *	Super Class
 *
 *	@package     Package Name
 *	@subpackage  Subpackage
 *	@category    Category
 *	@author      Adrián Gonzalo
 *	@link        http://www.hardcod.es
 */
Class Users_model extends CI_Model 
{	
	/**
	 *	Constructor: loads database.
	 */
	public function __construct()
	{
		$this->load->database();
	}

	/**
	 *	Pivated Methods
	 */

	/**
	 *	Generates a random string (50 length by default).
	 *
	 *	@param       int  		$length    	Input int
	 *	@return      string 	$code 		Output string
	 */
	private function _generate_code($length = 50)
	{
		$values = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','W','X','Y','Z');
		$code = '';
		for($i = 0; $i < $length; $i++)
		{
			shuffle($values);
			$code .= $values[0];
		}
		return $code;
	}

	/**
	 *	Public Methods
	 **/

	/**
	 * 	Gets users from table users by default. Accepts username or email to return
	 * 	a specific user if exists.	
	 *
	 * @param       string  $username  Input string
	 * @param       string  $email 	   Input string
	 * @return      array
	 */
	public function get_users($username = FALSE, $email = FALSE)
	{
		if($username === FALSE OR $username === NULL)
		{
			if($email === FALSE)
			{
				$query = $this->db->get('users');
				return $query->result_array();	
			}
			$query = $this->db->get_where('users', array('email'	=>	$email));
			return $query->row_array();	
		}
		else
		{
			$query = $this->db->get_where('users', array('username'	=>	$username));
			return $query->row_array();
		}
	}

	/**
	 * 	Gets user who matches the activation code.	
	 *
	 * @param       string  $code    Input string
	 * @return      array
	 */
	public function get_user_by_activation_code($code = FALSE)
	{
		if($code === FALSE)
		{
			return FALSE;
		}
		$query = $this->db->get_where('users', array('activation_code'	=>	$code));
		return $query->row_array();
	}

	/**
	 * 	Gets user who matches the recovery code.
	 *
	 * @param       string  $code    Input string
	 * @return      array
	 */
	public function get_user_by_recovery($code = FALSE)
	{
		if($code === FALSE)
		{
			return FALSE;
		}
		$query = $this->db->get_where('users', array('recovery'	=>	$code));
		return $query->row_array();
	}

	/**
	 * 	Inserts a new user in table users if the username and email are not
	 *	already in use.
	 *
	 * @param       array  	$userData    Input array
	 * @return      boolean	
	 */
	public function set_user($userData = FALSE)
	{
		if(	$userData === FALSE OR 
			! is_array($userData) OR
			! isset($userData['username']) OR
			! isset($userData['email']) OR
			! isset($userData['hash']))
		{
			return FALSE;
		}
		$user = $this->get_users($userData['username']);
		if(count($user['username']) > 0)
		{
			return FALSE;
		}
		$user = $this->get_users(NULL, $userData['email']);
		if(count($user['username']) >0)
		{
			return FALSE;
		}
		$userData['activation_code'] = $this->_generate_code();
		$userData['register'] = date('Y-m-d H:i:s');
		return $this->db->insert('users', $userData);		
	}

	/**
	 * 	Updates an user in table users if exists.
	 *
	 * @param       array  	$userData    Input array
	 * @return      boolean	
	 */
	public function update_user($userData = FALSE)
	{
		if(	$userData === FALSE OR 
			! is_array($userData) OR
			! isset($userData['username']) OR
			! isset($userData['email']))
		{
			return FALSE;
		}
		$user = $this->get_users($userData['username']);
		//	If user doesn't exist, we can't update it. So username cannot be modified.
		if(count($user['username']) === 0)
		{
			return FALSE;
		}
		//	If that email is in use, we can't set it for the user.
		$user = $this->get_users(NULL, $userData['email']);
		if(count($user['username']) >0 && $user['username'] != $userData['username'])
		{
			return FALSE;
		}
		$this->db->where('username', $userData['username']);
		return $this->db->update('users', $userData);		
	}

	/**
	 * 	Deletes an user if exists or deletes all users
	 *	if calls the function withour parameters.
	 *
	 * @param       string  	$username    Input string
	 * @return      boolean	
	 */
	public function delete_users($username = FALSE)
	{
		if($username === FALSE)
		{
			return $this->db->empty_table('users');
		}
		else
		{
			return $this->db->delete('users', array('username'	=>	$username));
		}
	}
}
?>