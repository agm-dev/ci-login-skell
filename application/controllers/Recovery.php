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
Class Recovery extends CI_Controller 
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
	public function index($code = FALSE)
	{
		$data['title'] = APP_TITLE;

		$this->load->helper('form');
		$this->load->library('form_validation');

		if($code === FALSE)
		{			
			//If there's no code, shows the recovery form request.				
			$this->form_validation->set_rules('txt_username', 'Username or email', 'required');	
			if($this->form_validation->run() === FALSE)
			{					
				$this->load->view('templates/header', $data);
				$this->load->view('recovery/form');
				$this->load->view('templates/footer');
			}
			else
			{
				$data['user'] = $this->users_model->get_users($this->input->post('txt_username'));
				if(count($data['user']['username']) == 0)
				{
					$data['user'] = $this->users_model->get_users(NULL, $this->input->post('txt_username'));								
					if(count($data['user']['username']) == 0)
					{
						$this->load->view('templates/header', $data);
						$this->load->view('recovery/form_sent');
						$this->load->view('templates/footer');
						$this->output->_display(); //Without this sentence exit() would interrupt the output library.
						exit();			
					}				
				}
				//If there's a valid user, generates a recovery code, updates that user, and sends a mail.
				$recovery = $this->_generate_code();
				$array = array(	'username'	=>	$data['user']['username'],
								'email'		=>	$data['user']['email'],
								'recovery'	=>	$recovery);
				$this->users_model->update_user($array);
				$message = "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>".
							"<h2>".APP_TITLE." - Password Recovery</h2>".
							"<p>You have requested a password recovery for your account. If you haven't, someone has done it.</p>".
							"<p>To set a new password for your account, you have to visit the link below:</p>".
							"<a target='_blank' href='".base_url()."recovery/".$recovery."'>Change your password.</a>".
							"</body></html>";
				$this->_email($data['user']['email'], 'Password Recovery', $message);
				$this->load->view('templates/header', $data);
				$this->load->view('recovery/form_sent');
				$this->load->view('templates/footer');

			}
		}
		else
		{		
			//If there's a code, looks for it on database.
			$data['user'] = $this->users_model->get_user_by_recovery($code);
			if(count($data['user']['username']) > 0)
			{
				$this->form_validation->set_rules('txt_password1', 'Password field1', 'required');	
				$this->form_validation->set_rules('txt_password2', 'Password field2', 'required');	
				if(	$this->form_validation->run() === FALSE OR
					$this->input->post('txt_password1') != $this->input->post('txt_password2'))
				{					
					$this->load->view('templates/header', $data);
					$this->load->view('recovery/new_password', $data);
					$this->load->view('templates/footer');
				}
				else
				{
					//Updates password for that user, deletes recovery code and resets login attempts.
					$array = array(	'username'		=>	$data['user']['username'],
									'email'			=>	$data['user']['email'],
									'recovery'		=>	NULL,
									'login_fails'	=>	0,
									'hash'			=>	password_hash($this->input->post('txt_password1'), PASSWORD_DEFAULT, array('cost'	=>	$this->_calculate_cost()))); //You can use constant HASH_COST if you have set it on constants.php file.			
					$this->users_model->update_user($array);

					//Sends email to the user and shows success view.
					$message = "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>".
								"<h2>".APP_TITLE." - Password Recovery</h2>".
								"<p>You have changed your password. If your account was locked, it should be unlocked now.</p>".							
								"<a target='_blank' href='".base_url()."'>Try to log in with your new password!</a>".
								"</body></html>";
					$this->_email($data['user']['email'], 'Password Recovery', $message);
					$this->load->view('templates/header', $data);
					$this->load->view('recovery/password_updated');
					$this->load->view('templates/footer');
				}
			}
			else
			{
				redirect('/');
			}
		}
	}

	/**
	 *	Pivated Methods
	 */
	
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