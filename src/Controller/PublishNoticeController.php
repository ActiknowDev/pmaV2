<?php

namespace App\Controller;

class PublishNoticeController extends AppController
{
    private $session;
    public function initialize(): void
    {
        parent::initialize();
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('default_new');
        $this->session = $this->request->getSession();
        $this->getTableLocator()->get('PublishNotice');
    }

    public function index()
    {
        $user_id = $this->session->read('user_data')['id'];
        $role_name = explode(",", $this->session->read('user_data')['role_name']);
        $notice_data =  $this->PublishNotice->find()->where(['status' => 1])->order(['id' => 'DESC'])->toArray();
        $this->set(compact('role_name', 'user_id', 'notice_data'));
    }

    public function addNotice()
    {
        $publish_notice_tbl =  $this->PublishNotice->newEmptyEntity();
        $this->PublishNotice->patchEntity($publish_notice_tbl, $this->request->getData());

        if ($this->PublishNotice->save($publish_notice_tbl)) {
            echo 1;
        }
        die;
    }

    // public function updateNotice()
    // {

    //     if ($this->request->is('put')) {
    //         $publish_notice_tbl = $this->PublishNotice->query();
    //         $updateStatus = $publish_notice_tbl->update()
    //             ->set([
    //                 'user_id' => $this->request->getData('user_id'),
    //                 'title' => $this->request->getData('title'),
    //                 'description' => $this->request->getData('description'),
    //             ])
    //             ->where(['id' => $this->request->getData('noticeId')])
    //             ->execute();
    //         if ($updateStatus) {
    //             echo 1;
    //             die;
    //         }
    //     } else {
    //         $notice_id = $this->request->getQuery('id');
    //         $publish_notice_tbl = $this->PublishNotice->findById($notice_id)->toArray();
    //         echo json_encode($publish_notice_tbl);
    //         die;
    //     }
    // }

    public function updateNotice()
{

    if ($this->request->is('put')) {
        $publish_notice_tbl = $this->PublishNotice;

        $updateStatus = $publish_notice_tbl->updateAll(
            [
                'user_id' => $this->request->getData('user_id'),
                'title' => $this->request->getData('title'),
                'description' => $this->request->getData('description'),
            ],
            ['id' => $this->request->getData('noticeId')]
        );

        if ($updateStatus) {
            echo 1;
            die;
        }
    } else {
        $notice_id = $this->request->getQuery('id');
        $publish_notice_tbl = $this->PublishNotice->findById($notice_id)->toArray();
        echo json_encode($publish_notice_tbl);
        die;
    }
}

    // public function deleteNotice()
    // {
    //     $user_id = $this->session->read('user_data')['id'];
    //     $notice_id = $this->request->getQuery('id');
    //     $publish_notice_tbl = $this->PublishNotice->query();
    //     $status = $publish_notice_tbl->update()
    //         ->set(['status' => 0, 'user_id' => $user_id])
    //         ->where(['id' => $notice_id])
    //         ->execute();

    //     if ($status) {
    //         echo 1;
    //     }
    //     die;
    // }

    public function deleteNotice()
    {
        $this->autoRender = false;

        $user_id = $this->request->getSession()->read('user_data.id');
        $notice_id = $this->request->getQuery('id');

        $publish_notice_tbl = $this->fetchTable('PublishNotice');

        $result = $publish_notice_tbl->updateAll(
            [
                'status' => 0,
                'user_id' => $user_id
            ],
            [
                'id' => $notice_id
            ]
        );

        echo $result ? 1 : 0;
        exit;
    }

    public function uploadImage()
    {
        $this->request->allowMethod(['post']);

        if (!empty($_FILES['upload']['name'])) {

            $file = $_FILES['upload'];
            $fileName = time() . '_' . $file['name'];
            $uploadPath = WWW_ROOT . 'uploads/editor_img/';
            $filePath = $uploadPath . $fileName;

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $filePath)) {

                $url = $this->request->getAttribute('webroot') . 'uploads/editor_img/' . $fileName;

                $response = [
                    "uploaded" => 1,
                    "fileName" => $fileName,
                    "url" => $url
                ];
            } else {
                $response = ["uploaded" => 0, "error" => ["message" => "Upload failed"]];
            }
        } else {
            $response = ["uploaded" => 0, "error" => ["message" => "No file"]];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($response));
    }

    public function summernote()
    {
    }
}
