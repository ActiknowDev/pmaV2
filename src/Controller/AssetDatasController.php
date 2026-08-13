<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * AssetDatas Controller
 *
 * @method \App\Model\Entity\AssetData[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AssetDatasController extends AppController
{

    public function initialize(): void
    {

        parent::initialize();
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('default_new');
        $this->AssetDatas = $this->fetchTable('AssetDatas');
		$this->AssetCategories = $this->fetchTable('AssetCategories');
        // $this->loadModel("AssetDatas");
        // $this->loadModel("AssetCategories");
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->viewBuilder()->setLayout('default_new');
        $assetData = $this->AssetDatas->find("all")->contain(["AssetCategories"])->toArray();

        $category_data = $this->AssetCategories->find("list", [
            "keyField" => "id",
            "valueField" => "cat_name"
        ])->toArray();

        // echo "<pre>";
        // print_r($assetData);
        // die;

        $this->set(compact('assetData', "category_data"));
    }

    /**
     * View method
     *
     * @param string|null $id Asset Data id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $assetData = $this->AssetDatas->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('assetData'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $assetData = $this->AssetDatas->newEmptyEntity();
        if ($this->request->is('post')) {
            // echo "<pre>"; print_r($this->request->getData()); die;
            $dateOfPurchase = $this->request->getData('date_of_purchase');
            $assetData = $this->AssetDatas->patchEntity($assetData, $this->request->getData());
            $assetData->configuration = $this->request->getData('configuration');
            $assetData->asset_price = $this->request->getData('asset_price');
            $assetData->free_asset_status = $this->request->getData('free_asset_status');
            $assetData->date_of_purchase = $dateOfPurchase ? date('Y-m-d', strtotime($dateOfPurchase)) : date('Y-m-d');
            $assetData->description =$this->request->getData('description');

            if ($this->AssetDatas->save($assetData)) {
                $this->Flash->success(__('The asset data has been saved.'));

                if ($this->request->getData('redirect_page') === 'list') {
                    return $this->redirect(['controller' => 'AssetAssignedEntries', 'action' => 'assetsList']);
                }
                return $this->redirect(['controller' => 'AssetAssignedEntries', 'action' => 'index']);
            }

$errors = $assetData->getErrors();

echo "<pre>";
print_r($errors);
die;
            $this->Flash->error(__('The asset data could not be saved. Please, try again.'));
            return $this->redirect(['controller' => 'AssetAssignedEntries', 'action' => 'index']);
        }
        
        $this->set(compact('assetData'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Asset Data id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {

        if ($this->request->is(['patch', 'post', 'put'])) {

            $assetData = $this->AssetDatas->get($this->request->getData("id"), [
                'contain' => [],
            ]);

            $assetData = $this->AssetDatas->patchEntity($assetData, $this->request->getData());
            if ($this->AssetDatas->save($assetData)) {
                $this->Flash->success(__('The asset data has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The asset data could not be saved. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->set(compact('assetData'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Asset Data id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete', "get"]);
        $assetData = $this->AssetDatas->get($id);
        if ($this->AssetDatas->delete($assetData)) {
            $this->Flash->success(__('The asset data has been deleted.'));
        } else {
            $this->Flash->error(__('The asset data could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function editAsset()
    {
    }
}
