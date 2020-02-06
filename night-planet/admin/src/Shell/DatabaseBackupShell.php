<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Log\Log;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\BatchComponent;

/**
 * DatabaseBackup shell command.
 */
class DatabaseBackupShell extends Shell
{
    public $tasks = ['Mysqldump']; // ← タスクの読み込み
     function initialize()
    {
    // コンポーネントを参照(コンポーネントを利用する場合)
        $this->Batch = new BatchComponent(new ComponentRegistry());
    }
    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        //$this->Batch->execMysqldump();
        $this->Batch->execMysqldump();
        // $this->out($this->OptionParser->help());
        // // タスクの実行
        // $this->Mysqldump->main();
    }
}
