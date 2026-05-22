<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Competencies Controller
 *
 * @property \App\Model\Table\CompetenciesTable $Competencies
 * @method \App\Model\Entity\Competency[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CompetenciesController extends AppController
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

        // $competencies = $this->paginate($this->Competencies);
        $competencies = $this->Competencies->find()->order(['id'=>'DESC'])->toArray();

        $this->set(compact('competencies'));
    }

    /**
     * View method
     *
     * @param string|null $id Competency id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $competency = $this->Competencies->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('competency'));
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

        $competency = $this->Competencies->newEmptyEntity();
        if ($this->request->is('post')) {
            $competency = $this->Competencies->patchEntity($competency, $this->request->getData());
            if(!$this->Competencies->exists(['name' => $this->request->getData('name')]) ){
                if ($this->Competencies->save($competency)) {
                    $this->Flash->success(__('The competency has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The competency already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The competency could not be saved. Please, try again.'));
        }
        $this->set(compact('competency'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Competency id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Authorization->skipAuthorization();
    	$this->viewBuilder()->setLayout('default_new');
        
        $competency = $this->Competencies->get($id, [
            'contain' => [],
        ]);
        $this->set(compact('competency'));
        $this->render('edit', 'ajax');
    }
    public function update()
    {
        $this->Authorization->skipAuthorization();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $competency = $this->Competencies->get($this->request->getData('id'));
            $competency = $this->Competencies->patchEntity($competency, $this->request->getData());
            if(!$this->Competencies->exists(['name' => $this->request->getData('name'),'id !=' => $this->request->getData('id')]) ){
                if ($this->Competencies->save($competency)) {
                    $this->Flash->success(__('The competency has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The competency already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The competency could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Competency id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'delete']);

        $competency = $this->Competencies->get($id);
        if ($this->Competencies->delete($competency)) {
            $this->Flash->success(__('The competency has been deleted.'));
        } else {
            $this->Flash->error(__('The competency could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function updateStatus($id,$status){      
		$this->autoRender=false;
		$this->Authorization->skipAuthorization();
		if ($this->request->is('ajax')){
		    $query = $this->Competencies->query();
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
