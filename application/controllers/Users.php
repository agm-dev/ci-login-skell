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
		$data['title'] = APP_TITLE;				
		$this->_check_login();
		$data['admin'] = $this->session->admin;
		if($this->session->admin)
		{
			$data['users'] = $this->users_model->get_users();
			$this->load->view('templates/header', $data);
			$this->load->view('users/index', $data);
			$this->load->view('templates/footer');
		}
		else
		{
			redirect('/');
		}
	}

	public function create()
	{

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
		$data['title'] = APP_TITLE;
		$user = $this->users_model->get_users($this->session->id);
		if(! isset($user['username']) || count($user['username']) == 0) 
		{			
			// Main page.
			redirect('/');
		}
		
	}
}