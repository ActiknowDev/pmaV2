<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * EmployeeAcademics Controller
 *
 * @property \App\Model\Table\EmployeeAcademicsTable $EmployeeAcademics
 * @method \App\Model\Entity\EmployeeAcademic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeeAcademicsController extends AppController
{

     public function initialize(): void{

        parent::initialize();


         $this->Authorization->skipAuthorization();
     }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $employeeAcademics = $this->paginate($this->EmployeeAcademics);

        $this->set(compact('employeeAcademics'));
    }

    /**
     * View method
     *
     * @param string|null $id Employee Academic id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeAcademic = $this->EmployeeAcademics->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('employeeAcademic'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */

    public function save_academic_img($obj){



    if(!empty($obj->getClientFilename())){

        $fileName = $obj->getClientFilename();
                            
        $targetPath = 'img/'.'ACC/'.time().$fileName;
        $obj->moveTo($targetPath);

        return $targetPath;


    }

    return false;

  }



    public function add()
    {
        $employeeAcademic = $this->EmployeeAcademics->newEmptyEntity();
        if ($this->request->is('post')) {
            // print_r( $this->request->getData());
            // die;
            // $employeeAcademic = $this->EmployeeAcademics->patchEntity($employeeAcademic, $this->request->getData());
    $employeeAcademic->user_id = $this->request->getData('user_id');
    $employeeAcademic->ac_type = $this->request->getData('ac_type');
    $employeeAcademic->ac_org = $this->request->getData('ac_org');
    $employeeAcademic->ac_education = $this->request->getData('ac_education');
    $employeeAcademic->ac_passout = $this->request->getData('ac_passout');
    $res = $this->save_academic_img($this->request->getData('acc_certificate'));
    if($res){
      $employeeAcademic->acc_certificate = $res;  
    }

    $res = $this->save_academic_img($this->request->getData('acc_mark'));
    if($res){

    $employeeAcademic->acc_mark = $res;
  
    }
        if ($this->EmployeeAcademics->save($employeeAcademic)) {
            $this->Flash->success(__('The employee academic has been saved.'));

            return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$this->request->getData("user_id")]);
        }
        $this->Flash->error(__('The employee academic could not be saved. Please, try again.'));
        }
        $users = $this->EmployeeAcademics->Users->find('list', ['limit' => 200]);
        $this->set(compact('employeeAcademic', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Academic id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeeAcademic = $this->EmployeeAcademics->get($this->request->getData('_id'), [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            

           
            $employeeAcademic->user_id = $this->request->getData('user_id');
            $employeeAcademic->ac_type = $this->request->getData('ac_type');
            $employeeAcademic->ac_org = $this->request->getData('ac_org');
             $employeeAcademic->ac_education = $this->request->getData('ac_education');
            $employeeAcademic->ac_passout = $this->request->getData('ac_passout');
              $res = $this->save_academic_img($this->request->getData('acc_certificate'));
            if($res){
              $employeeAcademic->acc_certificate = $res;  
            }

            $res = $this->save_academic_img($this->request->getData('acc_mark'));
            if($res){

            $employeeAcademic->acc_mark = $res;
          
            }


            if ($this->EmployeeAcademics->save($employeeAcademic)) {
                $this->Flash->success(__('The employee academic has been saved.'));

                 return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$this->request->getData("user_id")]);
            }
            $this->Flash->error(__('The employee academic could not be saved. Please, try again.'));
        }
        
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Academic id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete','get']);

        $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        $role = $userSession['role'];

        $employeeAcademic = $this->EmployeeAcademics->get($id);
        $user_id = $employeeAcademic->user_id;

         if($role<=4){

        if ($this->EmployeeAcademics->delete($employeeAcademic)) {
            $this->Flash->success(__('The employee academic has been deleted.'));
        } else {
            $this->Flash->error(__('The employee academic could not be deleted. Please, try again.'));
        }

    }
         return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$user_id]);
    }
}
