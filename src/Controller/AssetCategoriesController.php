<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * AssetCategories Controller
 *
 * @method \App\Model\Entity\AssetCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AssetCategoriesController extends AppController
{


    public function initialize(): void
    {

          parent::initialize();
           $this->Authorization->skipAuthorization();
          $this->AssetCategories = $this->fetchTable('AssetCategories');
           

    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
         $this->viewBuilder()->setLayout('default_new');
        
         $assetCategoryData = $this->AssetCategories->find("all")->toArray();
         $this->set(compact("assetCategoryData"));

    }

    /**
     * View method
     *
     * @param string|null $id Asset Category id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $assetCategory = $this->AssetCategories->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('assetCategory'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $assetCategory = $this->AssetCategories->newEmptyEntity();
        if ($this->request->is('post')) {
            $assetCategory = $this->AssetCategories->patchEntity($assetCategory, $this->request->getData());
            if ($this->AssetCategories->save($assetCategory)) {
                $this->Flash->success(__('The asset category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The asset category could not be saved. Please, try again.'));
        }
        $this->set(compact('assetCategory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Asset Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
       
        if ($this->request->is(['patch', 'post', 'put'])) {

             $assetCategory = $this->AssetCategories->get($this->request->getData("id"), [
            'contain' => [],
        ]);

            $assetCategory = $this->AssetCategories->patchEntity($assetCategory, $this->request->getData());
            if ($this->AssetCategories->save($assetCategory)) {
                $this->Flash->success(__('The asset category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The asset category could not be saved. Please, try again.'));
        }
        //$this->set(compact('assetCategory'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Asset Category id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete',"get"]);
        $assetCategory = $this->AssetCategories->get($id);
        if ($this->AssetCategories->delete($assetCategory)) {
            $this->Flash->success(__('The asset category has been deleted.'));
        } else {
            $this->Flash->error(__('The asset category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
