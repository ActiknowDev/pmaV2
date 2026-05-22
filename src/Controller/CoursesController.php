<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Courses Controller
 *
 * @property \App\Model\Table\CoursesTable $Courses
 * @method \App\Model\Entity\Course[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CoursesController extends AppController
{
    public function initialize(): void{
        parent::initialize();
        
        // $this->loadModel('Training');
        $this->fetchTable('Training');
        
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    public function index()
    {
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('default_new');

        $trainingTable = $this->fetchTable('Training');

        // $courses = $this->paginate($this->Courses);

        $courses = $this->Courses->find('all', [
            'contain' => ['Training'],
        ])
        ->order(['Courses.id' => 'DESC'])
        ->toArray();

        $training = $trainingTable->find('list', [
            "keyField" => "id",
            "valueField" => "name"
        ])
        ->where(['status' => 1])
        ->toArray();

        $this->set(compact('courses', 'training'));
    }
    // public function index()
    // {
    //     $this->Authorization->skipAuthorization();
    // 	$this->viewBuilder()->setLayout('default_new');

    //     // $courses = $this->paginate($this->Courses);
    //     $courses = $this->Courses->find('all', [
    //         'contain' => ['Training'],
    //     ])->order(['Courses.id'=>'DESC'])->toArray();

    //     $training = $this->Training->find('list',[ "keyField"=>"id", "valueField"=>"name"])->where(['status'=>1])->toArray();

    //     $this->set(compact('courses','training'));
    // }

    /**
     * View method
     *
     * @param string|null $id Course id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $course = $this->Courses->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('course'));
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

        $course = $this->Courses->newEmptyEntity();
        if ($this->request->is('post')) {
            $course = $this->Courses->patchEntity($course, $this->request->getData());
            if(!$this->Courses->exists(['name' => $this->request->getData('name')]) ){
                if ($this->Courses->save($course)) {
                    $this->Flash->success(__('The course has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The course already exists. Please, try again.'));  
               return $this->redirect(['action' => 'index']);
            }
            die();
            $this->Flash->error(__('The course could not be saved. Please, try again.'));
        }
        $this->set(compact('course'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Course id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Authorization->skipAuthorization();
    	$this->viewBuilder()->setLayout('default_new');

          $trainingTable = $this->fetchTable('Training');

        $course = $this->Courses->get($id, [
            'contain' => ['Training'],
        ]);
        // $training = $this->Training->find('list',[ "keyField"=>"id", "valueField"=>"name"])->where(['status'=>1])->toArray();

        $training = $trainingTable->find('list', [
                "keyField" => "id",
                "valueField" => "name"
            ])
            ->where(['status' => 1])
            ->toArray();

        $this->set(compact('course','training'));
        $this->render('edit', 'ajax');

    }

    public function update()
    {
        $this->Authorization->skipAuthorization();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $course = $this->Courses->get($this->request->getData('id'));
            $course = $this->Courses->patchEntity($course, $this->request->getData());
            if(!$this->Courses->exists(['name' => $this->request->getData('name'),'training_id' => $this->request->getData('training_id'),'id !=' => $this->request->getData('id') ]) ){
                if ($this->Courses->save($course)) {
                    $this->Flash->success(__('The course has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The course already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Course id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'delete']);

        $course = $this->Courses->get($id);
        if ($this->Courses->delete($course)) {
            $this->Flash->success(__('The course has been deleted.'));
        } else {
            $this->Flash->error(__('The course could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // public function updateStatus($id,$status){      
	// 	$this->autoRender=false;
	// 	$this->Authorization->skipAuthorization();
	// 	if ($this->request->is('ajax')){
	// 	    $query = $this->Courses->query();
	// 		if($query->update()
	// 		    ->set(['status' => $status])
	// 		    ->where(['id' => $id])
	// 		    ->execute()){

    //             echo 1;
    //         }
	// 		else{
	// 		 	echo 0;
    //          }
    //         exit;
	// 	}
    // }

    public function updateStatus($id, $status)
{
    $this->autoRender = false;
    $this->Authorization->skipAuthorization();

    if ($this->request->is('ajax')) {

        $query = $this->Courses->updateQuery();

        $result = $query
            ->set(['status' => $status])
            ->where(['id' => $id])
            ->execute();

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }

        exit;
    }
}
}
