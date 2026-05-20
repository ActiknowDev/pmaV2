<?php

namespace App\Controller;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Security;
use Cake\Database\Expression\QueryExpression;

class TicketsController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();

		// $this->loadComponent('Paginator');
		$this->loadComponent('Flash');
		// $this->loadComponent('RequestHandler');
        $this->userTbl = $this->fetchTable('Users');
        $this->projectTbl = $this->fetchTable('Projects');
        $this->ticketTbl = $this->fetchTable('Tickets');
        $this->commentTbl = $this->fetchTable('CommentNotes');
        $this->planTbl = $this->fetchTable('Plans');
        $this->TicketDocumentsTbl = $this->fetchTable('TicketDocuments');
	}

	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		$this->Authentication->addUnauthenticatedActions(['login', 'signup']);
	}

	public function index()
	{
		//set layout
		$this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
        // validation for valid user
        $roleArray = $userSession['role_name'];
        $validList = [4, 6, 9, 10, 12];
        $this->routeValidation($roleArray,$validList);

		// Ticket Status
		$ticket_status = [1,2,3];

        // case not handled if user is deleted - do it later
		$ticket =  $this->ticketTbl->find()
        ->select([
            'id' => 'Tickets.id',
            'title' => 'Tickets.title',
            'description' => 'Tickets.description',
            'ticket_type' => 'Tickets.ticket_type',
            'status' => 'Tickets.status',
            'created_at' => 'DATE_FORMAT(Tickets.created_at, "%h:%i:%s %m/%d/%Y")',
            'project_name' => 'Projects.project_name',
            'project_id' => 'Tickets.project_id',
			'manager_id' => 'Projects.project_manager_id',
            'client_id' => 'Projects.client_id',
            'client_name' => 'Users.client_name'
        ])
        ->join([
            'Projects' => [
                'table' => 'projects',
                'type' => 'LEFT',
                'conditions' => 'Tickets.project_id = Projects.id',
            ],
            'Users' => [
                'table' => 'users',
                'type' => 'LEFT',
                'conditions' => 'Tickets.client_id = Users.id',
            ]
        ])
        ->where(['Tickets.delete_tickets' => 0])
        // ->where(['Projects.project_manager_id' => $userSession['id'], 'Tickets.delete_tickets' => 0])
        ->order(['Tickets.id' => 'DESC'])
        ->toArray();

        $clientList = $this->userTbl->find()
                      ->select([
                                'client_name' => 'Users.client_name',
                                'id' => 'Users.id',
                                'project_id' => 'Projects.id'
                                ])
                      ->join([
                        'Projects' => [
                            'table' => 'projects',
                            'type' => 'INNER',
                            'conditions' => 'Users.id = Projects.client_id',
                        ],
                        'SupportPlan' => [
                            'table' => 'support_plans',
                            'type' => 'INNER',
                            'conditions' => 'SupportPlan.client_id = Users.id'
                        ]
                        ])
                        ->where(['Projects.deleted' => 1, 'Users.deleted' => 1,'SupportPlan.status' => 1, 'SupportPlan.deleted' => 0])
                    //   ->where(['Projects.project_manager_id' => $userSession['id'], 'Projects.deleted' => 1, 'Users.deleted' => 1,'SupportPlan.status' => 1, 'SupportPlan.deleted' => 0])
                    //   ->group(['Users.id'])
                         ->group(['Users.id', 'Users.client_name', 'Projects.id'])
                      ->order(["name" => "ASC"])
                      ->toArray();
                      
		$ticket1 = $ticket2 = $ticket3 = 0;
		if(count($ticket)>0)
		{
			foreach($ticket as $t)
			{
				if($t->status==3)
				$ticket1++;
				if($t->status==1)
				$ticket2++;
				if($t->status==2)
				$ticket3++;
			}
		}

		$this->set(compact('userSession', 'ticket', 'ticket_status', 'ticket1', 'ticket2', 'ticket3', 'clientList'));
	}

	public function kanbanModal()
    {
        if ($this->request->is('get')) {

			$this->Authorization->skipAuthorization();
			$this->paginate();
			$session = new \Cake\Http\Session();
			$userSession = $session->read('data');
            $id = $this->request->getQuery('id');
            $client_id = $this->request->getQuery('clientId');

            $ticketData = $this->ticketTbl->find()
                        ->select([
                            'id' => 'Tickets.id',
                            'title' => 'Tickets.title',
                            'status' => 'Tickets.status',
                            'project_name' => 'Projects.project_name',
                            'description' => 'Tickets.description',
                            'ticket_type' => 'Tickets.ticket_type',
                            'client_name' => 'Client.client_name',
                            'manager_name' => 'Manager.name',
                            'role_id' => 'Manager.role', 
                            'client_id' => 'Tickets.client_id',
                            'created' => 'DATE_FORMAT(DATE_SUB(Tickets.created_at, INTERVAL 1 HOUR), "%m-%d-%Y %H:%i:%s")'
                        ])
                        ->join([
                            'Manager' => [ 
                                'table' => 'users',
                                'type' => 'INNER',
                                'conditions' => 'Manager.id = Tickets.added_by'
                            ],
                            'Client' => [
                                'table' => 'users',
                                'type' => 'INNER',
                                'conditions' => 'Client.id = Tickets.client_id'
                            ],
                            'Projects' => [
                                'table' => 'projects',
                                'type' => 'INNER',
                                'conditions' => 'Projects.id = Tickets.project_id'
                            ]
                        ])
                        ->where(['Tickets.id' => $id,'Tickets.delete_tickets' => 0])
                        // ->group(['Tickets.id'])
                        ->group([
                            'Tickets.id',
                            'Tickets.title',
                            'Tickets.status',
                            'Projects.project_name',
                            'Tickets.description',
                            'Tickets.ticket_type',
                            'Client.client_name',
                            'Manager.name',
                            'Manager.role',
                            'Tickets.client_id',
                            'Tickets.created_at'
                        ])
                        ->toArray();

            $ticketAttachment = $this->TicketDocumentsTbl->find()
                                ->select(['id','doc_type','document','added_by'])
                                ->where(['ticket_id' => $id])->toArray();

            // echo "<pre>";
            // print_r($ticketAttachment);
            // die();

            $commentData = $this->commentTbl->find()->select([
                'id' => 'CommentNotes.id',
                'comment_notes' => 'CommentNotes.comment_notes',
                'cmt_time' => 'DATE_FORMAT(CommentNotes.created_at,"%m-%d-%Y %H:%i:%s")',
                'image' => 'Client.user_image',
                'client_name' => 'Client.client_name'
            ])
            ->join([
                'Client' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'Client.id = CommentNotes.user_id'
                ]
            ])->where(['ticket_id' => $id])->toArray();
            
            $this->set(compact('ticketData', 'commentData', 'userSession', 'client_id', 'ticketAttachment'));
        }
      
    }

    public function addShowComment()
    {
        $this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		$this->paginate();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');

        if ($this->request->is('post')) {
            $status = $this->commentTbl->query()->update()
            ->set(['seen' => 1])->where(['ticket_id' => $this->request->getData('ticketId'),'user_id !='=>$userSession['id']]);
            $status->execute();

            $comment = $this->commentTbl->newEmptyEntity();
            $commentMsg = preg_replace('/\s+/', ' ', $this->request->getData('commentMessage'));
            $comment->ticket_id = $this->request->getData('ticketId');
            $comment->user_id = $this->request->getData('userId');
            $comment->comment_notes = $commentMsg;
            $comment->type = 1;
   
            if ($this->commentTbl->save($comment)) {

                $emailData = $this->ticketTbl->find()->select(['title'=>'Tickets.title','client_name'=>'Users.client_name','manager_name'=>'Manager.name','client_email'=>'Users.email'])->join([
                    'Users' => [
                        'table' => 'users',
                        'type' => 'INNER',
                        'conditions' => 'Users.id = Tickets.client_id'
                    ],
                    'Manager' => [
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'Tickets.manager_id = Manager.id'
                    ]
                ])->where(['Tickets.id'=>$this->request->getData('ticketId')])->first();
               
                $client_name    = $emailData->client_name;
                $manager_name   = $emailData->manager_name;
                $client_email  = $emailData->client_email;
                $title          = $emailData->title;
                $this->sendResponsetoManager($client_name,$manager_name,$client_email,$title);

                echo json_encode($comment);
                die;
            }
        }
    }

    public function ticketStatus()
    {
        $this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		$this->paginate();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');

        if ($this->request->is('get')) {
            $ticketId = $this->request->getQuery('ticketId');
            $statusValue = $this->request->getQuery('status');
            $ticketData = $this->ticketTbl->find()->where(['id' => $ticketId])->first();
            // $status = $this->ticketTbl->query()->update()
            //         ->set(['status'=>$statusValue])->where(['id' => $ticketId]);
            $status = $this->ticketTbl->updateQuery()
                ->set(['status' => $statusValue])
                ->where(['id' => $ticketId])
                ->execute();
            if ($status->execute()) { 
                echo 1;
                die;
            }
        }
    }

    public function ticketType()
    {
        $this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');

        if ($this->request->is('get')) {
            $ticketId = $this->request->getQuery('ticketId');
            $statusValue = $this->request->getQuery('type');
            $ticketData = $this->ticketTbl->find()->where(['id' => $ticketId])->first();
            // $status = $this->ticketTbl->query()->update()
            //         ->set(['ticket_type'=>$statusValue])->where(['id' => $ticketId]);
            $status =$this->ticketTbl->updateQuery()
                ->set(['ticket_type' => $statusValue])
                ->where(['id' => $ticketId])
                ->execute();
            if ($status->execute()) { 
                echo 1;
                die;
            }
        }
    }

    public function add()
    {
        $this->viewBuilder()->setLayout('default_new');
		$this->Authorization->skipAuthorization();
		$session = new \Cake\Http\Session();
		$userSession = $session->read('data');
        if ($this->request->is('post')) {
            
            $ticket = $this->ticketTbl->newEmptyEntity();
            $title = $this->request->getData('title');
            $project_id = $this->request->getData('project_id');
            $ticket_type = $this->request->getData('ticket_type');
            $notes = $this->request->getData('notes');
            $status = $this->request->getData('status');
            $client_id = $this->request->getData('client_name');
            $ticket->client_id = $client_id;
            $ticket->title = $title;
            $ticket->description = $notes;
            $ticket->ticket_type = $ticket_type;
            $ticket->project_id = $project_id;
            $ticket->status = $this->request->getData('status');
            $ticket->added_by = $userSession['id'];

            $result = $this->ticketTbl->save($ticket);
            if ($result) {
                // Add Ticket Documents 
                $files = $_FILES['files']['name'];
                if (!empty($files)) {
                    for ($i = 0; $i < count($files); $i++) {
                        $fileName = chr(rand(97, 122)) . rand(10000, 99999) . $_FILES['files']['name'][$i];
                        if (!empty($_FILES['files']['name'][$i])) {
                            $TicketDocuments = $this->TicketDocumentsTbl->newEmptyEntity();
                            $sourcePath = $_FILES['files']['tmp_name'][$i];
                            $targetPath = WWW_ROOT . 'img' . DS . 'tickets_file' . DS . $_FILES['files']['name'][$i];

                            if (move_uploaded_file($sourcePath, $targetPath)) {
                                $ticket->document = $_FILES['files']['name'][$i];
                            }
                            $ext = strtolower(pathinfo($_FILES['files']['name'][$i], PATHINFO_EXTENSION));
                            if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'bmp' || $ext == 'eps') {
                                $TicketDocuments->doc_type = 2;
                            } else {
                                $TicketDocuments->doc_type = 1;
                            }

                            $TicketDocuments->ticket_id = $result->id;
                            $TicketDocuments->added_by = 1;
                            $TicketDocuments->document = $_FILES['files']['name'][$i];
                            $this->TicketDocumentsTbl->save($TicketDocuments);
                        }
                    }
                }

                $this->Flash->success(__('The Ticket has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            else{
                $this->Flash->error(__('Something went wrong'));  
				return $this->redirect(['action' => 'index']);
            }
            // End 
            }
    }

    public function clientProject()
	{
		$client_id = $this->request->getQuery('client_id');
		if(!empty($client_id)){
            // $this->SupportPlan = TableRegistry::get('SupportPlan');
			// $this->Projects = TableRegistry::get('Projects');

            $this->Projects = $this->fetchTable('Projects');
            $this->SupportPlan = $this->fetchTable('SupportPlan');
			$projects = $this->Projects->find('all')
				->select(['id','project_name'])
                ->join([
                    'SupportPlan' => [
                        'table' => 'support_plans',
                        'type' => 'INNER',
                        'conditions' => 'SupportPlan.project_id = Projects.id'
                    ]
                ])
				->where(['Projects.client_id'=>$client_id,'Projects.deleted' => 1, 'SupportPlan.status' => 1])
                // ->group(['Projects.id'])
                ->group(['Projects.id', 'Projects.project_name'])
                ->order(['Projects.project_name'])
				->toArray();
			// disabled enables
			$html = '';
			foreach($projects as $row){
				$html .= '<option value="' . $row['id'] . '">' . $row['project_name'] . '</option>';
			}
			echo json_encode($html);
		}exit;
	}
	
}