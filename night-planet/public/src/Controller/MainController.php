<?php
namespace App\Controller;

use Token\Util\Token;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
* Users Controller
*
* @property \App\Model\Table\UsersTable $Users
*
* @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class MainController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('users');
        $this->Shops = TableRegistry::get('shops');
        $this->Coupons = TableRegistry::get('coupons');
        $this->Casts = TableRegistry::get('casts');
        $this->Jobs = TableRegistry::get('jobs');
        $this->Shop_infos = TableRegistry::get('shop_infos');
        $this->Diarys = TableRegistry::get('diarys');
        $this->MasterCodes = TableRegistry::get("master_codes");
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('userDefault');

        // SEO対策
        $title = str_replace("_service_name_", LT['000'], TITLE['TOP_TITLE']);
        $description = str_replace("_service_name_", LT['000'], META['TOP_DESCRIPTION']);
        $this->set(compact("title", "description"));
    }

    public function top()
    {
        //$this->redirect("http://localhost:8080/api-googles");
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $diarys = $this->Util->getNewDiarys(PROPERTY['NEW_INFO_MAX'], null, null);
        $notices = $this->Util->getNewNotices(PROPERTY['NEW_INFO_MAX']);
        $insta_data = $this->Util->getInstagram(null, API['INSTAGRAM_USER_NAME'], API['INSTAGRAM_BUSINESS_ID'], API['INSTAGRAM_GRAPH_API_ACCESS_TOKEN']);
        $this->set(compact('selectList', 'diarys', 'notices', 'insta_data'));
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
