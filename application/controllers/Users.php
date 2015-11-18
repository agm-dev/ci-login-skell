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
	public function __construct()
	{
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
								'hash'		=>	password_hash($this->input->post('txt_password1'), PASSWORD_DEFAULT, array('cost'	=>	$this->_calculate_cost()))); //You can use constant HASH_COST if you have set it on constants.php file.
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
				if($this->input->post('chk_email') == 1)
				{					
					$userInfo = $this->users_model->get_users($userData['username']);
					$message = 	"<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>".
								"<h2>".APP_TITLE." - Account activation</h2>".
								"<p>Now you are registered in".APP_TITLE.". Visit the link below to activate your account.</p>".
								"<a target='_blank' href='".base_url()."activate/".$userInfo['activation_code']."'>Click to activate your account!</a>".
								"</body></html>";
					$this->_email($userInfo['email'], 'Account activation', $message);
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

	public function update($username = FALSE)
	{
		if($username === FALSE)
		{
			redirect('/users');
		}

		$this->_check_login();
		if($this->session->admin != 1)
		{
			redirect('/');
		}

		$data['title'] = APP_TITLE;
		$data['user'] = $this->users_model->get_users($username);
		$data['updated'] = 2;
		if(!isset($data['user']['username'])){
			redirect('/users');
		}

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('txt_username','Username', 'required');		
		$this->form_validation->set_rules('txt_email1', 'Email', 'required');
		$this->form_validation->set_rules('txt_email2', 'Email (repeat)', 'required');

		if(	$this->form_validation->run() === FALSE OR
			$this->input->post('txt_password1') != $this->input->post('txt_password2') OR
			$this->input->post('txt_email1') != $this->input->post('txt_email2'))		
		{				
			$this->load->view('templates/header', $data);
			$this->load->view('users/update', $data);
			$this->load->view('templates/footer');
		}
		else
		{
			$userData = array(	'username'	=>	$this->input->post('txt_username'),
								'email'		=>	$this->input->post('txt_email1'));
			if(strlen($this->input->post('txt_password1')) > 0)
			{
				$userData['hash'] = password_hash($this->input->post('txt_password1'), PASSWORD_DEFAULT, array('cost'	=>	$this->_calculate_cost())); //You can use constant HASH_COST if you have set it on constants.php file.
			}
			if($this->input->post('chk_activated') == 1)
			{
				$userData['activated'] = 1;
				$userData['activation'] = date('Y-m-d H:i:s');	
			}
			else
			{
				$userData['activated'] = 0;
				$userData['activation'] = NULL;		
			}
			if($this->input->post('chk_admin') == 1)
			{
				$userData['admin'] = 1;
			}
			else
			{
				$userData['admin'] = 0;
			}
			if($this->users_model->update_user($userData))
			{				
				$data['updated'] = 1;	
			}
			else
			{
				$data['updated'] = 0;
			}
			$data['user'] = $this->users_model->get_users($username);			
			$this->load->view('templates/header', $data);
			$this->load->view('users/update', $data);
			$this->load->view('templates/footer');	
		}
	}

	public function delete($username = FALSE)
	{
		if($username === FALSE)
		{
			redirect('/users');
		}

		$this->_check_login();
		if($this->session->admin != 1)
		{
			redirect('/');
		}

		$data['title'] = APP_TITLE;
		$data['user'] = $this->users_model->get_users($username);
		if(!isset($data['user']['username']))
		{
			redirect('/users');
		}

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('chk_delete','Checkbox', 'required');	

		if($this->form_validation->run() === FALSE)
		{			
			$this->load->view('templates/header', $data);			
			$this->load->view('users/delete', $data);
			$this->load->view('templates/footer');
		}
		else
		{			
			$this->users_model->delete_users($data['user']['username']);
			$this->load->view('templates/header', $data);			
			$this->load->view('users/user_deleted', $data);
			$this->load->view('templates/footer');	
		}
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
	 *	Sends an email.
	 */
	private function _email($to, $subject, $message)
	{
		if(!isset($to) OR !isset($subject) OR !isset($message))
		{
			return FALSE;
		}
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$this->email->initialize($config);
		$this->email->from(MAIL_ADDRESS, APP_TITLE);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if($this->email->send())
		{
			return TRUE;
		}
		return FALSE;
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