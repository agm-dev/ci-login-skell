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
Class Register extends CI_Controller 
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
		$data['title'] = APP_TITLE;
		$data['admin'] = 0; //This is a variable used by the view.
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
			//If validation is ok, then creates the user.
			$userData = array(	'username'	=>	$this->input->post('txt_username'),
								'email'		=>	$this->input->post('txt_email1'),
								'hash'		=>	password_hash($this->input->post('txt_password1'), PASSWORD_DEFAULT, array('cost'	=>	$this->_calculate_cost()))); //You can use constant HASH_COST if you have set it on constants.php file.			

			if($this->users_model->set_user($userData))
			{
				$userInfo = $this->users_model->get_users($userData['username']);
				$message = 	"<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>".
							"<h2>".APP_TITLE." - Account activation</h2>".
							"<p>Now you are registered in".APP_TITLE.". Visit the link below to activate your account.</p>".
							"<a target='_blank' href='".base_url()."activate/".$userInfo['activation_code']."'>Click to activate your account!</a>".
							"</body></html>";
				$this->_email($userInfo['email'], 'Account activation', $message);
			
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

	/**
	 *	Pivated Methods
	 */

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

}