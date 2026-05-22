<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * CareerLevels Controller
 *
 * @property \App\Model\Table\CareerLevelsTable $CareerLevels
 * @method \App\Model\Entity\CareerLevel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CareerLevelsController extends AppController
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
        // $careerLevels = $this->paginate($this->CareerLevels);
        $careerLevels = $this->CareerLevels->find()->order(['id'=>'DESC'])->toArray();
        $this->set(compact('careerLevels'));
    }

    /**
     * View method
     *
     * @param string|null $id Career Level id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $careerLevel = $this->CareerLevels->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('careerLevel'));
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

        $careerLevel = $this->CareerLevels->newEmptyEntity();
        if ($this->request->is('post')) {
            $careerLevel = $this->CareerLevels->patchEntity($careerLevel, $this->request->getData());
            if(!$this->CareerLevels->exists(['name' => $this->request->getData('name')]) ){            
                if ($this->CareerLevels->save($careerLevel)) {
                    $this->Flash->success(__('The career level has been saved.'));
    
                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The career level already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The career level could not be saved. Please, try again.'));
        }
        $this->set(compact('careerLevel'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Career Level id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Authorization->skipAuthorization();

        $careerLevel = $this->CareerLevels->get($id, [
            'contain' => [],
        ]);
        // if ($this->request->is(['patch', 'post', 'put'])) {
        //     $careerLevel = $this->CareerLevels->patchEntity($careerLevel, $this->request->getData());
        //     if ($this->CareerLevels->save($careerLevel)) {
        //         $this->Flash->success(__('The career level has been saved.'));

        //         return $this->redirect(['action' => 'index']);
        //     }
        //     $this->Flash->error(__('The career level could not be saved. Please, try again.'));
        // }
        $this->set(compact('careerLevel'));
        $this->render('edit', 'ajax');
    }
    public function update()
    {
        $this->Authorization->skipAuthorization();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $careerLevel = $this->CareerLevels->get($this->request->getData('id'));
            $careerLevel = $this->CareerLevels->patchEntity($careerLevel, $this->request->getData());
            if(!$this->CareerLevels->exists(['name' => $this->request->getData('name'),'id !=' => $this->request->getData('id')]) ){
                if ($this->CareerLevels->save($careerLevel)) {
                    $this->Flash->success(__('The career level has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The career level already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The career level could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Career Level id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'delete']);

        $careerLevel = $this->CareerLevels->get($id);
        if ($this->CareerLevels->delete($careerLevel)) {
            $this->Flash->success(__('The career level has been deleted.'));
        } else {
            $this->Flash->error(__('The career level could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function updateStatus($id,$status){      
		$this->autoRender=false;
		$this->Authorization->skipAuthorization();
		if ($this->request->is('ajax')){
		    $query = $this->CareerLevels->query();
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
