<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;

/**
 * Salaries Controller
 *
 * @property \App\Model\Table\SalariesTable $Salaries
 * @method \App\Model\Entity\Salary[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SalariesController extends AppController
{

    // public $paginate = [
    //     'limit' => 20,
    // ];

    protected array $paginate = [
        'limit' => 20,
    ];
    public function initialize(): void
    {
        parent::initialize();

        $this->viewBuilder()->setLayout('default_new');
        $this->Authorization->skipAuthorization();
        // $this->EmployeePunchTime = $this->getTableLocator()->get("EmployeePunchTime");
        // $this->Users = $this->getTableLocator()->get("Users");
        $this->Users = $this->fetchTable("Users");
        $this->EmployeePunchTime = $this->fetchTable("EmployeePunchTime");
    }

    public function index($user_id = null, $key = null)
    {
        if (!is_numeric($user_id)) {
            $this->Flash->error(_('Please select user.'));
            $this->redirect($this->referer());
        }
        $this->Salaries->belongsTo(
            'Creator',
            [
                'className' => 'Users',
                'foreignKey' => 'created_by'
            ]
        );


        if (isset($this->request->getData()['k']))
            $key = $this->request->getData()['k'];
        // print_r($this->request->getData()['k']);
        // die();
        $salaries = $this->Salaries->find("all", ['contain' => ['Users', 'Creator'], 'conditions' => [
            'amount_type' => 'salary',
            'user_id' => $user_id
        ]]);


        $bonus =  $this->Salaries->find("all", ['contain' => ['Users', 'Creator'], 'conditions' => [
            'amount_type' => 'bonus',
            'user_id' => $user_id
        ]]);

        //pr($salaries);die;
        $salary = $this->Salaries->newEmptyEntity();
        $this->set(compact('salaries', 'salary', 'user_id', 'key', 'bonus'));
    }


    public function view($id = null)
    {
        try {
            $salary = $this->Salaries->get($id, [
                'contain' => ['Users'],

            ]);
        } catch (\Exception $e) {

            $salary = [];
        }
        $this->set(compact('salary'));
    }


    public function add()
    {
        $this->Salaries = $this->fetchTable("Salaries");
        $salary = $this->Salaries->newEmptyEntity();
        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();

            //$data['amount'] = new QueryExpression('AES_ENCRYPT("'.$data["amount"].'","'.$data["key"].'")');
            $data['amount'] = openssl_encrypt($data["amount"], "AES-128-ECB", $data["key"]);

            $data['created_by'] = $this->request->getSession()->read('data.id');
            $salary = $this->Salaries->patchEntity($salary, $data);
            //pr($salary);die;
            if ($this->Salaries->save($salary)) {
                $this->Flash->success(__('The salary has been saved.'));

                return $this->redirect(['action' => 'index/' . $data['user_id']]);
            }
            $this->Flash->error(__('The salary could not be saved. Please, try again.'));
        }
        //$users = $this->Salaries->Users->find('list', ['limit' => 200]);
        $this->set(compact('salary', 'users'));
    }


    public function edit($id = null)
    {
         $this->Salaries = $this->fetchTable("Salaries");
        $salary = $this->Salaries->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $salary = $this->Salaries->patchEntity($salary, $this->request->getData());
            if ($this->Salaries->save($salary)) {
                $this->Flash->success(__('The salary has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The salary could not be saved. Please, try again.'));
        }
        $users = $this->Salaries->Users->find('list', ['limit' => 200]);
        $this->set(compact('salary', 'users'));
    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salary = $this->Salaries->get($id);
        if ($this->Salaries->delete($salary)) {
            $this->Flash->success(__('The salary has been deleted.'));
        } else {
            $this->Flash->error(__('The salary could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }

    public function addSalary()
    {
        // $this->loadModel('Expense'); 
        $this->Expenses = $this->fetchTable('Expenses');
        $expense = $this->Expenses->newEmptyEntity();
        if ($this->request->is(['ajax'])) {
            $data = $this->request->getData();

            $data['amount'] = openssl_encrypt($data["amount"], "AES-128-ECB", $data["key"]);

            $data['created_by'] = $this->request->getSession()->read('data.id');

            $oldData = $this->Expenses->findByUserIdAndMonthAndYear($data['user_id'], $data['month'], $data['year'])->first();

            if (!empty($oldData)) {
                $data['id'] = $oldData['id'];
            }
            $expense = $this->Expenses->patchEntity($expense, $data);

            if ($this->Expenses->save($expense)) {
                echo "true";
                die;
            } else {
                echo "false";
                die;
            }
        }
    }
    public function list($key = null)
    {
        $this->Authorization->skipAuthorization();
		$this->viewBuilder()->setLayout('default_new');
		$conn = ConnectionManager::get('default');
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
		$user_id = $userSession['id'];
		// validation for valid user
		$roleArray = $userSession['role_name'];
		$validList = [12];
		$this->routeValidation($roleArray,$validList);

        if ($this->request->getSession()->check('cost.key'))
            $key = $this->request->getSession()->read('cost.key');
        // $this->loadModel('Expense');
        // $this->loadModel("Users");

       $this->Expenses = $this->fetchTable('Expenses');
        $this->Users = $this->fetchTable("Users");

        $year = !empty($this->request->getData('year')) ? $this->request->getData('year') : date('Y');

        $curr = date('Y');
        $userlist = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])
            ->where(['status' => 1, 'name IS NOT NULL'])
            //->where(['company_id !=' =>0])
            ->toArray();

        $yearlist = [];
        for ($p = 0; $p < 6; $p++) {
            $yearlist[$curr - $p] = $curr - $p;
        }
        if ($this->request->is('post')) {
            $key = $this->request->getData('k');
            $this->request->getSession()->write('cost.key', $key);
            $this->request->getSession()->write('cost.year', $year);
        }
        $this->Users->hasMany('Expenses', ['foreignKey' => 'user_id']);
        $this->Users->hasOne('Salaries', ['foreignKey' => 'user_id']);
        // $this->Expense->belongsTo('Users', ['foreignKey' => 'user_id']);
        $uQuery = $this->Users->find(
            'all',
            [
                'contain' => [
                    // "Expense" => [
                    //     'conditions' => [
                    //         'year' => $year
                    //     ]
                    // ],
                    "Expenses" => [
                        'conditions' => [
                            'year' => $year
                        ]
                    ],
                    "Salaries" => [
                        'conditions' => [
                            'amount_type' => 'salary'
                        ]
                    ],
                ]
            ]
        )
            ->where(
                [
                    'status' => 1,
                    'role' => 3
                ]
            );
        $expenselist = $this->paginate($uQuery);
        //pr($expenselist);die;
        $this->set(compact('userlist', 'yearlist', 'expenselist', 'key', 'year'));
    }

    public function clear()
    {
        $this->request->getSession()->delete('cost');
        $this->redirect($this->referer());
    }

    public function employeeAttendance()
    {
        $employee = $this->Users->find()
            ->select(['id', 'name'])
            ->where(['role' => 3, 'status' => 1, 'deleted' => 1])->toArray();


        $dayList = [];
        $lastMonth = (int) date("m", strtotime("-1 month"));
        $year = (int) date("Y");
        if ($this->request->getQuery('month'))
            $lastMonth = $this->request->getQuery('month');
        if ($this->request->getQuery('year'))
            $year = $this->request->getQuery('year');
        if ($this->request->getQuery('month') && $this->request->getQuery('year')) {
            $lastMonth = $this->request->getQuery('month');
            $year = $this->request->getQuery('year');
        }

        if ($this->request->getQuery('employee-attendance')) {
            $empName = $this->request->getQuery('employee-attendance');
            $start_date = "01-" . $lastMonth . "-" . $year;
            $start_time = strtotime($start_date);
            $end_time = strtotime("1 month", $start_time);

            for ($i = $start_time; $i < $end_time; $i += 86400) {
                if (date('D', $i) == 'Sun' || date('D', $i) == 'Sat')
                    continue;
                $dayList[] = date('Y-m-d', $i);
            }
            $empId = $this->Users->find()->select(['id'])->where(['name' => $empName])->first()->id;

            $this->set(compact('dayList', 'empName', 'empId'));
        }
        // echo '<pre>';
        // print_r($empIssueId);
        // die;
        $today=date('Y-m-d');
        $todayattendence = $this->EmployeePunchTime->find('All')
            ->where(['dom' => $today])
            ->toArray();
        $totalemp=count($todayattendence); 
        // dd($totalemp);   

        $this->set(compact('employee', 'lastMonth', 'year','totalemp'));
    }

    // Change Issue Type
    public function changeIssueType()
    {
        if ($this->request->is('GET')) {
            $empName = $this->request->getQuery('empName');
            $issueVal =  $this->request->getQuery('issueVal');
            $date = $this->request->getQuery('issueDate');
            // echo $empName;
            // echo $issueVal;
            // echo var_dump($date);
            // die;
            $issueUpdate = $this->EmployeePunchTime->query()->update()
                ->set(['issue_type' => $issueVal])
                ->where(['emp' => $empName, 'dom' => date('Y-m-d', strtotime($date))]);
            if ($issueUpdate->execute()) {
                echo 1;
                die;
            }
            echo 0;
            die;
        }
    }
}
