<?php

declare(strict_types=1);

namespace App\Controller;


use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Core\Configure;
use Cake\Utility\Security;
use Cake\Datasource\ConnectionManager;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\Validation\Validator;





/**
 * EmployeeDetails Controller
 *
 * @property \App\Model\Table\EmployeeDetailsTable $EmployeeDetails
 * @method \App\Model\Entity\EmployeeDetail[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeeDetailsController extends AppController
{

  public function initialize(): void
  {
    parent::initialize();

    // $this->loadComponent('Paginator');
    $this->loadComponent('Flash'); // Include the FlashComponent
    // $this->loadComponent('RequestHandler');
    // $this->loadModel("Users");
    // $this->loadModel("EmployeeAcademics");
    // $this->loadModel("EmployeeDetails");
    // $this->loadModel("EmployeeOtherDetails");
    // $this->loadModel("EmployeeProofs");
    // $this->loadModel("EmployeeReferences");
    // $this->loadModel("EmployeeWorkHistorys");

    // $this->loadModel("MyTeams");
    // $this->loadModel("MyTeamResources");
    // $this->loadModel("EmployeeWorkHistorySlips");

      $this->Users = $this->fetchTable('Users');
    $this->EmployeeAcademics = $this->fetchTable('EmployeeAcademics');
    $this->EmployeeDetails = $this->fetchTable('EmployeeDetails');
    $this->EmployeeOtherDetails = $this->fetchTable('EmployeeOtherDetails');
    $this->EmployeeProofs = $this->fetchTable('EmployeeProofs');
    $this->EmployeeReferences = $this->fetchTable('EmployeeReferences');
    $this->EmployeeWorkHistorys = $this->fetchTable('EmployeeWorkHistorys');
    $this->MyTeams = $this->fetchTable('MyTeams');
    $this->MyTeamResources = $this->fetchTable('MyTeamResources');
    $this->EmployeeWorkHistorySlips = $this->fetchTable('EmployeeWorkHistorySlips');
    $this->CareerLevels = $this->fetchTable('CareerLevels');

    $this->Authorization->skipAuthorization();
  }
  /**
   * Index method
   *
   * @return \Cake\Http\Response|null|void Renders view
   */
  public function index()
  {
    $this->viewBuilder()->setLayout('default_new');

    // $this->paginate = [
    //     'contain' => ['Users'],
    // ];
    // $employeeDetails = $this->paginate($this->EmployeeDetails);

    $this->set(compact('employeeDetails'));
  }

  /**
   * View method
   *
   * @param string|null $id Employee Detail id.
   * @return \Cake\Http\Response|null|void Renders view
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id = null)
  {
    $employeeDetail = $this->EmployeeDetails->get($id, [
      'contain' => ['Users'],
    ]);

    $this->set(compact('employeeDetail'));
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
   */

  public function reference_insert($id, $name, $org, $desgn, $address, $contact, $flag)
  {

    $employeeReferences = $this->EmployeeReferences->newEmptyEntity();
    $employeeReferencesTable = $this->getTableLocator()->get("EmployeeReferences");

    $employeeReferences->user_id = $id;
    $employeeReferences->ref_name = $name;
    $employeeReferences->ref_org = $org;
    $employeeReferences->ref_desigtion = $desgn;
    $employeeReferences->ref_address = $address;
    $employeeReferences->ref_contact = $contact;
    $employeeReferences->ref_flag = $flag;
    $employeeReferencesTable->save($employeeReferences);
  }


  public function insert_proof($id, $data, $flag)
  {

    $imgValid = $this->imageValidation($data);
    // echo "<pre>";
    // print_r($imgValid);
    // die;
    if (!$imgValid && !empty($data)) {
      $this->Flash->error('Upload accepting img, png, jpeg, jpg and other popular image formats', ['key' => 'imgError']);
      return $this->redirect(['controller' => 'EmployeeDetails', 'action' => 'edit', $id]);
    }

    if (!empty($data)) {
      $employeeProofsTable = $this->getTableLocator()->get("EmployeeProofs");

      $count = 1;
      for ($i = 0; $i < count($data); $i++) {
        $employeeProofs = $this->EmployeeProofs->newEmptyEntity();
        $fileName =  chr(rand(65, 122)) . "_" . $data[$i]->getClientFilename();

        $targetPath = WWW_ROOT . 'img' . '/' . 'ADP' . '/' . $fileName;

        $data[$i]->moveTo($targetPath);

        $employeeProofs->user_id = $id;
        $employeeProofs->prf_file = $fileName;
        $employeeProofs->prf_flag = $flag;
        $employeeProofsTable->save($employeeProofs);

        $count++;
      }


      // print_r($count);

      // die;

    }
  }


  public function save_academic_img($obj)
  {



    if (!empty($obj->getClientFilename())) {

      $fileName = $obj->getClientFilename();

      $targetPath = 'img/' . 'ACC/' . time() . $fileName;
      $obj->moveTo($targetPath);

      return $targetPath;
    }

    return false;
  }


  public function deleteimgproof()
  {

    $this->autoRender = false;


    if ($this->request->is('ajax')) {
      // img\ADP
      $file = $this->request->getData('fileName');
      $dir = WWW_ROOT . 'img' . '/' . 'ADP' . '/' . $file;
      $emp_proof = $this->EmployeeProofs->get($this->request->getData('id'));

      if ($this->EmployeeProofs->delete($emp_proof)) {
        unlink($dir);
        echo "Yes";
      } else {
        echo "No";
      }
    }
  }



  public function deleteimgworkhistory()
  {

    $this->autoRender = false;

    if ($this->request->is('ajax')) {

      $emp_his = $this->EmployeeWorkHistorySlips->get($this->request->getData('id'));
      $emp_his_Table = $this->getTableLocator()->get("EmployeeWorkHistorySlips");

      if ($emp_his_Table->delete($emp_his)) {
        echo "Yes";
      } else {
        echo "No";
      }
    }
  }


  public function deleteacademic()
  {

    $this->autoRender = false;


    if ($this->request->is('ajax')) {

      $emp_acad = $this->EmployeeAcademics->get($this->request->getData('id'));
      $emp_acad_Table = $this->getTableLocator()->get("EmployeeAcademics");

      if ($this->request->getData("flag") == "mark") {


        $emp_acad->acc_mark = null;
      } else if ($this->request->getData("flag") == "cert") {


        $emp_acad->acc_certificate = null;
      }


      if ($emp_acad_Table->save($emp_acad)) {
        echo "Yes";
      } else {
        echo "No";
      }
    }
  }




  public function insert_academic($id, $type, $org, $ac_education, $ac_passout, $certifacte, $marksheet)
  {

    // print_r(array($id,$type,$org,$ac_education,$ac_passout,$certifacte,$marksheet));
    // die;

    $emp_academic = $this->EmployeeAcademics->newEmptyEntity();
    $emp_academicTable = $this->getTableLocator()->get("EmployeeAcademics");


    $emp_academic->user_id = $id;
    $emp_academic->ac_type = $type;
    $emp_academic->ac_org = $org;
    $emp_academic->ac_education = $ac_education;
    $emp_academic->ac_passout = $ac_passout;
    $res = $this->save_academic_img($certifacte);
    if ($res) {
      $emp_academic->acc_certificate = $res;
    }

    $res = $this->save_academic_img($marksheet);
    if ($res) {

      $emp_academic->acc_mark = $res;
    }

    $emp_academicTable->save($emp_academic);
  }


  function save_image_work_hstry($obj)
  {

    if (!empty($obj->getClientFilename())) {

      $fileName = $obj->getClientFilename();

      $targetPath = 'img/' . 'Sal/' . time() . $fileName;
      $obj->moveTo($targetPath);

      return $targetPath;
    }

    return false;
  }


  public function insert_work_history($id, $name, $desgn, $location, $cmp_doj, $cmp_dor, $cmp_splip)
  {

    // echo "<pre>";
    // print_r($id,$name,$desgn,$location,$cmp_doj,$com_dor,$cmp_splip);
    // die();

    $employeeWorkhistorys = $this->EmployeeWorkHistorys->newEmptyEntity();
    $employeeWorkhistorysTable = $this->getTableLocator()->get("EmployeeWorkHistorys");


    if (!empty($id)) {



      $employeeWorkhistorys->user_id = $id;
      $employeeWorkhistorys->cmp_name = $name;
      $employeeWorkhistorys->cmp_desgnation = $desgn;
      $employeeWorkhistorys->cmp_location = $location;




      $employeeWorkhistorys->cmp_doj = $cmp_doj;
      $employeeWorkhistorys->cmp_dor = $cmp_dor;

      $res = $this->save_image_work_hstry($cmp_splip);

      if ($res) {

        $employeeWorkhistorys->cmp_splip = $res;
      }

      $employeeWorkhistorysTable->save($employeeWorkhistorys);
    }
  }


  public function save_other_inform_img($obj, $fname)
  {

    if (!empty($obj->getClientFilename())) {

      $fileName = $obj->getClientFilename();

      $targetPath = 'img/' . $fname . '/' . time() . $fileName;
      $obj->moveTo($targetPath);

      return $targetPath;
    }

    return false;
  }




  public function add()
  {

    $this->viewBuilder()->setLayout('default_new');


    $request_data = $this->request->getData();
    // echo "<pre>";
    // print_r($request_data);
    // die;
    if ($this->request->is('post')) {

      $user = $this->Users->newEntity($request_data);
      $usertable = $this->getTableLocator()->get("Users");
      $usertable->save($user);
      if ($usertable->save($user)) {


        $employeeDetail = $this->EmployeeDetails->newEntity($request_data);
        $employeeDetailtable = $this->getTableLocator()->get("EmployeeDetails");
        $employeeDetail->user_id = $user->id;
        $employeeDetailtable->save($employeeDetail);

        $ref_length = count($request_data['ref_gov_name']);
        for ($i = 0; $i < $ref_length; $i++) {

          $this->reference_insert(
            $user->id,
            $request_data['ref_gov_name'][$i],
            $request_data['ref_gov_org'][$i],
            $request_data['ref_gov_desgn'][$i],
            $request_data['ref_gov_address'][$i],
            $request_data['ref_gov_contact'][$i],
            "GOV"
          );
        }



        $ref_length = count($request_data['ref_last_name']);


        for ($i = 0; $i < $ref_length; $i++) {

          $this->reference_insert(
            $user->id,
            $request_data['ref_last_name'][$i],
            $request_data['ref_last_org'][$i],
            $request_data['ref_last_desgn'][$i],
            $request_data['ref_last_addrss'][$i],
            $request_data['ref_last_contact'][$i],
            "PRV"
          );
        }

        if (!empty($request_data['identity_proof'][0]->getClientFilename()))
          $this->insert_proof($user->id, $this->request->getData('identity_proof'), 'IDP');

        if (!empty($request_data['address_proof'][0]->getClientFilename()))
          $this->insert_proof($user->id, $request_data['address_proof'], "ADP");



        $academic_count = count($request_data['education_type']);
        for ($i = 0; $i < $academic_count; $i++) {

          // // print_r(array($request_data['education_type'][$i],$request_data['education_org'][$i],$request_data['education_Education'][$i],$request_data['education_passout'][$i],$request_data['certificate'][$i],$request_data['Marksheet'][$i]));
          // die;
          $this->insert_academic($user->id, $request_data['education_type'][$i], $request_data['education_org'][$i], $request_data['education_Education'][$i], $request_data['education_passout'][$i], $request_data['certificate'][$i], $request_data['Marksheet'][$i]);
        }

        $work_his_count = count($request_data['comp_name']);
        for ($i = 0; $i < $work_his_count; $i++) {

          $this->insert_work_history($user->id, $request_data['comp_name'][$i], $request_data['cmp_design'][$i], $request_data['cmp_location'][$i], $request_data['cmp_doj'][$i], $request_data['cmp_dor'][$i], $request_data['salary_slip'][$i]);
        }


        $employeeOtherDetails = $this->EmployeeOtherDetails->newEmptyEntity();
        $employeeOtherDetaisTable = $this->getTableLocator()->get("EmployeeOtherDetails");

        $employeeOtherDetails->user_id = $user->id;
        $employeeOtherDetails->bank_acc_no = $request_data['bank_acc_no'];

        // if(!empty($request_data['passbook']->getClientFilename()))

        if (!empty($request_data['pf_form'][0]->getClientFilename()))
          $this->insert_proof($user->id, $this->request->getData('pf_form'), 'pf_form');

        if (!empty($request_data['emp_certificate'][0]->getClientFilename()))
          $this->insert_proof($user->id, $this->request->getData('emp_certificate'), 'emp_certificate');

        if (!empty($request_data['nda_form'][0]->getClientFilename()))
          $this->insert_proof($user->id, $this->request->getData('nda_form'), 'nda_form');

        // $res = $this->save_other_inform_img($request_data['pf_form'], 'Other');
        // if ($res) {

        //   $employeeOtherDetails->pf_form = $res;
        // }

        // $res = $this->save_other_inform_img($request_data['emp_certificate'], 'Other');
        // if ($res) {

        //   $employeeOtherDetails->emp_certific = $res;
        // }

        // $res = $this->save_other_inform_img($request_data['nda_form'], 'Other');
        // if ($res) {

        //   $employeeOtherDetails->nda_form = $res;
        // }


        $employeeOtherDetaisTable->save($employeeOtherDetails);

        // $this->set(array("msg"=>"success"));
        // $this->RequestHandler->renderAs($this,'json');

        // $this->redirect([

        //  'action'=>'index'

        // ]);

        echo "Passsed";
        die;
      } else {

        // $this->set(array("msg"=>"failed"));
        //     $this->RequestHandler->renderAs($this,'json');


        echo "Failed";
      }
    }
  }

  /**
   * Edit method
   *
   * @param string|null $id Employee Detail id.
   * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   * 
   * 
   */


  public function get_reporting_manager()
  {

    $session = new \Cake\Http\Session();
    $userSession = $session->read('data');

    // $this->loadModel("Users");
    $this->fetchTable("Users");
    $role = $userSession['role'];

    $parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];


    $conn = ConnectionManager::get('default');

    $users = $this->Users->find("list", [
      "keyField" => "id",
      "valueField" => "name",
      "conditions" => [
        "role" => 3, "deleted" => 1, "company_id" => $parent_id, "status" => 1
      ],
      "order" => [
        "name" => "asc"
      ]
    ])->toArray();


    return $users;
  }


  // public function edit($id = null)
  // {
  //   $session = new \Cake\Http\Session();
  //   $userSession = $session->read('data');

  //   $role = $userSession['role'];

  //   $parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];


  //   // echo "<pre>";
  //   // print_r($request_data);
  //   // die;


  //   $employeeReference = $this->EmployeeReferences->newEmptyEntity();
  //   $employeeAcademic = $this->EmployeeAcademics->newEmptyEntity();

  //   $employeeWorkHistory = $this->EmployeeWorkHistorys->newEmptyEntity();
  //   $workhistoryslip = $this->EmployeeWorkHistorySlips->newEmptyEntity();



  //   $this->viewBuilder()->setLayout('default_new');

  //   if ($this->request->is(['post', 'put'])) {

  //     $request_data = $this->request->getData();

  //     if ($request_data["emp_detail_id"]) {

  //       $employeeDetail = $this->EmployeeDetails->get($request_data["emp_detail_id"]);
  //     } else {
  //       $employeeDetail = $this->EmployeeDetails->newEmptyEntity();
  //     }

  //     if ($request_data["user_id"]) {

  //       $user = $this->Users->get($request_data['user_id']);
  //     } else {
  //       $user = $this->Users->newEmptyEntity();
  //       $request_data["role"] = 3;
  //       $request_data["status"] = 1;
  //       $request_data["company_id"] = $parent_id;
  //       // $request_data["password"] = Security::hash('password');
  //       $request_data["password"] = 'password';
  //       if ($request_data["doj"] <= date('Y-m-15')) {
  //         $request_data["el"] = 0.66;
  //         $month = date('m', strtotime($request_data["doj"]));
  //         if (in_array($month, [4, 6, 7, 9, 10, 12, 1, 3])) {
  //           $request_data["cl"] = 1;
  //         } else {
  //           $request_data["sl"] = 1;
  //         }
  //       }

  //       if (isset($request_data["email"]) && !empty($request_data["email"])) {

  //         //  $this->SendWelcomeMail($request_data["email"]);

  //       }
  //     }

  //     $role_name = implode(',', $this->request->getData('role_name'));

  //     $request_data["role_name"] = $role_name;

  //     $user->role_name = $role_name;
  //     $usertable = $this->Users->patchEntity($user, $request_data);

  //     //  echo "<pre>";
  //     //  print_r($usertable);
  //     // die; 
  //     $this->Users->save($usertable);


  //     $request_data["user_id"] = $usertable->id;


  //     $my_team_res_tab = TableRegistry::get('MyTeamResources');

  //     $res_cond = $my_team_res_tab->find("list", [
  //       "conditions" => [
  //         "resid" => $usertable->id
  //       ],
  //       "select" => [
  //         "id"
  //       ],
  //       "valueField" => "id"
  //     ])->toArray();

  //     if (empty($request_data["teamid"])) {
  //       $request_data["teamid"] = null;
  //     }

  //     if (count($res_cond) > 0) {

  //       if ($request_data["teamid"]) {

  //         $my_team_res_obj = $my_team_res_tab->updateAll(["my_team_id" => $request_data["teamid"]], ["resid" => $usertable->id]);
  //       } else {

  //         $my_team_res_obj = $my_team_res_tab->deleteAll(["resid" => $usertable->id]);
  //       }
  //     } else {

  //       $res_save_data = [
  //         "resid" => $usertable->id,
  //         "my_team_id" => $request_data["teamid"]
  //       ];

  //       $my_team_entity = $my_team_res_tab->newEntity($res_save_data);

  //       $my_team_res_tab->save($my_team_entity);
  //     }


  //     // echo "<pre>";
  //     //  print_r($my_team_res_obj);
  //     //  die;

  //     // $my_team_res_obj->my_team_id = $request_data["teamid"];
  //     // $my_team_res_tab->save($my_team_res_obj);

  //     // echo "<pre>";
  //     // echo $request_data['note'];
  //     // print_r($request_data);
  //     // die;
  //     $employeeDetail->email_dob = $request_data['email_dob'];
  //     $employeeDetail->note = $request_data['note'];
  //     $employeeDetail->aadhar_card = $request_data['aadhar_card'];
  //     $employeeDetail->blood_group = $request_data['blood_group'];
  //     $employeeDetailtable = $this->EmployeeDetails->patchEntity($employeeDetail, $request_data);
  //     // $employeeDetailtable->email_dob = $this->request->getData('email_dob');
  //     $res = $this->EmployeeDetails->save($employeeDetailtable);


  //     if (!empty($request_data['identity_proof'][0]->getClientFilename()))
  //       $this->insert_proof($request_data['user_id'], $request_data['identity_proof'], 'IDP');

  //     if (!empty($request_data['address_proof'][0]->getClientFilename()))
  //       $this->insert_proof($request_data['user_id'], $request_data['address_proof'], 'ADP');


  //     if ($this->EmployeeOtherDetails->findByUserId($request_data["user_id"])->first()) {

  //       $employeeOtherDetails = $this->EmployeeOtherDetails->findByUserId($request_data["user_id"])->first();
  //     } else {

  //       $employeeOtherDetails = $this->EmployeeOtherDetails->newEmptyEntity();
  //     }

  //     $employeeOtherDetaisTable = $this->getTableLocator()->get("EmployeeOtherDetails");

  //     $employeeOtherDetails->user_id = $request_data["user_id"];
  //     $employeeOtherDetails->bank_acc_no = $request_data["bank_acc_no"];

  //     if (!empty($request_data['passbook']->getClientFilename())) {
  //       $fileName = $request_data['passbook']->getClientFilename();
  //       $extension = pathinfo($fileName, PATHINFO_EXTENSION);
  //       $allowedExtension =  array('jpeg', 'jpg', "png", "JPEG", "JPG", "PNG", "pdf", "PDF","xlsx","xls","docs","doc","csv");
  //       if (in_array($extension, $allowedExtension)) {
  //         $fileName =  chr(rand(65, 122)) . rand(10, 999) . "_" . $request_data['passbook']->getClientFilename();

  //         $targetPath = WWW_ROOT . 'img' . '/' . 'ADP' . '/' . $fileName;

  //         $request_data['passbook']->moveTo($targetPath);
  //         $employeeOtherDetails->passbook = $fileName;
  //       } else {
  //         $this->Flash->error('Upload accepting img, png, jpeg, jpg and other popular image formats', ['key' => 'imgError']);
  //         if ($this->request->getData('edit-profile') == 'edit-profile')
  //           return $this->redirect("/edit-profile");
  //         else
  //           return $this->redirect(['action' => 'edit', $request_data["user_id"]]);
  //       }
  //     }

  //     if (!empty($request_data['pf_form'][0]->getClientFilename()))
  //       $this->insert_proof($user->id, $this->request->getData('pf_form'), 'pf_form');

  //     if (!empty($request_data['emp_certificate'][0]->getClientFilename()))
  //       $this->insert_proof($user->id, $this->request->getData('emp_certificate'), 'emp_certificate');

  //     if (!empty($request_data['nda_form'][0]->getClientFilename()))
  //       $this->insert_proof($user->id, $this->request->getData('nda_form'), 'nda_form');

  //     // $res = $this->save_other_inform_img($request_data['pf_form'], 'Other');
  //     // if ($res) {

  //     //   $employeeOtherDetails->pf_form = $res;
  //     // }

  //     // $res = $this->save_other_inform_img($request_data['emp_certificate'], 'Other');
  //     // if ($res) {

  //     //   $employeeOtherDetails->emp_certific = $res;
  //     // }

  //     // $res = $this->save_other_inform_img($request_data['nda_form'], 'Other');
  //     // if ($res) {

  //     //   $employeeOtherDetails->nda_form = $res;
  //     // }


  //     $employeeOtherDetaisTable->save($employeeOtherDetails);



  //     $this->Flash->success(__('Changes has been saved'));

  //     if ($this->request->getData('edit-profile') == 'edit-profile')
  //       return $this->redirect("/edit-profile");
  //     else
  //       return $this->redirect(['action' => 'edit', $request_data["user_id"]]);
  //   }

  //   $user_data = [];

  //   if ($id) {
  //     $user_data = $this->Users->get($id, ['contain' => ['EmpDetail', 'EmpAcad', 'EmpOthdetail', 'EmpProof', 'EmpRef', 'EmpWorkHistory', 'EmployeeOtherDetails', "EmpWorkHistory.EmployeeWorkHistorySlips"]]);
  //   }


  //   // echo "<pre>";
  //   // print_r($user_data);
  //   // die;

  //   $my_team_data = $this->MyTeams->find("list", [
  //     "keyField" => "id",
  //     "valueField" => "team_name"
  //   ])->toArray();


  //   // echo "<pre>";
  //   // print_r($my_team_data);
  //   // die;

  //   $reporting_managers = $this->get_reporting_manager();

  //   $this->loadModel('CareerLevels');
  //   $levels = $this->CareerLevels->find('list', ['keyField' => 'id', 'valueField' => 'name'])->where(['status' => 1])->toArray();

  //   $this->set(compact('user_data', 'employeeReference', 'employeeAcademic', 'employeeWorkHistory', 'reporting_managers', "my_team_data", 'levels'));
  // }

    public function edit($id = null)
  {
    $session = new \Cake\Http\Session();
    $userSession = $session->read('data');

    $role = $userSession['role'];

    $parent_id = ($role == 1) ? $userSession['id'] : $userSession['parent_id'];

    $employeeReference = $this->EmployeeReferences->newEmptyEntity();
    $employeeAcademic = $this->EmployeeAcademics->newEmptyEntity();

    $employeeWorkHistory = $this->EmployeeWorkHistorys->newEmptyEntity();
    $workhistoryslip = $this->EmployeeWorkHistorySlips->newEmptyEntity();

    $this->viewBuilder()->setLayout('default_new');

    if ($this->request->is(['post', 'put'])) {

      $request_data = $this->request->getData();

      if ($request_data["emp_detail_id"]) {

        $employeeDetail = $this->EmployeeDetails->get($request_data["emp_detail_id"]);
      } else {
        $employeeDetail = $this->EmployeeDetails->newEmptyEntity();
      }

      if ($request_data["user_id"]) {

        $user = $this->Users->get($request_data['user_id']);
      } else {
        $user = $this->Users->newEmptyEntity();
        $request_data["role"] = 3;
        $request_data["status"] = 1;
        $request_data["company_id"] = $parent_id;
        $request_data["password"] = 'password';
        if ($request_data["doj"] <= date('Y-m-15')) {
          $request_data["el"] = 0.66;
          $month = date('m', strtotime($request_data["doj"]));
          if (in_array($month, [4, 6, 7, 9, 10, 12, 1, 3])) {
            $request_data["cl"] = 1;
          } else {
            $request_data["sl"] = 1;
          }
        }

        if (isset($request_data["email"]) && !empty($request_data["email"])) {

        }
      }

      // $role_name = implode(',', $this->request->getData('role_name'));
      $roleData = $this->request->getData('role_name');
        if (is_array($roleData)) {
          $role_name = implode(',', $roleData);
        } elseif (is_string($roleData)) {
          $role_name = $roleData;
        } else {
          $role_name = '';
        }


      $request_data["role_name"] = $role_name;

      $user->role_name = $role_name;
      if (!empty($request_data['user_image']) && $request_data['user_image']->getClientFilename()) {
          $file = $request_data['user_image'];
          $fileName = $file->getClientFilename();
          $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

          if (in_array($extension, $allowedExtensions)) {
              // Generate unique filename
              $uniqueFileName = chr(rand(65, 90)) . time() . '_' . $fileName;
              $uploadPath = WWW_ROOT . 'img' . DS . 'user_images' . DS;

              // Create folder if not exists
              if (!file_exists($uploadPath)) {
                  mkdir($uploadPath, 0755, true);
              }

              $filePath = $uploadPath . $uniqueFileName;
              $file->moveTo($filePath);

              // Save filename to the request data
              $request_data['user_image'] = $uniqueFileName;
          } else {
              $this->Flash->error('Please upload a valid image file (jpg, jpeg, png, gif, webp)');
              return $this->redirect(['action' => 'edit', $request_data["user_id"]]);
          }
      } else {
          // If no new image uploaded, keep existing image
          if (!empty($user->user_image)) {
              $request_data['user_image'] = $user->user_image;
          }
      }
      unset($request_data['user_image']);
      $usertable = $this->Users->patchEntity($user, $request_data);

      $this->Users->save($usertable);

      $request_data["user_id"] = $usertable->id;

      // $my_team_res_tab = TableRegistry::get('MyTeamResources');
      $my_team_res_tab = $this->fetchTable('MyTeamResources');

      $res_cond = $my_team_res_tab->find("list", [
        "conditions" => [
          "resid" => $usertable->id
        ],
        "select" => [
          "id"
        ],
        "valueField" => "id"
      ])->toArray();

      if (empty($request_data["teamid"])) {
        $request_data["teamid"] = null;
      }

      if (count($res_cond) > 0) {

        if ($request_data["teamid"]) {

          $my_team_res_obj = $my_team_res_tab->updateAll(["my_team_id" => $request_data["teamid"]], ["resid" => $usertable->id]);
        } else {

          $my_team_res_obj = $my_team_res_tab->deleteAll(["resid" => $usertable->id]);
        }
      } else {

        $res_save_data = [
          "resid" => $usertable->id,
          "my_team_id" => $request_data["teamid"]
        ];

        $my_team_entity = $my_team_res_tab->newEntity($res_save_data);

        $my_team_res_tab->save($my_team_entity);
      }

      $employeeDetail->email_dob = $request_data['email_dob'];
      $employeeDetail->note = $request_data['note'];
      $employeeDetail->aadhar_card = $request_data['aadhar_card'];
      $employeeDetail->blood_group = $request_data['blood_group'];
      $employeeDetailtable = $this->EmployeeDetails->patchEntity($employeeDetail, $request_data);
      $res = $this->EmployeeDetails->save($employeeDetailtable);

      if (!empty($request_data['identity_proof'][0]->getClientFilename()))
        $this->insert_proof($request_data['user_id'], $request_data['identity_proof'], 'IDP');

      if (!empty($request_data['address_proof'][0]->getClientFilename()))
        $this->insert_proof($request_data['user_id'], $request_data['address_proof'], 'ADP');


      if ($this->EmployeeOtherDetails->findByUserId($request_data["user_id"])->first()) {

        $employeeOtherDetails = $this->EmployeeOtherDetails->findByUserId($request_data["user_id"])->first();
      } else {

        $employeeOtherDetails = $this->EmployeeOtherDetails->newEmptyEntity();
      }

      $employeeOtherDetaisTable = $this->getTableLocator()->get("EmployeeOtherDetails");

      $employeeOtherDetails->user_id = $request_data["user_id"];
      $employeeOtherDetails->bank_acc_no = $request_data["bank_acc_no"];

      if (!empty($request_data['passbook']->getClientFilename())) {
        $fileName = $request_data['passbook']->getClientFilename();
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowedExtension =  array('jpeg', 'jpg', "png", "JPEG", "JPG", "PNG", "pdf", "PDF","xlsx","xls","docs","doc","csv");
        if (in_array($extension, $allowedExtension)) {
          $fileName =  chr(rand(65, 122)) . rand(10, 999) . "_" . $request_data['passbook']->getClientFilename();

          $targetPath = WWW_ROOT . 'img' . '/' . 'ADP' . '/' . $fileName;

          $request_data['passbook']->moveTo($targetPath);
          $employeeOtherDetails->passbook = $fileName;
        } else {
          $this->Flash->error('Upload accepting img, png, jpeg, jpg and other popular image formats', ['key' => 'imgError']);
          if ($this->request->getData('edit-profile') == 'edit-profile')
            return $this->redirect("/edit-profile");
          else
            return $this->redirect(['action' => 'edit', $request_data["user_id"]]);
        }
      }

      if (!empty($request_data['pf_form'][0]->getClientFilename()))
        $this->insert_proof($user->id, $this->request->getData('pf_form'), 'pf_form');

      if (!empty($request_data['emp_certificate'][0]->getClientFilename()))
        $this->insert_proof($user->id, $this->request->getData('emp_certificate'), 'emp_certificate');

      if (!empty($request_data['nda_form'][0]->getClientFilename()))
        $this->insert_proof($user->id, $this->request->getData('nda_form'), 'nda_form');
      $employeeOtherDetaisTable->save($employeeOtherDetails);
      $this->Flash->success(__('Changes has been saved'));

      if ($this->request->getData('edit-profile') == 'edit-profile')
        return $this->redirect("/edit-profile");
      else
        return $this->redirect(['action' => 'edit', $request_data["user_id"]]);
    }

    $user_data = [];

    if ($id) {
      $user_data = $this->Users->get($id, ['contain' => ['EmpDetail', 'EmpAcad', 'EmpOthdetail', 'EmpProof', 'EmpRef', 'EmpWorkHistory', 'EmployeeOtherDetails', "EmpWorkHistory.EmployeeWorkHistorySlips"]]);
    }
    $my_team_data = $this->MyTeams->find("list", [
      "keyField" => "id",
      "valueField" => "team_name"
    ])->toArray();

    $reporting_managers = $this->get_reporting_manager();

    // $this->loadModel('CareerLevels');
    $this->fetchTable('CareerLevels');
    $levels = $this->CareerLevels->find('list', ['keyField' => 'id', 'valueField' => 'name'])->where(['status' => 1])->toArray();

    $this->set(compact('user_data', 'employeeReference', 'employeeAcademic', 'employeeWorkHistory', 'reporting_managers', "my_team_data", 'levels'));
  }

  /**
   * Delete method
   *
   * @param string|null $id Employee Detail id.
   * @return \Cake\Http\Response|null|void Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */

  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $employeeDetail = $this->EmployeeDetails->get($id);
    if ($this->EmployeeDetails->delete($employeeDetail)) {
      $this->Flash->success(__('The employee detail has been deleted.'));
    } else {
      $this->Flash->error(__('The employee detail could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  public function editProfile()
  {

    $this->viewBuilder()->setLayout('default_new');

    $identity = $this->request->getAttribute('identity');

    $id = $identity->id;
    $user_data = [];

    if ($id) {
      $user_data = $this->Users->get($id, ['contain' => ['EmpDetail', 'EmpAcad', 'EmpOthdetail', 'EmpProof', 'EmpRef', 'EmpWorkHistory', 'EmployeeOtherDetails', "EmpWorkHistory.EmployeeWorkHistorySlips"]]);
    }

    // echo "<pre>";
    // print_r($user_data);
    // die;

    $my_team_data = $this->MyTeams->find("list", [
      "keyField" => "id",
      "valueField" => "team_name"
    ])->toArray();

    $reporting_managers = $this->get_reporting_manager();

    // $this->loadModel('CareerLevels');
    $this->fetchTable('CareerLevels');
    $levels = $this->CareerLevels->find('list', ['keyField' => 'id', 'valueField' => 'name'])->where(['status' => 1])->toArray();

    // echo "<pre>";
    // print_r($identity);
    // die;

    $this->set(compact('user_data', 'my_team_data', 'reporting_managers', 'levels'));
  }
}