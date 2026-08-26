<?php

declare(strict_types=1);

namespace App\Controller;
use Cake\Datasource\ConnectionManager;


class AssetAssignedEntriesController extends AppController
{

    public function initialize(): void
    {

        parent::initialize();
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('default_new');
        // $this->loadModel("AssetCategories");
        // $this->loadModel("AssetDatas");
        // $this->loadModel("Users");
        // $this->loadModel("AssetAssignedEntries");
        $this->Users = $this->fetchTable('Users');
		$this->AssetDatas = $this->fetchTable('AssetDatas');
		$this->AssetCategories = $this->fetchTable('AssetCategories');
		$this->AssetAssignedEntries = $this->fetchTable('AssetAssignedEntries');
        $this->AssetExpenses = $this->getTableLocator()->get("AssetExpenses");
    }

    public function index()
    {
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('default_new');
        $conn = ConnectionManager::get('default');
        $session = new \Cake\Http\Session();
        $userSession = $session->read('data');
        $user_id = $userSession['id'];
        // validation for valid user
        $roleArray = $userSession['role_name'];
        $validList = [4, 6, 9, 10];
        $this->routeValidation($roleArray,$validList);

        $user_data = $this->Users->find("list", [
            "keyField" => "id",
            "valueField" => "name",
            "conditions" => [
                "role" => 3,
                "status" => 1,
                "deleted" => 1
            ],
            "order" => [
                "name" => "ASC"
            ]
        ])->toArray();

      

        $assetCategories = $this->AssetCategories->find()
            ->select(['id', 'cat_name'])
            ->toArray();

        $asset_data = $this->AssetCategories->find('all')
            ->select([
                'cat_name' => 'AssetCategories.cat_name',
                'asset_id' => 'AssetDatas.id',
                'product_name' => 'AssetDatas.product_name',
                'serial_number' => 'AssetDatas.serial_number',
                'configuration' => 'AssetDatas.configuration',
                'asset_price' => 'AssetDatas.asset_price',
                'created_at' => 'AssetDatas.created_at',
                'free_asset_status' => 'AssetDatas.free_asset_status',
                'user_id' => 'ANY_VALUE(AssetAssignedEntries.user_id)',
                'date_of_assign' => 'ANY_VALUE(AssetAssignedEntries.date_of_assign)',
                'active' => 'ANY_VALUE(AssetAssignedEntries.active)',
                'name' => 'ANY_VALUE(Users.name)',
                'expenses_amount' => 'ANY_VALUE(AssetExpenses.expenses_amount)',
                // 'user_id' => '(AssetAssignedEntries.user_id)',
                // 'date_of_assign' => '(AssetAssignedEntries.date_of_assign)',
                // 'active' => '(AssetAssignedEntries.active)',
                // 'name' => '(Users.name)',
                // 'expenses_amount' => '(AssetExpenses.expenses_amount)',
            ])
            ->join([
                'AssetDatas' => [
                    'table' => 'asset_datas',
                    'type' => 'INNER',
                    'conditions' => 'AssetDatas.asset_categorie_id = AssetCategories.id'
                ],
                'AssetAssignedEntries' => [
                    'table' => 'asset_assigned_entries',
                    'type' => 'LEFT',
                    // 'conditions' => 'AssetAssignedEntries.asset_id = AssetDatas.id'
                    'conditions' => [
                        'AssetAssignedEntries.asset_id = AssetDatas.id',
                        'AssetAssignedEntries.dor IS NULL',
                    ]
                ],
                'Users' => [
                    'table' => 'users',
                    'type' => 'LEFT',
                    'conditions' => 'Users.id = AssetAssignedEntries.user_id'
                ],
                'AssetExpenses' => [
                    'table' => 'asset_expenses',
                    'type' => 'LEFT',
                    'conditions' => 'AssetExpenses.asset_id = AssetDatas.id'
                ],
            ])->group('AssetDatas.id')
            ->toArray();
            
            // echo "<pre>";
            // print_r($asset_data);
            // die();

        // $assigned_entries = $this->AssetAssignedEntries->find("all")->contain(["AssetDatas", "AssetDatas.AssetCategories", "AssetDatas.AssetExpenses", "Users"])->where(["AssetAssignedEntries.active" => 1])->toArray();
        // echo "<pre>";
        // print_r($asset_data);
        // die;

        $this->set(compact("user_data", "asset_data", "assigned_entries", "assetCategories"));
    }

    public function view($id = null)
    {
        $assetAssignedEntry = $this->AssetAssignedEntries->get($id, [
            'contain' => ['Users', 'AssetCategories'],
        ]);

        $this->set(compact('assetAssignedEntry'));
    }


