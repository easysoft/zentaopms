<?php
/**
 * The syncer of svn.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     svn
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include './config.php';
include './api.class.php';
$syncer = new syncer($config);
$syncer->run();

class syncer
{
    /**
     * The svn binary svnClient.
     * 
     * @var string   
     * @access public
     */
    public $svnClient;

    /**
     * The zentao client.
     * 
     * @var string   
     * @access public
     */
    public $zentaoClient;

    /**
     * Repos.
     * 
     * @var array 
     * @access public
     */
    public $repos = array(); 

    /**
     * The log root.
     * 
     * @var string
     * @access public
     */
    public $logRoot = '';

    /**
     * The construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct($config)
    {
        $this->setConfig($config);
        $this->setRepos();
        $this->setLogRoot();
        $this->loginZentao();
    }

    /**
     * Set config.
     * 
     * @param  object    $config 
     * @access public
     * @return void
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Set the repos.
     * 
     * @access public
     * @return void
     */
    public function setRepos()
    {
        if(!$this->config->svn->repos) die("You must set one svn repo.\n");
        $this->repos = $this->config->svn->repos;
    }

    /**
     * Set the log root.
     * 
     * @access public
     * @return void
     */
    public function setLogRoot()
    {
        $this->logRoot = './log/';
        if(!is_dir($this->logRoot)) mkdir($this->logRoot);
    }

    /**
     * Login to zentao.
     * 
     * @access public
     * @return void
     */
    public function loginZentao()
    {
        $zentaoConfig = $this->config->zentao;
        $this->zentaoClient = new ztclient($zentaoConfig->path, $zentaoConfig->user, $zentaoConfig->password);
    }

    /**
     * Run. 
     * 
     * @access public
     * @return void
     */
    public function run()
    {
        foreach($this->repos as $name => $repo)
        {
            $this->printLog("begin repo $name");
            $repo = (object)$repo;
            $repo->name = $name;
            $this->setRepo($repo);

            $savedRevision = $this->getSavedRevision();
            $this->printLog("start from revision $savedRevision");
            $logs = $this->getRepoLogs($repo, $savedRevision);
            $this->printLog("get " . count($logs) . " logs");

            $this->printLog('begin posting logs');
            print_r($this->zentaoClient->post('svn', 'apiSync', array('logs' => $logs)));
        }
    }

    /**
     * Set repo.
     * 
     * @param  object    $repo 
     * @access public
     * @return void
     */
    public function setRepo($repo)
    {
        $this->setClient($repo);
        $this->setLogFile($repo->name);
    }

    /**
     * Set the svn binary svnClient of a repo.
     * 
     * @param  object    $repo 
     * @access public
     * @return void
     */
    public function setClient($repo)
    {
        if($this->config->svn->client == '') die("You must set the svn svnClient file.\n");
        $this->svnClient = $this->config->svn->client . " --non-interactive";
        if(isset($repo->username)) $this->svnClient .= " --username '$repo->username' --password '$repo->password' --no-auth-cache";
    }

    /**
     * Set the log file of a repo.
     * 
     * @param  string    $repoName 
     * @access public
     * @return void
     */
    public function setLogFile($repoName)
    {
        $this->logFile = $this->logRoot . $repoName;
    }

    /**
     * Get repo logs.
     * 
     * @param  object  $repo 
     * @param  int     $fromRevision 
     * @access public
     * @return string
     */
    public function getRepoLogs($repo, $fromRevision)
    {
        $parsedLogs = array();

        /* The svn log command. */
        $cmd  = $this->svnClient . " log -r $fromRevision:HEAD -v --xml $repo->path";
        $logs = `$cmd`;

        return $logs;
    }

    /**
     * Get the saved revision.
     * 
     * @access public
     * @return int
     */
    public function getSavedRevision()
    {
        if(!file_exists($this->logFile)) return 0;
        return (int)trim(file_get_contents($this->logFile));
    }

    /**
     * Save the last revision.
     * 
     * @param  int    $revision 
     * @access public
     * @return void
     */
    public function saveLastRevision($revision)
    {
        file_put_contents($this->logFile, $revision);
    }

    /**
     * Pring log.
     * 
     * @param  sting    $log 
     * @access public
     * @return void
     */
    public function printLog($log)
    {
        echo date('Y-m-d H:i:s') . " $log\n";
    }
}
