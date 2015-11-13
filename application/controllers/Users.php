<?PHP
/**
 *	Users
 *
 *	@package     Package Name
 *	@subpackage  Subpackage
 *	@category    Controller
 *	@author      Adrián Gonzalo
 *	@link        http://www.hardcod.es
 */
Class Users extends CI_Controller 
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
		$this->_check_login();
		$data['admin'] = $this->session->admin;
		if($this->session->admin != 1)
		{
			redirect('/');
		}
		$data['title'] = APP_TITLE;				
		$data['users'] = $this->users_model->get_users();
		$this->load->view('templates/header', $data);
		$this->load->view('users/index', $data);
		$this->load->view('templates/footer');
	}

	public function create()
	{		
		$this->_check_login();
		if($this->session->admin != 1)
		{
			redirect('/');
		}
		$data['title'] = APP_TITLE;
		$data['admin'] = $this->session->admin;		
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('txt_username','Username', 'required');
		$this->form_validation->set_rules('txt_password1', 'Password', 'required');
		$this->form_validation->set_rules('txt_password2', 'Password (repeat)', 'required');
		$this->form_validation->set_rules('txt_email1', 'Email', 'required');
		$this->form_validation->set_rules('txt_email2', 'Email (repeat)', 'required');

		if(	$this->form_validation->run() === FALSE OR
			$this->input->post('txt_password1') != $this->input->post('txt_password2') OR
			$this->input->post('txt_email1') != $this->input->post('txt_email2'))
		{			
			$this->load->view('templates/header', $data);			
			$this->load->view('users/create', $data);
			$this->load->view('templates/footer');
		}
		else
		{
			//Si se pasa la validación, creamos el usuario.
			$userData = array(	'username'	=>	$this->input->post('txt_username'),
								'email'		=>	$this->input->post('txt_email1'),
								'hash'		=>	password_hash($this->input->post('txt_password1'), PASSWORD_DEFAULT, array('cost'	=>	$this->_calculate_cost())));
			if($this->input->post('chk_activated') == 1)
			{
				$userData['activated'] = 1;
				$userData['activation'] = date('Y-m-d H:i:s');	
			}
			if($this->input->post('chk_admin') == 1)
			{
				$userData['admin'] = 1;
			}

			if($this->users_model->set_user($userData))
			{
				if($this->input->post('chk_email') === 1)
				{
					//Send email with activation code.
					//TODO
				}

				$this->load->view('templates/header', $data);			
				$this->load->view('users/create_success', $data);
				$this->load->view('templates/footer');	
			}					
			else
			{
				$this->load->view('templates/header', $data);			
				$this->load->view('users/create_error', $data);
				$this->load->view('templates/footer');	
			}			
		}
	}

	public function update()
	{

	}

	public function delete()
	{

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
		$user = $this->users_model->get_users($this->session->id);
		if(! isset($user['username']) || count($user['username']) == 0) 
		{			
			// Main page.
			redirect('/');
		}
		
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