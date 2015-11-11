<?PHP
/**
 *	Login
 *
 *	@package     Package Name
 *	@subpackage  Subpackage
 *	@category    Category
 *	@author      Adrián Gonzalo
 *	@link        http://www.hardcod.es
 */
Class Login extends CI_Controller 
{
	/**
	 *	Constructor
	 **/
	public function __construct(){

		parent::__construct();
		$this->load->model('users_model');
	}

	/**
	 *	Public Methods
	 **/
	public function index()
	{
		//LOGIN FORM Y PÁGINA PRINCIPAL

		//Check login - si logueado redirigir a success.

		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = APP_TITLE;

		$this->form_validation->set_rules('txt_username', 'Username', 'required');
		$this->form_validation->set_rules('txt_password', 'Password', 'required');

		if($this->form_validation->run() === FALSE)
		{			
			// Login form.
			$this->load->view('templates/header', $data);
			$this->load->view('login/index');
			$this->load->view('templates/footer');
		}
		else
		{
			// Check credentials.
			$user = $this->users_model->get_users($this->input->post('txt_username'));
			if(count($user['username']) == 0)
			{
				$user = $this->users_model->get_users(NULL, $this->input->post('txt_username'));								
				if(count($user['username']) == 0)
				{
					header('Location: '. base_url());
				}				
			}
			if(	($user['username'] == $this->input->post('txt_username') OR
				$user['email'] == $this->input->post('txt_username')) &&
				password_verify($this->input->post('txt_password'), $user['hash']) )
			{
				$this->session->id = $user['username'];
				// Loged user page.
				$this->load->view('templates/header', $data);
				$this->load->view('login/success');
				$this->load->view('templates/footer');
			}
			else
			{
				// Login form.
				header('Location: '. base_url());
			}
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('admin');
		$this->session->sess_destroy();
		header('Location: '. base_url());
	}

	/**
	 *	Pivated Methods
	 */

	/**
	 *	Checks if there is a valid session and redirects the user
	 *	where he belongs.
	 */
	private function _check_login()
	{
		
	}

	/**
	 *	Eval the server to calculate the max cost available to the
	 *	max delay target. 8-10 is a good reference for cost.
	 *
	 *	@return      int 	$cost 		Output int
	 */	
	private function _calculate_cost()
	{		
		$timeTarget = 0.05; // 50 milisegundos
		$cost = 8;
		do
		{
		    $cost++;
		    $start = microtime(true);
		    password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
		    $end = microtime(true);
		}
		while(($end - $start) < $timeTarget);
		return $cost;
	}

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
}
?>