    public function checkexists($id)
    {

        $current_date = date('Y-m-d');

        $res = $this->AssetAssignedEntries->updateAll(["active" => 0, "dor" => $current_date], [
            "active" => 1,
            "asset_id" => $id
        ]);
    }

    public function add()
    {
        $assetAssignedEntry = $this->AssetAssignedEntries->newEmptyEntity();
        if ($this->request->is('post')) {

            // echo '<pre>'; print_r($this->request->getData()); die;

            $assetAssignedEntry->categories_id = $this->request->getData('categories_id');
            $assetAssignedEntry->asset_id = $this->request->getData('asset_id');
            $assetAssignedEntry->user_id = $this->request->getData('user_id');
            $assetAssignedEntry->date_of_assign = date("Y-m-d", strtotime($this->request->getData('assign_date')));
            
            if ($this->AssetAssignedEntries->save($assetAssignedEntry)) {
                $this->AssetAssignedEntries->AssetDatas->updateAll(
                    ['free_asset_status' => null], ['id' => $assetAssignedEntry->asset_id]
                );
                $this->Flash->success(__('The asset assigned entry has been saved.'));
                if ($this->request->getData('redirect_page') === 'list') {
                    return $this->redirect([ 'action' => 'assetsList' ]);
                }   
                return $this->redirect([ 'action' => 'editAssetData', $this->request->getData('asset_id') ]);
            }
            $this->Flash->error(__('The asset assigned entry could not be saved. This Serial Number already exists.'));

            return $this->redirect(['action' => 'index']);
        }
        // $users = $this->AssetAssignedEntries->Users->find('list', ['limit' => 200]);
        // $assets = $this->AssetAssignedEntries->AssetCategories->find('list', ['limit' => 200]);
        $this->set(compact('assetAssignedEntry', 'users', 'assets'));
    }


    public function editAsset()
    {
        $user_data = $this->Users->find("all", [
            "fields" => ['id', 'name'],
            "conditions" => [
                "role" => 3,
                "status" => 1
            ],
            "order" => [
                "name" => "ASC"
            ]
        ])->toArray();

        $assetCategories = $this->AssetCategories->find()
            ->select(['id', 'cat_name'])
            ->toArray();


        if ($this->request->is('get')) {
            $this->viewBuilder()->setLayout('ajax');
            $id = $this->request->getQuery('id');
            $assetEntryData = $this->AssetAssignedEntries->find()->where(['id' => $id])->first();
            $assetAssignId = $assetEntryData->id;
            $userId = $assetEntryData->user_id;
            $assetId = $assetEntryData->asset_id;
            $categoriesId = $assetEntryData->categories_id;

            $asset_data = $this->AssetDatas->find("all", [
                "fields" => ['id', 'product_name'],
                "order" => [
                    "product_name" => "ASC"
                ],
                // "conditions" => [
                //     "id" => $assetId
                // ]
            ])->toArray();

            $this->set(compact('user_data', 'assetCategories', 'asset_data', 'assetAssignId', 'userId', 'assetId', 'categoriesId'));
        } else if ($this->request->is(['patch', 'post', 'put'])) {

            // echo "<pre>";
            // print_r($this->request->getData());
            // die;

            $this->AssetAssignedEntries->query()
                ->update()
                ->set([
                    'categories_id' => (int) $this->request->getData('categories_id'),
                    'asset_id' => (int) $this->request->getData('asset_id'),
                ])
                ->where(['id' => (int) $this->request->getData('id')])
                ->execute();

            return $this->redirect("/asset-assigned-entries");
        }
    }

    // public function freeAssets() {
    //     $this->autoRender = false;
        
    //     if ($this->request->is("ajax")) {
    //         $status = $this->request->getData("free_asset");
    //         $assetId = $this->request->getData("id");
    //         $result = $this->AssetDatas->updateAll(
    //             ['asset_status' => $status],
    //             ['id' => $assetId]
    //         );
            
    //         if ($result) {
    //             $response = ["success" => true];
    //         } else {
    //             $response = ["success" => false];
    //         }
            
    //         $this->response->withType("json");
    //         echo json_encode($response);
    //     }
    // }

