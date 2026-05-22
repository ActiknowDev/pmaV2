<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * EmployeeWorkHistorys Controller
 *
 * @property \App\Model\Table\EmployeeWorkHistorysTable $EmployeeWorkHistorys
 * @method \App\Model\Entity\EmployeeWorkHistory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeeWorkHistorysController extends AppController
{

     public function initialize(): void
    {
        parent::initialize();

         $this->Authorization->skipAuthorization();
        //   $this->loadModel("EmployeeWorkHistorys");
        //   $this->loadModel("EmployeeWorkHistorySlips");
          $this->fetchTable("EmployeeWorkHistorys");
          $this->fetchTable("EmployeeWorkHistorySlips");
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
        $employeeWorkHistorys = $this->paginate($this->EmployeeWorkHistorys);

        $this->set(compact('employeeWorkHistorys'));
    }

    /**
     * View method
     *
     * @param string|null $id Employee Work History id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeWorkHistory = $this->EmployeeWorkHistorys->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('employeeWorkHistory'));
    }


  
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
         

        if ($this->request->is('post')) {
            $request_data = $this->request->getData();


        
         $new_arr = [];


                 foreach($request_data["slips"] as $slip){

                    $res = $this->save_image_work_hstry($slip);
                    $temp_arr["cmp_splip"] = $res;
                    $new_arr[] = $temp_arr;

                  }

                  $request_data["employee_work_history_slips"] = $new_arr;    

         $employeeWorkHistory = $this->EmployeeWorkHistorys->newEntity($request_data,["associated"=>["EmployeeWorkHistorySlips"]]);             


             if ($this->EmployeeWorkHistorys->save($employeeWorkHistory,["associated"=>["EmployeeWorkHistorySlips"]])) {

                $this->Flash->success(__('The employee work history has been saved.'));

               return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$this->request->getData("user_id")]);
            }

         // echo "<pre>";
         // print_r($request_data);
         // die;
            $this->Flash->error(__('The employee work history could not be saved. Please, try again.'));
        }
  
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Work History id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */


    function save_image_work_hstry($obj){

    if(!empty($obj->getClientFilename())){

        $fileName = $obj->getClientFilename();
                            
        $targetPath = 'img/'.'Sal/'.time().$fileName;
        $obj->moveTo($targetPath);

        return $targetPath;


    }

    return false;


  }


    public function edit($id = null)
    {
        
        if ($this->request->is(['patch', 'post', 'put'])) {

           


            $request_data = $this->request->getData();

            // echo "<pre>";
            // print_r($request_data);
            // die;

             $employeeWorkHistory = $this->EmployeeWorkHistorys->get($this->request->getData("id"));




         $new_arr = [];


                 foreach($request_data["cmp_splip"] as $slip){

                    $res = $this->save_image_work_hstry($slip);
                    $temp_arr["cmp_splip"] = $res;
                    $new_arr[] = $temp_arr;

                  }

                  $request_data["employee_work_history_slips"] = $new_arr; 


                  $employeeWorkHistory = $this->EmployeeWorkHistorys->patchEntity($employeeWorkHistory,$request_data,["associated"=>["EmployeeWorkHistorySlips"]]);



    if ($this->EmployeeWorkHistorys->save($employeeWorkHistory,["associated"=>["EmployeeWorkHistorySlips"]])) {
                $this->Flash->success(__('The employee work history has been saved.'));

                return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$this->request->getData("user_id")]);
            }
            $this->Flash->error(__('The employee work history could not be saved. Please, try again.'));
        }
       
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Work History id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete','get']);
        $employeeWorkHistory = $this->EmployeeWorkHistorys->get($id);
         $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        $role = $userSession['role'];


        $user_id = $employeeWorkHistory->user_id;

        if($role<=4){

        if ($this->EmployeeWorkHistorys->delete($employeeWorkHistory)) {
            $this->Flash->success(__('The employee work history has been deleted.'));
        } else {
            $this->Flash->error(__('The employee work history could not be deleted. Please, try again.'));
        }
        return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$user_id]);
    }

         return $this->redirect(['controller'=>'EmployeeDetails','action' => 'edit',$user_id]);
    }
}
