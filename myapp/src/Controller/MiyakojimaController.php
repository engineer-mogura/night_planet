<?php
namespace App\Controller;

use Token\Util\Token;
use Cake\Mailer\MailerAwareTrait;
use Cake\Error\Debugger;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

/**
* Users Controller
*
* @property \App\Model\Table\UsersTable $Users
*
* @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class MiyakojimaController extends \App\Controller\AreaController
{
    use MailerAwareTrait;

    public function index()
    {
        if ($this->request->is('ajax')) {
            $this->render();
        }
        // エリアタイトルをセット
        $title .=  AREA[$this->request->url]['label'];
        $isArea = true;
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('isArea', 'title', 'selectList'));
        $this->render();
    }

    public function genre($genre)
    {
        if ($this->request->is('ajax')) {
            $this->render();
        }
        // エリアタイトルをセット
        $title .=  AREA[$this->request->url]['label'];
        $isArea = true;
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('isArea', 'title', 'selectList'));
        $this->render();
    }

    /**
     * json返却用の設定
     *
     * @param array $validate
     * @return void
     */
    public function confReturnJson()
    {
        $this->viewBuilder()->autoLayout(false);
        $this->autoRender = false;
        $this->response->charset('UTF-8');
        $this->response->type('json');
    }

}
