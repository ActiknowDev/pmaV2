<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Lessions Controller
 *
 * @property \App\Model\Table\LessionsTable $Lessions
 * @method \App\Model\Entity\Lession[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LessionsController extends AppController
{
    public function initialize(): void{
        parent::initialize();
        
        // $this->loadModel('Courses');
        // $this->loadModel('Training');        
        // $this->loadModel('LessionDocuments');        
        $this->fetchTable('Courses');
        $this->fetchTable('Training');        
        $this->fetchTable('LessionDocuments');        
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    // public function index()
    // {

    //     $this->Authorization->skipAuthorization();
    // 	$this->viewBuilder()->setLayout('default_new');

    //     // $lessions = $this->paginate($this->Lessions);
    //     $lessions = $this->Lessions->find('all', [
    //         'contain' => ['Courses','Courses.Training'],
    //     ])->order(['Lessions.id'=>'DESC'])->toArray();
        
    //     $courses = $this->Courses->find('list',[ "keyField"=>"id", "valueField"=>"name"])->where(['status'=>1])->toArray();
    //     $trainings = $this->Training->find('list',[ "keyField"=>"id", "valueField"=>"name"])->where(['status'=>1])->toArray();

    //     $this->set(compact('lessions','courses','trainings'));
    // }

    public function index()
    {

        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('default_new');

        $coursesTable = $this->fetchTable('Courses');
        $trainingTable = $this->fetchTable('Training');

        // $lessions = $this->paginate($this->Lessions);
        $lessions = $this->Lessions->find('all', [
            'contain' => ['Courses', 'Courses.Training'],
        ])->order(['Lessions.id' => 'DESC'])->toArray();

        $courses = $coursesTable->find('list', [
            "keyField" => "id",
            "valueField" => "name"
        ])->where(['status' => 1])->toArray();

        $trainings = $trainingTable->find('list', [
            "keyField" => "id",
            "valueField" => "name"
        ])->where(['status' => 1])->toArray();

        $this->set(compact('lessions', 'courses', 'trainings'));
    }

    /**
     * View method
     *
     * @param string|null $id Lession id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $lession = $this->Lessions->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('lession'));
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

        $lession = $this->Lessions->newEmptyEntity();
        if ($this->request->is('post')) {
            $files = $this->request->getData('files'); 

            $lession = $this->Lessions->patchEntity($lession, $this->request->getData());
            if(!$this->Lessions->exists(['name' => $this->request->getData('name')]) ){
                if ($res = $this->Lessions->save($lession)) {
                    if(!empty($files)){
                        foreach($files as $k => $file){
                            $info = pathinfo($file->getClientFilename());
                            $ext = $info['extension']; // get the extension of the file
            
                            $imageName =  $file->getClientFilename();
                            $destination = WWW_ROOT.'img/Lessions/'.$res->id;
            
                            if (!file_exists($destination)) { 
                                mkdir($destination, 0777, true);
                            }
            
                            if( move_uploaded_file($file->getStream()->getMetadata('uri'), $destination."/".$imageName) ) {
                                $lessionDoc = $this->LessionDocuments->newEmptyEntity();
                                $lessionDoc->lession_id = $res->id;
                                $lessionDoc->name = $imageName;
                                $lessionDoc->type = $ext;
                                                   
                                $this->LessionDocuments->save($lessionDoc);
                            }
                            else{                                                   
                                $this->Flash->success(__('Could not upload file'));
                                return $this->redirect(['action' => 'index']);
                            }
                        }
                    }                    
                    $this->Flash->success(__('The lession has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The lession already exists. Please, try again.'));  
            return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lession could not be saved. Please, try again.'));
        }
        $this->set(compact('lession'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Lession id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Authorization->skipAuthorization();
    	$this->viewBuilder()->setLayout('default_new');

        $lession = $this->Lessions->get($id, [
            'contain' => ['Courses.Training','LessionDocuments'],
        ]);
        // if ($this->request->is(['patch', 'post', 'put'])) {
        //     $lession = $this->Lessions->patchEntity($lession, $this->request->getData());
        //     if ($this->Lessions->save($lession)) {
        //         $this->Flash->success(__('The lession has been saved.'));

        //         return $this->redirect(['action' => 'index']);
        //     }
        //     $this->Flash->error(__('The lession could not be saved. Please, try again.'));
        // }
        $trainings = $this->Training->find('list',[ "keyField"=>"id", "valueField"=>"name"])->where(['status'=>1])->toArray();

        $courses = $this->Courses->find('list',[ "keyField"=>"id", "valueField"=>"name"])->where(['status'=>1])->toArray();
        // echo "<pre>";print_r($lession);die();
        $this->set(compact('lession','courses','trainings'));
        $this->render('edit', 'ajax');
    }

    public function update()
    {
        $this->Authorization->skipAuthorization();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $files = $this->request->getData('files'); 

            $lession = $this->Lessions->get($this->request->getData('id'));
            $lession = $this->Lessions->patchEntity($lession, $this->request->getData());
            if(!$this->Lessions->exists(['name' => $this->request->getData('name'),'course_id' => $this->request->getData('course_id'),'id !=' => $this->request->getData('id')]) ){
                if ($res = $this->Lessions->save($lession)) {
                    if(!empty($files)){
                        foreach($files as $k => $file){
                            $info = pathinfo($file->getClientFilename());
                            $ext = $info['extension']; // get the extension of the file
            
                            $imageName =  $file->getClientFilename();
                            $destination = WWW_ROOT.'img/Lessions/'.$res->id;
            
                            if (!file_exists($destination)) { 
                                mkdir($destination, 0777, true);
                            }
            
                            if( move_uploaded_file($file->getStream()->getMetadata('uri'), $destination."/".$imageName) ) {
                                $lessionDoc = $this->LessionDocuments->newEmptyEntity();
                                $lessionDoc->lession_id = $res->id;
                                $lessionDoc->name = $imageName;
                                $lessionDoc->type = $ext;
                                                   
                                $this->LessionDocuments->save($lessionDoc);
                            }
                            else{                                                   
                                $this->Flash->success(__('Could not upload file'));
                                return $this->redirect(['action' => 'index']);
                            }
                        }
                    }
                    $this->Flash->success(__('The lession has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The lession already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lession could not be saved. Please, try again.'));
        }
    }


    /**
     * Delete method
     *
     * @param string|null $id Lession id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'delete']);

        $lession = $this->Lessions->get($id);
        if ($this->Lessions->delete($lession)) {
            $this->Flash->success(__('The lession has been deleted.'));
        } else {
            $this->Flash->error(__('The lession could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function updateStatus($id,$status){      
		$this->autoRender=false;
		$this->Authorization->skipAuthorization();
		if ($this->request->is('ajax')){
		    $query = $this->Lessions->query();
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

    public function getCoursesByTrainingId($training_id){
        $this->autoRender=false;
		$this->Authorization->skipAuthorization();
        // $res = [];
		if ($this->request->is('ajax')){
            $courses = $this->Courses->find('list',[ "keyField"=>"id", "valueField"=>"name"])->where(['training_id'=>$training_id])->toArray();
            // echo "<pre>";print_r($courses);
            if($courses){
                $res = ['success'=>true,'data'=>$courses] ;
                // print_r($res);
            }
            else{
                $res = ['success'=>false,'data'=>[] ] ;
            }
            echo json_encode($res);
            exit;
        }
        else{
            echo "else";
        }

    }

    public function deleteLessonDocument($id = null)
    {
        $this->autoRender=false;
        $this->Authorization->skipAuthorization();

		if ($this->request->is('ajax')){

            $lession_doc = $this->LessionDocuments->get($id);
            if ($this->LessionDocuments->delete($lession_doc)) {
                echo "1";exit;
            } else {
                echo "0";exit;
            }
        }
        else{
            echo "else";
        }
    }

}
