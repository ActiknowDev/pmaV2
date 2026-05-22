<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * CareerTracks Controller
 *
 * @property \App\Model\Table\CareerTracksTable $CareerTracks
 * @method \App\Model\Entity\CareerTrack[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CareerTracksController extends AppController
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

        // $careerTracks = $this->paginate($this->CareerTracks);
        $careerTracks = $this->CareerTracks->find()->order(['id'=>'DESC'])->toArray();
        $this->set(compact('careerTracks'));
    }

    /**
     * View method
     *
     * @param string|null $id Career Track id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $careerTrack = $this->CareerTracks->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('careerTrack'));
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

        $careerTrack = $this->CareerTracks->newEmptyEntity();
        if ($this->request->is('post')) {
            $careerTrack = $this->CareerTracks->patchEntity($careerTrack, $this->request->getData());
            if(!$this->CareerTracks->exists(['name' => $this->request->getData('name')]) ){
                if ($this->CareerTracks->save($careerTrack)) {
                    $this->Flash->success(__('The career track has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The career track already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The career track could not be saved. Please, try again.'));
        }
        $this->set(compact('careerTrack'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Career Track id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $this->Authorization->skipAuthorization();
    	$this->viewBuilder()->setLayout('default_new');

        $careerTrack = $this->CareerTracks->get($id, [
            'contain' => [],
        ]);
        // if ($this->request->is(['patch', 'post', 'put'])) {
        //     $careerTrack = $this->CareerTracks->patchEntity($careerTrack, $this->request->getData());
        //     if(!$this->CareerTracks->exists(['name' => $this->request->getData('name')]) ){
        //         if ($this->CareerTracks->save($careerTrack)) {
        //             $this->Flash->success(__('The career track has been saved.'));

        //             return $this->redirect(['action' => 'index']);
        //         }
        //     }
        //     else{
        //         $this->Flash->error(__('The career track already exists. Please, try again.'));  
        //         return $this->redirect(['action' => 'add']);
        //     }
        //     $this->Flash->error(__('The career track could not be saved. Please, try again.'));
        // }
        $this->set(compact('careerTrack'));
        $this->render('edit', 'ajax');

    }

    public function update()
    {
        $this->Authorization->skipAuthorization();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $careerTrack = $this->CareerTracks->get($this->request->getData('id'));
            $careerTrack = $this->CareerTracks->patchEntity($careerTrack, $this->request->getData());
            if(!$this->CareerTracks->exists(['name' => $this->request->getData('name'),'id !=' => $this->request->getData('id')]) ){
                if ($this->CareerTracks->save($careerTrack)) {
                    $this->Flash->success(__('The career track has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The career track already exists. Please, try again.'));  
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The career track could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Career Track id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    { 
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'delete']);
        $careerTrack = $this->CareerTracks->get($id);
        if ($this->CareerTracks->delete($careerTrack)) {
            $this->Flash->success(__('The career track has been deleted.'));
        } else {
            $this->Flash->error(__('The career track could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    // public function rolesResponsibilities(){

    //     $this->Authorization->skipAuthorization();
    // 	$this->viewBuilder()->setLayout('default_new');

    //     $careerTracks = $this->CareerTracks->find()->select(['id','name'])->where(['status' => '1'])->toArray();

    //     // $this->loadModel('Competencies');
    //     $this->fetchTable('Competencies');

    //     $competencies = $this->Competencies->find()->select(['id','name'])->where(['status' => '1'])->toArray();

    //     $this->loadModel('CareerLevels');
    //     $careerLevels = $this->CareerLevels->find()->select(['id','name'])->where(['status' => '1'])->toArray();

    //     $this->loadModel('Training');
    //     $training = $this->Training->find()->select(['id','name'])->where(['status' => '1'])->toArray();
        
    //     $career_track_id = '';
    //     if ($this->request->is(['patch', 'post', 'put'])) {
    //         $career_track_id = $this->request->getData('career_trackId');
    //     }
    //     if($this->request->getQuery('career_track_id') > 0){
    //         $career_track_id = $this->request->getQuery('career_track_id');
    //     }
    //     $this->loadModel('TrackCompetencyLevelMappings');
    //     $competencyMappingData = $this->TrackCompetencyLevelMappings->find('All')->where(['career_track_id' => $career_track_id])->toArray();

    //     $this->loadModel('CareerTrackTrainingMappings');
    //     $careerMappingData = $this->CareerTrackTrainingMappings->find('All')->where(['career_track_id' => $career_track_id])->toArray();

    //     $careerTracksEntity = $this->CareerTracks->newEmptyEntity();

    //     // echo "<pre>";print_r($careerTracksEntity); exit;

    //     $this->set(compact('careerTracks','competencies','careerLevels','training','career_track_id','careerMappingData','competencyMappingData','careerTracksEntity'));

    // }


    public function rolesResponsibilities(){

            $this->Authorization->skipAuthorization();
            $this->viewBuilder()->setLayout('default_new');

            $careerTracks = $this->CareerTracks->find()
                ->select(['id','name'])
                ->where(['status' => '1'])
                ->toArray();

            $competenciesTable = $this->fetchTable('Competencies');

            $competencies = $competenciesTable->find()
                ->select(['id','name'])
                ->where(['status' => '1'])
                ->toArray();

            $careerLevelsTable = $this->fetchTable('CareerLevels');

            $careerLevels = $careerLevelsTable->find()
                ->select(['id','name'])
                ->where(['status' => '1'])
                ->toArray();

            $trainingTable = $this->fetchTable('Training');

            $training = $trainingTable->find()
                ->select(['id','name'])
                ->where(['status' => '1'])
                ->toArray();

            $career_track_id = '';

            if ($this->request->is(['patch', 'post', 'put'])) {
                $career_track_id = $this->request->getData('career_trackId');
            }

            if ($this->request->getQuery('career_track_id') > 0) {
                $career_track_id = $this->request->getQuery('career_track_id');
            }

            $trackCompetencyLevelMappingsTable = $this->fetchTable('TrackCompetencyLevelMappings');

            $competencyMappingData = $trackCompetencyLevelMappingsTable->find('all')
                ->where(['career_track_id' => $career_track_id])
                ->toArray();

            $careerTrackTrainingMappingsTable = $this->fetchTable('CareerTrackTrainingMappings');

            $careerMappingData = $careerTrackTrainingMappingsTable->find('all')
                ->where(['career_track_id' => $career_track_id])
                ->toArray();

            $careerTracksEntity = $this->CareerTracks->newEmptyEntity();

            $this->set(compact(
                'careerTracks',
                'competencies',
                'careerLevels',
                'training',
                'career_track_id',
                'careerMappingData',
                'competencyMappingData',
                'careerTracksEntity'
            ));
        }

    public function updateStatus($id,$status){      
		$this->autoRender=false;
		$this->Authorization->skipAuthorization();
		if ($this->request->is('ajax')){
			// $company = TableRegistry::get('Projects');
		    $query = $this->CareerTracks->query();
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

    // public function updateCompetencyLevelMapping()
    // {
    //     $this->Authorization->skipAuthorization();

    //     if ($this->request->is(['patch', 'post', 'put'])) {
           
    //         $competencyIds = $this->request->getData('competency_id');
    //         $contents = $this->request->getData('content');
    //         // $this->loadModel('TrackCompetencyLevelMappings');
    //         $this->fetchTable('TrackCompetencyLevelMappings');
    //         $flag = 0;

    //         foreach ($competencyIds as $key => $competencyId) {
    //             foreach ($contents as $k => $content) {
    //                 $data = $this->TrackCompetencyLevelMappings->find('All')->where(['career_track_id'=>$this->request->getData('career_track_id'), 'competency_id' => $competencyId,'career_level_id' => $k])->first();
    //                 if(empty($data)){
    //                     $data = $this->TrackCompetencyLevelMappings->newEmptyEntity();
    //                     $data->career_track_id = $this->request->getData('career_track_id');
    //                     $data->competency_id =$competencyId;
    //                     $data->career_level_id =$k;
    //                 }
    //                 $data->content = $content[$key];
    //                 if($this->TrackCompetencyLevelMappings->save($data)){
    //                     $flag = 1;
    //                 }else{
    //                     $flag = 0;
    //                 }
    //             }
    //         }
    //         $trainingIds = $this->request->getData('training_id');
    //         if($flag == '1'){
    //             if(!empty($trainingIds)){
    //                 $this->loadModel('CareerTrackTrainingMappings');

    //                 $deleteData = $this->CareerTrackTrainingMappings->find()->where(['career_track_id' => $this->request->getData('career_track_id')])->toArray();
    //                 // print_r($deleteData);
    //                 // echo $this->request->getData('career_track_id'); exit;
    //                 if(!empty($deleteData)){
    //                     $this->CareerTrackTrainingMappings->deleteAll($deleteData);
    //                 }
    //                 // echo "<pre>"; print_r($deleteData);
    //                 // echo $this->request->getData('career_track_id'); exit;
    //                 foreach ($trainingIds as $level => $trainingId) {
    //                     foreach ($trainingId as $k1 => $value) {
    //                         $mappingData = $this->CareerTrackTrainingMappings->newEmptyEntity();
    //                         $mappingData->career_track_id = $this->request->getData('career_track_id');
    //                         $mappingData->training_id = $value;
    //                         $mappingData->career_level_id = $level;
    //                         $this->CareerTrackTrainingMappings->save($mappingData);
    //                     }
    //                 }
    //             }
    //             $this->Flash->success(__('Data has been saved.'));
    //         }else{
    //             $this->Flash->error(__('Data not saved. Please, try again.'));
    //         }
    //         return $this->redirect('/roles_responsibilities?career_track_id='.$this->request->getData('career_track_id'));
    //         // return $this->redirect(['action' => 'rolesResponsibilities']);
    //     }
    // }

    public function updateCompetencyLevelMapping()
{
    $this->Authorization->skipAuthorization();

    if ($this->request->is(['patch', 'post', 'put'])) {

        $competencyIds = $this->request->getData('competency_id');
        $contents = $this->request->getData('content');

        // ✅ FIX: assign fetchTable result
        $trackCompetencyLevelMappingsTable = $this->fetchTable('TrackCompetencyLevelMappings');

        $flag = 0;

        foreach ($competencyIds as $key => $competencyId) {
            foreach ($contents as $k => $content) {

                $data = $trackCompetencyLevelMappingsTable->find('all')
                    ->where([
                        'career_track_id' => $this->request->getData('career_track_id'),
                        'competency_id' => $competencyId,
                        'career_level_id' => $k
                    ])
                    ->first();

                if (empty($data)) {
                    $data = $trackCompetencyLevelMappingsTable->newEmptyEntity();
                    $data->career_track_id = $this->request->getData('career_track_id');
                    $data->competency_id = $competencyId;
                    $data->career_level_id = $k;
                }

                $data->content = $content[$key];

                if ($trackCompetencyLevelMappingsTable->save($data)) {
                    $flag = 1;
                } else {
                    $flag = 0;
                }
            }
        }

        $trainingIds = $this->request->getData('training_id');

        if ($flag == '1') {

            if (!empty($trainingIds)) {

                $careerTrackTrainingMappingsTable = $this->fetchTable('CareerTrackTrainingMappings');

                $deleteData = $careerTrackTrainingMappingsTable->find()
                    ->where(['career_track_id' => $this->request->getData('career_track_id')])
                    ->toArray();

                if (!empty($deleteData)) {
                    $careerTrackTrainingMappingsTable->deleteAll([
                        'career_track_id' => $this->request->getData('career_track_id')
                    ]);
                }

                foreach ($trainingIds as $level => $trainingId) {
                    foreach ($trainingId as $k1 => $value) {

                        $mappingData = $careerTrackTrainingMappingsTable->newEmptyEntity();
                        $mappingData->career_track_id = $this->request->getData('career_track_id');
                        $mappingData->training_id = $value;
                        $mappingData->career_level_id = $level;

                        $careerTrackTrainingMappingsTable->save($mappingData);
                    }
                }
            }

            $this->Flash->success(__('Data has been saved.'));
        } else {
            $this->Flash->error(__('Data not saved. Please, try again.'));
        }

        return $this->redirect('/roles_responsibilities?career_track_id=' . $this->request->getData('career_track_id'));
    }
}
}