    public function edit($id = null)
    {

        if ($this->request->is(['patch', 'post', 'put'])) {

            // echo "<pre>";
            // print_r($this->request->getData());
            // die;

            $id = $this->request->getData("id");

            $assetAssignedEntry = $this->AssetAssignedEntries->get($id, [
                'contain' => [],
            ]);


            if ($assetAssignedEntry->user_id != $this->request->getData("user_id")) {

                $assetAssignedEntry = $this->AssetAssignedEntries->newEmptyEntity();

                $this->checkexists($this->request->getData("asset_id"));
            }

            $assetAssignedEntry = $this->AssetAssignedEntries->patchEntity($assetAssignedEntry, $this->request->getData());
            if ($this->AssetAssignedEntries->save($assetAssignedEntry)) {

                $this->Flash->success(__('The asset assigned entry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The asset assigned entry could not be saved. This Serial Number already exists.'));

            return $this->redirect(['action' => 'index']);
        }
        return $this->redirect(['action' => 'index']);
    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete', "get"]);
        $AssetDatas = $this->AssetDatas->get($id);
        if ($this->AssetDatas->delete($AssetDatas)) {
            $this->Flash->success(__('The asset assigned entry has been deleted.'));
        } else {
            $this->Flash->error(__('The asset assigned entry could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function getlogentries()
    {


        $this->autoRender = false;

        if ($this->request->is(['post', 'put'])) {

            $asset_id = $this->request->getData("asset_id");

            $asset_Data = $this->AssetAssignedEntries->find("all", [
                "contain" => [
                    'Users',
                    'AssetDatas'
                ],
                "conditions" => [
                    "asset_id" => $asset_id
                ]
            ])->toArray();


            print_r(json_encode($asset_Data));
        }
    }

    public function fetchAsset()
    {
        $this->autoRender = false;
        $this->Authorization->skipAuthorization();

        if ($this->request->is("GET")) {
            $catId = $this->request->getQuery('catId');

            $assetData = $this->AssetDatas->find()
                ->where(['asset_categorie_id' => $catId])
                ->toArray();

            echo json_encode($assetData);
            die;
        }
    }

    public function editAssetData($asset_id)
    {
        $user_data = $this->Users->find()
            ->select(['id', 'name'])
            ->where(["role" => 3, "status" => 1, "deleted" => 1])
            ->order(["name" => "ASC"])
            ->toArray();

        $assetCategories = $this->AssetCategories->find()
            ->select(['id', 'cat_name'])
            ->toArray();

        $AssetDatas = $this->AssetDatas->find('all')
            ->where(['id' => $asset_id])
            ->first();
        // echo '<pre>';
        // print_r($AssetDatas);
        // die;
        $AssetAssignedEntries = $this->AssetAssignedEntries->find("all")
            ->where(['asset_id' => $asset_id,'dor IS NULL'])
            ->first();
        
        $AssetCategory = [];
        if($AssetDatas->asset_categorie_id){
            $AssetCategory = $this->AssetCategories->find("all")
                ->where(['id' => $AssetDatas->asset_categorie_id])
                ->first();
        }

        $allAssignedEntries =  $this->AssetAssignedEntries->find("all")->select([
            'user_name' => 'Users.name',
            'id' => 'AssetAssignedEntries.id',
            'date_of_assign' => 'AssetAssignedEntries.date_of_assign',
            'asset_id' => 'AssetAssignedEntries.asset_id',
            'date_of_release' => 'AssetAssignedEntries.dor',
            'active' => 'AssetAssignedEntries.active',
            'asset_release_remark' => 'AssetAssignedEntries.asset_release_remark',
        ])->contain(["Users"])->where(['AssetAssignedEntries.asset_id' => $asset_id])->toArray();

        // echo '<pre>';
        // print_r($allAssignedEntries);
        // die;

        $assigned_entries = $this->AssetAssignedEntries->find()->select([
            'user_id' => 'Users.id',
            'user_name' => 'Users.name',
            'asset_assign_id' => 'AssetAssignedEntries.id',
            'date_of_assign' => 'AssetAssignedEntries.date_of_assign',
            'asset_id' => 'AssetAssignedEntries.asset_id',
            // 'categories_id' => 'AssetAssignedEntries.categories_id',
            'dor' => 'AssetAssignedEntries.dor',
            'active' => 'AssetAssignedEntries.active',
            'asset_datas_id' => 'AssetDatas.id',
            'asset_categorie_id' => 'AssetDatas.asset_categorie_id',
            'product_name' => 'AssetDatas.product_name',
            'serial_number' => 'AssetDatas.serial_number',
            'configuration' => 'AssetDatas.configuration',
            'asset_price' => 'AssetDatas.asset_price',
            'cat_name' => 'AssetCategories.cat_name',
        ])->contain(["AssetDatas", "AssetDatas.AssetCategories", "Users"])->where(["AssetAssignedEntries.active" => 1, 'AssetAssignedEntries.asset_id' => $asset_id])->first();

        // $assetExpenses = $this->AssetExpenses->find()->where(['asset_id' => $asset_id])->toArray();
        $totalAssetExpenses = $this->AssetExpenses->find()->select(['total' => 'SUM(expenses_amount)'])->where(['asset_id' => $asset_id]);
        $totalAssetExpenses = $totalAssetExpenses->first()->total;
        // echo '<pre>';
        // print_r($assigned_entries);
        // die;

        $this->set(compact('asset_id', 'user_data', 'assetCategories', 'AssetCategory', 'AssetDatas', 'AssetAssignedEntries', 'assigned_entries', 'totalAssetExpenses', 'allAssignedEntries'));
    }

    // public function releaseAsset($id, $asset_id)
    // {
    //     $releaseDate = date("Y-m-d");
    //     $AssetAssignedEntries = $this->AssetAssignedEntries->find()->where(['id' => $id])->first();
    //     $AssetAssignedEntries->dor = $releaseDate;
    //     $AssetAssignedEntries->active = 0;

    //     // echo $asset_id;
    //     // echo '<pre>';
    //     // print_r($AssetAssignedEntries);
    //     // die;

    //     if ($this->AssetAssignedEntries->save($AssetAssignedEntries)) {
    //         $this->Flash->success(__('The asset has been released.'));
    //     }
    //     return $this->redirect('/asset-assigned-entries/editAssetData/' . $asset_id);
    // }
    public function releaseAsset() {
        
        $releaseDate = date("Y-m-d");
        $id = $this->request->getData('assignment_id');
        $asset_id = $this->request->getData('asset_id');
        $remark = $this->request->getData('asset_release_remark');
        $free_asset_status = $this->request->getData('free_asset_status');

        $AssetAssignedEntries = $this->AssetAssignedEntries ->find() ->where(['id' => $id]) ->first();

        $AssetAssignedEntries->dor = $releaseDate;
        $AssetAssignedEntries->active = 0;
        $AssetAssignedEntries->asset_release_remark = $remark;        

        if ($this->AssetAssignedEntries->save($AssetAssignedEntries)) {

            // Update description in AssetDatas table
            $assetData = $this->AssetAssignedEntries->AssetDatas ->find()->where(['AssetDatas.id' => $asset_id]) ->first();

            if ($assetData) {
                $assetData->free_asset_status = $free_asset_status;
                $this->AssetAssignedEntries->AssetDatas->save($assetData);
            }

            $this->Flash->success(__('The asset has been released.'));
        } else {
            $this->Flash->error(__('The asset could not be released.'));
        }
        if ($this->request->getData('redirect_page') == 'list') {
            return $this->redirect('/assets-list');
        }
        return $this->redirect('/asset-assigned-entries/editAssetData/' . $asset_id);
    }


    public function fetchAssetData()
    {
        $this->autoRender = false;
        $this->Authorization->skipAuthorization();

        if ($this->request->is("GET")) {
            $catId = $this->request->getQuery('catId');
            $asset_id = $this->request->getQuery('asset_id');

            $assetData = $this->AssetDatas->find()
                ->where(['asset_categorie_id' => $catId])
                ->toArray();

            // print_r($assetData);
            // die;

            $this->set(compact('asset_id', 'assetData'));

            $this->render("asset_data", "ajax");
        }
    }

    // public function editAssignAssetData()
    // {
    //     if ($this->request->is(['post', 'put', 'patch'])) {
    //         // echo '<pre>';
    //         // print_r($this->request->getData());
    //         // die;
    //         $asset_assign_id = $this->request->getData('asset_assign_id');
    //         if(!empty($asset_assign_id)){
    //             $asset_assign_id = $this->request->getData('asset_assign_id');  
    //         } else {
    //             $asset_assign_id='';
    //         }
    //         $asset_id = $this->request->getData('asset_id');

    //         $isAssignedEntries = $this->AssetAssignedEntries->exists(['id' => $asset_assign_id]);
    //         $isAssetDatas = $this->AssetDatas->exists(['id' => $asset_id]);

    //         if ($isAssignedEntries) {
    //             $AssetAssignedEntries = $this->AssetAssignedEntries->find()
    //                 ->where(['id' => $asset_assign_id])
    //                 ->first();
    //                 if(!empty($this->request->getData('user_id'))){
    //                     $AssetAssignedEntries->user_id = $this->request->getData('user_id');
    //                 }
    //             $AssetAssignedEntries->asset_id = $asset_id;
    //             $AssetAssignedEntries->categories_id = $this->request->getData('categories_id');
    //             if(!empty($this->request->getData('date_of_assign'))) {
    //                 $AssetAssignedEntries->date_of_assign = date("Y-m-d", strtotime($this->request->getData('date_of_assign')));
    //             }

    //             if ($this->AssetAssignedEntries->save($AssetAssignedEntries)) {

    //                 if ($isAssetDatas) {
    //                     $AssetDatas = $this->AssetDatas->find()
    //                         ->where(['id' => $asset_id])
    //                         ->first();
    //                     $AssetDatas->asset_categorie_id = $this->request->getData('categories_id');
    //                     $AssetDatas->product_name = $this->request->getData('product_name');
    //                     $AssetDatas->serial_number     = $this->request->getData('serial_number');
    //                     $AssetDatas->configuration = $this->request->getData('configuration');
    //                     $AssetDatas->asset_price = $this->request->getData('asset_price');
    //                     if($this->request->getData('free_asset_status')){
    //                         $AssetDatas->free_asset_status = $this->request->getData('free_asset_status');
    //                     }

    //                     $this->AssetDatas->save($AssetDatas);
    //                 } else {
    //                     $AssetDatas = $this->AssetDatas->newEmptyEntity();
    //                     $AssetDatas->asset_categorie_id = $this->request->getData('categories_id');
    //                     $AssetDatas->product_name = $this->request->getData('product_name');
    //                     $AssetDatas->serial_number     = $this->request->getData('serial_number');
    //                     $AssetDatas->configuration = $this->request->getData('configuration');
    //                     $AssetDatas->asset_price = $this->request->getData('asset_price');
    //                     if($this->request->getData('free_asset_status')){
    //                         $AssetDatas->free_asset_status = $this->request->getData('free_asset_status');
    //                     }
    //                     $this->AssetDatas->save($AssetDatas);
    //                 }

    //                 $this->Flash->success(__('The asset data has been updated.'));
    //                 return $this->redirect('/asset-assigned-entries/editAssetData/' . $asset_id);
    //             }
    //         } else {
    //             // $AssetAssignedEntries = $this->AssetAssignedEntries->newEmptyEntity();
    //             // if(!empty($this->request->getData('user_id'))){
    //             //     $AssetAssignedEntries->user_id = $this->request->getData('user_id');
    //             // }
    //             // $AssetAssignedEntries->asset_id = $asset_id;
    //             // $AssetAssignedEntries->categories_id = $this->request->getData('categories_id');
    //             // if(!empty($this->request->getData('date_of_assign'))) {
    //             //     $AssetAssignedEntries->date_of_assign = date("Y-m-d", strtotime($this->request->getData('date_of_assign')));
    //             // }

    //             // if ($this->AssetAssignedEntries->save($AssetAssignedEntries)) {

    //                 if ($isAssetDatas) {
    //                     $AssetDatas = $this->AssetDatas->find()
    //                         ->where(['id' => $asset_id])
    //                         ->first();
    //                     $AssetDatas->asset_categorie_id = $this->request->getData('categories_id');
    //                     $AssetDatas->product_name = $this->request->getData('product_name');
    //                     $AssetDatas->serial_number     = $this->request->getData('serial_number');
    //                     $AssetDatas->configuration = $this->request->getData('configuration');
    //                     $AssetDatas->asset_price = $this->request->getData('asset_price');
    //                     if($this->request->getData('free_asset_status')){
    //                         $AssetDatas->free_asset_status = $this->request->getData('free_asset_status');
    //                     }

    //                     $this->AssetDatas->save($AssetDatas);
    //                 } else {
    //                     $AssetDatas = $this->AssetDatas->newEmptyEntity();
    //                     $AssetDatas->asset_categorie_id = $this->request->getData('categories_id');
    //                     $AssetDatas->product_name = $this->request->getData('product_name');
    //                     $AssetDatas->serial_number     = $this->request->getData('serial_number');
    //                     $AssetDatas->configuration = $this->request->getData('configuration');
    //                     $AssetDatas->asset_price = $this->request->getData('asset_price');
    //                     if($this->request->getData('free_asset_status')){
    //                         $AssetDatas->free_asset_status = $this->request->getData('free_asset_status');
    //                     }
    //                     $this->AssetDatas->save($AssetDatas);
    //                 }

    //                 $this->Flash->success(__('The asset data has been updated.'));
    //                 return $this->redirect('/asset-assigned-entries/editAssetData/' . $asset_id);
    //             // }
    //         }

    //         $this->Flash->error(__('The asset data not updated.'));
    //         return $this->redirect('/asset-assigned-entries/editAssetData/' . $asset_id);
    //     }
    // }

    public function editAssignAssetData() {
        if (!$this->request->is(['post', 'put', 'patch'])) {
            return;
        }

        $data = $this->request->getData();

        $assetAssignId = $data['asset_assign_id'] ?? null;
        $assetId       = $data['asset_id'] ?? null;

        if (empty($assetId)) {
            $this->Flash->error(__('Invalid asset ID.'));
            return $this->redirect('/asset-assigned-entries');
        }
        $asset = $this->AssetDatas->find()->where(['id' => $assetId])->first();
        if (!$asset) {
            $asset = $this->AssetDatas->newEmptyEntity();
        }
        if (!empty($assetAssignId)) {
            $assignedAsset = $this->AssetAssignedEntries->find()->where(['id' => $assetAssignId])->first();
            if ($assignedAsset) {
                if (isset($data['user_id']) && $data['user_id'] !== '') {
                    $assignedAsset->user_id = $data['user_id'];
                }
                if (isset($data['asset_id'])) {
                    $assignedAsset->asset_id = $assetId;
                }
                if (isset($data['categories_id'])) {
                    $assignedAsset->categories_id = $data['categories_id'];
                }
                if (!empty($data['date_of_assign'])) {
                    $assignedAsset->date_of_assign = date( 'Y-m-d', strtotime($data['date_of_assign']) );
                }
                if (!$this->AssetAssignedEntries->save($assignedAsset)) {
                    $this->Flash->error( __('The assigned asset could not be updated.') );
                    return $this->redirect( '/asset-assigned-entries/editAssetData/' . $assetId );
                }
            }
        }

        if (isset($data['categories_id'])) {
            $asset->asset_categorie_id = $data['categories_id'];
        }
        if (isset($data['product_name'])) {
            $asset->product_name = $data['product_name'];
        }
        if (isset($data['serial_number'])) {
            $asset->serial_number = $data['serial_number'];
        }
        if (isset($data['configuration'])) {
            $asset->configuration = $data['configuration'];
        }
        if (isset($data['asset_price'])) {
            $asset->asset_price = $data['asset_price'];
        }
        if (array_key_exists('free_asset_status', $data)) {
            $asset->free_asset_status = $data['free_asset_status'];
        }
        if (!empty($data['date_of_purchase'])) {
            $asset->date_of_purchase = date('Y-m-d', strtotime($data['date_of_purchase']) );
        } 
        if (array_key_exists('description', $data)) {
            $asset->description = $data['description'];
        }
        if (!$this->AssetDatas->save($asset)) {
            $this->Flash->error(__('The asset data could not be updated.'));
            return $this->redirect('/asset-assigned-entries/editAssetData/' . $assetId);
        }
        $this->Flash->success(__('The asset data has been updated.'));
        return $this->redirect('/asset-assigned-entries/editAssetData/' . $assetId);
    }


    public function addExpenses()
    {
        if ($this->request->is(['post', 'put', 'patch'])) {

            // echo '<pre>';
            // print_r($this->request->getData());
            // die;
            $asset_id = $this->request->getData('asset_id');
            $AssetExpenses = $this->AssetExpenses->newEmptyEntity();
            $AssetExpenses->asset_id = $asset_id;
            $AssetExpenses->serial_number = $this->request->getData('serial_number');
            $AssetExpenses->expense_type = $this->request->getData('expense_type');
            $AssetExpenses->expenses_amount = $this->request->getData('expenses_amount');
            $AssetExpenses->expense_date = date('Y-m-d', strtotime($this->request->getData('expense_date')));

            if ($this->AssetExpenses->save($AssetExpenses)) {
                $this->Flash->success(__('Expense has been saved.'));
                return $this->redirect('/asset-assigned-entries/editAssetData/' . $asset_id);
            }
            $this->Flash->success(__('Expense has not been saved.'));
            return $this->redirect('/asset-assigned-entries/editAssetData/' . $asset_id);
        }
    }

    public function expenseTblData()
    {
        if ($this->request->is('get')) {
            $asset_id = $this->request->getQuery('asset_id');
            $productName = $this->request->getQuery('productName');
            $assetExpenses = $this->AssetExpenses->find()->where(['asset_id' => $asset_id])->toArray();

            $this->set(compact('assetExpenses', 'productName'));

            $this->render('expense_table', 'ajax');
        }
    }

    public function deleteExpense()
    {
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $id = $this->request->getQuery('id');
            $AssetExpenses = $this->AssetExpenses->get(['id' => $id]);
            if ($this->AssetExpenses->delete($AssetExpenses)) {
                // $this->Flash->success(__('The Expenses has been deleted.'));
                echo 1;
            }
        }
    }

    public function editExpense()
    {
        if ($this->request->is(['post', 'put', 'patch'])) {
            // echo '<pre>';
            // print_r($this->request->getData());
            // die;
            $asset_id = $this->request->getData('asset_id');
            $AssetExpenses = $this->AssetExpenses->find()->where(['id' => $this->request->getData('id')])->first();
            $AssetExpenses->expense_type = $this->request->getData('expense_type');
            $AssetExpenses->expenses_amount = $this->request->getData('expenses_amount');
            $AssetExpenses->expense_date = date('Y-m-d', strtotime($this->request->getData('expense_date')));
            if ($this->AssetExpenses->save($AssetExpenses)) {
                $this->Flash->success(__('The Expenses has been updated.'));
                return $this->redirect('/asset-assigned-entries/editAssetData/' . $asset_id);
            }
        }
        $this->viewBuilder()->setLayout('ajax');
        $id = $this->request->getQuery('id');
        $assetExpenses = $this->AssetExpenses->find()->where(['id' => $id])->first();
        $this->set(compact('assetExpenses'));
    }

    public function filterData()
    {
        if ($this->request->is("GET")) {
            $type = $this->request->getQuery('type');
            $value = $this->request->getQuery('value'); // it has from value when type = date_of_purchase
            $to = $this->request->getQuery('to');

            $query = $this->AssetAssignedEntries->find("all")
            ->contain(["AssetDatas", "AssetDatas.AssetCategories", "AssetDatas.AssetExpenses", "Users"])
            ->where([ "AssetAssignedEntries.active" => 1 ]);

            if ($type == 'category') {
                // $query->where(["AssetCategories.cat_name LIKE" => "%" . $value . "%" ]);
                if ($type == 'category') {
                    $query->matching('AssetDatas.AssetCategories', function ($q) use ($value) {
                        return $q->where([
                            'AssetCategories.cat_name LIKE' => '%' . $value . '%'
                        ]);
                    });
                }
            } elseif ($type == 'date_of_purchase') {                
                $conditions = [];

                if (!empty($value)) {
                    $conditions["AssetDatas.date_of_purchase >="] = $value . " 00:00:00";
                }                
                if (!empty($to)) {
                    $conditions["AssetDatas.date_of_purchase <="] = $to . " 23:59:59";
                }
                
                if (!empty($conditions)) {
                    $query->where($conditions);
                }
            } elseif ($type == 'status') {
                // $query->where(["AssetDatas.free_asset_status LIKE" => "%" . $value . "%"]);
                $query->matching('AssetDatas', function ($q) use ($value) {
                    return $q->where([
                        'AssetDatas.free_asset_status LIKE' => '%' . $value . '%'
                    ]);
                });
            } else {
                $query->where(["Users.name LIKE" => "%" . $value . "%"]);
            }
            // echo "<pre>";
            // print_r($query);die();

            $assigned_entries = $query->toArray();
            // echo "<pre>";
            // print_r($assigned_entries);
            // die();
            // if ($type == 'category') {
            //     $assigned_entries = $this->AssetAssignedEntries->find("all")
            //         ->contain(["AssetDatas", "AssetDatas.AssetCategories", "AssetDatas.AssetExpenses", "Users"])
            //         ->where(["AssetAssignedEntries.active" => 1, "AssetCategories.cat_name LIKE" => "%" . $value . "%"])
            //         ->toArray();
            // } else {
            //     $assigned_entries = $this->AssetAssignedEntries->find("all")
            //         ->contain(["AssetDatas", "AssetDatas.AssetCategories", "AssetDatas.AssetExpenses", "Users"])
            //         ->where(["AssetAssignedEntries.active" => 1, "Users.name LIKE" => "%" . $value . "%"])
            //         ->toArray();
            // }

            $this->set(compact("assigned_entries"));
            $this->render("filter_data_table", "ajax");
        }
    }


    public function assetsList(){
        $this->layout = 'default';
        $this->AssetDatas = $this->fetchTable('AssetDatas');
        $expenseQuery = $this->AssetDatas->AssetExpenses->find();
        $assets = $this->AssetDatas->find();

        $assets->select([
            'AssetDatas.id',
            'AssetDatas.product_name',
            'AssetDatas.serial_number',
            'AssetDatas.configuration',
            'AssetDatas.asset_price',
            'AssetDatas.free_asset_status',
            'AssetDatas.date_of_purchase',
            'AssetDatas.description',
            'AssetDatas.created_at',
            
            'cat_name' => 'AssetCategories.cat_name',

            // 'expense_id' => 'AssetExpenses.id',
            // 'expense_amount' => 'AssetExpenses.expenses_amount',
            'expense_amount' => $expenseQuery
                ->select([ 'total' => $expenseQuery->func()->sum('expenses_amount') ])
                ->where([ 'AssetExpenses.asset_id = AssetDatas.id' ]),

            // Assignment
            'assignment_id' => 'AssetAssignedEntries.id',
            'user_id' => 'AssetAssignedEntries.user_id',
            'asset_id' => 'AssetAssignedEntries.asset_id',
            'date_of_assign' => 'AssetAssignedEntries.date_of_assign',
            'dor' => 'AssetAssignedEntries.dor',
            'active' => 'AssetAssignedEntries.active',
            'asset_release_remark' => 'AssetAssignedEntries.asset_release_remark',

            // User
            'user_name' => 'Users.name'
        ])
        ->leftJoin(
            ['AssetCategories' => 'asset_categories'],
            ['AssetCategories.id = AssetDatas.asset_categorie_id']
        )
        // ->leftJoin(
        //     ['AssetExpenses' => 'asset_expenses'],
        //     ['AssetExpenses.asset_id = AssetDatas.id']
        // )
        // Asset Assignment
        ->leftJoin(
            ['AssetAssignedEntries' => 'asset_assigned_entries'],
            [
                'AssetAssignedEntries.asset_id = AssetDatas.id',
                'AssetAssignedEntries.active' => 1
            ]
        )
        // User
        ->leftJoin(
            ['Users' => 'users'],
            ['Users.id = AssetAssignedEntries.user_id']
        )
        ->order([
            'AssetDatas.id' => 'DESC'
        ]);

        $assets = $assets->all();

        $user_data = $this->Users->find("list", [
            "keyField" => "id",
            "valueField" => "name",
            "conditions" => [
                "role" => 3,
                "status" => 1,
                "deleted" => 1
            ],
            "order" => [
                "name" => "ASC"
            ]
        ])->toArray();

      

        $assetCategories = $this->AssetCategories->find()
            ->select(['id', 'cat_name'])
            ->toArray();

        $this->set(compact('assets','user_data','assetCategories'));
    }

    public function assetsDataFilter(){
        if (!$this->request->is('GET')) {
            return;
        }

        $type = $this->request->getQuery('type');
        $value = trim($this->request->getQuery('value') ?? '');
        $to = trim($this->request->getQuery('to') ?? '');

        $expenseQuery = $this->AssetDatas->AssetExpenses->find();
        $assets = $this->AssetDatas->find();

        $assets->select([
            'AssetDatas.id',
            'AssetDatas.product_name',
            'AssetDatas.serial_number',
            'AssetDatas.configuration',
            'AssetDatas.asset_price',
            'AssetDatas.free_asset_status',
            'AssetDatas.date_of_purchase',
            'AssetDatas.description',
            'AssetDatas.created_at',

            // Category
            'cat_name' => 'AssetCategories.cat_name',

            // Expenses
            // 'expense_id' => 'AssetExpenses.id',
            // 'expense_amount' => 'AssetExpenses.expenses_amount',
            'expense_amount' => $expenseQuery
                ->select([ 'total' => $expenseQuery->func()->sum('expenses_amount') ])
                ->where([ 'AssetExpenses.asset_id = AssetDatas.id' ]),

            'assignment_id' => 'AssetAssignedEntries.id',
            'user_id' => 'AssetAssignedEntries.user_id',
            'asset_id' => 'AssetAssignedEntries.asset_id',
            'date_of_assign' => 'AssetAssignedEntries.date_of_assign',
            'dor' => 'AssetAssignedEntries.dor',
            'active' => 'AssetAssignedEntries.active',
            'asset_release_remark' => 'AssetAssignedEntries.asset_release_remark',

            'user_name' => 'Users.name'
        ])
        ->leftJoin(
            ['AssetCategories' => 'asset_categories'],
            [ 'AssetCategories.id = AssetDatas.asset_categorie_id' ]
        )
        // Expenses
        // ->leftJoin(
        //     ['AssetExpenses' => 'asset_expenses'],
        //     [ 'AssetExpenses.asset_id = AssetDatas.id' ]
        // )
        
        ->leftJoin(
            ['AssetAssignedEntries' => 'asset_assigned_entries'],
            [ 'AssetAssignedEntries.asset_id = AssetDatas.id', 'AssetAssignedEntries.active' => 1 ]
        )
        ->leftJoin(
            ['Users' => 'users'],
            [ 'Users.id = AssetAssignedEntries.user_id' ]
        );
        if ($type === 'category' && $value !== '') {
            $assets->where(['AssetCategories.cat_name LIKE' => '%' . $value . '%']);
        } elseif ($type === 'date_of_purchase') {
            if ($value !== '') {
                $assets->where(['AssetDatas.date_of_purchase >=' => $value . ' 00:00:00']);
            }
            if ($to !== '') {
                $assets->where(['AssetDatas.date_of_purchase <=' => $to . ' 23:59:59']);
            }

        } elseif ($type === 'status' && $value !== '') {
            if( $value == 'Assigned'){
                $assets->where([ 'AssetAssignedEntries.active' => 1, 'AssetDatas.free_asset_status IS' => null ]);
            }else{
                $assets->where(['AssetDatas.free_asset_status LIKE' => '%' . $value . '%' ]);
            }

        } elseif ($type === 'assign' && $value !== '') {
            $assets->where([ 'Users.name LIKE' => '%' . $value . '%' ]);
        }

        $assets->order([ 'AssetDatas.id' => 'DESC' ]);
        $assets = $assets->toArray();
        $this->set(compact('assets'));
        $this->render('filter_data_table_assets_list', 'ajax');
    }

}