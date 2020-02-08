<?php
namespace App\Controller;

use Cake\Log\Log;
use Cake\Event\Event;
use Token\Util\Token;
use Cake\Collection\Collection;
use Cake\Console\Shell;
use Cake\Console\Command\DatabaseBackupShell;

use Cake\Console\Command\AppShell;

/**
 * Controls the data flow into shops object and updates the view whenever data changes.
 */
class ExeShellController extends AppController
{

    static public $exec_path = ""; // シェル実行パス

    public function beforeFilter(Event $event)
    {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);

        // シェル実行パス判定
        if (strpos(ADMIN_DOMAIN, 'local') === false) {
          // レンタルサーバーのPHPパスを設定する
          ExeShellController::$exec_path = '/usr/local/php/7.1/bin/php ' . ROOT . DS .'bin/cake.php ';
        } else {
          // ローカル仮想サーバーのPHPパスを設定する
          ExeShellController::$exec_path = 'php ' . ROOT . DS .'bin/cake.php ';
        }

    }

    public function databaseBackup()
    {
      Log::info('--------'.__LINE__ . '::' . __METHOD__ . '::処理を開始します。--------', 'batch_snpr');
      exec(ExeShellController::$exec_path .$this->request->param('action') , $output, $result);
      Log::info(__LINE__ . '::' . __METHOD__ . '::' . "アウトプット:".$output . "結果コード:" . $result, 'batch_snpr');
      Log::info('--------'.__LINE__ . '::' . __METHOD__ . '::処理を終了します。--------', 'batch_snpr');
      // ダミー画面を表示する
      // シンプルレイアウトを使用
      $this->viewBuilder()->layout('simpleDefault');
      return $this->render("/exeshell/result");
    }

    /**
     * 新着フォト投稿DB登録処理
     *
     * @return void
     */
    public function saveNewPhotosRank()
    {
      Log::info('--------'.__LINE__ . '::' . __METHOD__ . '::処理を開始します。--------', 'batch_snpr');
      exec(ExeShellController::$exec_path .$this->request->param('action') , $output, $result);
      Log::info(__LINE__ . '::' . __METHOD__ . '::' . "アウトプット:".$output . "結果コード:" . $result, 'batch_snpr');
      Log::info('--------'.__LINE__ . '::' . __METHOD__ . '::処理を終了します。--------', 'batch_snpr');
      // ダミー画面を表示する
      // シンプルレイアウトを使用
      $this->viewBuilder()->layout('simpleDefault');
      return $this->render("/exeshell/result");
    }

}
