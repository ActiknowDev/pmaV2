<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Mailer\Mailer;
use Cake\Core\Configure;
use Cake\Http\Session;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization', [
            'skipAuthorization' => ['login']
        ]);

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }    

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->addUnauthenticatedActions(['sendemailtohr', 'upworkRefreshToken', 'contracts', 'engagementList', 'milestone']);
        $this->checkValidUser();
    }

    public $restrict_array = [

        "users-index",
        "reports-revenues",
        "clients-index",
        "employeedetails-edit",
        "companies-edit_project",
        "assetassignedentries-index",
        "assetdatas-index",
        "assetcategories-index"
    ];


    public function checkValidUser()
    {

        $current_controller = strtolower($this->request->getParam('controller'));

        /* Current View */
        $current_action = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->request->getParam('action')));

        $current_controller_action = $current_controller . '-' . $current_action;

        // echo $current_controller_action;
        // die;

        if (in_array($current_controller_action, $this->restrict_array)) {

            $session = new Session();
            $userSession = $session->read('data');

            if ( isset($userSession["role_name"]) && $userSession["role_name"] == "user") {

                $this->redirect(["controller" => "Users", "action" => "login"]);
            }
        }
    }

    public function calculateLeaves($userId = 168)
    {
        $this->loadModel('Users');
        $this->loadModel('Leaves');
        $this->Leaves->findAllByUserId($userId);
    }

    public function setFrom($from)
    {

        $this->fromMail = $from;
    }

    public function SendMail($to, $message)
    {

        $mailer = new Mailer();
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setTo($to)
            ->setSubject('PMA Test')
            ->viewBuilder()
            ->setTemplate('send');
        $mailer->setViewVars(['message' => $message]);

        $mailer->deliver();
    }


    public function SendWelcomeMail($to)
    {

        $mailer = new Mailer();
        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setTo($to)
            ->setSubject('Welcome')
            ->viewBuilder()
            ->setTemplate('welcome');
        $mailer->setViewVars(['name' => $to]);
        $mailer->deliver();
    }

    public function sendTaskNotification($to, $assign_by)
    {
        // echo "i ran";
        // echo $to;
        // echo $assign_by;
        // die;

        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setTo($to)
            ->setSubject('Assigned Task')
            ->viewBuilder()
            ->setTemplate('task');
        $mailer->setViewVars(['name' => $assign_by]);
        $mailer->deliver();
    }

    public function sendApplyLeaveNotic($reportingManagerEmial, $leaveType, $subject, $applyBy, $from_date, $to_date, $email = null,$reason=null,$cut_leave=null)
    {
        // dd($reason);
        $mailer = new Mailer();

        $emailCc = [];

        foreach ($email as $value) {
            $emailCc[] = $value['email'];
        }

        // print_r($emailCc);
        // die;

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setTo($reportingManagerEmial)
            // ->setTo("sanjay.kumar@actiknow.com")
            ->setCc($emailCc)
            ->setSubject($subject . ' Request - ' . $applyBy . ' - ' . $from_date . ' to ' . $to_date)
            ->viewBuilder()
            ->setTemplate('leave');

        $mailer->setViewVars(['name' => $applyBy, 'leaveType' => $leaveType, 'from_date' => $from_date, 'to_date' => $to_date,'reason'=>$reason,'cut_leave'=>$cut_leave]);
        $mailer->deliver();
    }

    public function approveLeaveNotic($empEmail, $status, $managerName, $from_date, $to_date)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            ->setEmailFormat('html')
            ->setTo($empEmail)
            // ->setTo("sanjay.kumar@actiknow.com")
            ->setSubject($status)
            ->viewBuilder()
            ->setTemplate('approveleave');

        $mailer->setViewVars(['status' => $status, 'managerName' => $managerName, 'from_date' => $from_date, 'to_date' => $to_date]);
        $mailer->deliver();
    }

    public function invoiceMailPDF($filePath)
    {
        $mailer = new Mailer();

        $mailer->setTransport('default');
        $mailer
            // ->setEmailFormat('html')
            // ->setTo($empEmail)
            ->setTo('sanjay.kumar@actiknow.com')
            ->setSubject("Invoice PDF")
            ->viewBuilder();
        // $mailer->setAttachments([ROOT . DS . "webroot" . DS . "image" . DS . "E-Way Bill System.pdf"]);
        $mailer->setAttachments([$filePath]);
        // $mailer->setViewVars(['status' => $status, 'managerName' => $managerName, 'from_date' => $from_date, 'to_date' => $to_date]);
        $mailer->deliver();
    }

    public function imageValidation($file)
    {
        $validData = [];
        $errData = [];
        for ($i = 0; $i < count($file); $i++) {
            $fileName = $file[$i]->getClientFilename();
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowedExtension =  array('jpeg', 'jpg', "png", "JPEG", "JPG", "PNG", "pdf", "PDF","xlsx","xls","docs","doc","csv");
            if (in_array($extension, $allowedExtension)) {
                $validData[] = true;
            } else {
                $errData[] = false;
            }
        }
        if (count($file) == count($validData)) {
            return true;
        } else {
            return false;
        }
    }

    // Client Notification mail for bug fixing portal 
    public function clientNotification($email,$client_name,$password)
    {
        $mailer = new Mailer();
        $mailer->setTransport('default');
        $mailer->setEmailFormat('html')
            ->setTo($email)
            // ->setTo("kajal.bharti@actiknow.com")
            ->setCc("devendra.singh@actiknow.com")
            ->setCc("twinkle.gupta@actiknowbi.com")
            ->setSubject("Welcome to Actiknow's Bug Reporting Platform")
            ->viewBuilder()
            ->setTemplate('clientnotification');
        $mailer->setViewVars(['name' => $client_name,'email' => $email,'password' => $password]);
        $mailer->deliver();
    }
    // End

    // sendResponsetoManager Start 
    public function sendResponsetoManager($client_name,$manager_name,$client_email,$title)
    {
        $mailer = new Mailer();
        $subject = "New Response Received on a Ticket ". $title." by ".$manager_name;
        $mailer->setTransport('default');
        $mailer->setEmailFormat('html')
         // ->setTo($email)
        ->setTo("kajal.bharti@actiknow.com")
        ->setSubject($subject)
        ->viewBuilder()
        ->setTemplate('chaticket');
        $mailer->setViewVars(['client_name'=> $client_name, 'manager_name'=> $manager_name, 'title' => $title]);
        $mailer->deliver();
    }
    // End 

    // check user and validation for routing
    public function routeValidation($roleName,$role)
    {
        if (array_intersect($roleName, $role)) {
			return true;
		} else {
            http_response_code(403); // Set the HTTP status code to 403 (Forbidden)
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Access Denied</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f9fa;
                    text-align: center;
                    padding: 100px;
                }
                .error-container {
                    display: inline-block;
                    padding: 40px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #3fd5db;
                    font-size: 48px;
                    margin-bottom: 10px;
                }
                p {
                    font-size: 18px;
                    color: #333;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h1>403 Forbidden</h1>
                <p>You are not authorized to access this page.</p>
            </div>
        </body>
        </html>
        ';
        exit();
        }

    }
}
