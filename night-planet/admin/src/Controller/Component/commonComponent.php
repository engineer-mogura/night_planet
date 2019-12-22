<?php

namespace App\Controller\Component;

use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component;
use Cake\Mailer\MailerAwareTrait;

class commonComponent extends Component
{

    use MailerAwareTrait; // メールクラス
    public $components = ['Util']; // Utilコンポーネント
    /**
     * サービスプラン期間適応外をフリープランに変更する処理
     *
     * @param [type] $id
     * @param [type] $diaryPath
     * @return array
     */
    public function changeServicePlan()
    {

        $result = true; // 正常終了フラグ
        $servece_plans = TableRegistry::get('servece_plans');
        $owners        = TableRegistry::get('owners');
        $plans = $servece_plans->find("all")
                    ->where(['NOW() > to_end', 'to_end !=' => '0000-00-00'])
                    ->contain(['owners'])
                    ->toArray();
 
        $update_entities = array();

        foreach ($plans as $key => $plan) {
            $update_entity = [];
            $update_entity['id'] = $plan->id;
            $update_entity['course'] = 0;
            $update_entity['previous_plan'] = $plan->current_plan;
            $update_entity['current_plan'] = SERVECE_PLAN['free']['label'];
            $update_entity['from_start'] = '0000-00-00';
            $update_entity['to_end'] = '0000-00-00';
            array_push($update_entities, $update_entity);
        }
        $entities = $servece_plans->patchEntities(
                $servece_plans, $update_entities, ['validate' => false]);
        try {
            // レコード更新実行
            // if (!$servece_plans->saveMany($entities)) {
            //     throw new RuntimeException('レコードの更新ができませんでした。');
            // }
            foreach ($plans as $key => $plan) {
                $email = new Email('default');
                $email->setFrom([MAIL['FROM_INFO'] => MAIL['FROM_NAME']])
                    ->setSubject(MAIL['EXPIRED_SERVICE_PLAN'])
                    ->setTo($plan->owner->email)
                    ->setBcc(MAIL['FROM_INFO'])
                    ->setTemplate("expired_service_plan")
                    ->setLayout("simple_layout")
                    ->emailFormat("html")
                    ->viewVars(['plan' => $plan])
                    ->send();
            }

            // Log::info("ID：【".$owner[0]['id']."】アドレス：【".$owner[0]->email
            //     ."】" . RESULT_M['CHANGE_PLAN_SUCCESS'] . ', pass_reset');

        } catch (RuntimeException $e) {
            $result = false; // 異常終了フラグ
            $this->log($this->Util->setLog($auth, $e));
        }
        return $result;
    }

}
