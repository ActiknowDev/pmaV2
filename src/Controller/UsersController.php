<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Core\Configure;
use Cake\Utility\Security;
use Cake\Datasource\ConnectionManager;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\Validation\Validator;
use Cake\View\Helper\formHelper;
use Cake\Mailer\TransportFactory;
// use Cake\Mailer\Email;
use Cake\Mailer\Mailer;

class UsersController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();

		$this->loadComponent('Flash'); 
		//$this->loadModel("AssigendProjectTasks");
		//$this->loadModel("Projects");
		//$this->loadModel("MyTeams");
		// $this->loadModel("Users");
		//$this->loadModel("MyTeamResources");
		// $this->EmployeePunchTime = $this->getTableLocator()->get('EmployeePunchTime');
	}

	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		// Configure the login action to not require authentication, preventing
		// the infinite redirect loop issue
		$this->Authentication->addUnauthenticatedActions(['login', 'signup', 'forgotpassword', 'resetpassword', 'credentials']);
	}

	public function forgotpassword()
	{
		$this->viewBuilder()->setLayout('login');
		$this->Authorization->skipAuthorization();
		if ($this->request->is('post')) {
			$myemail = $this->request->getData('email');


			$mytoken = Security::hash(Security::randomBytes(25));

			$usertable = $this->fetchTable('Users');//TableRegistry::get('Users');

			$user = $usertable->find('all')->where(['email' => $myemail])->first();

			$user->token = $mytoken;

			$exists = $usertable->exists(['email' => $myemail]);
			if ($exists == 1) {

				$url = Router::Url(['controller' => 'users', 'action' => 'resetpassword'], true) . '/' . $mytoken;

				if ($usertable->save($user)) {


					$emailMessage = ' <b>Hello</b> ' . $user->name . ' <br><br> <b> please click the link below to reset your password</b> <br/><br/> <a href="' . $url . '">resetpassword</a>';


					//$email = new Email('default');
					$email = new Mailer('default');
					$email->setFrom(['karshnik.singh@actiknowbi.com' => 'Pma'])
						->setTo($myemail)
						->setSubject('forgot Password')
						->setEmailFormat('html')
						->setViewVars(array('msg' => $emailMessage))
						->deliver($emailMessage);

					$this->Flash->success('reset password link has been sent to your email (' . $myemail . '), please open your inbox');
					return $this->redirect(['action' => 'forgotpassword']);
					// return $this->redirect(['action'=>'resetpassword']);
				}
			} else {
				$this->Flash->error(__('Email does not exists !'));
				return $this->redirect(['action' => 'forgotpassword']);
			}

			$this->set(compact('myemail'));
		}
	}
	public function resetpassword($mytoken)
	{
		$this->viewBuilder()->setLayout('login');
		$this->Authorization->skipAuthorization();
		// $this->loadModel("Users");
		$user = $this->fetchTable('Users')->find("all")->where(['token' => $mytoken])->first();

		if ($this->request->is('post')) {
			$password = $this->request->getdata('password');
			$confirmpassword = $this->request->getdata('confirmpassword');

			$user->password = $password;
			$user->token = ' ';
			// print_r($user);
			// die();
			if ($password == $confirmpassword) {

				$this->fetchTable('Users')->save($user);
				$this->Flash->success(__('Your password has been successfully updated.'));
				return $this->redirect(['action' => 'login']);
			} else {
				$this->Flash->error(__('Your password could not be saved. Please, try again.'));
			}
		}

		$this->set(compact('user'));
	}



	public function login()
	{    
		//set layout
		$this->viewBuilder()->setLayout('login');

		//skip authorization
		// $this->Authorization->skipAuthorization();

		$this->request->allowMethod(['get', 'post']);

		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in

		if ($result->isValid()) {


			$identity = $this->Authentication->getIdentity();

			$user = $identity->getOriginalData();
			if ($user->status == 1) {
				$user_id = $user->id;
				$user_email = $user->email;
				$user_name = $user->name;
				$client_name = $user->client_name;
				$company_name = $user->company_name;
				$user_role = $user->role;
				$user_parent_id = $user->company_id;
				$contact_person_name = $user->contact_person_name;
				$r = $user->role_name;
				$role_name = explode(',', $r);
				if ($user->role == 0)
					$type = 'Admin';
				else
					$type = 'not';
				// $role = 'user';
				// if(in_array(4,$role_name))
				// 	$role = 'manager';
				// elseif(in_array(6,$role_name))
				// 	$role= 'bd';
				// elseif(in_array(5,$role_name))
				// 	$role= 'techlead';
				// if(in_array(9,$role_name))
				// 	$nrole= 'reporting';	
				// if(in_array(10,$role_name))
				// 	$nrole= 'project';	

				// $user_obj = TableRegistry::get('Users');
				$usersTable = $this->fetchTable('Users');

				$current_date_time = date("Y-m-d H:i:s");

				$userData = $usersTable->get($user_id);
				$userData->last_login = $current_date_time;
				$usersTable->save($userData);

				// $query = $usersTable->query();
				// $query->update()
				// 	->set(['last_login' => $current_date_time])
				// 	->where(['id' => $user_id])
				// 	->execute();

				$session_array = array('id' => $user_id, 'email' => $user_email, 'name' => $user_name, 'role' => $user_role, 'parent_id' => $user_parent_id, 'client_name' => $client_name, 'company_name' => $company_name, 'type' => $type, 'contact_person_name' => $contact_person_name, 'role_name' => $role_name, 'reporting_manager' => $user->reporting_manager);
				$session = new \Cake\Http\Session();
				$session->write('data', $session_array);
				$session->write('menu', 1);
				$session->write('user_data', $user->toArray());
				if ($user->role == 0) {
					$session->write('menu', 0);
					// admin login redirect to /companies after login success
					$redirect = $this->request->getQuery('redirect', [
						'controller' => 'Companies',
						'action' => 'index',
					]);
				} elseif ($user->role == 1) {
					// redirect to /project after login success
					$redirect = $this->request->getQuery('redirect', [
						'controller' => 'Companies',
						'action' => 'listProject',
					]);
				} else {
					// $redirect = $this->request->getQuery('redirect', [
					// 	'controller' => 'Companies',
					// 	'action' => 'myProject',
					// ]);
					$redirect = $this->request->getQuery('redirect', [
						'controller' => 'Companies',
						// 'action' => 'timesheetRecord',
						'action' => 'myProject',
					]);
					// $redirect = $this->redirect("/publish_notice");
				}

				return $this->redirect($redirect);
			} else {

				$this->Flash->error(__('Your account is deactivated'));
				return $this->redirect('/logout');
			}
		}
		// display error if user submitted and authentication failed
		if ($this->request->is('post') && !$result->isValid()) {
			$this->Flash->error(__('Invalid username or password'));
		}
	}

	public function signup()
	{

		//set layout
		$this->viewBuilder()->setLayout('login');

		$this->Authorization->skipAuthorization();

		$user = $this->Users->newEmptyEntity();
		if ($this->request->is('post')) {
			$user = $this->Users->patchEntity($user, $this->request->getData());

			//pr() or debug() functions to print post data
			// Hardcoding the user_id is temporary, and will be removed later
			// when we build authentication out.
			$user->role = 0;
			if ($this->Users->save($user)) {
				$this->Flash->success(__('Your user has been saved.'));
				return $this->redirect(['action' => 'list']);
			}
			$this->Flash->error(__('Unable to add your user.'));
		}
		$this->set('user', $user);
	}

	public function dashboard()
	{
		$this->Authorization->skipAuthorization();
		//set layout
		$this->viewBuilder()->setLayout('default_new');

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$name = $userSession['company_name'];

		$this->set(compact('name'));
	}

	public function index($status = 'active')
	{
		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');

		// validation for valid user
		$roleArray = $userSession['role_name'];
		$validList = [12];
		$this->routeValidation($roleArray,$validList);

		// $this->loadComponent('Paginator');
		$this->paginate();

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$role = $userSession['role'];
		$parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];
		$parent_name = $userSession['contact_person_name'];

		if ($status == 'active') {
			$users = $this->Users->find("all", [
				"contain" => [
					"TeamData",
					"ReportingManagerData",
					"EmpDetail",
				], "conditions" => [
					"Users.deleted" => 1,
					"Users.role" => 3,
					"Users.company_id" => $parent_id
				]
			])
				->where(['Users.status' => 1])
				->toArray();
		} elseif ($status == 'inactive') {
			$users = $this->Users->find("all", [
				"contain" => [
					"TeamData",
					"ReportingManagerData",
					"EmpDetail",
				], "conditions" => [
					"Users.deleted" => 1,
					"Users.role" => 3,
					"Users.company_id" => $parent_id
				]
			])
				->where(['Users.status' => 0])
				->toArray();
		} else {

			$users = $this->Users->find("all", [
				"contain" => [
					"TeamData",
					"ReportingManagerData",
					"EmpDetail",
				], "conditions" => [
					"Users.deleted" => 1,
					"Users.role" => 3,
					"Users.company_id" => $parent_id
				]
			])->toArray();
		}
		$userQuery = "SHOW COLUMNS FROM users";
		$userResult = $conn->execute($userQuery)->fetchAll('assoc');
		$empQuery = "SHOW COLUMNS FROM employee_details";
		$empResult = $conn->execute($empQuery)->fetchAll('assoc');
		$teamQuery = "SHOW COLUMNS FROM my_teams";
		$teamResult = $conn->execute($teamQuery)->fetchAll('assoc');

		$result = [];
		foreach ($userResult as $col) {
			if ($col['Field'] != 'id' && $col['Field'] != 'password' && $col['Field'] != 'token' && $col['Field'] != 'company_id' && $col['Field'] != 'company_name' && $col['Field'] != 'company_website_link' && $col['Field'] != 'contact_person_name' && $col['Field'] != 'country_code' && $col['Field'] != 'total_cl' && $col['Field'] != 'total_sl' && $col['Field'] != 'point_of_contact' && $col['Field'] != 'role_type' && $col['Field'] != 'teamid'  && $col['Field'] != 'user_image' && $col['Field'] != 'team' && $col['Field'] != 'client_name' && $col['Field'] != 'role_name' && $col['Field'] != 'role' && $col['Field'] != 'created' && $col['Field'] != 'modified' && $col['Field'] != 'deleted' && $col['Field'] != 'location' && $col['Field'] != 'pf_no' && $col['Field'] != 'emp_type' && $col['Field'] != 'dol') {
				$result[] = $col['Field'];
			}
		}
		foreach ($empResult as $val) {
			if ($val['Field'] != 'id' && $val['Field'] != 'user_id' && $val['Field'] != 'guardian_name' && $val['Field'] != 'email_dob' && $val['Field'] != 'doj' && $val['Field'] != 'mobile_no' && $val['Field'] != 'phone_no' && $val['Field'] != 'location' && $val['Field'] != 'pan_no' && $val['Field'] != 'ntc_perd' && $val['Field'] != 'bond' && $val['Field'] != 'house_no_prsnt' && $val['Field'] != 'locality_prsnt' && $val['Field'] != 'city_prsnt' && $val['Field'] != 'state_prsnt' && $val['Field'] != 'zip_prsnt' && $val['Field'] != 'phone_prsnt' && $val['Field'] != 'house_no_prmnt' && $val['Field'] != 'locality_prmnt' && $val['Field'] != 'city_prmnt' && $val['Field'] != 'state_prmnt' && $val['Field'] != 'zip_prmnt' && $val['Field'] != 'phone_prmnt') {
				$result[] = $val['Field'];
			}
		}

		foreach ($teamResult as $val) {
			if ($val['Field'] != 'id' && $val['Field'] != 'created_by' && $val['Field'] != 'tech_lead' && $val['Field'] != 'project_manager' && $val['Field'] != 'created_at') {
				$result[] = $val['Field'];
			}
		}

		// echo "<pre>";
		// foreach ($result as $val) {
		// 	echo $val . "<br>";
		// }
		// print_r();
		// die;


		//retreive total User number
		$totalUsers = $this->Users->find('all', array('conditions' => array('Users.deleted' => 1, 'Users.role' => 3, 'Users.company_id' => $parent_id)))->count();

		//retreive total active User number
		$totalActiveUsers = $this->Users->find('all', array('conditions' => array('Users.status' => 1, 'Users.deleted' => 1, 'Users.role' => 3, 'Users.company_id' => $parent_id)))->count();

		//retreive total inactive User number
		$totalInactiveUsers = $this->Users->find('all', array('conditions' => array('Users.status' => 0, 'Users.deleted' => 1, 'Users.role' => 3, 'Users.company_id' => $parent_id)))->count();

		$userReport = $this->Users
			->find()
			->where(['deleted' => 1, 'role' => 3, 'company_id' => $parent_id])
			->all();



		$this->set(compact('users', 'status', 'totalUsers', 'totalActiveUsers', 'totalInactiveUsers', 'userReport', 'parent_id', 'parent_name', 'result'));
	}

	public function add()
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		$user = $this->Users->newEmptyEntity();

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$role = $userSession['role'];

		$parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];

		if ($this->request->is('ajax')) {

			$users = $this->Users->find()
				->where(function (QueryExpression $exp) use ($parent_id) {
					$orConditions = $exp->or(['name' => $this->request->getData('name')])
						->eq('email', $this->request->getData('email'));
					return $exp
						->add($orConditions)
						->eq('deleted', 1)
						->eq('company_id', $parent_id);
				})
				->toArray();


			if (count($users) > 0) {
				$this->Flash->error(__('This User already exist.'));
				$data['sussess'] = 'false';
				echo json_encode($data);
			} else {
				$user = $this->Users->patchEntity($user, $this->request->getData());
				$user->role = 3;
				$user->status = 1;
				$role_name = implode(',', $this->request->getData('role_name'));
				$user->role_name = $role_name;
				$user->company_id = $parent_id; //$this->request->getAttribute('identity')->getIdentifier();
				$user->password = Security::hash('password');
				if ($this->Users->save($user)) {
					$data['sussess'] = 'true';
					echo json_encode($data);
					$this->SendWelcomeMail($user->email);
				}
			}
		}
	}
	public function edit($id)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		$user = $this->Users
			->findById($id)
			->firstOrFail();
		if ($this->request->is(['post', 'put'])) {
			$this->Users->patchEntity($user, $this->request->getData());
			$user->role = 3;
			$user->status = 1;
			$role_name = implode(',', $this->request->getData('role_name'));
			$user->role_name = $role_name;
			// $user->parent_id = $this->request->getAttribute('identity')->getIdentifier();
			$user->password = Security::hash('password');
			if ($this->Users->save($user)) {
				echo "true";
			}
		}
	}

	// public function delete($id)
	// {
	// 	$this->Authorization->skipAuthorization();
	// 	$user = TableRegistry::get('Users');
	// 	$teamres = TableRegistry::get("MyTeamResources");

	// 	$teamres->deleteAll(["resid" => $id]);

	// 	$query = $user->query();
	// 	$query->update()
	// 		->set(['deleted' => 0])
	// 		->where(['id' => $id])
	// 		->execute();
	// 	return $this->redirect(['controller' => 'Users', 'action' => 'index']);
	// }

	public function delete($id)
	{
		$this->Authorization->skipAuthorization();

		$user = $this->fetchTable('Users');
		$teamres = $this->fetchTable('MyTeamResources');

		$teamres->deleteAll([
			"resid" => $id
		]);

		$user->updateQuery()
			->set([
				'deleted' => 0
			])
			->where([
				'id' => $id
			])
			->execute();

		return $this->redirect([
			'controller' => 'Users',
			'action' => 'index'
		]);
	}


	//change status
	// public function updateStatus($id, $status)
	// {
	// 	$this->autoRender = false;
	// 	$this->Authorization->skipAuthorization();
	// 	if ($this->request->is('ajax')) {
	// 		$user = TableRegistry::get('Users');
	// 		$query = $user->query();
	// 		$query->update()
	// 			->set(['status' => $status])
	// 			->where(['id' => $id])
	// 			->execute();
	// 		return $this->redirect(['controller' => 'Users', 'action' => 'index']);
	// 	}
	// }

	public function updateStatus($id, $status)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		if ($this->request->is('ajax')) {

			$user = $this->fetchTable('Users');

			$query = $user->updateQuery();

			$query->set([
					'status' => $status
				])
				->where([
					'id' => $id
				])
				->execute();

			return $this->redirect([
				'controller' => 'Users',
				'action' => 'index'
			]);
		}
	}

	public function editUser($id)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$role = $userSession['role'];
		$parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];
		$parent_name = $userSession['contact_person_name'];

		$user = $this->Users
			->findById($id)
			->firstOrFail();

		$local['id'] = $user->id;
		$local['parent_id'] = $user->company_id;
		$local['name'] = $user->name;
		$local['email'] = $user->email;
		$team_id = $user->team;
		$reporting_manager_id = $user->reporting_manager;
		$local['technology'] = $user->technology;
		$local['designation'] = $user->designation;
		$local['parent_name'] = $parent_name;
		$role_name_ids = $user->role_name;
		$role_name_selected = explode(',', $role_name_ids);

		$reporting_manager_options = '';
		$reporting_managers = $this->Users
			->find()
			->where(['deleted' => 1, 'role' => 3, 'id !=' => $id, 'company_id' => $parent_id])
			->all();
		$c = 0;
		foreach ($reporting_managers as $key => $value) {
			if ($parent_name == $value->name) $c = 1;
			if ($reporting_manager_id == $value->id) {
				$reporting_manager_options .= '<option value="' . $value->id . '" selected>' . $value->name . '</option>';
			} else {
				$reporting_manager_options .= '<option value="' . $value->id . '">' . $value->name . '</option>';
			}
		}
		if ($c == 0) {
			if ($reporting_manager_id == $parent_id)
				$reporting_manager_options .= '<option value="' . $parent_id . '" selected>' . $parent_name . '</option>';
			else
				$reporting_manager_options .= '<option value="' . $parent_id . '">' . $parent_name . '</option>';
		}
		$local['reporting_manager_options'] = $reporting_manager_options;

		$team = '<option value="">Select Team</option>
		   <option value="37" ';
		if ($team_id == 37)
			$team .= 'selected';
		$team .= '>Sumit Team</option><option value="43" ';
		if ($team_id == 43)
			$team .= 'selected';
		$team .= '>Deepika M Team </option><option value="46" ';
		if ($team_id == 46)
			$team .= 'selected';
		$team .= '>Deepika R Team</option><option value="44" ';
		if ($team_id == 44)
			$team .= 'selected';
		$team .= '>Pinkey Team</option><option value="45" ';
		if ($team_id == 45)
			$team .= 'selected';
		$team .= '>Rana Team</option><option value="179" ';
		if ($team_id == 179)
			$team .= 'selected';
		$team .= '>Vikrant Team</option><option value="57" ';
		if ($team_id == 57)
			$team .= 'selected';
		$team .= '>Deepak Team</option>';


		$local['team'] = $team;

		$role_name_list = array('4' => 'Manager', '5' => 'Tech Lead', '6' => 'BD', '7' => 'Developer', '8' => 'Designer');

		$role_name_options = '';

		foreach ($role_name_list as $key_list => $role_name_list_item) {



			if (in_array($key_list, $role_name_selected)) {
				$role_name_options .= '<option value="' . $key_list . '" selected>' . $role_name_list_item . '</option>';
			} else {
				$role_name_options .= '<option value="' . $key_list . '">' . $role_name_list_item . '</option>';
			}
		}
		$local['role_name_options'] = $role_name_options;
		$local['src'] = '<script>$("#langOpt_edit1").multiselect({
			columns: 1,
			placeholder: "Select Option",
			search: true
			});</script>';

		// echo "<pre>";print_r($local);die;
		echo json_encode($local);
	}



	public function logout()
	{
		$this->Authorization->skipAuthorization();
		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in
		if ($result->isValid()) {
			$this->Authentication->logout();
			return $this->redirect(['controller' => 'Users', 'action' => 'login']);
		}
	}

	public function userView($id)
	{
		//set layout
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$this->Authorization->skipAuthorization();

		$this->Projects = $this->fetchTable('Projects');//TableRegistry::get('Projects');
		$this->Users = $this->fetchTable('Users');
		$user = $this->Users
			->findById($id)
			->firstOrFail();

		$manager = '';
		if ($user->reporting_manager != '') {
			$manager =  $this->Users->find('all')
				->select(['name'])
				->where(['id' => $user->reporting_manager])
				->first();
		}

		$query = "SELECT p.*,c.client_name,pm.name as manager,tl.name as lead FROM projects p JOIN users c ON p.client_id = c.id JOIN users pm ON p.project_manager_id=pm.id JOIN users tl ON p.tech_lead_id=tl.id WHERE (p.project_manager_id=" . $id . " OR p.tech_lead_id=" . $id . " OR p.bd_id=" . $id . " OR FIND_IN_SET(" . $id . ",p.resources) != 0)";
		$stmtProduct = $conn->execute($query);
		$projects = $stmtProduct->fetchAll('assoc');
		// echo '<pre>'; print_r($list); exit;

		$complete = $going = 0;
		foreach ($projects as $p) {
			if ($p['status'] == 'Completed')
				$complete++;
			else
				$going++;
		}
		// echo '<pre>'; print_r($projects); exit;
		$this->set(compact('user', 'projects', 'complete', 'going', 'manager'));
	}

	//change password function
	public function changePassword()
	{
		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');


		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$id = $userSession['id'];

		if ($this->request->is('post')) {
			$password = $this->request->getData('password');
			$hash = (new DefaultPasswordHasher())->hash($password);

			$query = "UPDATE users SET password='" . $hash . "' WHERE id=" . $id;
			$stmtProduct = $conn->execute($query);
			$this->Flash->success(__('You have changed your password successfully.'));
		}
	}

	public function matchpwd($old)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		$conn = ConnectionManager::get('default');

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$id = $userSession['id'];


		if ($this->request->is('ajax')) {


			$identity = $this->Authentication->getIdentity();
			$user = $identity->getOriginalData();

			if ((new DefaultPasswordHasher())->check($old, $user->password)) {
				$data['result'] = true;
				echo json_encode($data);
			} else {
				$data['result'] = false;
				echo json_encode($data);
			}
		}
	}

	public function timesheet()
	{
		// print_r('hi');
		// die;

		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];

		$this->request->getSession()->write('page', 'timesheet');

		$query = "SELECT p.id,p.project_name,p.milestone_id,u.client_name FROM projects p JOIN users u ON p.client_id=u.id WHERE p.deleted=1 AND p.status!='Completed' AND p.active=1 AND FIND_IN_SET(" . $user_id . ",resources)!=0";

		$stmtProduct = $conn->execute($query);
		$list = $stmtProduct->fetchAll('assoc');
		$projects = array();

		
		$weekStart = (date('N') == 1) ? date('Y-m-d') : date('Y-m-d', strtotime('last monday'));
		$weekEnd = date('Y-m-d', strtotime($weekStart . ' +5 days'));

		// Extract month numbers
		$startMonth = date('m', strtotime($weekStart));
		$endMonth = date('m', strtotime($weekEnd));

		foreach ($list as $l) {
			// echo "<pre>";
			// print_r($l);

			$p['id'] = $l['id'];
			$p['project_name'] = $l['project_name'];
			$p['client_name'] = $l['client_name'];
			$p['miles'] = array();
			if ($l['milestone_id']) {
				// $query = "SELECT id,title FROM project_milestones p WHERE p.id IN (" . $l['milestone_id'] . ") AND p.deleted=0 AND status != 'Completed'";
				$query = "
					SELECT p.id, p.title, p.due_date, pr.client_id
					FROM project_milestones p
					JOIN projects pr ON p.project_id = pr.id
					WHERE p.id IN (" . $l['milestone_id'] . ")
					AND p.deleted = 0
					AND p.status != 'Completed'
					AND (
						(
							pr.client_id != '144'
							AND (MONTH(p.due_date) = " . $startMonth . " OR MONTH(p.due_date) = " . $endMonth . ")
						)
						OR pr.client_id = '144'
					)
				";
				$stmtProduct = $conn->execute($query);
				$mlist = $stmtProduct->fetchAll('assoc');

				foreach ($mlist as $m) {

					$data['id'] = $m['id'];
					$data['title'] = $m['title'];

					$query = "SELECT time_slot FROM project_allocations WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id;
					$stmtProduct = $conn->execute($query);
					$allot = $stmtProduct->fetchAll('assoc');

					if (count($allot) > 0)
						$data['alot'] = $allot[0]['time_slot'];
					else
						$data['alot'] = 0;


					$query = "SELECT IFNULL(SUM(time_used),0) as time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id;
					$stmtProduct = $conn->execute($query);
					$allot = $stmtProduct->fetchAll('assoc');
					if (count($allot) > 0)
						$data['used'] = $allot[0]['time_used'];
					else
						$data['used'] = "";

					$data['mtime'] = $data['tutime'] = $data['wtime'] = $data['thtime'] = $data['ftime'] = $data['stime'] = "";
					$data['mnotes'] = $data['tunotes'] = $data['wnotes'] = $data['thnotes'] = $data['fnotes'] = $data['snotes'] = "";

					$n = date('N') - 1;
					$data['monday'] = (date('N') == 1) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
					$n = date('N') - 2;
					$data['tuesday'] = (date('N') == 2) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
					$n = date('N') - 3;
					$data['wednesday'] = (date('N') == 3) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
					$n = date('N') - 4;
					$data['thursday'] = (date('N') == 4) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
					$n = date('N') - 5;
					$data['friday'] = (date('N') == 5) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
					$n = date('N') - 6;
					$data['saturday'] = (date('N') == 6) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));

					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['monday'] . "'";
					$stmtProduct = $conn->execute($query);
					$monday = $stmtProduct->fetchAll('assoc');
					if (count($monday) > 0) {
						$data['mtime'] = $monday[0]['time_used'];
						$data['mnotes'] = $monday[0]['notes'];
					}


					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['tuesday'] . "'";
					$stmtProduct = $conn->execute($query);
					$tuesday = $stmtProduct->fetchAll('assoc');
					if (count($tuesday) > 0) {
						$data['tutime'] = $tuesday[0]['time_used'];
						$data['tunotes'] = $tuesday[0]['notes'];
					}

					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['wednesday'] . "'";
					$stmtProduct = $conn->execute($query);
					$wednesday = $stmtProduct->fetchAll('assoc');
					if (count($wednesday) > 0) {
						$data['wtime'] = $wednesday[0]['time_used'];
						$data['wnotes'] = $wednesday[0]['notes'];
					}


					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['thursday'] . "'";
					$stmtProduct = $conn->execute($query);
					$thursday = $stmtProduct->fetchAll('assoc');
					if (count($thursday) > 0) {
						$data['thtime'] = $thursday[0]['time_used'];
						$data['thnotes'] = $thursday[0]['notes'];
					}


					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['friday'] . "'";
					$stmtProduct = $conn->execute($query);
					$friday = $stmtProduct->fetchAll('assoc');
					if (count($friday) > 0) {
						$data['ftime'] = $friday[0]['time_used'];
						$data['fnotes'] = $friday[0]['notes'];
					}


					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['saturday'] . "'";
					$stmtProduct = $conn->execute($query);
					$saturday = $stmtProduct->fetchAll('assoc');
					if (count($saturday) > 0) {
						$data['stime'] = $saturday[0]['time_used'];
						$data['snotes'] = $saturday[0]['notes'];
					}


					$p['miles'][] = $data;
				}
			}
			$projects[] = $p;
		}

		// $query = "SELECT p.id,p.project_name,p.milestone_id,u.client_name,p.resources FROM projects p JOIN users u ON p.client_id=u.id WHERE p.deleted=1 AND p.status!='Completed' AND p.active=1 AND p.resources!=0";

		// $stmtProduct = $conn->execute($query);
		// $list = $stmtProduct->fetchAll('assoc');
		// foreach($list as $l){
		// 	// $arr[] = $l['project_name'];
		// 	$arr[] =([
				
		// 		'id'=> $l['id'],
		// 		'project_name'=> $l['project_name'],
		// 		'milestone_id'=> $l['milestone_id'],
		// 		'client_name'=> $l['client_name'],
		// 		'resourse_id' => explode(",",$l['resources'])

		// 	]);
			
		// 	// $arr1[]=$arr[0]['resourse_id'];
			
		// // die;
		// }
		// foreach($arr as $a) {
		// 	$query1 = "SELECT `u`.`name` AS `username`, `p`.`project_name` AS `pname`, `pm`.`title` AS `mtitle` FROM users u JOIN projects p ON u.id=p.user_id JOIN project_milestones pm ON p.id=pm project_id WHERE p.deleted=1 AND p.status!='Completed' AND p.active=1 AND FIND_IN_SET($arr,resources)!=0";

		// $stmtProduct1 = $conn->execute($query1);
		// $list1 = $stmtProduct1->fetchAll('assoc');

		// echo "<pre>";
		// print_r($list1);
		// }
		// echo "<pre>";
		// print_r($projects);
		// die;

		$pdate = date('Y-m-d', strtotime('last saturday'));
		$ndate = date('Y-m-d', strtotime('next monday'));

		$this->set(compact('projects', 'pdate', 'ndate'));
	}


	public function timesheetReport($month=null,$year=null) {

		
		if($month==null) {
			$month=date('m');
		} else {
			$month=$month;
		}
		if($year==null){
			$year=date('Y');
		} else {
			$year=$year;
		}

		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];
		// validation for valid user
		$roleArray = $userSession['role_name'];
		$validList = [10,4,13];
		$this->routeValidation($roleArray,$validList);

		$this->request->getSession()->write('page', 'timesheet_report');

		// previous 
		// $query = "SELECT user_timesheets.id,user_timesheets.milestone_id,user_timesheets.resource_id,sum(user_timesheets.time_used) as time_used,user_timesheets.work_date, project_milestones.title, project_milestones.project_id,projects.project_name,projects.bill,users.name as username,users.id as userid FROM `user_timesheets` LEFT JOIN project_milestones ON user_timesheets.milestone_id=project_milestones.id LEFT JOIN projects ON projects.id=project_milestones.project_id LEFT JOIN users ON users.id=user_timesheets.resource_id WHERE month(work_date)=". $month ." AND year(work_date)=".$year." 
		// GROUP BY 
		// users.name
		// ";

		// $query = "
		// SELECT 
		// 	ANY_VALUE(user_timesheets.id) AS id,
		// 	ANY_VALUE(user_timesheets.milestone_id) AS milestone_id,
		// 	user_timesheets.resource_id,
		// 	SUM(user_timesheets.time_used) AS time_used,
		// 	ANY_VALUE(user_timesheets.work_date) AS work_date,
		// 	ANY_VALUE(project_milestones.title) AS title,
		// 	ANY_VALUE(project_milestones.project_id) AS project_id,
		// 	ANY_VALUE(projects.project_name) AS project_name,
		// 	ANY_VALUE(projects.bill) AS bill,
		// 	users.name AS username,
		// 	users.id AS userid
		// FROM `user_timesheets`
		// LEFT JOIN project_milestones ON user_timesheets.milestone_id = project_milestones.id
		// LEFT JOIN projects ON projects.id = project_milestones.project_id
		// LEFT JOIN users ON users.id = user_timesheets.resource_id
		// WHERE month(work_date) = $month AND year(work_date) = $year
		// GROUP BY users.name, users.id, user_timesheets.resource_id
		// ";

		$query = "
			SELECT 
				ANY_VALUE(user_timesheets.id) AS id,
				ANY_VALUE(user_timesheets.milestone_id) AS milestone_id,
				user_timesheets.resource_id,
				SUM(user_timesheets.time_used) AS time_used,
				ANY_VALUE(user_timesheets.work_date) AS work_date,
				ANY_VALUE(project_milestones.title) AS title,
				ANY_VALUE(project_milestones.project_id) AS project_id,
				ANY_VALUE(projects.project_name) AS project_name,
				ANY_VALUE(projects.bill) AS bill,
				users.name AS username,
				users.id AS userid
			FROM `user_timesheets`
			LEFT JOIN project_milestones ON user_timesheets.milestone_id = project_milestones.id
			LEFT JOIN projects ON projects.id = project_milestones.project_id
			LEFT JOIN users ON users.id = user_timesheets.resource_id
			WHERE month(work_date) = $month AND year(work_date) = $year
			GROUP BY users.name, users.id, user_timesheets.resource_id
			";

		// $query = "
		// 	SELECT 
		// 		(user_timesheets.id) AS id,
		// 		(user_timesheets.milestone_id) AS milestone_id,
		// 		user_timesheets.resource_id,
		// 		SUM(user_timesheets.time_used) AS time_used,
		// 		(user_timesheets.work_date) AS work_date,
		// 		(project_milestones.title) AS title,
		// 		(project_milestones.project_id) AS project_id,
		// 		(projects.project_name) AS project_name,
		// 		(projects.bill) AS bill,
		// 		users.name AS username,
		// 		users.id AS userid
		// 	FROM `user_timesheets`
		// 	LEFT JOIN project_milestones ON user_timesheets.milestone_id = project_milestones.id
		// 	LEFT JOIN projects ON projects.id = project_milestones.project_id
		// 	LEFT JOIN users ON users.id = user_timesheets.resource_id
		// 	WHERE month(work_date) = $month AND year(work_date) = $year
		// 	GROUP BY users.name, users.id, user_timesheets.resource_id
		// 	";


		// dd($query);
		$stmtProduct = $conn->execute($query);
		$list = $stmtProduct->fetchAll('assoc');
		$projects = array();
		// dd($list);

		foreach ($list as $l) {

			$p['project_id'] = $l['project_id'];
			// dd($p['project_id']);
			$p['project_name'] = $l['project_name'];
			$p['bill'] = $l['bill'];
			$p['userid'] = $l['userid'];
			$p['username'] = $l['username'];
			// $p['time_used'] = $l['time_used'];
			// $p['miles'] = array();
			
				// $query = "SELECT id,title,project_id FROM project_milestones p WHERE p.id IN (" . $l['milestone_id'] . ") AND p.deleted=0 AND status != 'Completed' AND month(p.due_date)=". $month . " AND year(p.due_date)=".$year;
			// $query = "SELECT (select sum(project_allocations.time_slot) from project_allocations where project_allocations.milestone_id in (select id from project_milestones where project_milestones.project_id = " . $l['project_id'] . " ) AND project_allocations.resource_id=" . $l['userid'] . " ) as time_slot";
			// $stmtProduct = $conn->execute($query);
			// $mlist = $stmtProduct->fetchAll('assoc');

			// $billable_time_used = "SELECT sum(user_timesheets.time_used) as time_used,users.name as username FROM `user_timesheets` LEFT JOIN project_milestones ON user_timesheets.milestone_id=project_milestones.id LEFT JOIN projects ON projects.id=project_milestones.project_id LEFT JOIN users ON users.id=user_timesheets.resource_id WHERE month(work_date)=05 AND year(work_date)=2023 AND user_timesheets.resource_id=502 AND projects.bill='Billable' GROUP BY users.name";
			$billable_time_used ="SELECT sum(user_timesheets.time_used) as time_used,users.name as username, (SELECT SUM(project_allocations.time_slot) FROM project_allocations INNER JOIN project_milestones ON project_allocations.milestone_id = project_milestones.id INNER JOIN projects ON project_milestones.project_id = projects.id WHERE projects.bill = 'Billable' AND project_allocations.resource_id = " . $l['userid'] . ") AS time_slot FROM `user_timesheets` LEFT JOIN project_milestones ON user_timesheets.milestone_id=project_milestones.id LEFT JOIN projects ON projects.id=project_milestones.project_id LEFT JOIN users ON users.id=user_timesheets.resource_id WHERE month(user_timesheets.work_date)=". $month ." AND year(user_timesheets.work_date)=". $year ." AND user_timesheets.resource_id=" . $l['userid'] . " AND projects.bill='Billable' GROUP BY users.name";
			$stmtProduct = $conn->execute($billable_time_used);
			$billable_time_used_data = $stmtProduct->fetchAll('assoc');

			$non_billable_time_used = "SELECT sum(user_timesheets.time_used) as time_used,users.name as username, (SELECT SUM(project_allocations.time_slot) FROM project_allocations INNER JOIN project_milestones ON project_allocations.milestone_id = project_milestones.id INNER JOIN projects ON project_milestones.project_id = projects.id WHERE projects.bill = 'Non Billable' AND project_allocations.resource_id = " . $l['userid'] . ") AS time_slot FROM `user_timesheets` LEFT JOIN project_milestones ON user_timesheets.milestone_id=project_milestones.id LEFT JOIN projects ON projects.id=project_milestones.project_id LEFT JOIN users ON users.id=user_timesheets.resource_id WHERE month(user_timesheets.work_date)=". $month ." AND year(user_timesheets.work_date)=". $year ." AND user_timesheets.resource_id=" . $l['userid'] . " AND projects.bill='Non Billable' GROUP BY users.name";
			$stmtProduct = $conn->execute($non_billable_time_used);
			$non_billable_time_used_data = $stmtProduct->fetchAll('assoc');
			// dd($billable_time_used_data[0]['time_used']);
			$p['non_billable_time_used'] = count($non_billable_time_used_data) > 0 ? $non_billable_time_used_data[0]['time_used'] : 0;
			// $p['non_billable_time_slot'] = $non_billable_time_used_data[0]['time_slot'];
			// $p['non_billable_time_slot'] = count($non_billable_time_used_data) > 0 ? $non_billable_time_used_data[0]['time_slot'] : 0;
			$p['billable_time_used'] = count($billable_time_used_data) > 0 ? $billable_time_used_data[0]['time_used'] : 0;
			// $p['billable_time_slot'] = count($billable_time_used_data) > 0 ? $billable_time_used_data[0]['time_slot'] : 0;
			// $p['billable_time_used'] = $billable_time_used_data[0]['time_used'];
			// $p['billable_time_slot'] = $billable_time_used_data[0]['time_slot'];
			// $p['time_slot'] = $mlist;

			$time_slot="SELECT sum(project_allocations.time_slot) as time_slot,projects.project_name,project_allocations.resource_id FROM `project_allocations` LEFT JOIN project_milestones ON project_milestones.id=project_allocations.milestone_id LEFT JOIN projects ON projects.id=project_milestones.project_id WHERE month(project_milestones.due_date)=". $month ." AND year(project_milestones.due_date)=". $year ." AND project_allocations.resource_id=" . $l['userid'] . " AND projects.bill='Billable' GROUP by project_allocations.resource_id,projects.project_name";
			$stmtProduct = $conn->execute($time_slot);
			$time_slot = $stmtProduct->fetchAll('assoc');
			$p['time_slot']=$time_slot;

			$non_billable_time_slot="SELECT sum(project_allocations.time_slot) as time_slot,projects.project_name,project_allocations.resource_id FROM `project_allocations` LEFT JOIN project_milestones ON project_milestones.id=project_allocations.milestone_id LEFT JOIN projects ON projects.id=project_milestones.project_id WHERE month(project_milestones.due_date)=". $month ." AND year(project_milestones.due_date)=". $year ." AND project_allocations.resource_id=" . $l['userid'] . " AND projects.bill='Non Billable' GROUP by project_allocations.resource_id,projects.project_name";
			$stmtProduct = $conn->execute($non_billable_time_slot);
			$non_billable_time_slot = $stmtProduct->fetchAll('assoc');
			// $p['non_billable_time_slot']=$non_billable_time_slot;
			$p['non_billable_time_slot'] = count($non_billable_time_slot) > 0 ? $non_billable_time_slot : 0;

			$projects[] = $p;
		}
		// dd($projects);

		$this->set(compact('projects','month','year'));

	}

	public function timesheetReportData($id,$month,$year) {
		// $year=date('Y');
	$this->Authorization->skipAuthorization();
	// $this->viewBuilder()->setLayout('default_new');
	$conn = ConnectionManager::get('default');
	$session = new \Cake\Http\Session();
	$userSession = $session->read('data');
	$user_id = $id;

	$query = "
	SELECT user_timesheets.id,user_timesheets.milestone_id,user_timesheets.resource_id,sum(user_timesheets.time_used) as time_used,user_timesheets.work_date, project_milestones.title, project_milestones.project_id,projects.project_name,projects.bill,users.name as username,users.id as userid FROM `user_timesheets` LEFT JOIN project_milestones ON user_timesheets.milestone_id=project_milestones.id LEFT JOIN projects ON projects.id=project_milestones.project_id LEFT JOIN users ON users.id=user_timesheets.resource_id WHERE month(work_date)=" .$month. " AND year(work_date)=". $year ." AND user_timesheets.resource_id=" . $user_id . "
	 GROUP BY 
	 projects.project_name, users.name , user_timesheets.resource_id,user_timesheets.id,user_timesheets.milestone_id, user_timesheets.work_date, project_milestones.title, project_milestones.project_id,projects.bill,users.id
	 ";
	// dd($query);
	$stmtProduct = $conn->execute($query);
	$list = $stmtProduct->fetchAll('assoc');
	$projects = array();
	// dd($list);

	foreach ($list as $l) {

		$p['project_id'] = $l['project_id'];
		// dd($p['project_id']);
		$p['project_name'] = $l['project_name'];
		$p['bill'] = $l['bill'];
		$p['userid'] = $l['userid'];
		$p['username'] = $l['username'];
		$p['time_used'] = $l['time_used'];
		// $p['miles'] = array();
		
			// $query = "SELECT id,title,project_id FROM project_milestones p WHERE p.id IN (" . $l['milestone_id'] . ") AND p.deleted=0 AND status != 'Completed' AND month(p.due_date)=". $month . " AND year(p.due_date)=".$year;
		$query = "SELECT (select sum(project_allocations.time_slot) from project_allocations where project_allocations.milestone_id in (select id from project_milestones where project_milestones.project_id = ".$l['project_id']." AND month(project_milestones.due_date)=". $month . " AND year(project_milestones.due_date)=". $year . " ) AND project_allocations.resource_id=".$l['userid']." ) as time_slot";
		$stmtProduct = $conn->execute($query);
		$mlist = $stmtProduct->fetchAll('assoc');
		$p['time_slot'] = $mlist;
		
		$projects[] = $p;
	}
	// dd($projects);

	$this->set(compact('projects'));
	}

	public function allotment($id, $val, $day)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		$conn = ConnectionManager::get('default');

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];

		$query = "SELECT id FROM user_timesheets WHERE milestone_id=" . $id . " AND resource_id=" . $user_id . " AND work_date='" . $day . "'";
		$stmtProduct = $conn->execute($query);
		$mlist = $stmtProduct->fetchAll('assoc');

		$no_attend = (int)$val;

		if (count($mlist) > 0 && $no_attend >= 0 && $no_attend <= 24) {
			$query = "UPDATE user_timesheets SET time_used=" . $val . " WHERE id=" . $mlist[0]['id'];
			$stmtProduct = $conn->execute($query);
		} else {
			$query = "INSERT INTO user_timesheets(milestone_id,resource_id,time_used,work_date) VALUES(" . $id . "," . $user_id . "," . $val . ",'" . $day . "')";
			$stmtProduct = $conn->execute($query);
		}
	}


	public function notespop()
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');

		$id = $userSession['id'];
		$notes = $this->request->getData('notes');
		$milestoneid = $this->request->getData('milestoneid');
		$timesheetday = $this->request->getData('timesheetday');

		// $this->loadModel('UserTimesheets');
		$this->UserTimesheets = $this->fetchTable('UserTimesheets');
		$allotmentnotes = $this->UserTimesheets->find('all')->where(['milestone_id' => $milestoneid, 'resource_id' => $id, 'work_date' => $timesheetday])->first();

		if (!empty($allotmentnotes)) {
			$allotmentnotes->notes = $notes;
			if ($allotmentnotes->time_used <= 0) {
				$allotmentnotes->time_used = 0;
			}
		} else {
			$allotmentnotes = $this->UserTimesheets->newEmptyEntity();
			$allotmentnotes->milestone_id = $milestoneid;
			$allotmentnotes->resource_id = $id;
			$allotmentnotes->time_used = 0;
			$allotmentnotes->notes = $notes;
			$allotmentnotes->work_date = $timesheetday;
		}

		if ($this->UserTimesheets->save($allotmentnotes)) {
			$data = [];
			$data['status']       = 'success';
			$data['note']         = $notes;
			$data['milestone_id'] = $milestoneid;
			$data['time_used']    = $allotmentnotes->time_used == '' ? 0 : $allotmentnotes->time_used;
			$data['work_date']    = $timesheetday;
		} else {
			$data = [];
			$data['status'] = 'fail';
		}
		echo json_encode($data);
	}

	public function prevWeek($date)
	{
		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];

		$query = "SELECT p.id,p.project_name,p.milestone_id,u.client_name FROM projects p JOIN users u ON p.client_id=u.id  WHERE p.deleted=1 AND p.status != 'Completed' AND p.active = 1 AND FIND_IN_SET(" . $user_id . ",resources)!=0";
		$stmtProduct = $conn->execute($query);
		$list = $stmtProduct->fetchAll('assoc');
		$projects = array();

		$weekStart = (date('N', strtotime($date)) == 1) 
			? date('Y-m-d', strtotime($date)) 
			: date('Y-m-d', strtotime('last monday', strtotime($date)));

		$weekEnd = date('Y-m-d', strtotime($weekStart . ' +5 days'));

		
		// Extract month numbers
		$startMonth = date('m', strtotime($weekStart));
		$endMonth = date('m', strtotime($weekEnd));

		$startYear = date('Y', strtotime($weekStart));
		$endYear = date('Y', strtotime($weekEnd));

		foreach ($list as $l) {

			$p['id'] = $l['id'];
			$p['project_name'] = $l['project_name'];
			$p['client_name'] = $l['client_name'];
			$p['miles'] = array();
			if ($l['milestone_id']) {
				// $query = "SELECT id,title FROM project_milestones p WHERE p.deleted = 0 AND p.id IN (" . $l['milestone_id'] . ")";

				// $query = "
				// 	SELECT p.id, p.title, p.due_date, pr.client_id
				// 	FROM project_milestones p
				// 	JOIN projects pr ON p.project_id = pr.id
				// 	WHERE p.id IN (" . $l['milestone_id'] . ")
				// 	AND p.deleted = 0
				// 	AND (
				// 		(
				// 			pr.client_id != '144'
				// 			AND (
				// 				(MONTH(p.due_date) = " . $startMonth . " AND YEAR(p.due_date) = " . $startYear . ")
				// 				OR
				// 				(MONTH(p.due_date) = " . $endMonth . " AND YEAR(p.due_date) = " . $endYear . ")
				// 			)
				// 		)
				// 		OR pr.client_id = '144'
				// 	)
				// ";

				$query = "
				SELECT 
					p.id, 
					p.title, 
					p.due_date, 
					pr.client_id
				FROM 
					project_milestones p
				JOIN 
					projects pr ON p.project_id = pr.id
				WHERE 
					p.id IN (" . $l['milestone_id'] . ")
					AND p.deleted = 0
					AND (
						(
							pr.client_id != '144'
							AND (
								(MONTH(p.due_date) = " . $startMonth . " AND YEAR(p.due_date) = " . $startYear . ")
								OR
								(MONTH(p.due_date) = " . $endMonth . " AND YEAR(p.due_date) = " . $endYear . ")
							)
						)
						OR (
							pr.client_id = '144'
							AND (
								YEAR(p.due_date) = " . $startYear . "
								OR YEAR(p.due_date) = " . $endYear . "
							)
						)
					)
			";
				$stmtProduct = $conn->execute($query);
				$mlist = $stmtProduct->fetchAll('assoc');

				foreach ($mlist as $m) {
					$data['id'] = $m['id'];
					$data['title'] = $m['title'];

					$query = "SELECT time_slot FROM project_allocations WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id;
					$stmtProduct = $conn->execute($query);
					$allot = $stmtProduct->fetchAll('assoc');
					if (count($allot) > 0)
						$data['alot'] = $allot[0]['time_slot'];
					else
						$data['alot'] = "-";


					$query = "SELECT IFNULL(SUM(time_used),0) as time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id;
					$stmtProduct = $conn->execute($query);
					$allot = $stmtProduct->fetchAll('assoc');
					if (count($allot) > 0)
						$data['used'] = $allot[0]['time_used'];
					else
						$data['used'] = "-";



					$data['mtime'] = $data['tutime'] = $data['wtime'] = $data['thtime'] = $data['ftime'] = $data['stime'] = "-";
					$data['mnotes'] = $data['tunotes'] = $data['wnotes'] = $data['thnotes'] = $data['fnotes'] = $data['snotes'] = "";


					$data['monday'] = date('Y-m-d', strtotime('-5 days', strtotime($date)));

					$data['tuesday'] = date('Y-m-d', strtotime('-4 days', strtotime($date)));

					$data['wednesday'] = date('Y-m-d', strtotime('-3 days', strtotime($date)));

					$data['thursday'] = date('Y-m-d', strtotime('-2 days', strtotime($date)));

					$data['friday'] = date('Y-m-d', strtotime('-1 days', strtotime($date)));

					$data['saturday'] = $date;

					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['monday'] . "'";
					$stmtProduct = $conn->execute($query);
					$monday = $stmtProduct->fetchAll('assoc');
					if (count($monday) > 0) {
						$data['mtime'] = $monday[0]['time_used'];
						$data['mnotes'] = $monday[0]['notes'];
					}
					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['tuesday'] . "'";
					$stmtProduct = $conn->execute($query);
					$tuesday = $stmtProduct->fetchAll('assoc');
					if (count($tuesday) > 0) {
						$data['tutime'] = $tuesday[0]['time_used'];
						$data['tunotes'] = $tuesday[0]['notes'];
					}
					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['wednesday'] . "'";
					$stmtProduct = $conn->execute($query);
					$wednesday = $stmtProduct->fetchAll('assoc');
					if (count($wednesday) > 0) {
						$data['wtime'] = $wednesday[0]['time_used'];
						$data['wnotes'] = $wednesday[0]['notes'];
					}
					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['thursday'] . "'";
					$stmtProduct = $conn->execute($query);
					$thursday = $stmtProduct->fetchAll('assoc');
					if (count($thursday) > 0) {
						$data['thtime'] = $thursday[0]['time_used'];
						$data['thnotes'] = $thursday[0]['notes'];
					}
					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['friday'] . "'";
					$stmtProduct = $conn->execute($query);
					$friday = $stmtProduct->fetchAll('assoc');
					if (count($friday) > 0) {
						$data['ftime'] = $friday[0]['time_used'];
						$data['fnotes'] = $friday[0]['notes'];
					}
					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['saturday'] . "'";
					$stmtProduct = $conn->execute($query);
					$saturday = $stmtProduct->fetchAll('assoc');
					if (count($saturday) > 0) {
						$data['stime'] = $saturday[0]['time_used'];
						$data['snotes'] = $saturday[0]['notes'];
					}

					$p['miles'][] = $data;
				}
			}
			$projects[] = $p;
		}

		$data = array();
		$data['monday'] = date('D d M', strtotime('-5 days', strtotime($date)));

		$data['tuesday'] = date('D d M', strtotime('-4 days', strtotime($date)));

		$data['wednesday'] = date('D d M', strtotime('-3 days', strtotime($date)));

		$data['thursday'] = date('D d M', strtotime('-2 days', strtotime($date)));

		$data['friday'] = date('D d M', strtotime('-1 days', strtotime($date)));

		$data['saturday'] = date('D d M', strtotime($date));

		$pdate = date('Y-m-d', strtotime('last saturday', strtotime($date)));
		$ndate = date('Y-m-d', strtotime('next monday', strtotime($date)));

		// echo "<pre>";
		// print_r($projects);
		// die;
		$this->set(compact('projects', 'pdate', 'ndate', 'data'));
	}


	public function nextWeek($date)
	{
		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];

		$query = "SELECT p.id,p.project_name,p.milestone_id,u.client_name FROM projects p JOIN users u ON p.client_id=u.id WHERE p.deleted=1 AND p.status != 'Completed' AND p.active=1 AND FIND_IN_SET(" . $user_id . ",resources)!=0";
		$stmtProduct = $conn->execute($query);
		$list = $stmtProduct->fetchAll('assoc');
		$projects = array();
		$weekStart = (date('N', strtotime($date)) == 1) 
		? date('Y-m-d', strtotime($date)) 
		: date('Y-m-d', strtotime('last monday', strtotime($date)));

		$weekEnd = date('Y-m-d', strtotime($weekStart . ' +5 days'));

		// Extract month numbers
		$startMonth = date('m', strtotime($weekStart));
		$endMonth = date('m', strtotime($weekEnd));

		$startYear = date('Y', strtotime($weekStart));
		$endYear = date('Y', strtotime($weekEnd));

		foreach ($list as $l) {
			$p['id'] = $l['id'];
			$p['project_name'] = $l['project_name'];
			$p['client_name'] = $l['client_name'];
			$p['miles'] = array();
			if ($l['milestone_id']) {
				// $query = "SELECT id,title FROM project_milestones p WHERE p.deleted = 0 AND p.id IN (" . $l['milestone_id'] . ")";
				// $query = "
				// 	SELECT p.id, p.title, p.due_date, pr.client_id
				// 	FROM project_milestones p
				// 	JOIN projects pr ON p.project_id = pr.id
				// 	WHERE p.id IN (" . $l['milestone_id'] . ")
				// 	AND p.deleted = 0
				// 	AND (
				// 		(MONTH(p.due_date) = " . $startMonth . " AND YEAR(p.due_date) = " . $startYear . ")
				// 		OR
				// 		(MONTH(p.due_date) = " . $endMonth . " AND YEAR(p.due_date) = " . $endYear . ")
				// 	)
				// ";
				$query = "
					SELECT 
						p.id, 
						p.title, 
						p.due_date, 
						pr.client_id
					FROM 
						project_milestones p
					JOIN 
						projects pr ON p.project_id = pr.id
					WHERE 
						p.id IN (" . $l['milestone_id'] . ")
						AND p.deleted = 0
						AND (
							(
								pr.client_id != '144'
								AND (
									(MONTH(p.due_date) = " . $startMonth . " AND YEAR(p.due_date) = " . $startYear . ")
									OR
									(MONTH(p.due_date) = " . $endMonth . " AND YEAR(p.due_date) = " . $endYear . ")
								)
							)
							OR (
								pr.client_id = '144'
								AND (
									YEAR(p.due_date) = " . $startYear . "
									OR YEAR(p.due_date) = " . $endYear . "
								)
							)
						)
				";

				$stmtProduct = $conn->execute($query);
				$mlist = $stmtProduct->fetchAll('assoc');

				foreach ($mlist as $m) {
					$data['id'] = $m['id'];
					$data['title'] = $m['title'];

					$query = "SELECT time_slot FROM project_allocations WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id;
					$stmtProduct = $conn->execute($query);
					$allot = $stmtProduct->fetchAll('assoc');
					if (count($allot) > 0)
						$data['alot'] = $allot[0]['time_slot'];
					else
						$data['alot'] = "-";


					$query = "SELECT IFNULL(SUM(time_used),0) as time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id;
					$stmtProduct = $conn->execute($query);
					$allot = $stmtProduct->fetchAll('assoc');
					if (count($allot) > 0)
						$data['used'] = $allot[0]['time_used'];
					else
						$data['used'] = "-";



					$data['mtime'] = $data['tutime'] = $data['wtime'] = $data['thtime'] = $data['ftime'] = $data['stime'] = "-";
					$data['mnotes'] = $data['tunotes'] = $data['wnotes'] = $data['thnotes'] = $data['fnotes'] = $data['snotes'] = "";


					$data['monday'] = $date;

					$data['tuesday'] = date('Y-m-d', strtotime('+1 days', strtotime($date)));

					$data['wednesday'] = date('Y-m-d', strtotime('+2 days', strtotime($date)));

					$data['thursday'] = date('Y-m-d', strtotime('+3 days', strtotime($date)));

					$data['friday'] = date('Y-m-d', strtotime('+4 days', strtotime($date)));

					$data['saturday'] = date('Y-m-d', strtotime('+5 days', strtotime($date)));;

					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['monday'] . "'";
					$stmtProduct = $conn->execute($query);
					$monday = $stmtProduct->fetchAll('assoc');
					if (count($monday) > 0){
						$data['mtime'] = $monday[0]['time_used'];
						$data['mnotes'] = $monday[0]['notes'];
					}

					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['tuesday'] . "'";
					$stmtProduct = $conn->execute($query);
					$tuesday = $stmtProduct->fetchAll('assoc');
					if (count($tuesday) > 0) {
						$data['tutime'] = $tuesday[0]['time_used'];
						$data['tunotes'] = $tuesday[0]['notes'];
					}

					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['wednesday'] . "'";
					$stmtProduct = $conn->execute($query);
					$wednesday = $stmtProduct->fetchAll('assoc');
					if (count($wednesday) > 0) {
						$data['wtime'] = $wednesday[0]['time_used'];
						$data['wnotes'] = $wednesday[0]['notes'];
					}

					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['thursday'] . "'";
					$stmtProduct = $conn->execute($query);
					$thursday = $stmtProduct->fetchAll('assoc');
					if (count($thursday) > 0) {
						$data['thtime'] = $thursday[0]['time_used'];
						$data['thnotes'] = $thursday[0]['notes'];
					}

					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['friday'] . "'";
					$stmtProduct = $conn->execute($query);
					$friday = $stmtProduct->fetchAll('assoc');
					if (count($friday) > 0) {
						$data['ftime'] = $friday[0]['time_used'];
						$data['fnotes'] = $friday[0]['notes'];
					}

					$query = "SELECT notes,time_used FROM user_timesheets WHERE milestone_id=" . $m['id'] . " AND resource_id=" . $user_id . " AND work_date='" . $data['saturday'] . "'";
					$stmtProduct = $conn->execute($query);
					$saturday = $stmtProduct->fetchAll('assoc');
					if (count($saturday) > 0) {
						$data['stime'] = $saturday[0]['time_used'];
						$data['snotes'] = $saturday[0]['notes'];
					}


					$p['miles'][] = $data;
				}
			}
			$projects[] = $p;
		}

		$data = array();
		$data['monday'] = date('D d M', strtotime($date));

		$data['tuesday'] = date('D d M', strtotime('+1 days', strtotime($date)));

		$data['wednesday'] = date('D d M', strtotime('+2 days', strtotime($date)));

		$data['thursday'] = date('D d M', strtotime('+3 days', strtotime($date)));

		$data['friday'] = date('D d M', strtotime('+4 days', strtotime($date)));

		$data['saturday'] = date('D d M', strtotime('+5 days', strtotime($date)));

		$pdate = date('Y-m-d', strtotime('last saturday', strtotime($date)));
		$ndate = date('Y-m-d', strtotime('next monday', strtotime($date)));

		// pr($projects);
		// die;

		$this->set(compact('projects', 'pdate', 'ndate', 'data'));
	}


	// public function myteam($value = null, $date = null)
	// {

	// 	$this->Authorization->skipAuthorization();
	// 	$this->viewBuilder()->setLayout('default_new');
	// 	$conn = ConnectionManager::get('default');

	// 	$session = new \Cake\Http\Session();

	// 	$userSession = $session->read('user_data');
	// 	$userSes = $session->read('data');
	// 	$user_id = $userSession['id'];
	// 	// validation for valid user
	// 	$roleArray = $userSes['role_name'];
	// 	$validList = [4];
	// 	$this->routeValidation($roleArray,$validList);

	// 	$role_type_glob = $userSession['role_type'];

	// 	// echo "<pre>";
	// 	// print_r($userSession);
	// 	// die;	


	// 	// $this->loadModel("MyTeams");
	// 	// $this->loadModel("MyTeamResources");
	// 	$this->MyTeams = $this->fetchTable("MyTeams");
	// 	$this->MyTeamResources = $this->fetchTable("MyTeamResources");




	// 	if ($role_type_glob == 2) {

	// 		$my_team_data = $this->MyTeams->find("all")->contain(["MyTeamResources", "MyTeamResources.Resource", "TechLeadData", "ProjectManagerData"])->toArray();
	// 	} else {

	// 		$my_team_data = $this->MyTeams->find("all", [
	// 			"contain" => ["MyTeamResources", "MyTeamResources.Resource", "TechLeadData", "ProjectManagerData"],
	// 			"conditions" => [
	// 				"OR" => [
	// 					"tech_lead" => $user_id,
	// 					"project_manager" => $user_id
	// 				]
	// 			]

	// 		])->toArray();
	// 	}


	// 	$assigned_user_id = $this->MyTeamResources->find("list", [
	// 		"valueField" => "resid"
	// 	])->toArray();


	// 	$my_team_res_ids = $this->MyTeamResources->find("list", [
	// 		"contain" => ["MyTeams"],
	// 		"conditions" => [
	// 			"OR" => [
	// 				"tech_lead" => $user_id,
	// 				"project_manager" => $user_id
	// 			]
	// 		],
	// 		"valueField" => "resid",
	// 		"select" => ["resid"]
	// 	])->toArray();

	// 	// echo "<pre>";
	// 	// print_r($my_team_res_ids);

	// 	// die;




	// 	$project_manager = $this->Users->find("list", [
	// 		"conditions" => ["status" => 1, "role" => 3, "deleted" => 1],
	// 		"select" => ["id", "name"],
	// 		"order" => ["name" => "ASC"],
	// 		"valueField" => "name",
	// 		"keyField" => "id",
	// 		"order" => [
	// 			"name" => "ASC"
	// 		]
	// 	])->toArray();


	// 	$res_arr_cond = [
	// 		"status" => 1,
	// 		"role" => 3,
	// 		"deleted" => 1
	// 	];

	// 	if (!empty($assigned_user_id)) {

	// 		$res_arr_cond["ID NOT IN"] = $assigned_user_id;
	// 	}

	// 	$resource_arr = $this->Users->find("list", [
	// 		"valueField" => "name",
	// 		"keyField" => "id",
	// 		'conditions' => $res_arr_cond,
	// 		"select" => [
	// 			"id", "name"
	// 		],
	// 		"order" => [
	// 			"name" => "ASC"
	// 		]
	// 	])->toArray();

	// 	$edit_or_cond = [
	// 		"status" => 1,
	// 		"role" => 3,
	// 		"deleted" => 1
	// 	];


	// 	if ($role_type_glob != 2) {

	// 		if (!empty($my_team_res_ids)) {

	// 			$edit_or_cond["ID IN"] = $my_team_res_ids;
	// 		}

	// 		if (!empty($assigned_user_id)) {


	// 			$edit_or_cond["ID NOT IN"] = $assigned_user_id;
	// 		}
	// 	}

	// 	$edit_res_arr = $this->Users->find("list", [
	// 		"valueField" => "name",
	// 		"keyField" => "id",
	// 		'conditions' => [
	// 			"status" => 1,
	// 			"role" => 3,
	// 			"deleted" => 1,
	// 			"or" => $edit_or_cond
	// 		],
	// 		"select" => [
	// 			"id", "name"
	// 		],
	// 		"order" => [
	// 			"name" => "ASC"
	// 		]
	// 	])->toArray();




	// 	if ($date == null) {

	// 		$n = date('N') - 1;
	// 		$data['first'] = (date('N') == 1) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
	// 		$n = date('N') - 2;
	// 		$data['second'] = (date('N') == 2) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
	// 		$n = date('N') - 3;
	// 		$data['third'] = (date('N') == 3) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
	// 		$n = date('N') - 4;
	// 		$data['fourth'] = (date('N') == 4) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
	// 		$n = date('N') - 5;
	// 		$data['fifth'] = (date('N') == 5) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
	// 		$n = date('N') - 6;
	// 		$data['sixth'] = (date('N') == 6) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));


	// 		$pdate = date('Y-m-d', strtotime('last saturday'));
	// 		$ndate = date('Y-m-d', strtotime('next monday'));
	// 	} else if ($date != null && $value != null) {

	// 		if ($value == "prev") {


	// 			$data['first'] = date('Y-m-d', strtotime('-5 days', strtotime($date)));

	// 			$data['second'] = date('Y-m-d', strtotime('-4 days', strtotime($date)));

	// 			$data['third'] = date('Y-m-d', strtotime('-3 days', strtotime($date)));

	// 			$data['fourth'] = date('Y-m-d', strtotime('-2 days', strtotime($date)));

	// 			$data['fifth'] = date('Y-m-d', strtotime('-1 days', strtotime($date)));

	// 			$data['sixth'] = $date;
	// 		} else if ($value == "next") {

	// 			$data['first'] = $date;

	// 			$data['second'] = date('Y-m-d', strtotime('+1 days', strtotime($date)));

	// 			$data['third'] = date('Y-m-d', strtotime('+2 days', strtotime($date)));

	// 			$data['fourth'] = date('Y-m-d', strtotime('+3 days', strtotime($date)));

	// 			$data['fifth'] = date('Y-m-d', strtotime('+4 days', strtotime($date)));

	// 			$data['sixth'] = date('Y-m-d', strtotime('+5 days', strtotime($date)));;
	// 		}


	// 		$pdate = date('Y-m-d', strtotime('last saturday', strtotime($date)));
	// 		$ndate = date('Y-m-d', strtotime('next monday', strtotime($date)));
	// 	}


	// 	$conditions_arr = [];

	// 	if ($userSession["role_type"] != 2) {

	// 		$conditions_arr = [

	// 			"OR" => [
	// 				"MyTeams.tech_lead" => $user_id,
	// 				"MyTeams.project_manager" => $user_id
	// 			]

	// 		];
	// 	}




	// 	$team_select_data = $this->MyTeams->find("list", [
	// 		"valueField" => "team_name",
	// 		"keyField" => "id",
	// 		"conditions" => $conditions_arr
	// 	])->toArray();



	// 	$team_id_get = $this->MyTeams->find("list", [
	// 		"valueField" => "id",
	// 		"conditions" => $conditions_arr
	// 	])->toList();

	// 	$condition_array = [];

	// 	if (!empty($team_id_get)) {


	// 		$condition_array["id IN"] = $team_id_get;
	// 	}

	// 	$tech_lead_arr = $this->MyTeams->find("list", [
	// 		"valueField" => "tech_lead",
	// 		"conditions" => $condition_array
	// 	])->toList();

	// 	$project_manager_list = $this->MyTeams->find("list", [
	// 		"valueField" => "project_manager",
	// 		"conditions" => $condition_array
	// 	])->toList();



	// 	if (!empty($team_id_get)) {

	// 		$query = "SELECT users.* from users where (users.id IN (select resid from my_team_resources where  my_team_id IN (" . implode(',', $team_id_get) . ") ) OR users.id IN (" . implode(",", $tech_lead_arr) . ") OR users.id IN (" . implode(",", $project_manager_list) . ")) AND users.status=1 AND users.deleted=1 ";

	// 		$stmtUsers2 = $conn->execute($query);
	// 		$Manager_users = $stmtUsers2->fetchAll("assoc");
	// 	} else {

	// 		$Manager_users = [];
	// 	}



	// 	//       $query = "SELECT * from users where team =".$user_id;
	// 	// $stmtUsers = $conn->execute($query);
	// 	// $Manager_users = $stmtUsers->fetchAll("assoc");

	// 	$project_user = [];
	// 	foreach ($Manager_users as $key) {
	// 		$p['id'] = $key['id'];
	// 		$p['name'] = $key['name'];
	// 		$p["teamid"] = $key["teamid"];
	// 		$p["last_login"] = $key["last_login"];
	// 		$p['projects'] = array();

	// 		$query = "SELECT p.id,p.project_name,p.milestone_id,u.client_name FROM projects p JOIN users u ON p.client_id=u.id WHERE p.deleted=1 AND p.status != 'Completed' AND ((FIND_IN_SET(" . $key['id'] . ",resources)!=0) OR (project_manager_id=" . $key['id'] . ") OR (tech_lead_id=" . $key['id'] . ") OR (bd_id=" . $key['id'] . "))";
	// 		$stmtProduct = $conn->execute($query);
	// 		$list = $stmtProduct->fetchAll('assoc');
	// 		$project_info = array();
	// 		foreach ($list as $l) {
	// 			$project_info['id'] = $l['id'];
	// 			$project_info['project_name'] = $l['project_name'];
	// 			$project_info['client_name'] = $l['client_name'];
	// 			$project_info['miles'] = array();

	// 			if ($l['milestone_id']) {

	// 				$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['first'] . "' AND resource_id=" . $key['id'];
	// 				$stmtwork = $conn->execute($query);
	// 				$res = $stmtwork->fetchAll('assoc');
	// 				if (count($res) > 0) {
	// 					$project_info['miles']['first'] = $res[0]['work'];
	// 				} else {
	// 					$project_info['miles']['first'] = 0;
	// 				}


	// 				$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['second'] . "' AND resource_id=" . $key['id'];
	// 				$stmtwork = $conn->execute($query);
	// 				$res = $stmtwork->fetchAll('assoc');
	// 				if (count($res) > 0) {
	// 					$project_info['miles']['second'] = $res[0]['work'];
	// 				} else {
	// 					$project_info['miles']['second'] = 0;
	// 				}

	// 				$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['third'] . "' AND resource_id=" . $key['id'];
	// 				$stmtwork = $conn->execute($query);
	// 				$res = $stmtwork->fetchAll('assoc');
	// 				if (count($res) > 0) {
	// 					$project_info['miles']['third'] = $res[0]['work'];
	// 				} else {
	// 					$project_info['miles']['third'] = 0;
	// 				}

	// 				$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['fourth'] . "' AND resource_id=" . $key['id'];
	// 				$stmtwork = $conn->execute($query);
	// 				$res = $stmtwork->fetchAll('assoc');
	// 				if (count($res) > 0) {
	// 					$project_info['miles']['fourth'] = $res[0]['work'];
	// 				} else {
	// 					$project_info['miles']['fourth'] = 0;
	// 				}


	// 				$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['fifth'] . "' AND resource_id=" . $key['id'];
	// 				$stmtwork = $conn->execute($query);
	// 				$res = $stmtwork->fetchAll('assoc');
	// 				if (count($res) > 0) {
	// 					$project_info['miles']['fifth'] = $res[0]['work'];
	// 				} else {
	// 					$project_info['miles']['fifth'] = 0;
	// 				}

	// 				$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['sixth'] . "' AND resource_id=" . $key['id'];
	// 				$stmtwork = $conn->execute($query);
	// 				$res = $stmtwork->fetchAll('assoc');
	// 				if (count($res) > 0) {
	// 					$project_info['miles']['sixth'] = $res[0]['work'];
	// 				} else {
	// 					$project_info['miles']['sixth'] = 0;
	// 				}
	// 			}
	// 			$p['projects'][] = $project_info;
	// 		}

	// 		$project_user[] = $p;
	// 	}





	// 	$this->set(compact("project_manager", "my_team_data", "resource_arr", "edit_res_arr", 'pdate', 'ndate', "project_user", "data", "role_type_glob", "team_select_data"));
	// }

	public function myteam($value = null, $date = null)
	{
		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');

		$conn = ConnectionManager::get('default');

		$session = new \Cake\Http\Session();

		$userSession = $session->read('user_data');
		$userSes = $session->read('data');

		$user_id = $userSession['id'];

		// validation for valid user
		$roleArray = $userSes['role_name'];
		$validList = [4];
		$this->routeValidation($roleArray, $validList);

		$role_type_glob = $userSession['role_type'];

		$this->MyTeams = $this->fetchTable("MyTeams");
		$this->MyTeamResources = $this->fetchTable("MyTeamResources");
		$this->Users = $this->fetchTable("Users");

		/*
		|--------------------------------------------------------------------------
		| MY TEAM DATA
		|--------------------------------------------------------------------------
		*/

		if ($role_type_glob == 2) {

			$my_team_data = $this->MyTeams->find()
				->contain([
					"MyTeamResources",
					"MyTeamResources.Resource",
					"TechLeadData",
					"ProjectManagerData"
				])
				->toArray();

		} else {

			$my_team_data = $this->MyTeams->find()
				->contain([
					"MyTeamResources",
					"MyTeamResources.Resource",
					"TechLeadData",
					"ProjectManagerData"
				])
				->where([
					"OR" => [
						"tech_lead" => $user_id,
						"project_manager" => $user_id
					]
				])
				->toArray();
		}

		/*
		|--------------------------------------------------------------------------
		| ASSIGNED USER IDS
		|--------------------------------------------------------------------------
		*/

		$assigned_user_id = $this->MyTeamResources->find()
			->select(['resid'])
			->where([
				'resid IS NOT' => null
			])
			->all()
			->extract('resid')
			->toArray();

		/*
		|--------------------------------------------------------------------------
		| MY TEAM RESOURCE IDS
		|--------------------------------------------------------------------------
		*/

		$my_team_res_ids = $this->MyTeamResources->find()
			->contain(["MyTeams"])
			->select(['resid'])
			->where([
				"OR" => [
					"tech_lead" => $user_id,
					"project_manager" => $user_id
				],
				'resid IS NOT' => null
			])
			->all()
			->extract('resid')
			->toArray();

		/*
		|--------------------------------------------------------------------------
		| PROJECT MANAGER LIST
		|--------------------------------------------------------------------------
		*/

		$project_manager = $this->Users->find('list', [
			'keyField' => 'id',
			'valueField' => 'name'
		])
		->where([
			"status" => 1,
			"role" => 3,
			"deleted" => 1
		])
		->order([
			"name" => "ASC"
		])
		->toArray();

		/*
		|--------------------------------------------------------------------------
		| RESOURCE ARRAY
		|--------------------------------------------------------------------------
		*/

		$res_arr_cond = [
			"status" => 1,
			"role" => 3,
			"deleted" => 1
		];

		if (!empty($assigned_user_id)) {
			$res_arr_cond["id NOT IN"] = $assigned_user_id;
		}

		$resource_arr = $this->Users->find('list', [
			'keyField' => 'id',
			'valueField' => 'name'
		])
		->where($res_arr_cond)
		->order([
			"name" => "ASC"
		])
		->toArray();

		/*
		|--------------------------------------------------------------------------
		| EDIT RESOURCE ARRAY
		|--------------------------------------------------------------------------
		*/

		$edit_or_cond = [];

		if (!empty($my_team_res_ids)) {
			$edit_or_cond[] = [
				"id IN" => $my_team_res_ids
			];
		}

		if (!empty($assigned_user_id)) {
			$edit_or_cond[] = [
				"id NOT IN" => $assigned_user_id
			];
		}

		$edit_res_conditions = [
			"status" => 1,
			"role" => 3,
			"deleted" => 1
		];

		if (!empty($edit_or_cond)) {
			$edit_res_conditions['OR'] = $edit_or_cond;
		}

		$edit_res_arr = $this->Users->find('list', [
			'keyField' => 'id',
			'valueField' => 'name'
		])
		->where($edit_res_conditions)
		->order([
			"name" => "ASC"
		])
		->toArray();

		/*
		|--------------------------------------------------------------------------
		| DATE SECTION
		|--------------------------------------------------------------------------
		*/

		if ($date == null) {

			$n = date('N') - 1;
			$data['first'] = (date('N') == 1)
				? date('Y-m-d')
				: date('Y-m-d', strtotime('-' . $n . 'days'));

			$n = date('N') - 2;
			$data['second'] = (date('N') == 2)
				? date('Y-m-d')
				: date('Y-m-d', strtotime('-' . $n . 'days'));

			$n = date('N') - 3;
			$data['third'] = (date('N') == 3)
				? date('Y-m-d')
				: date('Y-m-d', strtotime('-' . $n . 'days'));

			$n = date('N') - 4;
			$data['fourth'] = (date('N') == 4)
				? date('Y-m-d')
				: date('Y-m-d', strtotime('-' . $n . 'days'));

			$n = date('N') - 5;
			$data['fifth'] = (date('N') == 5)
				? date('Y-m-d')
				: date('Y-m-d', strtotime('-' . $n . 'days'));

			$n = date('N') - 6;
			$data['sixth'] = (date('N') == 6)
				? date('Y-m-d')
				: date('Y-m-d', strtotime('-' . $n . 'days'));

			$pdate = date('Y-m-d', strtotime('last saturday'));
			$ndate = date('Y-m-d', strtotime('next monday'));

		} else {

			if ($value == "prev") {

				$data['first'] = date('Y-m-d', strtotime('-5 days', strtotime($date)));
				$data['second'] = date('Y-m-d', strtotime('-4 days', strtotime($date)));
				$data['third'] = date('Y-m-d', strtotime('-3 days', strtotime($date)));
				$data['fourth'] = date('Y-m-d', strtotime('-2 days', strtotime($date)));
				$data['fifth'] = date('Y-m-d', strtotime('-1 days', strtotime($date)));
				$data['sixth'] = $date;

			} else {

				$data['first'] = $date;
				$data['second'] = date('Y-m-d', strtotime('+1 days', strtotime($date)));
				$data['third'] = date('Y-m-d', strtotime('+2 days', strtotime($date)));
				$data['fourth'] = date('Y-m-d', strtotime('+3 days', strtotime($date)));
				$data['fifth'] = date('Y-m-d', strtotime('+4 days', strtotime($date)));
				$data['sixth'] = date('Y-m-d', strtotime('+5 days', strtotime($date)));
			}

			$pdate = date('Y-m-d', strtotime('last saturday', strtotime($date)));
			$ndate = date('Y-m-d', strtotime('next monday', strtotime($date)));
		}

		/*
		|--------------------------------------------------------------------------
		| TEAM CONDITIONS
		|--------------------------------------------------------------------------
		*/

		$conditions_arr = [];

		if ($userSession["role_type"] != 2) {

			$conditions_arr = [
				"OR" => [
					"MyTeams.tech_lead" => $user_id,
					"MyTeams.project_manager" => $user_id
				]
			];
		}

		/*
		|--------------------------------------------------------------------------
		| TEAM SELECT DATA
		|--------------------------------------------------------------------------
		*/

		$team_select_data = $this->MyTeams->find('list', [
			'keyField' => 'id',
			'valueField' => 'team_name'
		])
		->where($conditions_arr)
		->toArray();

		/*
		|--------------------------------------------------------------------------
		| TEAM IDS
		|--------------------------------------------------------------------------
		*/

		$team_id_get = $this->MyTeams->find()
			->select(['id'])
			->where($conditions_arr)
			->all()
			->extract('id')
			->filter(function ($id) {
				return !empty($id);
			})
			->toList();

		$condition_array = [];

		if (!empty($team_id_get)) {
			$condition_array["id IN"] = $team_id_get;
		}

		/*
		|--------------------------------------------------------------------------
		| TECH LEAD IDS
		|--------------------------------------------------------------------------
		*/

		$tech_lead_arr = $this->MyTeams->find()
			->select(['tech_lead'])
			->where($condition_array)
			->all()
			->extract('tech_lead')
			->filter(function ($id) {
				return !empty($id);
			})
			->toList();

		/*
		|--------------------------------------------------------------------------
		| PROJECT MANAGER IDS
		|--------------------------------------------------------------------------
		*/

		$project_manager_list = $this->MyTeams->find()
			->select(['project_manager'])
			->where($condition_array)
			->all()
			->extract('project_manager')
			->filter(function ($id) {
				return !empty($id);
			})
			->toList();

		/*
		|--------------------------------------------------------------------------
		| MANAGER USERS
		|--------------------------------------------------------------------------
		*/

		if (!empty($team_id_get)) {

			$query = "SELECT users.* 
					FROM users 
					WHERE (
							users.id IN (
								SELECT resid 
								FROM my_team_resources 
								WHERE my_team_id IN (" . implode(',', $team_id_get) . ")
							)";

			if (!empty($tech_lead_arr)) {
				$query .= " OR users.id IN (" . implode(",", $tech_lead_arr) . ")";
			}

			if (!empty($project_manager_list)) {
				$query .= " OR users.id IN (" . implode(",", $project_manager_list) . ")";
			}

			$query .= ") 
					AND users.status = 1 
					AND users.deleted = 1";

			$stmtUsers2 = $conn->execute($query);
			$Manager_users = $stmtUsers2->fetchAll('assoc');

		} else {

			$Manager_users = [];
		}

		/*
		|--------------------------------------------------------------------------
		| PROJECT USER DATA
		|--------------------------------------------------------------------------
		*/

		$project_user = [];

		foreach ($Manager_users as $key) {

			$p = [];

			$p['id'] = $key['id'];
			$p['name'] = $key['name'];
			$p['teamid'] = $key['teamid'] ?? '';
			$p['last_login'] = $key['last_login'] ?? '';
			$p['projects'] = [];

			$query = "SELECT 
						p.id,
						p.project_name,
						p.milestone_id,
						u.client_name
					FROM projects p
					JOIN users u ON p.client_id = u.id
					WHERE p.deleted = 1
					AND p.status != 'Completed'
					AND (
							FIND_IN_SET(" . $key['id'] . ", resources) != 0
							OR project_manager_id = " . $key['id'] . "
							OR tech_lead_id = " . $key['id'] . "
							OR bd_id = " . $key['id'] . "
					)";

			$stmtProduct = $conn->execute($query);
			$list = $stmtProduct->fetchAll('assoc');

			foreach ($list as $l) {

				$project_info = [];

				$project_info['id'] = $l['id'];
				$project_info['project_name'] = $l['project_name'];
				$project_info['client_name'] = $l['client_name'];
				$project_info['miles'] = [];

				$dates = [
					'first',
					'second',
					'third',
					'fourth',
					'fifth',
					'sixth'
				];

				foreach ($dates as $d) {

					$project_info['miles'][$d] = 0;

					if (!empty($l['milestone_id'])) {

						$query = "SELECT SUM(time_used) as work
								FROM user_timesheets
								WHERE milestone_id IN (" . $l['milestone_id'] . ")
								AND work_date = '" . $data[$d] . "'
								AND resource_id = " . $key['id'];

						$stmtwork = $conn->execute($query);
						$res = $stmtwork->fetch('assoc');

						$project_info['miles'][$d] = $res['work'] ?? 0;
					}
				}

				$p['projects'][] = $project_info;
			}

			$project_user[] = $p;
		}

		$this->set(compact(
			"project_manager",
			"my_team_data",
			"resource_arr",
			"edit_res_arr",
			"pdate",
			"ndate",
			"project_user",
			"data",
			"role_type_glob",
			"team_select_data"
		));
	}

	public function empNotesData($projectId,$userId,$date){
		$this->Authorization->skipAuthorization();
		// $this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$query="SELECT project_milestones.id, project_milestones.title, user_timesheets.id, user_timesheets.notes,user_timesheets.work_date FROM project_milestones JOIN user_timesheets ON user_timesheets.milestone_id = project_milestones.id JOIN users ON user_timesheets.resource_id = users.id WHERE project_milestones.project_id = ".$projectId." AND users.id = ".$userId." AND user_timesheets.work_date='$date'";
		$stmtProduct1 = $conn->execute($query);
		$data = $stmtProduct1->fetchAll('assoc');

		$this->set(compact('data'));
	}


	public function getteameditdata($t_id = null)
	{

		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		if ($this->request->is("ajax")) {




			$team_data = $this->MyTeams->find("all", [
				"contain" => ["MyTeamResources"],
				"conditions" => [
					"id" => $t_id
				]
			])->toArray();


			// $resource_str = "";

			// foreach($team_data[0]["my_team_resources"] as $resource){



			// }







			echo json_encode($team_data[0]);
		}
	}


	public function editteam()
	{

		$this->autoRender = false;
		$this->Authorization->skipAuthorization();


		if ($this->request->is("post")) {

			$request_data = $this->request->getData();

			$my_team_obj = $this->fetchTable("MyTeams");
			$my_team_res_obj = $this->fetchTable("MyTeamResources");


			// $my_team_obj = TableRegistry::get('MyTeams');
			// $my_team_res_obj = TableRegistry::get('MyTeamResources');

			$my_team_inter = $my_team_obj->findById($request_data['id'])->firstOrFail();



			$my_team_res_obj->deleteAll(["my_team_id" => $request_data["id"]]);



			foreach ($request_data["resources"] as $res) {

				// $temp["my_team_id"] = $my_team_entity->id;
				$temp["resid"] = $res;
				$request_data["my_team_resources"][] = $temp;
			}


			$my_team_obj->patchEntity($my_team_inter, $request_data);



			if ($my_team_obj->save($my_team_inter)) {


				return $this->redirect(["controller" => "Users", "action" => "myteam"]);
			}

			return $this->redirect(["controller" => "Users", "action" => "myteam"]);
		}
	}


	public function deleteteam($id)
	{

		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		$my_team_res_obj = TableRegistry::get('MyTeamResources');

		$my_team_res_obj->deleteAll(["my_team_id" => $id]);

		$entity = $this->MyTeams->get($id);
		$result = $this->MyTeams->delete($entity);
		return $this->redirect(["controller" => "Users", "action" => "myteam"]);
	}


	// public function addteam()
	// {

	// 	$this->Authorization->skipAuthorization();
	// 	$this->viewBuilder()->setLayout('default_new');

	// 	$session = new \Cake\Http\Session();

	// 	$this->loadModel("MyTeams");
		

	// 	$userSession = $session->read('data');
	// 	$user_id = $userSession['id'];


	// 	if ($this->request->is("post")) {

	// 		// echo "<pre>";
	// 		// print_r($this->request->getData());
	// 		// die;

	// 		$request_data = $this->request->getData();
	// 		$request_data["created_by"] = $user_id;



	// 		foreach ($request_data["resources"] as $res) {

	// 			// $temp["my_team_id"] = $my_team_entity->id;
	// 			$temp["resid"] = $res;
	// 			$request_data["my_team_resources"][] = $temp;
	// 		}

	// 		// echo "<pre>";
	// 		// print_r($request_data);
	// 		// die;



	// 		$myteam_table = $this->getTableLocator()->get('MyTeams');

	// 		// $my_team_res_tab = $this->getTableLocator()->get("MyTeamResources");


	// 		$my_team_entity = $myteam_table->newEntity($request_data, [
	// 			'associated' => ['MyTeamResources']
	// 		]);

	// 		// echo "<pre>";
	// 		// print_r($my_team_entity);
	// 		// die;


	// 		if ($myteam_table->save($my_team_entity, ["associated" => ["MyTeamResources"]])) {

	// 			// $new_res_arr = [];



	// 			// echo "<pre>";
	// 			// print_r($new_res_arr);
	// 			// die;

	// 			return $this->redirect(['action' => 'myteam', "controller" => "Users"]);
	// 		}

	// 		return $this->redirect(['action' => 'myteam', "controller" => "Users"]);
	// 	}
	// }

	public function addteam()
	{

		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');

		$session = new \Cake\Http\Session();

		$this->MyTeams = $this->fetchTable("MyTeams");

		$userSession = $session->read('data');
		$user_id = $userSession['id'];

		if ($this->request->is("post")) {

			$request_data = $this->request->getData();
			$request_data["created_by"] = $user_id;

			foreach ($request_data["resources"] as $res) {

				$temp["resid"] = $res;
				$request_data["my_team_resources"][] = $temp;
			}

			$my_team_entity = $this->MyTeams->newEntity($request_data, [
				'associated' => ['MyTeamResources']
			]);

			if ($this->MyTeams->save($my_team_entity, [
				"associated" => ["MyTeamResources"]
			])) {

				return $this->redirect([
					'action' => 'myteam',
					"controller" => "Users"
				]);
			}

			return $this->redirect([
				'action' => 'myteam',
				"controller" => "Users"
			]);
		}
	}


	//Uncomment this function in need

	//   public function myteam($value=null,$date=null)
	//   {

	//   	$this->Authorization->skipAuthorization();
	//   	$this->viewBuilder()->setLayout('default_new');
	//   	$conn = ConnectionManager::get('default');


	//   	$session = new \Cake\Http\Session();


	//       $userSession = $session->read('data');
	//       $user_id = $userSession['id'];

	//       if($date==null)
	//       {

	// 	$n = date('N')-1;
	//    	$data['first'] = (date('N')==1)?date('Y-m-d'):date('Y-m-d',strtotime('-'.$n.'days'));	
	//    	$n = date('N')-2;
	//    	$data['second'] = (date('N')==2)?date('Y-m-d'):date('Y-m-d',strtotime('-'.$n.'days'));
	//    	$n = date('N')-3;
	//    	 $data['third'] = (date('N')==3)?date('Y-m-d'):date('Y-m-d',strtotime('-'.$n.'days'));
	//    	$n = date('N')-4;
	//    	$data['fourth'] = (date('N')==4)?date('Y-m-d'):date('Y-m-d',strtotime('-'.$n.'days'));
	//    	$n = date('N')-5;
	//    	$data['fifth'] = (date('N')==5)?date('Y-m-d'):date('Y-m-d',strtotime('-'.$n.'days'));
	//    	$n = date('N')-6;
	//    	$data['sixth'] = (date('N')==6)?date('Y-m-d'):date('Y-m-d',strtotime('-'.$n.'days'));


	// 	$pdate = date('Y-m-d',strtotime('last saturday'));
	//    		$ndate = date('Y-m-d',strtotime('next monday'));


	// }
	// else if($date!=null&&$value!=null)
	// {

	// 	if ($value=="prev") 
	// 	{


	//   			$data['first'] = date('Y-m-d',strtotime('-5 days',strtotime($date)));	

	//     	$data['second'] = date('Y-m-d',strtotime('-4 days',strtotime($date)));

	//     	$data['third'] = date('Y-m-d',strtotime('-3 days',strtotime($date)));

	//     	$data['fourth'] = date('Y-m-d',strtotime('-2 days',strtotime($date)));

	//     	$data['fifth'] = date('Y-m-d',strtotime('-1 days',strtotime($date)));

	//     	$data['sixth'] = $date;

	// 	}
	// 	else if($value=="next")
	// 	{

	//    		$data['first'] = $date;	

	//     	$data['second'] = date('Y-m-d',strtotime('+1 days',strtotime($date)));

	//     	$data['third'] = date('Y-m-d',strtotime('+2 days',strtotime($date)));

	//     	$data['fourth'] = date('Y-m-d',strtotime('+3 days',strtotime($date)));

	//     	$data['fifth'] = date('Y-m-d',strtotime('+4 days',strtotime($date)));

	//     	$data['sixth'] = date('Y-m-d',strtotime('+5 days',strtotime($date)));;

	// 	}


	//    $pdate = date('Y-m-d',strtotime('last saturday',strtotime($date)));
	//   	   $ndate = date('Y-m-d',strtotime('next monday',strtotime($date)));


	//       }


	//       $query = "SELECT * from users where team =".$user_id;
	//       $stmtUsers = $conn->execute($query);
	//       $Manager_users = $stmtUsers->fetchAll("assoc");

	//       $project_user = [];
	//       foreach ($Manager_users as $key) 
	//       {
	//       	$p['id'] = $key['id'];
	//       	$p['name'] = $key['name'];
	//       	$p['projects'] = array();

	//       	 $query = "SELECT p.id,p.project_name,p.milestone_id,u.client_name FROM projects p JOIN users u ON p.client_id=u.id WHERE p.deleted=1 AND p.status != 'Completed' AND ((FIND_IN_SET(".$key['id'].",resources)!=0) OR (project_manager_id=".$key['id'].") OR (tech_lead_id=".$key['id'].") OR (bd_id=".$key['id']."))"; 
	//        $stmtProduct = $conn->execute($query);
	//        $list = $stmtProduct->fetchAll('assoc');
	//        $project_info = array();
	//         foreach($list as $l)
	//         {
	//         	$project_info['id'] = $l['id'];
	//         	$project_info['project_name'] = $l['project_name'];
	//         	$project_info['client_name'] = $l['client_name'];
	//         	$project_info['miles'] = array();

	//         	if($l['milestone_id'])
	// 	        {

	// 		        $query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (".$l['milestone_id'].") AND work_date='".$data['first']."' AND resource_id=".$key['id']; 
	// 		        $stmtwork = $conn->execute($query);
	// 		        $res = $stmtwork->fetchAll('assoc');
	// 		        if(count($res)>0){
	// 		        	$project_info['miles']['first'] = $res[0]['work'];
	// 		        }
	// 		        else{
	// 		        	$project_info['miles']['first'] = 0;
	// 		        }


	// 		        $query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (".$l['milestone_id'].") AND work_date='".$data['second']."' AND resource_id=".$key['id']; 
	// 		        $stmtwork = $conn->execute($query);
	// 		        $res = $stmtwork->fetchAll('assoc');
	// 		        if(count($res)>0){
	// 		        	$project_info['miles']['second'] = $res[0]['work'];
	// 		        }
	// 		        else{
	// 		        	$project_info['miles']['second'] = 0;
	// 		        }

	// 		        $query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (".$l['milestone_id'].") AND work_date='".$data['third']."' AND resource_id=".$key['id']; 
	// 		        $stmtwork = $conn->execute($query);
	// 		        $res = $stmtwork->fetchAll('assoc');
	// 		        if(count($res)>0){
	// 		        	$project_info['miles']['third'] = $res[0]['work'];
	// 		        }
	// 		        else{
	// 		        	$project_info['miles']['third'] = 0;
	// 		        }

	// 		        $query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (".$l['milestone_id'].") AND work_date='".$data['fourth']."' AND resource_id=".$key['id']; 
	// 		        $stmtwork = $conn->execute($query);
	// 		        $res = $stmtwork->fetchAll('assoc');
	// 		        if(count($res)>0){
	// 		        	$project_info['miles']['fourth'] = $res[0]['work'];
	// 		        }
	// 		        else{
	// 		        	$project_info['miles']['fourth'] = 0;
	// 		        }


	// 		        $query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (".$l['milestone_id'].") AND work_date='".$data['fifth']."' AND resource_id=".$key['id'];
	// 		        $stmtwork = $conn->execute($query);
	// 		        $res = $stmtwork->fetchAll('assoc');
	// 		        if(count($res)>0){
	// 		        	$project_info['miles']['fifth'] = $res[0]['work'];
	// 		        }
	// 		        else{
	// 		        	$project_info['miles']['fifth'] = 0;
	// 		        }

	// 		        $query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (".$l['milestone_id'].") AND work_date='".$data['sixth']."' AND resource_id=".$key['id']; 
	// 		        $stmtwork = $conn->execute($query);
	// 		        $res = $stmtwork->fetchAll('assoc');
	// 		        if(count($res)>0){
	// 		        	$project_info['miles']['sixth'] = $res[0]['work'];
	// 		        }
	// 		        else{
	// 		        	$project_info['miles']['sixth'] = 0;
	// 		        }



	// 		    }
	// 		    $p['projects'][] = $project_info;

	//        }

	//       	$project_user[] = $p;


	//       }

	//   // echo "<pre>";
	//   // print_r($project_user); exit;
	//   // print_r($pdate);



	//       $this->set(compact('pdate','ndate','project_user','data'));
	//       $this->set('_serialize',compact('pdate','ndate','project_user','data'));
	//   }


	public function getProjectDataById($id)
	{
		$conn = ConnectionManager::get('default');

		$query = "SELECT p.*,u.client_name as client_name FROM `projects` p LEFT JOIN users u ON p.client_id=u.id  where p.id=" . $id . "";

		$stmtproject = $conn->execute($query);
		$result = $stmtproject->fetch("assoc");


		return $result;
	}


	public function getDataSql($query)
	{

		$conn = ConnectionManager::get('default');
		$stmtUsers = $conn->execute($query);
		$res = $stmtUsers->fetch("assoc");


		return $res;
	}


	public function getproject_search_query($project_id_search)
	{

		$str = " AND aps.project_id=" . $project_id_search;

		return $str;
	}

	public function getClient_search_query($client_id_search)
	{
		$str = " AND cl.id=" . $client_id_search;
		return $str;
	}

	public function getDate_search_query($date_filter_search, $from_date_search, $to_date_search)
	{

		if ($date_filter_search == "today") {

			$search_date = date("Y-m-d");

			$str = " AND (aps.due_date='" . $search_date . "' OR aps.extend_days='" . $search_date . "')";
		} else if ($date_filter_search == "tomorrow") {
			$current_date = date("Y-m-d");
			$search_date =  date('Y-m-d', strtotime('+1 day', strtotime($current_date)));

			$str = " AND (aps.due_date='" . $search_date . "' OR aps.extend_days='" . $search_date . "')";
		} else if ($date_filter_search == "past") {

			$search_date = date('Y-m-d');

			$str = " AND (aps.due_date<'" . $search_date . "' OR aps.extend_days<'" . $search_date . "')";
		} else if ($date_filter_search == "custom") {

			$str = " AND ((aps.due_date>='" . $from_date_search . "' AND aps.due_date<='" . $to_date_search . "') OR (aps.extend_days>='" . $from_date_search . "' AND aps.extend_days<='" . $to_date_search . "'))";
		}

		return $str;
	}


	public function mytask($project_id_search = "all", $client_id_search = "all", $date_filter_search = "default", $from_date_search = null, $to_date_search = null)
	{

		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');


		$session = new \Cake\Http\Session();


		$userSession = $session->read('data');
		$user_id = $userSession['id'];


		// $tasks = $this->AssigendProjectTasks->find('all',['contain'=>['User_assigned','User_assigned_by']]);

		$column_list = "aps.`id`,aps.`completed`, aps.`completed_date`, aps.`status`, aps.`approved`, aps.`task_name`, aps.`description`, aps.`due_date`, aps.`created_at`, aps.`modified_at`, aps.`project_id`, aps.`extend_days`, aps.`extend_count`";

		$this->Projects = $this->fetchTable("Projects");
		$projects_data =  $this->Projects
			->find("all")
			->where(['active' => 1])
			->contain(["Client"])
			->order(['project_name' => 'ASC'])
			->toArray();



		$add_task_project  = $this->Projects
			->find("all")
			->where(['active' => 1])
			->contain(["Client"])
			->order(['project_name' => 'ASC'])
			->toArray();



		array_unshift($projects_data, ["id" => "all", "project_name" => "All", "client" => ["client_name" => ""]]);


		// <option value="all">All</option>

		// echo "<pre>";
		// var_dump($projects_data);
		// die;



		$client_data = $this->Users->find("all")
			->where(["role" => 2])
			->order(["client_name" => "ASC"])->toArray();



		array_unshift($client_data, ["id" => "all", "client_name" => "All"]);


		$date_data = [
			[
				"id" => "default",
				"name" => "All Date"
			],
			[
				"id" => "today",
				"name" => "Today"

			],
			[
				"id" => "tomorrow",
				"name" => "Tomorrow"
			],
			[
				"id" => "past",
				"name" => "Past Dues"
			],
			[
				"id" => "custom",
				"name" => "Custom"
			]


		];




		//mytask

		if ($user_id == 37) {



			$query = "SELECT " . $column_list . ",cl.client_name as client_name,cl.id as client_id,aby.id as assigned_by_id,aby.name as assigned_by,ast.id as assigned_to_id,ast.name as assigend_to,p.project_name as project_name FROM assigend_project_tasks aps JOIN projects p ON aps.project_id=p.id JOIN users cl ON p.client_id = cl.id JOIN users aby ON aps.assigned_by=aby.id JOIN users ast ON aps.assigned_to=ast.id WHERE (aps.assigned_by=aps.assigned_to OR aps.assigned_by=" . $user_id . ") AND aps.approved!=1";
		} else {

			$query = "SELECT " . $column_list . ",cl.client_name as client_name,cl.id as client_id,aby.id as assigned_by_id,aby.name as assigned_by,ast.id as assigned_to_id,ast.name as assigend_to,p.project_name as project_name FROM assigend_project_tasks aps JOIN projects p ON aps.project_id=p.id JOIN users cl ON p.client_id = cl.id JOIN users aby ON aps.assigned_by=aby.id JOIN users ast ON aps.assigned_to=ast.id WHERE aps.assigned_by=" . $user_id . " AND aps.assigned_to!=" . $user_id . " AND aps.approved!=1";
		}


		if ($project_id_search != "all") {

			$res = $this->getproject_search_query($project_id_search);
			$query = $query . $res;
		}

		if ($client_id_search != "all") {

			$res = $this->getClient_search_query($client_id_search);
			$query = $query . $res;
		}


		if ($date_filter_search != "default") {

			$res = $this->getDate_search_query($date_filter_search, $from_date_search, $to_date_search);
			$query = $query . $res;
		}


		$query = $query . " ORDER BY aps.due_date ASC,aps.completed DESC";


		// echo $query;
		// die;





		$stmtUsers = $conn->execute($query);
		#$assigned_to_task = $stmtUsers->fetchAll("assoc");
		$assigned_task = $stmtUsers->fetchAll("assoc");

		// print_r($assigned_task);
		// die;




		//assigned task



		$query = "SELECT " . $column_list . ",cl.client_name as client_name,cl.id as client_id,aby.id as assigned_by_id,aby.name as assigned_by,ast.id as assigned_to_id,ast.name as assigend_to,p.project_name as project_name FROM assigend_project_tasks aps JOIN projects p ON aps.project_id=p.id JOIN users cl ON p.client_id = cl.id JOIN users aby ON aps.assigned_by=aby.id JOIN users ast ON aps.assigned_to=ast.id";



		if ($project_id_search != "all") {

			$res = $this->getproject_search_query($project_id_search);
			$query = $query . $res;
		}

		if ($client_id_search != "all") {

			$res = $this->getClient_search_query($client_id_search);
			$query = $query . $res;
		}


		if ($date_filter_search != "default") {

			$res = $this->getDate_search_query($date_filter_search, $from_date_search, $to_date_search);
			$query = $query . $res;
		}



		$query = $query . " WHERE aps.assigned_to=" . $user_id . " AND aps.approved!=1 AND aps.completed=0 ORDER BY aps.due_date ASC";



		$stmtUsers = $conn->execute($query);
		// $assigned_me_task = $stmtUsers->fetchAll("assoc");
		// $mytask = [];
		$mytask =  $stmtUsers->fetchAll("assoc");






		$query = "SELECT id,name from users  WHERE role = 3  AND status=1 AND name IS NOT NULL ORDER BY name";

		$stmtUsers = $conn->execute($query);
		$users = $stmtUsers->fetchAll("assoc");


		$my_team_count = 0;

		if ($user_id == 37) {


			$query = "SELECT name,id from users where status=1 and role=3";

			$stmtUsers2 = $conn->execute($query);
			$myTeam = $stmtUsers2->fetchAll("assoc");
		} else {
			$this->MyTeams = $this->fetchTable("MyTeams");
			// $team_id_get = $this->MyTeams->find("list", [
			// 	"valueField" => "id",
			// 	"conditions" => [
			// 		"OR" => [
			// 			"MyTeams.tech_lead" => $user_id,
			// 			"MyTeams.project_manager" => $user_id
			// 		]
			// 	]
			// ])->toList();
			$team_id_get = $this->MyTeams->find("list", [
				"valueField" => "id",
				"conditions" => [
					"OR" => [
						"MyTeams.tech_lead" => $user_id,
						"MyTeams.project_manager" => $user_id
					]
				]
			])->toArray();
			$condition_array = [];

			if (!empty($team_id_get)) {


				$condition_array["id IN"] = $team_id_get;
			}

			// $tech_lead_arr = $this->MyTeams->find("list", [
			// 	"valueField" => "tech_lead",
			// 	"conditions" => $condition_array
			// ])->toList();

			$tech_lead_arr = $this->MyTeams->find("list", [
				"valueField" => "tech_lead",
				"conditions" => $condition_array
			])->toArray();

			$project_manager_list = $this->MyTeams->find("list", [
				"valueField" => "project_manager",
				"conditions" => $condition_array
			])->toArray();




			if (!empty($team_id_get)) {

				$query = "SELECT users.name,users.id from users where (users.id IN (select resid from my_team_resources where my_team_id IN (" . implode(',', $team_id_get) . ") ) OR users.id IN (" . implode(",", $tech_lead_arr) . ") OR users.id IN (" . implode(",", $project_manager_list) . ")) AND users.id!={$user_id}";

				$stmtUsers2 = $conn->execute($query);
				$myTeam = $stmtUsers2->fetchAll("assoc");
			} else {

				$myTeam = [];
			}
		}

		// echo "<pre>";
		// print_r($myTeam);
		// die;


		$myTeamData = [];
		foreach ($myTeam as $key) {


			$query = "SELECT aps.*,pt.project_name as project_name,cl.client_name as client_name,apts.taskid as notesid from assigend_project_tasks aps LEFT JOIN projects pt ON aps.project_id=pt.id JOIN users cl ON cl.id=pt.client_id LEFT JOIN assigend_project_task_notes apts ON aps.id = apts.taskid  where aps.assigned_to=" . $key['id'] . " AND aps.completed!=1 AND aps.approved!=1";



			if ($project_id_search != "all") {

				$res = $this->getproject_search_query($project_id_search);
				$query = $query . $res;
			}

			if ($client_id_search != "all") {

				$res = $this->getClient_search_query($client_id_search);
				$query = $query . $res;
			}


			if ($date_filter_search != "default") {

				$res = $this->getDate_search_query($date_filter_search, $from_date_search, $to_date_search);
				$query = $query . $res;
			}


			$query = $query . " ORDER BY aps.due_date DESC";

			$stmt = $conn->execute($query);
			$myTeamTask = $stmt->fetchAll("assoc");

			// echo "<pre>";
			// print_r($myTeamTask);
			// die;

			if (count($myTeamTask) > 0) {
				$p['name'] = $key['name'];
				$p['id'] = $key['id'];
				$p['tasks'] = [];

				// echo "<pre>";
				// print_r($myTeamTask);
				// die;
				foreach ($myTeamTask as $task_key) {
					$task['id'] = $task_key['id'];
					$task['due_date'] = $task_key['due_date'];
					$query = "SELECT name from users where id=" . $task_key['assigned_by'];


					$get_name = $conn->execute($query);
					$get_assigned_name = $get_name->fetchAll("assoc");

					$task['assigned_by_name'] = $get_assigned_name[0]['name'];
					$task['task_name'] = $task_key['task_name'];
					$task['description'] = $task_key['description'];
					$task['project_name'] = $task_key['project_name'];
					$task["extend_days"] = $task_key['extend_days'];
					$task["extend_count"] = $task_key["extend_count"];
					$task["client_name"] = $task_key["client_name"];
					$task["completed"] = $task_key["completed"];
					$task["notesid"] = $task_key["notesid"];
					$p['tasks'][] = $task;

					$my_team_count++;
				}
				$myTeamData[] = $p;
			}
		}


		// echo "<pre>";
		// print_r($myTeamData);
		// die;






		$this->AssigendProjectTasks = $this->fetchTable("AssigendProjectTasks");
		$completed_task = $this->AssigendProjectTasks->find("all")
			->contain(["Assigned_to_data", "Assigned_by_data", "Projects", "Projects.Client"])
			->where(["assigned_to" => $user_id, "completed" => 1, 'approved' => 0]);

		if ($project_id_search != "all") {

			$completed_task = $completed_task->where(["project_id" => $project_id_search]);
		}

		if ($client_id_search != "all") {


			$completed_task = $completed_task->where(["Client.id" => $client_id_search]);
		}

		if ($date_filter_search != "default") {


			if ($date_filter_search == "today") {

				$search_date = date("Y-m-d");

				$completed_task = $completed_task->where(

					function (QueryExpression $exp) use ($search_date) {

						return $exp
							->or(["AssigendProjectTasks.due_date" => $search_date, "AssigendProjectTasks.extend_days" => $search_date]);
					}

				);
			} else if ($date_filter_search == "tomorrow") {
				$current_date = date("Y-m-d");
				$search_date =  date('Y-m-d', strtotime('+1 day', strtotime($current_date)));

				$completed_task = $completed_task->where(

					function (QueryExpression $exp) use ($search_date) {

						return $exp
							->or(["AssigendProjectTasks.due_date" => $search_date, "AssigendProjectTasks.extend_days" => $search_date]);
					}

				);
			} else if ($date_filter_search == "past") {

				$search_date = date('Y-m-d');

				$completed_task = $completed_task->where(
					function (QueryExpression $exp) use ($search_date) {

						return $exp
							->or(["AssigendProjectTasks.due_date <" => $search_date, "AssigendProjectTasks.extend_days <" => $search_date]);
					}
				);
			} else if ($date_filter_search == "custom") {

				$completed_task = $completed_task->where(["( (AssigendProjectTasks.due_date BETWEEN :start AND :end) OR (AssigendProjectTasks.extend_days BETWEEN :start AND :end))"])->bind(':start', $from_date_search, 'date')->bind(':end', $to_date_search, 'date');
			}
		}







		$completed_task = $completed_task->order(["AssigendProjectTasks.due_date" => "DESC"])->toArray();


		$condition_array = [
			"approved" => 1,
			"completed" => 1,
			"OR" => [

				"assigned_to" => $user_id,
				"assigned_by" => $user_id
			],
		];

		if ($project_id_search != "all") {

			$condition_array["project_id"] = $project_id_search;
		}

		if ($client_id_search != "all") {

			$condition_array["Client.id"] = $client_id_search;
		}



		if ($date_filter_search != "default") {


			if ($date_filter_search == "today") {

				$search_date = date("Y-m-d");

				$condition_array["AssigendProjectTasks.due_date"] =  $search_date;
			} else if ($date_filter_search == "tomorrow") {
				$current_date = date("Y-m-d");
				$search_date =  date('Y-m-d', strtotime('+1 day', strtotime($current_date)));


				$condition_array["AssigendProjectTasks.due_date"] = $search_date;
			} else if ($date_filter_search == "past") {

				$search_date = date('Y-m-d');

				$condition_array["AssigendProjectTasks.due_date <"] = $search_date;
			} else if ($date_filter_search == "custom") {

				$condition_array["AND"] = [

					"OR" => [
						[
							"AssigendProjectTasks.due_date >=" => $from_date_search,
							"AssigendProjectTasks.due_date <=" => $to_date_search
						],
						[
							"AssigendProjectTasks.extend_days >=" => $from_date_search,
							"AssigendProjectTasks.extend_days <=" => $to_date_search
						]
					]
				];
			}
		}


		// var_dump($condition_array);
		// die;




		$approved_task = $this->AssigendProjectTasks->find("all", [
			"contain" => [
				"Assigned_to_data",
				"Assigned_by_data",
				"Projects",
				"Projects.Client"
			],
			"conditions" => $condition_array,
			"order" => ["AssigendProjectTasks.due_date" => "DESC"]

		])->toArray();

		// echo "<pre>";

		// print_r($approved_task);
		// die;


		// echo "<pre>";print_r($approved_task);die;




		// echo '<pre>';print_r($myTeamData);
		// die;


		$this->set(compact('assigned_task', 'users', 'mytask', 'myTeamData', 'user_id', 'projects_data', "completed_task", "approved_task", "client_data", "project_id_search", "client_id_search", "date_filter_search", "from_date_search", "to_date_search", "date_data", "add_task_project", "my_team_count"));
	}


	public function addnotestotask()
	{

		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];


		// $this->loadModel("AssigendProjectTaskNotes");
		$this->AssigendProjectTaskNotes = $this->fetchTable("AssigendProjectTaskNotes");

		if ($this->request->is('post')) {


			$all_request_data = $this->request->getData();

			// print_r($all_request_data);
			// die;


			$task_notes_obj = $this->AssigendProjectTaskNotes->newEmptyEntity();

			$all_request_data["user_id"] = $user_id;


			$task_patch = $this->AssigendProjectTaskNotes->patchEntity($task_notes_obj, $all_request_data);

			// $task_patch->user_id = $user_id;


			if ($this->AssigendProjectTaskNotes->save($task_patch)) {

				$this->redirect(['controller' => 'Users', 'action' => 'mytask']);
			} else {

				echo "Something Went Wrong";

				// $this->redirect(['controller' => 'Users', 'action' => 'mytask']);

			}
		}
	}

	public function sendalltasknotes($id)
	{

		$this->autoRender = false;
		$this->Authorization->skipAuthorization();


		// $this->loadModel("AssigendProjectTaskNotes");
		$this->AssigendProjectTaskNotes = $this->fetchTable("AssigendProjectTaskNotes");

		$all_notes = $this->AssigendProjectTaskNotes->find("all")->contain(["Users"])->where(["taskid" => $id]);

		echo json_encode($all_notes);
	}



	public function addTask()
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();


		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];
		$conn = ConnectionManager::get('default');

		// $this->loadModel("AssigendProjectTasks");
		$this->AssigendProjectTasks = $this->fetchTable("AssigendProjectTasks");


		if ($this->request->is('post')) {

			$all_request_data = $this->request->getData();


			$all_request_data["due_date"] = date('Y-m-d', strtotime($this->request->getData('due_date')));

			$all_request_data["assigned_by"] = $user_id;

			$task_obj = $this->AssigendProjectTasks->newEmptyEntity();

			$task_patch = $this->AssigendProjectTasks->patchEntity($task_obj, $all_request_data);


			if ($this->AssigendProjectTasks->save($task_patch)) {

				if ($this->request->getData("send_mail_task", false)) {

					$assigned_to = $this->Users->find("all")->where(["id" => $task_patch->assigned_to])->first()->toArray();
					// print_r($assigned_to);
					// die();
					// echo "<pre>";
					// print_r($userSession["name"]);
					// die;


					$this->sendTaskNotification($assigned_to["email"], $userSession["name"]);
				}

				$this->redirect(['controller' => 'Users', 'action' => 'mytask']);
			} else {
				echo "Error";
			}
		} else {

			echo "Go away";
		}
	}


	public function completedmytask($condition, $id)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();


		if ($this->request->is('ajax')) {
			$conn = ConnectionManager::get('default');
			$session = new \Cake\Http\Session();
			$userSession = $session->read('data');
			$user_id = $userSession['id'];


			if ($condition == "unchecked") {
				$query = "UPDATE assigend_project_tasks SET completed=1,status='completed',completed_date='" . date('Y-m-d') . "' WHERE
					     	id=" . $id . " AND assigned_to=" . $user_id . " ";

				$res = $conn->execute($query);
				if ($res) {
					echo "true";
				} else {
					echo "false";
				}
			} elseif ($condition == "checked") {

				$query = "UPDATE assigend_project_tasks SET completed=0,status='pending' WHERE id=" . $id . " AND assigned_to=" . $user_id . " ";

				$res = $conn->execute($query);
				if ($res) {
					echo "True";
				} else {
					echo "False";
				}
			}

			die();
		}
	}


	public function approvedtask()
	{
		$condition = "unchecked";
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];


		if ($this->request->is('post')) {

			$task_ids = $this->request->getData("task_ids");

			$imp_str = implode(",", $task_ids);



			if ($condition == "unchecked") {

				if ($user_id == 37) {

					$query = "UPDATE assigend_project_tasks SET approved=1 WHERE
     	id IN ({$imp_str})";
				} else {
					$query = "UPDATE assigend_project_tasks SET approved=1 WHERE
     	id IN ({$imp_str}) AND assigned_by={$user_id}";
				}

				$res = $conn->execute($query);

				if ($res) {
					echo "Success";
				} else {
					echo "Failure";
				}
			}
			die();
		}
	}



	public function editTask($id)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');

		$user_id = $userSession['id'];

		$this->Tasks = TableRegistry::get('AssigendProjectTasks');
		$task = $this->Tasks
			->findById($id)
			->firstOrFail();



		$local['id'] = $task->id;
		$local['task_name'] = $task->task_name;
		$local['due_date'] = date('Y-m-d', strtotime($task->due_date));
		$assigned_to = $task->assigned_to;

		$local['task_description'] = $task->description;

		$local['project_id'] = $task->project_id;


		$assigned_options = '';
		$users = $this->Users
			->find()
			->where(['deleted' => 1, 'role' => 3, 'status' => 1])
			->order(['name' => 'ASC'])
			->all();

		foreach ($users as $key => $value) {

			if ($assigned_to == $value->id) {
				$assigned_options .= '<option value="' . $value->id . '" selected>' . $value->name . '</option>';
			} else {
				$assigned_options .= '<option value="' . $value->id . '">' . $value->name . '</option>';
			}
		}

		$local['assigned_options'] = $assigned_options;

		$assigned_options_project = "";
		$projects = $this->Projects
			->find()
			->where(['active' => 1])
			->contain(["Client"])
			->order(['project_name' => 'ASC'])
			->all();

		foreach ($projects as $key => $value) {

			$project_name_var = substr($value['project_name'], 0, 20) . " - " . $value["client"]["client_name"];

			if ($task->project_id == $value->id) {

				$local["assigned_options_project_selected"] = $project_name_var;

				$assigned_options_project .= '<option value="' . $value->id . '" selected>' . $project_name_var . '</option>';
			} else {
				$assigned_options_project .= '<option value="' . $value->id . '">' . $project_name_var . '</option>';
			}
		}

		$local['assigned_options_project'] = $assigned_options_project;




		// echo "<pre>";print_r($local);die;


		echo json_encode($local);
	}

	public function updateTask($id)
	{
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');

		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		$this->Tasks = TableRegistry::get('AssigendProjectTasks');
		$task = $this->Tasks
			->findById($id)
			->firstOrFail();


		// print_r($this->request->getData());
		// die;

		if ($this->request->is(['post', 'put'])) {
			$all_data = $this->request->getData();


			$this->Tasks->patchEntity($task, $all_data);
			// $task->due_date = date('Y-m-d',strtotime($this->request->getData('due_date')));
			if ($this->request->getData("extend_days")) {
				$no = $this->request->getData("extend_days");

				if ($task->extend_days) {
					$current_date = date('Y-m-d', strtotime($task->extend_days));
				} else {

					$current_date = date('Y-m-d');
				}


				if ($this->request->getData("extend_days") == '1') {

					$cal_date = date('Y-m-d', strtotime('+1 days', strtotime($current_date)));
				} else if ($this->request->getData("extend_days") == '2') {

					$cal_date = date('Y-m-d', strtotime('+2 days', strtotime($current_date)));
				} else {

					$cal_date = date("Y-m-d", strtotime($this->request->getData("extend_days_custom")));
				}

				// echo $cal_date;
				// exit;

				$task->extend_days = $cal_date;
				if ($task->extend_count) {
					$calc = $task->extend_count;
				} else {
					$calc = 0;
				}
				$task->extend_count = (int)$calc + 1;
			}



			if ($this->Tasks->save($task)) {

				if ($this->request->getData("send_mail_task", false)) {

					$assigned_to = $this->Users->find("all")->where(["id" => $this->request->getData("assigned_to")])->first()->toArray();


					$this->sendTaskNotification($assigned_to["email"], $userSession["name"]);
				}

				echo "true";
			}
		}
	}

	public function deleteTask($id)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		$this->Tasks = TableRegistry::get('AssigendProjectTasks');
		$entity = $this->Tasks->get($id);
		$result = $this->Tasks->delete($entity);
		echo "true";
	}


	public function report2($value = null, $date = null)
	{

		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');

		if ($date == null) {

			$n = date('N') - 1;
			$data['first'] = (date('N') == 1) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
			$n = date('N') - 2;
			$data['second'] = (date('N') == 2) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
			$n = date('N') - 3;
			$data['third'] = (date('N') == 3) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
			$n = date('N') - 4;
			$data['fourth'] = (date('N') == 4) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
			$n = date('N') - 5;
			$data['fifth'] = (date('N') == 5) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));
			$n = date('N') - 6;
			$data['sixth'] = (date('N') == 6) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $n . 'days'));


			$pdate = date('Y-m-d', strtotime('last saturday'));
			$ndate = date('Y-m-d', strtotime('next monday'));
		} else if ($date != null && $value != null) {

			if ($value == "prev") {


				$data['first'] = date('Y-m-d', strtotime('-5 days', strtotime($date)));

				$data['second'] = date('Y-m-d', strtotime('-4 days', strtotime($date)));

				$data['third'] = date('Y-m-d', strtotime('-3 days', strtotime($date)));

				$data['fourth'] = date('Y-m-d', strtotime('-2 days', strtotime($date)));

				$data['fifth'] = date('Y-m-d', strtotime('-1 days', strtotime($date)));

				$data['sixth'] = $date;
			} else if ($value == "next") {

				$data['first'] = $date;

				$data['second'] = date('Y-m-d', strtotime('+1 days', strtotime($date)));

				$data['third'] = date('Y-m-d', strtotime('+2 days', strtotime($date)));

				$data['fourth'] = date('Y-m-d', strtotime('+3 days', strtotime($date)));

				$data['fifth'] = date('Y-m-d', strtotime('+4 days', strtotime($date)));

				$data['sixth'] = date('Y-m-d', strtotime('+5 days', strtotime($date)));;
			}


			$pdate = date('Y-m-d', strtotime('last saturday', strtotime($date)));
			$ndate = date('Y-m-d', strtotime('next monday', strtotime($date)));
		}

		$query = "SELECT * FROM users WHERE `deleted` = 1 AND `role` = 3 AND `company_id` = 10 ORDER BY name ASC";
		$stmtUsers = $conn->execute($query);
		$Manager_users = $stmtUsers->fetchAll("assoc");

		// echo "<pre>"; print_r($Manager_users); exit;

		$project_user = [];
		foreach ($Manager_users as $key) {
			$p['id'] = $key['id'];
			$p['name'] = $key['name'];
			$p["teamid"] = $key["teamid"];
			$p["last_login"] = $key["last_login"];
			$p['projects'] = array();

			$query = "SELECT p.id,p.project_name,p.milestone_id,u.client_name FROM projects p JOIN users u ON p.client_id=u.id WHERE p.deleted=1 AND p.status != 'Completed' AND ((FIND_IN_SET(" . $key['id'] . ",resources)!=0) )";
			$stmtProduct = $conn->execute($query);
			$list = $stmtProduct->fetchAll('assoc');

			// echo "<pre>"; print_r($list); exit;
			// $project_info = array();


			foreach ($list as $l) {
				$project_info['id'] = $l['id'];
				$project_info['project_name'] = $l['project_name'];
				$project_info['client_name'] = $l['client_name'];
				$project_info['miles'] = array();
				$miles = [];
				$weeklyMilestoneTotal = 0;

				if ($l['milestone_id']) {

					$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN  (" . $l['milestone_id'] . ") AND work_date='" . $data['first'] . "' AND resource_id=" . $key['id'];
					$stmtwork = $conn->execute($query);
					$res = $stmtwork->fetchAll('assoc');
					if (count($res) > 0) {
						// $project_info['miles']['first'] = $res[0]['work'];
						$miles['first'] = $res[0]['work'];

						$weeklyMilestoneTotal +=  $res[0]['work'];
					} else {
						// $project_info['miles']['first'] = 0;
						$miles['first'] = $res[0]['work'];
						$miles['first'] = 0;
					}


					$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['second'] . "' AND resource_id=" . $key['id'];
					$stmtwork = $conn->execute($query);
					$res = $stmtwork->fetchAll('assoc');
					if (count($res) > 0) {
						// $project_info['miles']['second'] = $res[0]['work'];
						$miles['second'] = $res[0]['work'];
						$weeklyMilestoneTotal +=  $res[0]['work'];
					} else {
						// $project_info['miles']['second'] = 0;
						$miles['first'] = 0;
					}

					$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['third'] . "' AND resource_id=" . $key['id'];
					$stmtwork = $conn->execute($query);
					$res = $stmtwork->fetchAll('assoc');
					if (count($res) > 0) {
						// $project_info['miles']['third'] = $res[0]['work'];
						$miles['third'] = $res[0]['work'];
						$weeklyMilestoneTotal +=  $res[0]['work'];
					} else {
						// $project_info['miles']['third'] = 0;
						$miles['third'] = 0;
					}

					$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['fourth'] . "' AND resource_id=" . $key['id'];
					$stmtwork = $conn->execute($query);
					$res = $stmtwork->fetchAll('assoc');
					if (count($res) > 0) {
						// $project_info['miles']['fourth'] = $res[0]['work'];
						$miles['fourth'] = $res[0]['work'];

						$weeklyMilestoneTotal +=  $res[0]['work'];
					} else {
						// $project_info['miles']['fourth'] = 0;
						$miles['fourth'] = 0;
					}


					$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['fifth'] . "' AND resource_id=" . $key['id'];
					$stmtwork = $conn->execute($query);
					$res = $stmtwork->fetchAll('assoc');
					if (count($res) > 0) {
						// $project_info['miles']['fifth'] = $res[0]['work'];
						$miles['fifth'] = $res[0]['work'];

						$weeklyMilestoneTotal +=  $res[0]['work'];
					} else {
						// $project_info['miles']['fifth'] = 0;
						$miles['fifth'] = 0;
					}

					$query = "SELECT SUM(time_used) as work FROM user_timesheets p WHERE p.milestone_id IN (" . $l['milestone_id'] . ") AND work_date='" . $data['sixth'] . "' AND resource_id=" . $key['id'];
					$stmtwork = $conn->execute($query);
					$res = $stmtwork->fetchAll('assoc');
					if (count($res) > 0) {
						// $project_info['miles']['sixth'] = $res[0]['work'];
						$miles['sixth'] = $res[0]['work'];

						$weeklyMilestoneTotal +=  $res[0]['work'];
					} else {
						// $project_info['miles']['sixth'] = 0;
						$miles['sixth'] = 0;
					}

					if ($weeklyMilestoneTotal > 0) {
						$project_info['miles'] = $miles;
						// $weeklyProjectTotal += $weeklyMilestoneTotal; 
					}
					// echo "<br> <br><pre>"; print_r($miles);
					// exit;

				}

				if ($weeklyMilestoneTotal > 0) {
					$p['projects'][] = $project_info;
				}
			}

			$project_user[] = $p;
		}

		// echo "<pre>"; print_r($project_user); exit;



		$this->set(compact('pdate', 'ndate', "project_user", "data"));
	}

	// users csv file

	public function usersCSVReport()
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		if (!empty($this->request->getData())) {

			$userData = $this->request->getData();
			$status = explode("/", $userData['status'])[count(explode("/", $userData['status'])) - 1];

			$userDetails = [];
			$all = [];
			$all[] = "id";
			$userDetails[] = "id";
			foreach ($userData['resources'] as $value) {
				if ($value == "dob") {
					$all[] = $value;
				}
				if ($value == "ctc") {
					$all[] = $value;
				}
				if ($value == "prev_appraisal") {
					$all[] = $value;
				}
				if ($value == "next_appraisal") {
					$all[] = $value;
				}
				if ($value == "team_name") {
					$all[] = $value;
				}
				if ($value == "blood_group") {
					$all[] = $value;
				}
				if ($value == "aadhar_card") {
					$all[] = $value;
				}
				if ($value == "note") {
					$all[] = $value;
				}
				if ($value != "dob" && $value != "ctc" && $value != "prev_appraisal" && $value != "next_appraisal" && $value != "team_name" && $value != "blood_group" && $value != "aadhar_card" && $value != "note") {
					$userDetails[] = $value;
					$all[] = $value;
				}
			}
				// dd($all);
			if (!empty($userDetails)) {

				$users = $this->Users->find()
					->select($userDetails);

				if ($status == 'active') {
					$users = $users->where(["deleted" => 1, "role" => 3, 'status' => 1])->toArray();
				} else if ($status == 'inactive') {
					$users = $users->where(["deleted" => 1, "role" => 3, 'status' => 0])->toArray();
				} else {
					$users = $users->where(["role" => 3])->toArray();
				}

				// echo '<pre>';
				// print_r(count($users));
				// die;

				$userAllData = [];
				foreach ($users as $value) {
					$userAllData[] = $value;
				}
			}

			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=employees-' . date("Y-m-d-h-i-s") . '.csv');
			$output = fopen('php://output', 'w');
			fputcsv($output, $all);

			foreach ($userAllData as $data) {

				foreach ($all as $value) {
					if ($value == 'id') {
						$userRow = [$data->id];
					}
					if ($value == 'email') {
						$userRow = array_merge($userRow, [$data->email]);
					}
					if ($value == 'name') {
						$userRow = array_merge($userRow, [$data->name]);
					}
					
					if ($value == 'reporting_manager') {
						//Helper class used for reporting manager name
						$customdtfhelper = new \App\View\Helper\ReportingMNameHelper(new \Cake\View\View());
						$name = $customdtfhelper->rName($data->reporting_manager);
						// echo $name . "<br>";
						$userRow = array_merge($userRow, [$name]);
					}
					if ($value == 'designation') {
						$userRow = array_merge($userRow, [$data->designation]);
					}
					if ($value == 'technology') {
						$userRow = array_merge($userRow, [$data->technology]);
					}
					if ($value == 'contact_no') {
						$userRow = array_merge($userRow, [$data->contact_no]);
					}
					if ($value == 'status') {
						if ($data->status == 1) {
							$status = "Active";
						} else {
							$status = "Inactive";
						}
						$userRow = array_merge($userRow, [$status]);
					}
					if ($value == 'el') {
						$userRow = array_merge($userRow, [$data->el]);
					}
					if ($value == 'cl') {
						$userRow = array_merge($userRow, [$data->cl]);
					}
					if ($value == 'sl') {
						$userRow = array_merge($userRow, [$data->sl]);
					}
					if ($value == 'lwp') {
						$userRow = array_merge($userRow, [$data->lwp]);
					}
					if ($value == 'comp_off') {
						$userRow = array_merge($userRow, [$data->comp_off]);
					}
					if ($value == 'doj') {
						$userRow = array_merge($userRow, [$data->doj]);
					}
					if ($value == 'last_login') {
						$userRow = array_merge($userRow, [$data->last_login]);
					}
					if ($value == 'level_id') {
						$userRow = array_merge($userRow, [$data->level_id]);
					}
					if ($value == 'dob') {
						//Helper class used for reporting DOB
						$customdtfhelper = new \App\View\Helper\ReportingMNameHelper(new \Cake\View\View());
						$dob = $customdtfhelper->dob($data->id);
						// echo $dob . "<br>";
						$userRow = array_merge($userRow, [$dob]);
					}
					if ($value == 'ctc') {
						//Helper class used for reporting CTC
						$customdtfhelper = new \App\View\Helper\ReportingMNameHelper(new \Cake\View\View());
						$ctc = $customdtfhelper->empCTC($data->id);
						// echo $ctc . "<br>";
						$userRow = array_merge($userRow, [$ctc]);
					}
					if ($value == 'prev_appraisal') {
						//Helper class used for reporting prevAppraisal
						$customdtfhelper = new \App\View\Helper\ReportingMNameHelper(new \Cake\View\View());
						$prevAppraisal = $customdtfhelper->prevAppraisal($data->id);
						// echo $prevAppraisal . "<br>";
						$userRow = array_merge($userRow, [$prevAppraisal]);
					}
					if ($value == 'next_appraisal') {
						//Helper class used for reporting nextAppraisal
						$customdtfhelper = new \App\View\Helper\ReportingMNameHelper(new \Cake\View\View());
						$nextAppraisal = $customdtfhelper->nextAppraisal($data->id);
						// echo $nextAppraisal . "<br>";
						$userRow = array_merge($userRow, [$nextAppraisal]);
					}
					if ($value == 'note') {
						$customdtfhelper = new \App\View\Helper\ReportingMNameHelper(new \Cake\View\View());
						$note = $customdtfhelper->note($data->id);
						$userRow = array_merge($userRow, [$note]);
					}
					if ($value == 'aadhar_card') {
						$customdtfhelper = new \App\View\Helper\ReportingMNameHelper(new \Cake\View\View());
						$aadhar_card = $customdtfhelper->aadhar_card($data->id);
						$userRow = array_merge($userRow, [$aadhar_card]);
					}
					if ($value == 'blood_group') {
						$customdtfhelper = new \App\View\Helper\ReportingMNameHelper(new \Cake\View\View());
						$blood_group = $customdtfhelper->blood_group($data->id);
						$userRow = array_merge($userRow, [$blood_group]);
					}
					if ($value == 'team_name') {
						//Helper class used for reporting team Name
						$customdtfhelper = new \App\View\Helper\ReportingMNameHelper(new \Cake\View\View());
						$teamName = $customdtfhelper->teamName($data->id);
						// echo $teamName . "<br>";

						$userRow = array_merge($userRow, [$teamName]);
					}
				}
				fputcsv($output, $userRow, ",", '"');
			}
			// print_r($userRow);
			die;
		} else {
			return $this->redirect(['controller' => 'users', 'action' => 'index']);
		}
	}

	public function userProfile()
	{
		$this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();

		$userId = $this->Authentication->getIdentity()->getIdentifier();

		if ($this->request->getQuery('reports-profile')) {
			$userId = base64_decode($this->request->getQuery('reports-profile'));
		} else if ($this->request->getQuery('reportees-profile')) {
			$userId = base64_decode($this->request->getQuery('reportees-profile'));
		}

		$userData = $this->Users->find()
			->select(['id', 'name', 'reporting_manager', 'designation', 'doj'])
			->where(['id' => $userId, 'deleted' => 1])->first();

		$manager = $this->Users->find()
			->select(['id', 'name'])
			->where(['id' => $userData->reporting_manager, 'status' => 1, 'deleted' => 1])->first();

		$subordinates = $this->Users->find()
			->select(['id', 'name'])
			->where(['reporting_manager' => $userId, 'status' => 1, 'deleted' => 1])->toArray();

		// echo $userId;
		// echo '<pre>';
		// print_r($userData);
		// die;

		$this->set(compact('userData', 'subordinates', 'manager'));
	}

	public function filterData()
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		if ($this->request->is("GET")) {
			$session = new \Cake\Http\Session();
			$userSession = $session->read('data');
			$role = $userSession['role'];
			$parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];

			$value = $this->request->getQuery('value');
			$status = $this->request->getQuery('status');

			if ($status == "inactive") {
				$condition = [
					"Users.status" => 0,
					"Users.deleted" => 1,
					"Users.role" => 3,
					"Users.company_id" => $parent_id,
					'OR' => [
						'ReportingManagerData.name LIKE' => "%$value%",
						'Users.designation LIKE' => "%$value%",
					]
				];
			} else if ($status == "all") {
				$condition = [
					"Users.deleted" => 1,
					"Users.role" => 3,
					"Users.company_id" => $parent_id,
					'OR' => [
						'ReportingManagerData.name LIKE' => "%$value%",
						'Users.designation LIKE' => "%$value%",
					]
				];
			} else {
				$condition = [
					"Users.status" => 1,
					"Users.deleted" => 1,
					"Users.role" => 3,
					"Users.company_id" => $parent_id,
					'OR' => [
						'ReportingManagerData.name LIKE' => "%$value%",
						'Users.designation LIKE' => "%$value%",
					]
				];
			}

			$users = $this->Users->find("all", [
				"fields" => [
					"id" => "Users.id",
					"user_name" => "Users.name",
					"rm_name" => "ReportingManagerData.name",
					"designation" => "Users.designation",
					"status" => "Users.status",
					"prev_appraisal" => "EmpDetail.prev_appraisal",
					"next_appraisal" => "EmpDetail.next_appraisal",
				],
				"contain" => [
					"TeamData",
					"ReportingManagerData",
					"EmpDetail",
				], "conditions" => $condition,
			])
				->toArray();

			echo json_encode($users);
			// echo "<pre>";
			// print_r($users);
			die;
		}
	}

	public function sendemailtohr()
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		$today =  Date("Y-m-d");
		// $today =  Date("2023-03-15");

		$todayData = $this->EmployeePunchTime->find()
			->where(['dom' => $today])
			->distinct(['emp'])
			->toArray();

		// echo '<pre>';
		// print_r($todayData);
		// echo WWW_ROOT . 'emp_doc';
		// die;


		$path = WWW_ROOT . 'emp_punch' . DS;
		$fileName = $path . 'emp_punch_' . $today . ".csv";

		$file = fopen($fileName, 'w');
		fputcsv($file, ['S.No.', 'Employee Name', 'Date'], ',');

		if (count($todayData) > 0) {
			$x = 1;
			$row = [];
			foreach ($todayData as $data) {
				$row = [
					$x,
					$data->emp,
					date("Y-m-d", strtotime($data->dom))
				];

				if (count($row) > 0) {
					fputcsv($file, $row, ',');
					++$x;
				}
			}
			fclose($file);

			$mailer = new Mailer();

			$mailer->setTransport('default');
			$mailer
				->setEmailFormat('html')
				// ->setTo("sanjay.kumar@actiknow.com")
				->setTo("himani.duhan@actiknow.com")
				->setSubject("Today Employee Present $today")
				->viewBuilder();
			$mailer->setAttachments([
				ROOT . DS . "webroot" . DS . "emp_punch" . DS . 'emp_punch_' . $today . ".csv",
			]);
			$mailer->deliver();
		}
	}

	public function employeePunchTimeReport($from=null,$to=null,$employee=null,$status=null) {

		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];
		// validation for valid user
		$roleArray = $userSession['role_name'];
		$validList = [10,4,13];
		$this->routeValidation($roleArray,$validList);
		$this->EmployeePunchTime = $this->fetchTable('EmployeePunchTime');

		if($from==null) {
			$from=date('Y-m-d');
		} else {
			$from=$from;
		}
		if($to==null){
			$to=date('Y-m-d');
		} else {
			$to=$to;
		}
		if($status==null) {
          $status='Present';
		} else {
			$status=$status;
		}
		// if($employee){
        //     $employee='';
		// } else {
		// 	$employee=$employee;
		// }
		if($employee==null || $employee=='Select Employee') {
			// $query = "SELECT u.emp_id, e.emp, u.manager_name, e.dom AS date, e.intime, e.outtime, TIMEDIFF(e.outtime, e.intime) AS total_time, TIMEDIFF(intime, '10:00:00') AS Late_by FROM emp_punch_time e LEFT JOIN ( SELECT u1.id AS emp_id, u1.name AS emp_name, u2.id AS manager_id, u2.name AS manager_name FROM users u1 LEFT JOIN users u2 ON u1.reporting_manager = u2.id WHERE u1.deleted=1 AND u1.company_id=10 ) AS u ON e.emp = u.emp_name WHERE e.dom >= '".$from."' AND e.dom <= "."'".$to."'";
			// $query ="SELECT u.emp_id, u.emp_name AS emp, u.manager_name, e.dom AS date, e.intime, e.outtime, TIMEDIFF(e.outtime, e.intime) AS total_time, TIMEDIFF(e.intime, '10:00:00') AS Late_by, CASE WHEN e.emp IS NOT NULL THEN 'Present' ELSE 'Absent' END AS status FROM ( SELECT u1.id AS emp_id, u1.name AS emp_name, u2.name AS manager_name FROM users u1 LEFT JOIN users u2 ON u1.reporting_manager = u2.id WHERE u1.deleted = 1 AND u1.company_id = 10 AND u1.role = 3 AND u1.status = 1 ) AS u LEFT JOIN emp_punch_time e ON e.emp = u.emp_name AND e.dom >= '".$from."' AND e.dom <= '".$to."' AND (CASE WHEN e.emp IS NOT NULL THEN 'Present' ELSE 'Absent' END) ="."'".$status."'";
			$query="SELECT u.emp_id, u.emp_name AS emp, u.manager_name, e.dom AS date, e.intime, e.outtime, TIMEDIFF(e.outtime, e.intime) AS total_time, TIMEDIFF(e.intime, '10:00:00') AS Late_by, CASE WHEN e.emp IS NOT NULL THEN 'Present' ELSE 'Absent' END AS status FROM ( SELECT u1.id AS emp_id, u1.name AS emp_name, u2.name AS manager_name FROM users u1 LEFT JOIN users u2 ON u1.reporting_manager = u2.id WHERE u1.deleted = 1 AND u1.company_id = 10 AND u1.role = 3 AND u1.status = 1 ) AS u LEFT JOIN emp_punch_time e ON e.emp = u.emp_name AND e.dom >= '".$from."' AND e.dom <= '".$to."' WHERE (CASE WHEN e.emp IS NOT NULL THEN 'Present' ELSE 'Absent' END) = "."'".$status."'";
		} else {
			// $query = "SELECT u.emp_id, e.emp, u.manager_name, e.dom AS date, e.intime, e.outtime, TIMEDIFF(e.outtime, e.intime) AS total_time, TIMEDIFF(intime, '10:00:00') AS Late_by FROM emp_punch_time e LEFT JOIN ( SELECT u1.id AS emp_id, u1.name AS emp_name, u2.id AS manager_id, u2.name AS manager_name FROM users u1 LEFT JOIN users u2 ON u1.reporting_manager = u2.id WHERE u1.deleted=1 AND u1.company_id=10 ) AS u ON e.emp = u.emp_name WHERE e.dom >= '".$from."' AND e.dom <= '".$to."' AND e.emp = "."'".$employee."'";
			$query ="SELECT u.emp_id, u.emp_name AS emp, u.manager_name, e.dom AS date, e.intime, e.outtime, TIMEDIFF(e.outtime, e.intime) AS total_time, TIMEDIFF(e.intime, '10:00:00') AS Late_by, CASE WHEN e.emp IS NOT NULL THEN 'Present' ELSE 'Absent' END AS status FROM ( SELECT u1.id AS emp_id, u1.name AS emp_name, u2.name AS manager_name FROM users u1 LEFT JOIN users u2 ON u1.reporting_manager = u2.id WHERE u1.deleted = 1 AND u1.company_id = 10 AND u1.role = 3 AND u1.status = 1 AND u1.name='".$employee."' ) AS u LEFT JOIN emp_punch_time e ON e.emp = u.emp_name AND e.dom >= '".$from."' AND e.dom <= '".$to."'";
		}
		
		// dd($query);
		$stmtProduct = $conn->execute($query);
		$list = $stmtProduct->fetchAll('assoc');
		// dd($list);

		$user_data = $this->Users->find()
            ->select(['id', 'name'])
            ->where(["role" => 3, "status" => 1, "deleted" => 1])
            ->order(["name" => "ASC"])
            ->toArray();

		$today=date('Y-m-d');
        $todayattendence = $this->EmployeePunchTime->find('All')
            ->where(['dom' => $today])
            ->toArray();
		// $todayforgetCard = $this->Leaves->find('All')
        //     ->where(['leave_type' => 'Forgot Card', 'applied_on' => $today])
        //     ->toArray();
		$query1 = "SELECT count(*) as totalforgetcard FROM `leaves` WHERE leave_type = 'Forgot Card' AND from_date = '$today'";
		$stmtProduct1 = $conn->execute($query1);
		$totalForgetCard = $stmtProduct1->fetchAll('assoc');
        $totalemp =count($todayattendence); 
		// $totalForgetCard = count($todayforgetCard);
		// dd($totalForgetCard);

		$this->set(compact('list','from','to','employee','user_data','totalemp','status','totalForgetCard'));
	}
	public function forgetCardData(){
		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$today=date('Y-m-d');
		// $query = "SELECT  FROM `leaves` WHERE leave_type = 'Forgot Card' AND from_date = '$today'";
		$query="SELECT leaves.leave_type,leaves.subject,leaves.applied_on,users.name,users.email FROM `leaves` LEFT JOIN users ON leaves.created_by=users.id WHERE leaves.leave_type='Forgot Card' AND leaves.from_date='$today'";
		$stmtProduct1 = $conn->execute($query);
		$data = $stmtProduct1->fetchAll('assoc');

		$this->set(compact('data'));
	}

	public function employeeTimesheetFilledReport($manager=null,$employee=null,$month=null,$year=null) {

		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];
		// validation for valid user
		$roleArray = $userSession['role_name'];
		$validList = [10,4,13];
		$this->routeValidation($roleArray,$validList);

		$this->EmployeePunchTime = $this->fetchTable('EmployeePunchTime');
    
		if($month==null) {
			$month=date('m');
		} else {
			$month=$month;
		}
		if($year==null){
			$year=date('Y');
		} else {
			$year=$year;
		}
		$last_date_of_month = date(''.$year.'-'.$month.'-t', strtotime(''.$year.'-'.$month.''));
		$strto = date('Y-m',strtotime($last_date_of_month));
		$current = date('Y-m');
		if($strto==$current) {
			$last_date_of_month = date(''.$year.'-'.$month.'-d');
		} else {
			$last_date_of_month = date(''.$year.'-'.$month.'-t', strtotime(''.$year.'-'.$month.''));
		}
		// dd($last_date_of_month);
		$str = $month;
		// $str1=trim($str,"0");
		$str1 = ltrim($str, "0");
		$month_date=date(''.$year.'-'.$str1.'-01');
		$month_format=date('M',strtotime($month_date));
		if($manager==null || $manager=='Select Manager') {
			if($employee==null || $employee=='Select Employee'){ 
				$query = 'SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name", SUM(d.time_used) AS "hours", CONCAT( ROUND( ( Count_Filled_Percentage("'.$last_date_of_month.'",SUM(d.time_used)) ), 2 ), "%" ) AS "filled" FROM users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.$month_format." ".$year.'" GROUP BY c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y")
				 ORDER BY c.name, MAX(d.work_date) DESC, MAX(d.resource_id)';

			} else {
				$query = 'SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name", SUM(d.time_used) AS "hours", CONCAT( ROUND( ( Count_Filled_Percentage("'.$last_date_of_month.'",SUM(d.time_used)) ), 2 ), "%" ) AS "filled" FROM users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.$month_format." ".$year.'" and d.name = "'.$employee.'" GROUP BY c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y")
				ORDER BY c.name, MAX(d.work_date) DESC, MAX(d.resource_id)';
			}
			// $query = 'SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name", SUM(d.time_used) AS "hours", CONCAT( ROUND( ( Count_Filled_Percentage('.$last_date_of_month.',SUM(d.time_used)) ), 2 ), "%" ) AS "filled" FROM users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.$month_format." ".$year.'" GROUP BY c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y") ORDER BY c.name, d.work_date DESC, d.resource_id';
		} else {
			$query = 'SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name", SUM(d.time_used) AS "hours", CONCAT( ROUND( ( Count_Filled_Percentage("'.$last_date_of_month.'",SUM(d.time_used)) ), 2 ), "%" ) AS "filled" FROM users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.$month_format." ".$year.'" and c.name = "'.$manager.'" GROUP BY c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y")
			ORDER BY c.name, MAX(d.work_date) DESC, MAX(d.resource_id)';
		}

		// if($employee==null || $employee=='Select Employee'){
		// 	$query = 'SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name", SUM(d.time_used) AS "hours", CONCAT( ROUND( ( Count_Filled_Percentage('.$last_date_of_month.',SUM(d.time_used)) ), 2 ), "%" ) AS "filled" FROM users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.$month_format." ".$year.'" GROUP BY c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y") ORDER BY c.name, d.work_date DESC, d.resource_id';
		// } else {
		// 	$query = 'SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name", SUM(d.time_used) AS "hours", CONCAT( ROUND( ( Count_Filled_Percentage('.$last_date_of_month.',SUM(d.time_used)) ), 2 ), "%" ) AS "filled" FROM users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.$month_format." ".$year.'" and d.name = "'.$employee.'" GROUP BY c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y") ORDER BY c.name, d.work_date DESC, d.resource_id';
		// }
		// $query = 'SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name", SUM(d.time_used) AS "hours", CONCAT( ROUND( ( Count_Filled_Percentage('.$last_date_of_month.',SUM(d.time_used)) ), 2 ), "%" ) AS "filled" FROM users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.$month_format." ".$year.'" GROUP BY c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y") ORDER BY c.name, d.work_date DESC, d.resource_id';
		if($manager !=null && $manager !='Select Manager' && $employee !=null && $employee !='Select Employee'){
			$query = 'SELECT c.name AS "manager", DATE_FORMAT(d.work_date, "%b %Y") AS "month_year", d.name AS "name", SUM(d.time_used) AS "hours", CONCAT( ROUND( ( Count_Filled_Percentage("'.$last_date_of_month.'",SUM(d.time_used)) ), 2 ), "%" ) AS "filled" FROM users c LEFT JOIN ( SELECT a.id, a.name, a.reporting_manager, b.resource_id, b.time_used, b.work_date FROM users a LEFT JOIN user_timesheets b ON a.id = b.resource_id ) d ON c.id = d.reporting_manager WHERE DATE_FORMAT(d.work_date, "%b %Y") = "'.$month_format." ".$year.'" and c.name = "'.$manager.'" and d.name = "'.$employee.'" GROUP BY c.name, d.name, DATE_FORMAT(d.work_date, "%b %Y") 
			ORDER BY c.name, MAX(d.work_date) DESC, MAX(d.resource_id)';

		}

		$notfilled = 'SELECT m.name AS manager, DATE_FORMAT("'.$last_date_of_month.'", "%b %Y") AS month_year, u.name AS name, 0 AS hours, "0%" AS filled FROM users u LEFT JOIN users m ON m.id = u.reporting_manager WHERE u.company_id = 10 AND u.role = 3 AND u.status = 1 AND u.deleted = 1 AND NOT EXISTS ( SELECT 1 FROM user_timesheets t WHERE t.resource_id = u.id AND DATE_FORMAT(t.work_date, "%b %Y") = "'.$month_format." ".$year.'" ) ORDER BY m.name, u.name';

		// echo $notfilled;
		// exit();

		try {
			$stmtlist = $conn->execute($notfilled);
			$notfilledlist = $stmtlist->fetchAll('assoc');
        } catch (\Exception $e) {
            // Handle or log the error
            echo 'Query failed: ' . $e->getMessage();
        }
		
		// dd($query);

		try {
			$stmtProduct = $conn->execute($query);
			$list = $stmtProduct->fetchAll('assoc');
        } catch (\Exception $e) {
            // Handle or log the error
            echo 'Query failed: ' . $e->getMessage();
        }
		
		// $stmtProduct = $conn->execute($query);
		// $list = $stmtProduct->fetchAll('assoc');

		$user_data = $this->Users->find()
            ->select(['id', 'name'])
            ->where(["role" => 3, "status" => 1, "deleted" => 1])
            ->order(["name" => "ASC"])
            ->toArray();

		$manager_data = $this->Users->find()
        ->select(['id', 'name'])
        ->where(["role" => 3, "status" => 1, "deleted" => 1,'role_name LIKE' => "%4%"])
        ->order(["name" => "ASC"])
        ->toArray();
		// dd($manager_data);	

		$today=date('Y-m-d');
        $todayattendence = $this->EmployeePunchTime->find('All')
            ->where(['dom' => $today])
            ->toArray();
        $totalemp =count($todayattendence); 

		$this->set(compact('list','manager','year','month','employee','user_data','totalemp','manager_data','notfilledlist'));
	}

	public function attendancePunchTimeReport($month=null,$year=null) {

		$this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		// dd($userSession['name']);
		$user_id = $userSession['id'];

		if($month==null) {
			$month=date('m');
		} else {
			$month=$month;
		}
		if($year==null){
			$year=date('Y');
		} else {
			$year=$year;
		}
		
		$query ="SELECT DISTINCT u.emp_id, u.emp_name AS emp, u.manager_name, e.dom AS date, e.intime, e.outtime, TIMEDIFF(e.outtime, e.intime) AS total_time, TIMEDIFF(e.intime, '10:00:00') AS Late_by, CASE WHEN e.emp IS NOT NULL THEN 'Present' ELSE 'Absent' END AS status FROM ( SELECT u1.id AS emp_id, u1.name AS emp_name, u2.name AS manager_name FROM users u1 INNER JOIN users u2 ON u1.reporting_manager = u2.id WHERE u1.deleted = 1 AND u1.company_id = 10 AND u1.role = 3 AND u1.status = 1 ) AS u INNER JOIN emp_punch_time e ON e.emp = u.emp_name AND month(e.dom)=". $month ." AND year(e.dom)=". $year ." AND e.emp = "."'".$userSession['name']."'";
		
		$stmtProduct = $conn->execute($query);
		$list = $stmtProduct->fetchAll('assoc');
		
		$query1 = "SELECT leave_type, from_date, to_date, status, wfh_type FROM `leaves` WHERE `created_by` = ".$user_id." AND month(from_date) = ".$month." AND year(from_date) = ".$year." AND (status = 'Pending' OR status = 'Approved')";
		$stmtProduct1 = $conn->execute($query1);
		$leaves = $stmtProduct1->fetchAll('assoc');
		$this->set(compact('list','month','year','leaves'));
	}

	public function exportPunchTimeReport($month = null, $year = null)
		{
			$this->Authorization->skipAuthorization();
			$this->viewBuilder()->setLayout('default_new');
			$conn = ConnectionManager::get('default');
			$session = new \Cake\Http\Session();
			$userSession = $session->read('data');
			$user_id = $userSession['id'];
			// validation for valid user
			$roleArray = $userSession['role_name'];
			$validList = [4, 6, 9, 10];
			$this->routeValidation($roleArray,$validList);

			if ($month == null) {
				$month = date('m');
			}
			if ($year == null) {
				$year = date('Y');
			}

			// $subquery = "SELECT MAX(e.id) as id, e.emp, DATE(e.dom) AS date
			// 			FROM emp_punch_time e
			// 			WHERE MONTH(e.dom) = '$month' AND YEAR(e.dom) = '$year'
			// 			GROUP BY e.emp, DATE(e.dom)";
			$user_query="SELECT id,name FROM `users` as u1 WHERE u1.deleted = 1 AND u1.company_id = 10 AND u1.role = 3 AND u1.status = 1";
			$stmtProduct_user = $conn->execute($user_query);
			$users_list = $stmtProduct_user->fetchAll('assoc');

			$query = "SELECT DISTINCT u.emp_id, u.emp_name AS emp, u.manager_name, e.DATE AS DATE, e.intime, e.outtime, TIMEDIFF(e.outtime, e.intime) AS total_time, TIMEDIFF(e.intime, '10:00:00') AS Late_by, CASE WHEN e.emp IS NOT NULL THEN 'Present' ELSE 'Absent' END AS STATUS FROM ( SELECT u1.id AS emp_id, u1.name AS emp_name, u2.name AS manager_name FROM users u1 INNER JOIN users u2 ON u1.reporting_manager = u2.id WHERE u1.deleted = 1 AND u1.company_id = 10 AND u1.role = 3 AND u1.status = 1 ) AS u LEFT JOIN ( SELECT MAX(e.id) AS id, e.emp, DATE(e.dom) AS DATE, MAX(e.intime) AS intime, MAX(e.outtime) AS outtime FROM emp_punch_time e WHERE MONTH(e.dom) = '$month' AND YEAR(e.dom) = '$year' GROUP BY e.emp, DATE(e.dom) ) AS e ON e.emp = u.emp_name ORDER BY u.emp_id, e.DATE";

			$stmtProduct = $conn->execute($query);
			$list = $stmtProduct->fetchAll('assoc');

			// Retrieve leave data
			$query1 = "SELECT leave_type, from_date, to_date, status,reason, wfh_type,wfh_flag,created_by FROM `leaves` WHERE (MONTH(from_date) = '$month' OR MONTH(to_date) = '$month') AND YEAR(from_date) = '$year' AND (status ='Pending' OR status='Approved')";
			$stmtProduct1 = $conn->execute($query1);
			$leaves = $stmtProduct1->fetchAll('assoc');

			$this->set(compact('users_list','list', 'month', 'year', 'leaves'));
		}

		public function sumOfApprovedLeave($userId)
    {
       
        $leaveCount =  $this->getTableLocator()->get('LeaveCount');
        
        $month = date('m');
        if ($month < 4) {

            $year = date('Y', strtotime('-1 year'));
            $nextYear = date('Y');
        } else {

            $year = date('Y');
            $nextYear = date('Y', strtotime('+1 year'));
        }

        // echo $year;
        // echo $nextYear;
        // die;

        $userLeaveCL = $this->Users->find()
            ->select([
                'cl' => 'Users.cl',
                'sumCL' => 'sum(LeaveCount.cl)',
                'comp_off' => 'Users.comp_off',
                'sumComp' => 'sum(LeaveCount.comp_off)',
                'lwp' => 'Users.lwp',
                'sumLWP' => 'sum(LeaveCount.lwp)'
            ])
            ->join([
                'LeaveCount' => [
                    'table' => 'leave_counting',
                    'type' => 'LEFT',
                    'conditions' => ['LeaveCount.user_id = Users.id', "LeaveCount.leave_date >= '$year-04-01'", "LeaveCount.leave_date <= '$nextYear-03-31'"]
                ]
            ])
            ->where(['Users.id' => $userId])
            ->toArray();
            // dd($userLeaveCL);

        $userLeaveELSL = $this->Users->find()
            ->select([
                'el' => 'Users.el',
                'sl' => 'Users.sl',
                'sumEL' => 'SUM(LeaveCount.el)',
                'sumSL' => 'SUM(LeaveCount.sl)',
            ])
            ->join([
                'LeaveCount' => [
                    'table' => 'leave_counting',
                    'type' => 'LEFT',
                    'conditions' => ['LeaveCount.user_id = Users.id']
                ]
            ])
            ->where(['Users.id' => $userId])
            ->toArray();

        $myLeave = ['cl' => $userLeaveCL[0]->cl, 'sumCL' => $userLeaveCL[0]->sumCL, 'el' => $userLeaveELSL[0]->el, 'sl' => $userLeaveELSL[0]->sl, 'sumEL' => $userLeaveELSL[0]->sumEL, 'sumSL' => $userLeaveELSL[0]->sumSL, 'lwp' => $userLeaveCL[0]->lwp, 'sumLWP' => $userLeaveCL[0]->sumLWP, 'comp_off' => $userLeaveCL[0]->comp_off, 'sumComp' => $userLeaveCL[0]->sumComp];

        // echo '<pre>';
        // print_r($myLeave);
        // die;

        // $leaveCountQuery = $leaveCount->find();
        // $myLeave = $leaveCountQuery
        //     ->select([
        //         'sumEl' => $leaveCountQuery->func()->sum('LeaveCount.el'),
        //         'sumSl' => $leaveCountQuery->func()->sum('LeaveCount.sl'),
        //         'sumCl' => $leaveCountQuery->func()->sum('LeaveCount.cl'),
        //         'sumCompOff' => $leaveCountQuery->func()->sum('LeaveCount.comp_off'),
        //         'sumLwp' => $leaveCountQuery->func()->sum('LeaveCount.lwp'),
        //         'u.el',
        //         'u.cl',
        //         'u.sl',
        //         'u.comp_off',
        //         'u.lwp'
        //     ])
        //     ->join([
        //         'table' => 'users',
        //         'alias' => 'u',
        //         'type' => 'right',
        //         'conditions' => 'u.id = LeaveCount.user_id'
        //     ])
        //     ->where([
        //         'u.id = ' . $userId,
        //         'OR' => [
        //             'LeaveCount.leave_date BETWEEN :start AND :end',
        //             'LeaveCount.id is null'
        //         ]
        //     ])
        //     ->bind(':start', $year . '-04-01', 'date')
        //     ->bind(':end',   $nextYear . '-03-31', 'date')
        //     ->toArray();

        return $myLeave;
    }

		public function userTotalLeaveReport($month = null, $year = null)
		{
			$this->Authorization->skipAuthorization();
			$this->viewBuilder()->setLayout('default_new');
			$conn = ConnectionManager::get('default');
			$session = new \Cake\Http\Session();
			$userSession = $session->read('data');
			$user_id = $userSession['id'];
			$roleArray = $userSession['role_name'];
			// validation for valid user
			$validList = [10,4,13];
			$this->routeValidation($roleArray,$validList);
			// dd($user_id);
			if (in_array(4, $roleArray)) {

			   if ($month == null) {
				$month = date('m');
			}
			if ($year == null) {
				$year = date('Y');
			}

			// Retrieve leave data
			// $query1 = "SELECT leave_type, from_date, to_date, status,reason, wfh_type,created_by FROM `leaves` WHERE MONTH(from_date) = '$month' AND YEAR(from_date) = '$year'";
			$query = "SELECT users.id AS uid, users.email, users.name, users.el AS uel, users.cl AS ucl, users.sl AS usl, users.lwp AS lwp, users.comp_off AS comp_off, SUM(leave_counting.el) AS el, SUM(leave_counting.cl) AS cl, SUM(leave_counting.sl) AS sl, SUM(leave_counting.lwp) AS lwp, SUM(leave_counting.comp_off) AS comp_off, ROUND( ( users.el - SUM(leave_counting.el) ), 1 ) AS tot_el, ( users.cl - SUM(leave_counting.cl) ) AS tot_cl, ( users.sl - SUM(leave_counting.sl) ) AS tot_sl, ( users.lwp - SUM(leave_counting.lwp) ) AS tot_lwp, ( users.comp_off - SUM(leave_counting.comp_off) ) AS tot_comp_off FROM users LEFT JOIN leave_counting ON leave_counting.user_id = users.id WHERE users.deleted = 1 AND users.company_id = 10 AND users.role = 3 AND users.status = 1 GROUP BY users.id";
			$stmtProduct = $conn->execute($query);
			$leaves = $stmtProduct->fetchAll('assoc');
			// dd($leaves['uid']);
			$leave_data = array();
			
			// foreach($leaves as $val) {
			// 	$leave_data['uid']=$val['uid'];
			// 	$leave_data['email']=$val['email'];
			// 	$leave_data['name']=$val['name'];
			// 	$myLeave = $this->sumOfApprovedLeave($val['uid']);
			// 	$leave_data['leaves'] = $myLeave;
			// }
			foreach ($leaves as $val) {
				$leave_data[] = array(
					'uid' => $val['uid'],
					'email' => $val['email'],
					'name' => $val['name'],
					'ucl' => $val['ucl'],
					'usl' => $val['usl'],
					'uel' => $val['uel'],
					'cl' => $val['cl'],
					'sl' => $val['sl'],
					'el' => $val['el'],
					'leaves' => array($this->sumOfApprovedLeave($val['uid'])
					)
				);
			}
			// dd($leave_data);

			$this->set(compact('month', 'year', 'leaves','leave_data'));
			} else {
				echo "Access Denied..!";
			   exit();
			}
		}

		public function userAppliedLeaveReport($from = null, $to = null)
		{
			$this->Authorization->skipAuthorization();
			$this->viewBuilder()->setLayout('default_new');
			$conn = ConnectionManager::get('default');
			$session = new \Cake\Http\Session();
			$userSession = $session->read('data');
			$user_id = $userSession['id'];
			$role = $userSession['role'];
			$roleArray = $userSession['role_name'];
			$validList = [10,4,13];
			$this->routeValidation($roleArray,$validList);

		if (in_array(4, $roleArray)) {
			// dd($user_id);
			// if($user_id==44 || $user_id==502) {

			if ($from == null) {
				$from = date('Y-m-01');
			}
			if ($to == null) {
				$to = date('Y-m-t');
			}

			// Retrieve leave data
			// $query1 = "SELECT leave_type, from_date, to_date, status,reason, wfh_type,created_by FROM `leaves` WHERE MONTH(from_date) = '$month' AND YEAR(from_date) = '$year'";
			// $query = "SELECT leaves.id,leaves.leave_type,leaves.applied_on,leaves.from_date,leaves.to_date,leaves.wfh_flag, leave_counting.id as lid,leave_counting.cl,leave_counting.sl,leave_counting.el,leave_counting.comp_off,leave_counting.lwp,users.name,users.id as uid FROM `leaves` LEFT JOIN leave_counting ON leaves.id=leave_counting.leave_id LEFT JOIN users ON users.id=leaves.created_by WHERE users.status=1 AND users.deleted=1 AND users.company_id=10 AND (leaves.status = 'Approved' OR leaves.status = 'Pending')";
			$query ="SELECT * FROM(SELECT lv.id AS leave_id,lv.wfh_flag AS wfh_flag, emp.id AS employee_id, emp.name AS employee_name, lv.leave_date as leavedate, lv.leave_type FROM (SELECT id, NAME FROM users) AS emp JOIN (SELECT id, leave_type, from_date, to_date, created_by FROM `leaves` WHERE (from_date BETWEEN '".$from."' AND '".$to."' OR to_date BETWEEN '".$from."' AND '".$to."' OR ('".$from."' BETWEEN from_date AND to_date AND '".$to."' BETWEEN from_date AND to_date)) AND (`status` = 'Approved' OR `status` = 'Pending')) AS lv_table ON emp.id = lv_table.created_by JOIN (SELECT id,leave_type,wfh_flag, DATE_ADD(from_date, INTERVAL days.day_num DAY) AS leave_date FROM `leaves` JOIN (SELECT 0 AS day_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS days ON DATEDIFF(to_date, from_date) >= days.day_num WHERE (from_date BETWEEN '".$from."' AND '".$to."' OR to_date BETWEEN '".$from."' AND '".$to."' OR ('".$from."' BETWEEN from_date AND to_date AND '".$to."' BETWEEN from_date AND to_date)) AND (`status` = 'Approved' OR `status` = 'Pending') ) AS lv ON lv_table.id = lv.id ) AS a LEFT JOIN leave_counting AS lc ON a.leave_id = lc.leave_id WHERE (lc.cl != 0 OR lc.el != 0 OR lc.sl != 0 OR comp_off != 0 OR lwp != 0)";
			$stmtProduct = $conn->execute($query);
			$leaves = $stmtProduct->fetchAll('assoc');
			
			// dd($leaves);

			$this->set(compact('from', 'to', 'leaves'));
			} else {
				echo "Access Denied..!";
			   exit();
			}
		}

		public function userCompLeave($from = null, $to = null)
		{
			$this->Authorization->skipAuthorization();
			$this->viewBuilder()->setLayout('default_new');
			$conn = ConnectionManager::get('default');
			$session = new \Cake\Http\Session();
			$userSession = $session->read('data');
			$user_id = $userSession['id'];
			$role = $userSession['role'];
			$roleArray = $userSession['role_name'];
			$validList = [10,4,13];
			$this->routeValidation($roleArray,$validList);

			if (in_array(4, $roleArray)) {

				$currentMonth = date('m');
				$currentYear = date('Y');

				if($from == null && $to == null) {
					if ($currentMonth >= 4) {
					$startDate = $currentYear . '-04-01';
					$endDate   = ($currentYear + 1) . '-03-31';
					$from = $startDate;
					$to = $endDate;
					} else {
						$startDate = ($currentYear - 1) . '-04-01';
						$endDate   = $currentYear . '-03-31';
						$from = $startDate;
						$to = $endDate;
					}

				} else {
					$startDate = $from;
					$endDate = $to;
				}

				$query = "SELECT leave_counting.*, users.name AS emp_name
						FROM leave_counting
						LEFT JOIN users ON users.id = leave_counting.user_id
						WHERE leave_counting.comp_off != 0
						AND leave_counting.leave_date BETWEEN '$startDate' AND '$endDate'";

				$stmtProduct = $conn->execute($query);
				$leaves = $stmtProduct->fetchAll('assoc');

				$provided = [];
				$taken = [];

				foreach ($leaves as $leave) {

					if ($leave['comp_off'] < 0) {
						$provided[] = $leave;   // company provided comp-off
					}

					if ($leave['comp_off'] > 0) {
						$taken[] = $leave;      // employee taken comp-off
					}
				}
				// dd($provided);

				$this->set(compact('provided','taken','from','to'));

			} else {
				echo "Access Denied..!";
				exit();
			}
		}

		public function leaveModule() {
			// dd($this->request->getData());
			$this->autoRender = false;
			$this->Authorization->skipAuthorization();
			$this->viewBuilder()->setLayout('default_new');
			$session = new \Cake\Http\Session();
			$userSession = $session->read('data');
			$user_name = $userSession['name'];

			if ($this->request->is('post')) {
				$leaveCount = $this->getTableLocator()->get('LeaveCount');
				$leaveInsert = $leaveCount->newEmptyEntity(); // Create a new entity

				$hasLeaveType = $this->request->getData('cl') || 
				$this->request->getData('sl') || 
				$this->request->getData('el') || 
				$this->request->getData('comp_off');

				if (!$hasLeaveType) {
					$this->Flash->error(__('Please select at least one leave type.'));
					return $this->redirect(['action' => 'userTotalLeaveReport']);
				}


				// $leave_type = $this->request->getData('leave_type');
				$leave_source = $this->request->getData('leave_source');
				// $qty = $this->request->getData('qty');

				// if ($leave_source == 'Addon') {
				// 	$leave = -$qty;
				// } else {
				// 	$leave = $qty;
				// }

				$leaveInsert->user_id = $this->request->getData('id');
				$leaveInsert->leave_id = 0;
				$leaveInsert->leave_date = date('Y-m-d');
				if($this->request->getData('cl')) {
					if ($this->request->getData('leave_source_cl')== 'Addon') {
						$leaveInsert->cl = -$this->request->getData('cl_qty');
					} else {
						$leaveInsert->cl = $this->request->getData('cl_qty');
					}
				}
				if($this->request->getData('sl')) {
					if ($this->request->getData('leave_source_sl')== 'Addon') {
						$leaveInsert->sl = -$this->request->getData('sl_qty');
					} else {
						$leaveInsert->sl = $this->request->getData('sl_qty');
					}
				}
				if($this->request->getData('el')) {
					if ($this->request->getData('leave_source_el')== 'Addon') {
						$leaveInsert->el = -$this->request->getData('el_qty');
					} else {
						$leaveInsert->el = $this->request->getData('el_qty');
					}
				}
				if($this->request->getData('comp_off')) {
					if ($this->request->getData('leave_source_comp_off')== 'Addon') {
						$leaveInsert->comp_off = -$this->request->getData('comp_off_qty');
					} else {
						$leaveInsert->comp_off = $this->request->getData('comp_off_qty');
					}
				}
				$leaveInsert->leave_desc = 'Manage-leave';
				$leaveInsert->action_by = $user_name;
				// $leaveInsert->$leave_type = $leave;
				// dd($leaveInsert);

				if ($leaveCount->save($leaveInsert)) {
					$this->Flash->success(__('Leave has been Saved'));
					return $this->redirect(['action' => 'userTotalLeaveReport']);
				} else {
					$this->Flash->error(__('Error saving leave.'));
					// Handle errors appropriately
				}
			}
		}

		public function updateLeaveData()
		{
			$this->autoRender = false;
			
			if ($this->request->is('ajax')) {
				$userId = $this->request->getData('userId');
				$colName = $this->request->getData('colName');
				$value = $this->request->getData('value');
				
				// Update database
				try {
					$user = $this->Users->get($userId);
					// dd($user);
					$user->$colName = $value;
					$this->Users->save($user);
					
					echo 'Data updated successfully.';
				} catch (RecordNotFoundException $e) {
					echo 'User not found.';
				}
			}
		}
}
