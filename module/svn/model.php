<?php
/**
 * The model file of svn module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     svn
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class svnModel extends model
{
    /**
     * The svn binary client.
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
        if(empty($this->repos)) return false;

        $this->setLogRoot();
        $this->setRestartFile();

        foreach($this->repos as $name => $repo)
        {
            $this->printLog("begin repo $name");
            $repo = (object)$repo;
            $repo->name = $name;
            if(!$this->setRepo($repo)) return false;

            $savedRevision = $this->getSavedRevision();
            $this->printLog("start from revision $savedRevision");
            $logs = $this->getRepoLogs($repo, $savedRevision);
            if(empty($logs)) continue;

            $this->printLog("get " . count($logs) . " logs");
            $this->printLog('begin parsing logs');
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
                        'story:' . join(' ', $objects['stories']) . 
                        ' task:' . join(' ', $objects['tasks']) . 
                        ' bug:'  . join(',', $objects['bugs']));

                    $this->saveAction2PMS($objects, $log);
                }
                else
                {
                    $this->printLog('no objects found' . "\n");
                }
                if($log->revision > $savedRevision) $savedRevision = $log->revision;
            }
            $this->saveLastRevision($savedRevision);
            $this->printLog("save revision $savedRevision");
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
        $this->logRoot = $this->app->getTmpRoot() . 'svn/';
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
     * @return bool
     */
    public function setRepos()
    {
        if(!$this->config->svn->repos)
        {
            echo "You must set one svn repo.\n";
            return false;
        }

        $this->repos = $this->config->svn->repos;
        return true;
    }

    /**
     * Get repos.
     * 
     * @access public
     * @return array
     */
    public function getRepos()
    {
        $repos = array();
        if(!$this->config->svn->repos) return $repos;

        foreach($this->config->svn->repos as $repo)
        {
            if(empty($repo['path'])) continue;
            $repos[] = $repo['path'];
        }
        return $repos;
    }

    /**
     * Set repo.
     * 
     * @param  object    $repo 
     * @access public
     * @return bool
     */
    public function setRepo($repo)
    {
        $this->setClient($repo);
        if(empty($this->client)) return false;

        $this->setLogFile($repo->name);
        $this->setRepoRoot($repo);
        return true;
    }

    /**
     * Set the svn binary client of a repo.
     * 
     * @param  object    $repo 
     * @access public
     * @return bool
     */
    public function setClient($repo)
    {
        if($this->config->svn->client == '') 
        {
            echo "You must set the svn client file.\n";
            return false;
        }

        $this->client = $this->config->svn->client . " --non-interactive";
        if(stripos($repo->path, 'https') === 0 or stripos($repo->path, 'svn') === 0)
        {
            $cmd = $this->config->svn->client . ' --version --quiet';
            $version = `$cmd`;
            if(version_compare($version, '1.6.0', '>'))
            {
                $this->client .= ' --trust-server-cert'; 
            }
        }
        if(isset($repo->username)) $this->client .= " --username $repo->username --password $repo->password --no-auth-cache";
        return true;
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
        $cmd  = $this->client . " info --xml $repo->path";
        $info = `$cmd`;
        $info = simplexml_load_string($info);
        $repoRoot = $info->entry->repository->root;
        $this->repoRoot = $repoRoot;
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

        /* The svn log command. */
        $cmd     = $this->client . " log -r $fromRevision:HEAD -v --xml $repo->path";
        $rawLogs = `$cmd`;
        $logs    = @simplexml_load_string($rawLogs);    // Convert it to object.
        if(!$logs)
        {
            echo "Some error occers: \nThe command is $cmd\n the svn logs is $rawLogs\n";
            return false;
        }

        /* Process logs. */
        foreach($logs->logentry as $entry) $parsedLogs[] = $this->convertLog($entry);
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
        /* Get author, revision, msg, date attributes. */
        $parsedLog = new stdClass();
        $parsedLog->author   = (string)$log->author; 
        $parsedLog->revision = (int)$log['revision']; 
        $parsedLog->msg      = trim((string)$log->msg);
        $parsedLog->date     = date('Y-m-d H:i:s', strtotime($log->date));

        /* Process files. */
        $parsedLog->files = array();
        foreach ($log->paths as $key => $paths)
        {
            $parsedFiles = array();
            foreach($paths as $path)
            {
                $action = (string)$path['action'];
                $parsedFiles[$action][] = (string)$path;
            }
        }
        $parsedLog->files = $parsedFiles;

        return $parsedLog;
    }

    /**
     * Parse the comment of svn, extract object id list from it.
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
        $encodings = str_replace(' ', '', isset($this->config->svn->encodings) ? $this->config->svn->encodings : '');
        if($encodings == '') return $comment;
        $encodings = explode(',', $encodings);

        /* Try convert. */
        foreach($encodings as $encoding)
        {
            if($encoding == 'utf-8') continue;
            $result = @iconv($encoding, 'utf-8', $comment);
            if($result) return $result;
        }

        return $comment;
    }

    /**
     * Diff a url.
     * 
     * @param  string $url 
     * @param  int    $revision 
     * @access public
     * @return string|bool
     */
    public function diff($url, $revision)
    {
        $repo = $this->getRepoByURL($url);
        if(!$repo) return false;

        $this->setClient($repo);
        if(empty($this->client)) return false;
        putenv('LC_CTYPE=en_US.UTF-8');

        $oldRevision = $revision - 1;

        $url = str_replace('%2F', '/', urlencode($url));
        $url = str_replace('%3A', ':', $url);

        $cmd = $this->client . " diff -r $oldRevision:$revision $url";
        $diff = `$cmd`;
        return $diff;
    }

    /**
     * Cat a url.
     * 
     * @param  string $url 
     * @param  int    $revision 
     * @access public
     * @return string|bool
     */
    public function cat($url, $revision)
    {
        $repo = $this->getRepoByURL($url);
        if(!$repo) return false;

        $this->setClient($repo);
        if(empty($this->client)) return false;

        putenv('LC_CTYPE=en_US.UTF-8');

        $url = str_replace('%2F', '/', urlencode($url));
        $url = str_replace('%3A', ':', $url);

        $cmd  = $this->client . " cat $url@$revision";
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
        foreach($this->config->svn->repos as $repo)
        {
            if(empty($repo['path'])) continue;
            if(strpos(strtolower($url), strtolower($repo['path'])) !== false) return (object)$repo;
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
        $action->action  = 'svncommited';
        $action->date    = $log->date;
        $action->comment = htmlspecialchars($this->iconvComment($log->msg));
        $action->extra   = $log->revision;

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
            ->andWhere('action')->eq('svncommited')
            ->fetch();
        if($record)
        {
            $this->dao->update(TABLE_ACTION)->data($action)->where('id')->eq($record->id)->exec();
            if($changes)
            {
                $historyID = $this->dao->findByAction($record->id)->from(TABLE_HISTORY)->fetch('id');
                if($historyID)
                {
                    $this->dao->update(TABLE_HISTORY)->data($changes)->where('id')->eq($historyID)->exec();
                }
                else
                {
                    $this->action->logHistory($record->id, array($changes));
                }
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
                $catLink  = trim(html::a(helper::createLink('svn', 'cat',  $param, 'html'), 'view', '', "class='repolink'"));
                $diffLink = trim(html::a(helper::createLink('svn', 'diff', $param, 'html'), 'diff', '', "class='repolink'"));
                $diff .= $action . " " . $file . " $catLink ";
                $diff .= $action == 'M' ? "$diffLink\n" : "\n" ;
            }
        }
        $changes = new stdclass();
        $changes->field = 'subversion';
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
        echo helper::now() . " $log\n";
    }
}
