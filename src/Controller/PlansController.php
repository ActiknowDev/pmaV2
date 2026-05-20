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

class PlansController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();
		// $this->loadComponent('Paginator');
		$this->loadComponent('Flash');
		// $this->loadComponent('RequestHandler');
		$this->Users = $this->fetchTable('Users');
		$this->SupportPlan = $this->fetchTable('SupportPlan');
		$this->Plans = $this->fetchTable('Plans');
	}

	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		$this->Authentication->addUnauthenticatedActions(['login', 'signup']);
	}

	// Plan Listing from plan table 
	public function index()
	{
		$this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		// validation for valid user
		$roleArray = $userSession['role_name'];
		$validList = [4, 6, 9, 10, 12];
		$this->routeValidation($roleArray,$validList);
		$plans = $this->Plans
			->find()
			->where(['deleted' => 0])
			->all();
		$this->set(compact('plans'));
	}
	// End 

	// Add a plan 
	public function addplan()
	{
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];
		
		if ($this->request->is(['patch', 'post', 'put'])) {
			$plans = $this->Plans->newEmptyEntity();
			$plans['user_id'] = $user_id;
			$plans['plan_name'] = $this->request->getData('plan_name');
			$plans['plan_duration'] = $this->request->getData('plan_duration');
			$plans['price'] = $this->request->getData('price');
			if ($this->Plans->save($plans)) {
				$this->Flash->success(__('Your Plan has been saved.'));
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
	public function editPlan()
	{
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];

        if ($this->request->is(['patch', 'post', 'put'])) {
			$plans = $this->Plans->get($this->request->getData('id'));
			$plans['user_id'] = $user_id;
			$plans['plan_name'] = $this->request->getData('plan_name');
			$plans['plan_duration'] = $this->request->getData('plan_duration');
			$plans['price'] = $this->request->getData('price');
			if ($this->Plans->save($plans)) {
				$this->Flash->success(__('Your Plan has been updated.'));
				return $this->redirect(['action' => 'index']);
			}
            $this->Flash->error(__('Plan could not be saved. Please, try again.'));
        }
	}
	// End 

	// Delete a Plan
	public function deletePlan($id)
	{
	  	$this->Authorization->skipAuthorization();
	  	$maintainaceExist = $this->SupportPlan->find()->select(['plan_id'])->where(['plan_id' => $id, 'deleted' => 0])->first();
		if(!empty($maintainaceExist['plan_id']))
		{
			$this->Flash->error(__('This plan is being used for Maintenance hence it cannot be deleted.'));
			return $this->redirect(['action' => 'index']);
		}
		else
		{
			// Delete Plan Id
			$query = $this->Plans->query();
			$query->update()
				->set(['deleted' => 1])
				->where(['id' => $id]);
			if ($query->execute())
			{
				$this->Flash->success(__('Plan Deleted Successfully!'));
				return $this->redirect(['action' => 'index']);
			}
		}
	}
	// End 
}
?>
