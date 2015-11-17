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
Class Activate extends CI_Controller 
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
		if($code === FALSE)
		{
			redirect('/');
		}
		
		$data['title'] = APP_TITLE;
		$data['user'] = $this->users_model->get_user_by_activation_code($code);

		if(isset($data['user']['username']))
		{
			//If there is an user with the activation code used,
			//then we activate the account and delete the code.
			$array = array(	'username'			=>	$data['user']['username'],
							'email'				=>	$data['user']['email'],
							'activated'			=>	1,
							'activation'		=>	date('Y-m-d H:i:s'),
							'activation_code'	=>	NULL);
			if($this->users_model->update_user($array))
			{
				$message = 	"<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>".
							"<h2>".APP_TITLE." - Account activation</h2>".
							"<p>Your account has been activated. Now you can log in the web.</p>".
							"<a target='_blank' href='".base_url()."'>Visit us!</a>".
							"</body></html>";
				$this->_email($data['user']['email'], 'Account activation', $message);
				$this->load->view('templates/header', $data);			
				$this->load->view('activate/success');
				$this->load->view('templates/footer');	
			}
			else
			{
				$this->load->view('templates/header', $data);			
				$this->load->view('activate/error');
				$this->load->view('templates/footer');	
			}			
		}
		else
		{
			redirect('/');
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
}