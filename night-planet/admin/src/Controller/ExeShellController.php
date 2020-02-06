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

    public function beforeFilter(Event $event)
    {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);
        // App::uses('AppShell','Console/Command');
        // App::uses('WatashiShell','Console/Command');
    }

    public function databasebackup()
    {
      // App::uses('AppShell', 'Console/Command');
      // App::uses('DatabaseBackupShell', 'Console/Command');
      //var_dump(phpinfo());
      // $shell = new \App\Shell\MysqldumpTask();
      // $shell->taskNames = ['Mysqldump'];
      // $shell->OptionParser = $shell->getOptionParser();
      // $shell->startup();
      // $shell->main();
      // exec('php /var/www/html/night-planet/admin/bin/cake.php DatabaseBackup');
      //exec('php ' . ROOT . DS .'bin/cake databasebackup ' . TMP . ' > /dev/null &');
      exec('php ' . ROOT . DS .'bin/cake.php DatabaseBackup');
      //$myShell = new \App\Shell\DatabaseBackupShell;
      // $myShell = new \App\Shell\Task\MysqldumpTask;
      // //$myShell->OptionParser = $myShell->getOptionParser();
      // $myShell->startup();
      //$myShell->main();
      // $this->render('/ExeShell/result');

      // $shell = new \App\Shell\DatabaseBackupShell;
      // $shell->getOptionParser();
      // $shell->main();
    }

    public function saveNewPhotosRank()
    {

      exec('php ' . ROOT . DS .'bin/cake.php SaveNewPhotosRank', $output, $result);
      //exec("php >>> \Cake\Log\Log::config('queriesLog', ['className' => 'File', 'path' => LOGS, 'levels' => ['notice', 'info', 'debug'], 'file' => 'sqllog'])", $output, $result);
      $this->log(__LINE__ . '::' . __METHOD__ . '::' . $output . "結果コード:" . $result, 'error');
      // ダミー画面を表示する
      // シンプルレイアウトを使用
      $this->viewBuilder()->layout('simpleDefault');
      return $this->render("/exeshell/result");
    }
    public function index()
    {
      var_dump(phpinfo());
    }
}
