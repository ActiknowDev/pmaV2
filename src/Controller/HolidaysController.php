<?php

namespace App\Controller;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Utility\Security;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Client;

class HolidaysController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();
		// $this->loadComponent('Paginator');
		$this->loadComponent('Flash');
		// $this->loadComponent('RequestHandler');
		// $this->SupportPlan = TableRegistry::get('SupportPlan');
		// $this->Holidays = TableRegistry::get('Holidays');
		// $this->Users = TableRegistry::get('Users');
		  $this->Holidays = $this->fetchTable('Holidays');
		$this->Users = $this->fetchTable('Users');
	}

	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		$this->Authentication->addUnauthenticatedActions(['login', 'signup']);
	}

	// Plan Listing from plan table 
	public function index()
	{
		$this->viewBuilder()->setLayout('custom_dev');
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');

		// $Holiday = $this->Holidays
		// 	->find()
		// 	->where(['deleted' => 0])
		// 	->all();
		// 	// dd($Holiday);
		// $this->set(compact('Holiday'));
	}

	public function fetchHolidays()
	{
		$this->autoRender = false;
		$this->Authorization->skipAuthorization();

		$session = $this->request->getSession();
		$userSession = $session->read('data');
		$userId = $userSession['id'];
		$role = $userSession['role'];
		$roleArray = $userSession['role_name'];

		$conn = \Cake\Datasource\ConnectionManager::get('default');

		$sqlQuery = "SELECT * FROM holidays WHERE deleted = 0 ORDER BY id";
		$stmtProduct = $conn->execute($sqlQuery);
		$eventArray = $stmtProduct->fetchAll('assoc');

		echo json_encode($eventArray);
		exit;
	}

	// public function fetchHolidays()
	// {
	// 	$this->autoRender = false;
	// 	$this->Authorization->skipAuthorization();
	// 	$this->viewBuilder()->setLayout('default_new');
		
	// 	$session = $this->request->getSession();
	// 	$userSession = $session->read('data');
	// 	$userId = $userSession['id'];
	// 	$role = $userSession['role'];
	// 	$roleArray = $userSession['role_name'];

	// 	$conn = ConnectionManager::get('default');
	// 	$sqlQuery = "SELECT * FROM holidays WHERE deleted=0 ORDER BY id";
	// 	$stmtProduct = $conn->execute($sqlQuery);
	// 	$eventArray = $stmtProduct->fetchAll('assoc');
	// 	// dd($eventArray);
	// 	echo json_encode($eventArray);
		
	// 	// $this->set(compact('eventArray'));
	// 	// $this->viewBuilder()->setOption('serialize', ['eventArray']);
	// }
	// End 

	// Add a plan 
	public function addHolidays()
	{

		// dd($this->request->getData());
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];
		
		if ($this->request->is(['patch', 'post', 'put'])) {
			$Holiday = $this->Holidays->newEmptyEntity();
			$Holiday['created_by'] = $user_id;
			$Holiday['title'] = $this->request->getData('title');
			$Holiday['start'] = $this->request->getData('start');
			$Holiday['end'] = $this->request->getData('end');
			if ($this->Holidays->save($Holiday)) {
				$this->Flash->success(__('Holiday has been saved.'));
				return $this->redirect(['action' => 'index']);
			}
			else
			{
				$this->Flash->error(__('Something went wrong'));
				return $this->redirect(['action' => 'index']);
			}
		}
	}
	// End 

	// Edit plan 
	public function editHolidays()
	{
		// dd($this->request->getData());
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];

        if ($this->request->is(['patch', 'post', 'put'])) {
			$Holiday = $this->Holidays->get($this->request->getData('id'));
			$Holiday['created_by'] = $user_id;
			$Holiday['title'] = $this->request->getData('title');
			$Holiday['start'] = $this->request->getData('start');
			$Holiday['end'] = $this->request->getData('end');
			if ($this->Holidays->save($Holiday)) {
				$this->Flash->success(__('Your Holiday has been updated.'));
				return $this->redirect(['action' => 'index']);
			}
            $this->Flash->error(__('Plan could not be saved. Please, try again.'));
        }
	}
	// End 

	// Delete a Plan
	// public function deleteHolidays()
	// {
	// 	$id = $this->getRequest()->getQuery('id');
	//   	$this->Authorization->skipAuthorization();
	// 		// Delete Plan Id
	// 		$query = $this->Holidays->query();
	// 		$query->update()
	// 			->set(['deleted' => 1])
	// 			->where(['id' => $id]);
	// 		if ($query->execute())
	// 		{
	// 			// $this->Flash->success(__('Deleted Successfully!'));
	// 			// return $this->redirect(['action' => 'index']);
	// 			echo 1;
	// 			exit();
	// 		}
	// }

	public function deleteHolidays()
	{
		$id = $this->getRequest()->getQuery('id');
		$this->Authorization->skipAuthorization();

		// Delete Plan Id
		$query = $this->Holidays->updateQuery();

		$query->set(['deleted' => 1])
			->where(['id' => $id]);

		if ($query->execute()) {
			echo 1;
			exit();
		}
	}
	// End 
}
?>
