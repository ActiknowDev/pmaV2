<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Security;
use Cake\Database\Expression\QueryExpression;
use PhpParser\Node\Stmt\Else_;

class ClientsController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();

		//$this->loadComponent('Paginator');  
		$this->loadComponent('Flash'); // Include the FlashComponent
		//$this->loadComponent('RequestHandler');
	}

	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		// Configure the login action to not require authentication, preventing
		// the infinite redirect loop issue
		$this->Authentication->addUnauthenticatedActions(['login', 'signup']);
	}

	public function index()
	{
		//set layout
		$this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		//$this->loadComponent('Paginator');
		$this->paginate();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$role = $userSession['role'];
		$parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];


		$client = $this->fetchTable('Users');
			// validation for valid user
		$roleArray = $userSession['role_name'];
		$validList = [4, 6, 9, 10];
		$this->routeValidation($roleArray,$validList);



		$clients = $client
			->find()
			->where(['deleted' => 1, 'role' => 2, 'company_id' => $parent_id])
			->order(['created' => 'DESC'])
			->all();

		// echo '<pre>';
		// print_r($clients);
		// die;

		//retreive total companies number		    
		$totalClients	= 	$client
			->find()
			->where(['deleted' => 1, 'role' => 2, 'company_id' => $parent_id])
			->count();

		//retreive total Active Companies number
		$totalActiveClients	= $client->find()->where(['deleted' => 1, 'role' => 2, 'status' => 1, 'company_id' => $parent_id])->count();

		$totalInactiveClients	= $client->find()->where(['deleted' => 1, 'role' => 2, 'status' => 0, 'company_id' => $parent_id])->count();

		$user = $this->getTableLocator()->get("Users");
		$pointContact = $user->find()
			->where(['find_in_set(\'' . 7 . '\',role_name)'])
			->select(['name', 'id'])
			->toArray();

		$this->set('title', 'PMA');
		$this->set(compact('clients', 'pointContact', 'totalClients', 'totalActiveClients', 'totalInactiveClients'));
	}

	
	public function add()
	{
		$this->autoRender = false;
		$conn = ConnectionManager::get('default');
		$this->Authorization->skipAuthorization();

		if ($this->request->is('post')) {
			$this->Users = $this->fetchTable('Users');


			$session = new \Cake\Http\Session();
			$userSession = $session->read('data');
			$role = $userSession['role'];
			$parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];

			
			$users = $this->Users->find('all')
				->select(['id'])
				->where(['client_name' => $this->request->getData('client_name'), 'deleted' => 1, 'company_id' => $parent_id])
				->toArray();

			if (count($users) > 0) {
				$this->Flash->error(__('This Client already exist.'));
				// $data['sussess'] = 'false';
				echo 0;
			} else {
				$client = $this->Users->newEmptyEntity();

				$client = $this->Users->patchEntity($client, $this->request->getData());
				$client->company_id = $parent_id;
				$client->role = 2;
				$client->status = 1;
				$client->point_of_contact = $this->request->getData('pointOfContant');
				$this->Users->save($client);

				$last_followup_date = date("Y-m-d", strtotime($this->request->getData('last_followup_date')));
				$next_followup_date = date("Y-m-d", strtotime($this->request->getData('next_followup_date')));

				// echo $next_followup_date;
				// die;
				$clientDataTbl = $this->getTableLocator()->get('ClientData');
				$clientData = $clientDataTbl->newEmptyEntity();

				$clientData->client_id = $client['id'];
				$clientData->potential = $this->request->getData('potential');
				$clientData->relationship = $this->request->getData('relationship');
				$clientData->last_followup_date = $last_followup_date;
				$clientData->next_followup_date = $next_followup_date;
				$clientData->description = $this->request->getData('description');
				if ($clientDataTbl->save($clientData)) {
					// $data['sussess'] = 'true';
					echo 1;
				}
			}
		}
		die;
	}
	//change status
	public function updateStatus($id, $status)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		if ($this->request->is('ajax')) {
			$client = $this->fetchTable('Users');
			$query = $client->query();
			$query->update()
				->set(['status' => $status])
				->where(['id' => $id])
				->execute();
			return $this->redirect(['controller' => 'Clients', 'action' => 'index']);
		}
	}
	// delete data 
	public function delete($id)
	{
		$this->Authorization->skipAuthorization();
		$client = $this->fetchTable('Users');
		$query = $client->query();
		$query->update()
			->set(['deleted' => 0])
			->where(['id' => $id]);

		if ($query->execute())
			echo 1;
		else
			echo 0;
		die;
	}
	public function edit($id)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		$client = $this->fetchTable('Users');
		$client = $client
			->findById($id)
			->firstOrFail();

		$user = $this->getTableLocator()->get('Users');

		$userClientData = $user->find('all')->contain([
			"client_data"
		])->where(['Users.id' => $id])->toList();
		// print_r($client->point_of_contact);
		// die;

		echo json_encode($userClientData);
		die;
	}


	public function editData($id)
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();
		$this->Users = $this->fetchTable('Users');
		$client = $this->Users
			->findById($id)
			->firstOrFail();

		$clientDataTbl = $this->getTableLocator()->get('ClientData');
		if ($this->request->is(['post', 'put'])) {
			$client = $this->Users->patchEntity($client, $this->request->getData());
			$client->point_of_contact = $this->request->getData('pointOfContant');
			if ($this->Users->save($client)) {
				$clientData = $clientDataTbl->find('all')->where(['client_id' => $client->id])->toArray();
				if (!empty($clientData)) {

					$clientDataTbl->deleteMany($clientData);
					$clientData = $clientDataTbl->newEmptyEntity();
					$last_followup_date = date("Y-m-d", strtotime($this->request->getData('last_followup_date')));
					$next_followup_date = date("Y-m-d", strtotime($this->request->getData('next_followup_date')));
					$clientData->client_id = $id;
					$clientData->potential = $this->request->getData('potential');
					$clientData->relationship = $this->request->getData('relationship');
					$clientData->last_followup_date = $last_followup_date;
					$clientData->next_followup_date = $next_followup_date;
					$clientData->description = $this->request->getData('description');
					$clientDataTbl->save($clientData);
					echo true;
				} else {
					$clientData = $clientDataTbl->newEmptyEntity();
					$clientData->client_id = $id;
					$clientData->potential = $this->request->getData('potential');
					$clientData->relationship = $this->request->getData('relationship');
					$clientData->last_followup_date = $this->request->getData('last_followup_date');
					$clientData->next_followup_date = $this->request->getData('next_followup_date');
					$clientData->description = $this->request->getData('description');
					$clientDataTbl->save($clientData);
					echo true;
				}
			}

			die;
		}
	}

	public function listAll($str)
	{
		$this->autoRender = false;
		$conn = ConnectionManager::get('default');
		$this->Authorization->skipAuthorization();

		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$role = $userSession['role'];
		$parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];


		$query = "SELECT id,client_name FROM users WHERE deleted=1 AND client_name LIKE '%" . $str . "%' AND company_id=" . $parent_id;
		$stmtProduct = $conn->execute($query);
		$list = $stmtProduct->fetchAll('assoc');


		foreach ($list as $l) {
			$result[] = $l['client_name'];
		}

		echo json_encode($result);
	}

	public function clientView($id)
	{
		//set layout
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$this->Authorization->skipAuthorization();

		$this->Projects = $this->fetchTable('Projects');
		$client = $this->fetchTable('Users');
		$client = $client
			->findById($id)
			->firstOrFail();

		$query = "SELECT p.*,pm.name as manager,tl.name as lead FROM projects p JOIN users pm ON p.project_manager_id=pm.id JOIN users tl ON p.tech_lead_id=tl.id WHERE p.client_id=" . $id;
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
		$this->set(compact('client', 'projects', 'complete', 'going'));
	}
}