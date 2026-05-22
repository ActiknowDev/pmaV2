<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * EmployeeReferences Controller
 *
 * @property \App\Model\Table\EmployeeReferencesTable $EmployeeReferences
 * @method \App\Model\Entity\EmployeeReference[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeeReferencesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $employeeReferences = $this->paginate($this->EmployeeReferences);

        $this->set(compact('employeeReferences'));
    }

    /**
     * View method
     *
     * @param string|null $id Employee Reference id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Authorization->skipAuthorization();
        $employeeReference = $this->EmployeeReferences->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('employeeReference'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
     
        
        $this->Authorization->skipAuthorization();
        $employeeReference = $this->EmployeeReferences->newEmptyEntity();
        if ($this->request->is('post')) {
            $employeeReference = $this->EmployeeReferences->patchEntity($employeeReference, $this->request->getData());
            if ($this->EmployeeReferences->save($employeeReference)) {
                $this->Flash->success(__('The employee reference has been saved.'));

                return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$this->request->getData("user_id")]);
            }
            $this->Flash->error(__('The employee reference could not be saved. Please, try again.'));
        }
        $users = $this->EmployeeReferences->Users->find('list', ['limit' => 200]);
        $this->set(compact('employeeReference', 'users','user_id'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Reference id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
        $this->Authorization->skipAuthorization();
        $id = $this->request->getData("_id");

        $employeeReference = $this->EmployeeReferences->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeReference = $this->EmployeeReferences->patchEntity($employeeReference, $this->request->getData());
            if ($this->EmployeeReferences->save($employeeReference)) {
                $this->Flash->success(__('The employee reference has been saved.'));

                return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$this->request->getData("user_id_sent")]);
            }
            $this->Flash->error(__('The employee reference could not be saved. Please, try again.'));
        }
        $users = $this->EmployeeReferences->Users->find('list', ['limit' => 200]);
        $this->set(compact('employeeReference', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Reference id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        $role = $userSession['role'];
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post', 'delete','get']);
        if($role<=4){
        $employeeReference = $this->EmployeeReferences->get($id);
        $user_id = $employeeReference->user_id;
        if ($this->EmployeeReferences->delete($employeeReference)) {
            $this->Flash->success(__('The employee reference has been deleted.'));
        } else {
            $this->Flash->error(__('The employee reference could not be deleted. Please, try again.'));
        }

    }
 return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$user_id]);
    }
}
