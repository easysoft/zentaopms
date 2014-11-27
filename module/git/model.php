<?php
/**
 * The model file of git module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     git
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class gitModel extends model
{
    /**
     * The git binary client.
     * 
     * @var int   
     * @access public
     */
    public $client;

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
     * The restart file.
     * 
     * @var string
     * @access public
     */
    public $restartFile = '';

    /**
     * The root path of a repo
     * 
     * @var string
     * @access public
     */
    public $repoRoot = '';

    /**
     * Users 
     * 
     * @var array 
     * @access public
     */
    public $users = array();

    /**
     * Construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('action');
    }

    /**
     * Run. 
     * 
     * @access public
     * @return void
     */
    public function run()
    {
        $this->setRepos();
        $this->setLogRoot();
        $this->setRestartFile();

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
            if(empty($logs)) continue;

            $this->printLog('begin parsing logs');
            $latestRevision = $logs[0]->revision;
            foreach($logs as $log)
            {
                $this->printLog("parsing log {$log->revision}");
                if($log->revision == $savedRevision)
                {
                    $this->printLog("{$log->revision} alread parsed, ommit it");
                    continue;
                }

                $this->printLog("comment is\n----------\n" . trim($log->msg) . "\n----------");
                $objects = $this->parseComment($log->msg);
                if($objects)
                {
                    $this->printLog('extract' . 
                        ' story:' . join(' ', $objects['stories']) . 
                        ' task:' . join(' ', $objects['tasks']) . 
                        ' bug:'  . join(',', $objects['bugs']));

                    $this->saveAction2PMS($objects, $log);
                }
                else
                {
                    $this->printLog('no objects found' . "\n");
                }
            }

            $this->saveLastRevision($latestRevision);
            $this->printLog("save revision $latestRevision");
            $this->deleteRestartFile();
            $this->printLog("\n\nrepo $name finished");
        }
    }

    /**
     * Set the log root.
     * 
     * @access public
     * @return void
     */
    public function setLogRoot()
    {
        $this->logRoot = $this->app->getTmpRoot() . 'git/';
        if(!is_dir($this->logRoot)) mkdir($this->logRoot);
    }

    /**
     * Set the restart file.
     * 
     * @access public
     * @return void
     */
    public function setRestartFile()
    {
        $this->restartFile = dirname(__FILE__) . '/restart';
    }

    /**
     * Delete the restart file.
     * 
     * @access public
     * @return void
     */
    public function deleteRestartFile()
    {
        if(is_file($this->restartFile)) unlink($this->restartFile);
    }

    /**
     * Set the repos.
     * 
     * @access public
     * @return void
     */
    public function setRepos()
    {
        if(!$this->config->git->repos) die("You must set one git repo.\n");
        $this->repos = $this->config->git->repos;
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
        $this->setRepoRoot($repo);
    }

    /**
     * Set the git binary client of a repo.
     * 
     * @param  object    $repo 
     * @access public
     * @return void
     */
    public function setClient($repo)
    {
        if($this->config->git->client == '') die("You must set the git client file.\n");
        $this->client = $this->config->git->client;
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
     * set the root path of a repo.
     * 
     * @param  object    $repo 
     * @access public
     * @return void 
     */
    public function setRepoRoot($repo)
    {
        $this->repoRoot = $repo->path;
    }

    /**
     * Get repo logs.
     * 
     * @param  object  $repo 
     * @param  int     $fromRevision 
     * @access public
     * @return array
     */
    public function getRepoLogs($repo, $fromRevision)
    {
        $parsedLogs = array();

        /* The git log command. */
        if($fromRevision)
        {
            $cmd = "cd $this->repoRoot; $this->client log --stat $fromRevision..HEAD --pretty=format:%an*_*%cd*_*%H*_*%s";
        }
        else
        {
            $cmd = "cd $this->repoRoot; $this->client log  --stat --pretty=format:%an*_*%cd*_*%H*_*%s";
        }
        exec($cmd, $list, $return);

        if(!$list and $return) die("Some error occers: \nThe command is $cmd\n");
        if(!$list and !$return) return array();

        /* Process logs. */
        $logs = array();
        $i    = 0;

        foreach($list as $line) 
        {
            if(!$line) 
            {
                $i++;
                continue;
            }
            $logs[$i][] = $line;
        }
        foreach($logs as $log)
        {
            $parsedLogs[] = $this->convertLog($log);
        }
        return $parsedLogs;
    }

    /**
     * Convert log from xml format to object.
     * 
     * @param  object    $log 
     * @access public
     * @return ojbect
     */
    public function convertLog($log)
    {

        list($account, $date, $hash, $comment) = explode('*_*', $log[0]);
        $parsedLog = new stdClass();
        $parsedLog->author    = $account;
        $parsedLog->revision  = $hash;
        $parsedLog->msg       = $comment;
        $parsedLog->date      = date('Y-m-d H:i:s', strtotime($date));
        $parsedLog->files     = array();

        unset($log[0]);
        foreach($log as $change)
        {
            if(strpos($change, '|') === false) continue;
            list($entry, $modify) = explode('|', $change);
            $entry = '/' . trim($entry);
            $parsedLog->files['M'][] = $entry;
        }

        return $parsedLog;
    }

    /**
     * Parse the comment of git, extract object id list from it.
     * 
     * @param  string    $comment 
     * @access public
     * @return array
     */
    public function parseComment($comment)
    {
        $stories = array(); 
        $tasks   = array();
        $bugs    = array();

        // bug|story|task(case insensitive) + some space + #|:|：(Chinese) + id lists(maybe join with space or ,)
        // $comment = "bug # 1,2,3,4 Bug:1 2 3 4 5 story:9999,1234566 story:456,1234566";
        $commonReg = "(?:\s){0,}(?:#|:|：){0,}([0-9, ]{1,})";
        $taskReg  = '/task' .  $commonReg . '/i';
        $storyReg = '/story' . $commonReg . '/i';
        $bugReg   = '/bug'   . $commonReg . '/i';

        if(preg_match_all($storyReg, $comment, $result)) $stories = join(' ', $result[1]);
        if(preg_match_all($taskReg, $comment, $result))  $tasks   = join(' ', $result[1]);
        if(preg_match_all($bugReg, $comment, $result))   $bugs    = join(' ', $result[1]);

        if($stories) $stories = array_unique(explode(' ', str_replace(',', ' ', $stories)));
        if($tasks)   $tasks   = array_unique(explode(' ', str_replace(',', ' ', $tasks)));
        if($bugs)    $bugs    = array_unique(explode(' ', str_replace(',', ' ', $bugs)));

        if(!$stories and !$tasks and !$bugs) return array();
        return array('stories' => $stories, 'tasks' => $tasks, 'bugs' => $bugs);
    }

    /**
     * Convert the comment to uft-8.
     * 
     * @param  string    $comment 
     * @access public
     * @return string
     */
    public function iconvComment($comment)
    {
        /* Get encodings. */
        $encodings = str_replace(' ', '', trim($comment));
        if($encodings == '') return $comment;
        $encodings = explode(',', $encodings);

        /* Try convert. */
        foreach($encodings as $encoding)
        {
            $result = @iconv($encoding, 'utf-8', $comment);
            if($result) return $result;
        }

        return $comment;
    }

    /**
     * Diff a url.
     * 
     * @param  string $path
     * @param  int    $revision 
     * @access public
     * @return string|bool
     */
    public function diff($path, $revision)
    {
        $repo = $this->getRepoByURL($path);
        if(!$repo) return false;

        $this->setClient($repo);
        putenv('LC_CTYPE=en_US.UTF-8');

        $path = str_replace('%2F', '/', urlencode($path));
        $path = str_replace('%3A', ':', $path);

        $cmd = "cd $repo->path;$this->client diff $revision^ $revision $path";
        $diff = `$cmd`;
        return $diff;
    }

    /**
     * Cat a url.
     * 
     * @param  string $path
     * @param  int    $revision 
     * @access public
     * @return string|bool
     */
    public function cat($path, $revision)
    {
        $repo = $this->getRepoByURL($path);
        if(!$repo) return false;

        $this->setClient($repo);

        putenv('LC_CTYPE=en_US.UTF-8');

        $path = str_replace('%2F', '/', urlencode($path));
        $path = str_replace('%3A', ':', $path);

        $subPath = substr($path, strlen($repo->path) + 1);
        $cmd  = "cd $repo->path;$this->client show $revision:$subPath";
        $code = `$cmd`;
        return $code;
    }

    /**
     * Get repo by url.
     * 
     * @param  string    $url 
     * @access public
     * @return object|bool
     */
    public function getRepoByURL($url)
    {
        foreach($this->config->git->repos as $repo)
        {
            if(empty($repo['path'])) continue;
            if(strpos($url, $repo['path']) !== false) return (object)$repo;
        }
        return false;
    }

    /**
     * Save action to pms.
     * 
     * @param  array    $objects 
     * @param  object   $log 
     * @param  string   $repoRoot 
     * @access public
     * @return void
     */
    public function saveAction2PMS($objects, $log, $repoRoot = '')
    {
        $action = new stdclass();
        $action->actor   = $log->author;
        $action->action  = 'gitcommited';
        $action->date    = $log->date;
        $action->comment = $this->iconvComment($log->msg);
        $action->extra   = substr($log->revision, 0, 10);

        $changes = $this->createActionChanges($log, $repoRoot);

        if($objects['stories'])
        {
            $products = $this->getStoryProducts($objects['stories']);
            foreach($objects['stories'] as $storyID)
            {
                $storyID = (int)$storyID;
                if(!isset($products[$storyID])) continue;

                $action->objectType = 'story';
                $action->objectID   = $storyID;
                $action->product    = $products[$storyID];
                $action->project    = 0;

                $this->saveRecord($action, $changes);
            }
        }

        if($objects['tasks'])
        {
            $productsAndProjects = $this->getTaskProductsAndProjects($objects['tasks']);
            foreach($objects['tasks'] as $taskID)
            {
                $taskID = (int)$taskID;
                if(!isset($productsAndProjects[$taskID])) continue;

                $action->objectType = 'task';
                $action->objectID   = $taskID;
                $action->product    = $productsAndProjects[$taskID]['product'];
                $action->project    = $productsAndProjects[$taskID]['project'];

                $this->saveRecord($action, $changes);
            }
        }

        if($objects['bugs'])
        {
            $productsAndProjects = $this->getBugProductsAndProjects($objects['bugs']);

            foreach($objects['bugs'] as $bugID)
            {
                $bugID = (int)$bugID;
                if(!isset($productsAndProjects[$bugID])) continue;

                $action->objectType = 'bug';
                $action->objectID   = $bugID;
                $action->product    = $productsAndProjects[$bugID]->product;
                $action->project    = $productsAndProjects[$bugID]->project;

                $this->saveRecord($action, $changes);
            }
        }
    }

    /**
     * Save an action to pms.
     * 
     * @param  object $action
     * @param  object $log
     * @access public
     * @return bool
     */
    public function saveRecord($action, $changes)
    {
        $record = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($action->objectType)
            ->andWhere('objectID')->eq($action->objectID)
            ->andWhere('extra')->eq($action->extra)
            ->andWhere('action')->eq('gitcommited')
            ->fetch();
        if($record)
        {
            $this->dao->update(TABLE_ACTION)->data($action)->where('id')->eq($record->id)->exec();
            if($changes)
            {
                $historyID = $this->dao->findByAction($record->id)->from(TABLE_HISTORY)->fetch('id');
                $this->dao->update(TABLE_HISTORY)->data($changes)->where('id')->eq($historyID)->exec();
            }
        }
        else
        {
            $this->dao->insert(TABLE_ACTION)->data($action)->autoCheck()->exec();
            if($changes)
            {
                $actionID = $this->dao->lastInsertID();
                $this->action->logHistory($actionID, array($changes));
            }
        }
    }

    /**
     * Create changes for action from a log.
     * 
     * @param  object    $log 
     * @param  string    $repoRoot 
     * @access public
     * @return array
     */
    public function createActionChanges($log, $repoRoot)
    {
        if(!$log->files) return array();
        $diff = '';

        $oldSelf = $this->server->PHP_SELF;
        $this->server->set('PHP_SELF', $this->config->webRoot);

        if(!$repoRoot) $repoRoot = $this->repoRoot;

        foreach($log->files as $action => $actionFiles)
        {
            foreach($actionFiles as $file)
            {
                $param = array('url' => helper::safe64Encode($repoRoot . $file), 'revision' => $log->revision);
                $catLink  = trim(html::a(helper::createLink('git', 'cat',  $param, 'html'), 'view', '', "class='repolink'"));
                $diffLink = trim(html::a(helper::createLink('git', 'diff', $param, 'html'), 'diff', '', "class='repolink'"));
                $diff .= $action . " " . $file . " $catLink ";
                $diff .= $action == 'M' ? "$diffLink\n" : "\n" ;
            }
        }
        $changes->field = 'git';
        $changes->old   = '';
        $changes->new   = '';
        $changes->diff  = trim($diff);

        $this->server->set('PHP_SELF', $oldSelf);
        return (array)$changes;
    }

    /**
     * Get products of stories.
     * 
     * @param  array    $stories 
     * @access public
     * @return array
     */
    public function getStoryProducts($stories)
    {
        return $this->dao->select('id, product')->from(TABLE_STORY)->where('id')->in($stories)->fetchPairs();
    }

    /**
     * Get products and projects of tasks.
     * 
     * @param  array    $tasks 
     * @access public
     * @return array
     */
    public function getTaskProductsAndProjects($tasks)
    {
        $records = array();
        $products = $this->dao->select('t1.id, t2.product')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.id')->in($tasks)->fetchPairs();

        $projects = $this->dao->select('id, project')->from(TABLE_TASK)->where('id')->in($tasks)->fetchPairs();

        foreach($projects as $taskID => $projectID)
        {
            $record = array();
            $record['project'] = $projectID;
            $record['product'] = isset($products[$taskID]) ? $products[$taskID] : 0;
            $records[$taskID] = $record;
        }
        return $records;
    }

    /**
     * Get products and projects of bugs.
     * 
     * @param  array    $bugs 
     * @access public
     * @return array
     */
    public function getBugProductsAndProjects($bugs)
    {
        return $this->dao->select('id, project, product')->from(TABLE_BUG)->where('id')->in($bugs)->fetchAll('id');
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
        if(file_exists($this->restartFile)) return 0;
        return trim(file_get_contents($this->logFile));
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
        echo helper::now() . " $log\n";
    }
}
