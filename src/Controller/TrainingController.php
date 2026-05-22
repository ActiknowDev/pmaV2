<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Datasource\ConnectionManager;

/**
 * Training Controller
 *
 * @property \App\Model\Table\TrainingTable $Training
 * @method \App\Model\Entity\Training[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TrainingController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
    	$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];

        $training = $this->paginate($this->Training);

        // validation for valid user
		$roleArray = $userSession['role_name'];
		$validList = [4, 10, 12];
		$this->routeValidation($roleArray,$validList);

        $this->set(compact('training'));
    }

    /**
     * View method
     *
     * @param string|null $id Training id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Authorization->skipAuthorization();
    	// $this->viewBuilder()->setLayout('default_new');
        
        $training = $this->Training->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('training'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->Authorization->skipAuthorization();
    	$this->viewBuilder()->setLayout('default_new');

        $training = $this->Training->newEmptyEntity();
        if ($this->request->is('post')) {
            $training = $this->Training->patchEntity($training, $this->request->getData());
            if(!$this->Training->exists(['name' => $this->request->getData('name')]) ){
                if ($this->Training->save($training)) {
                    $this->Flash->success(__('The training has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The training already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The training could not be saved. Please, try again.'));
        }
        $this->set(compact('training'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Training id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Authorization->skipAuthorization();
    	$this->viewBuilder()->setLayout('default_new');

        $training = $this->Training->get($id, [
            'contain' => [],
        ]);
        
        $this->set(compact('training'));
        $this->render('edit', 'ajax');
    }

    /**
     * Delete method
     *
     * @param string|null $id Training id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function update()
    {
        $this->Authorization->skipAuthorization();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $training = $this->Training->get($this->request->getData('id'));
            $training = $this->Training->patchEntity($training, $this->request->getData());
            if(!$this->Training->exists(['name' => $this->request->getData('name')]) ){
                if ($this->Training->save($training)) {
                    $this->Flash->success(__('The training has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The training already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The training could not be saved. Please, try again.'));
        }
    }

    public function delete($id = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'delete']);
        $training = $this->Training->get($id);
        if ($this->Training->delete($training)) {
            $this->Flash->success(__('The training has been deleted.'));
        } else {
            $this->Flash->error(__('The training could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function updateStatus($id,$status){      
		$this->autoRender=false;
		$this->Authorization->skipAuthorization();
		if ($this->request->is('ajax')){
			// $company = TableRegistry::get('Projects');
		    $query = $this->Training->query();
			if($query->update()
			    ->set(['status' => $status])
			    ->where(['id' => $id])
			    ->execute()){

                echo 1;
            }
			else{
			 	echo 0;
             }
            exit;
		}
    }


}
