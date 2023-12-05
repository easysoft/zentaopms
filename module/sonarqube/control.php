<?php
/**
 * The control file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @version     $Id: ${FILE_NAME} 5144 2022/1/11 8:55 上午 caoyanyi@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class sonarqube extends control
{
    /**
     * The mr constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        if(stripos($this->methodName, 'ajax') === false)
        {
            if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);

            if(!in_array(strtolower(strtolower($this->methodName)), array('browseproject', 'reportview', 'browseissue')))
            {
                if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
            }
        }

        /* This is essential when changing tab(menu) from gitlab to repo. */
        /* Optional: common::setMenuVars('devops', $this->session->repoID); */
        $this->loadModel('ci')->setMenu();
    }

    /**
     * Browse sonarqube.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $sonarqubeList = $this->loadModel('pipeline')->getList('sonarqube', $orderBy, $pager);

        $this->view->title         = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->browse;
        $this->view->sonarqubeList = $sonarqubeList;
        $this->view->orderBy       = $orderBy;
        $this->view->pager         = $pager;

        $this->display();
    }

    /**
     * Show sonarqube report.
     *
     * @param  int    $jobID
     * @access public
     * @return void
     */
    public function reportView(int $jobID)
    {
        $job         = $this->loadModel('job')->getByID($jobID);
        $qualitygate = $this->sonarqube->apiGetQualitygate($job->sonarqubeServer, $job->projectKey);
        $report      = $this->sonarqube->apiGetReport($job->sonarqubeServer, $job->projectKey);
        $measures    = array();
        if(isset($report->component->measures))
        {
            foreach($report->component->measures as $measure)
            {
                if(in_array($measure->metric, array('security_hotspots_reviewed', 'coverage', 'duplicated_lines_density')))
                {
                    $measures[$measure->metric] = $measure->value . '%';
                }
                else
                {
                    $measures[$measure->metric] = $measure->value;
                    if($measure->value > 1000) $measures[$measure->metric] = round($measure->value / 1000, 1) . 'K';
                }
            }
        }

        $projectName = $job->projectKey;
        $projects    = $this->sonarqube->apiGetProjects($job->sonarqubeServer, '', $job->projectKey);
        if(isset($projects[0]->name)) $projectName = $projects[0]->name;

        $this->view->measures    = $measures;
        $this->view->qualitygate = $qualitygate;
        $this->view->projectName = $projectName;
        $this->view->projectKey  = $job->projectKey;
        $this->view->sonarqubeID = $job->sonarqubeServer;

        $this->display();
    }

    /**
     * Ajax get project select.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return void
     */
    public function ajaxGetProjectList($sonarqubeID, $projectKey = '')
    {
        $projectPairs = $this->sonarqube->getProjectPairs($sonarqubeID, $projectKey);

        $options = array();
        foreach($projectPairs as $productKey => $projectName)
        {
            $options[] = array('text' => $projectName, 'value' => $productKey);
        }
        return print(json_encode($options));
    }

    /**
     * create a sonarqube.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $sonarqube = form::data($this->config->sonarqube->form->create)
                ->add('type', 'sonarqube')
                ->add('private',md5(rand(10,113450)))
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', helper::now())
                ->trim('url,account,password')
                ->skipSpecial('url,token,account,password')
                ->remove('token,appType')
                ->get();
            $this->checkToken($sonarqube, 0);
            $sonarqubeID = $this->loadModel('pipeline')->create($sonarqube);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('sonarqube', $sonarqubeID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->createServer;

        $this->display();
    }

    /**
     * Check post info.
     *
     * @param  int       $sonarqubeID
     * @access protected
     * @return void
     */
    protected function checkToken(object $sonarqube, int $sonarqubeID = 0)
    {
        $this->dao->update('sonarqube')->data($sonarqube)
            ->batchCheck(empty($sonarqubeID) ? $this->config->sonarqube->create->requiredFields : $this->config->sonarqube->edit->requiredFields, 'notempty')
            ->batchCheck("url", 'URL');
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if(strpos($sonarqube->url, 'http') !== 0) return $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->sonarqube->hostError))));

        /* Check name and url unique. */
        $existSonarQube = $this->dao->select('*')->from(TABLE_PIPELINE)
            ->where("type='sonarqube' and (name='{$sonarqube->name}' or url='{$sonarqube->url}')")
            ->andWhere('deleted')->eq('0')
            ->beginIF(!empty($sonarqubeID))->andWhere('id')->ne($sonarqubeID)->fi()
            ->fetch();
        if(isset($existSonarQube->name) and $existSonarQube->name == $sonarqube->name) return $this->send(array('result' => 'fail', 'message' => $this->lang->sonarqube->nameRepeatError));
        if(isset($existSonarQube->url) and $existSonarQube->url == $sonarqube->url) return $this->send(array('result' => 'fail', 'message' => $this->lang->sonarqube->urlRepeatError));

        $token  = base64_encode("{$sonarqube->account}:{$sonarqube->password}");
        $result = $this->sonarqube->apiValidate($sonarqube->url, $token);

        if(!empty($result)) return $this->send(array('result' => 'fail', 'message' => $result));
        $sonarqube->token = $token;
    }

    /**
     * Edit a sonarqube.
     *
     * @param  int    $sonarqubeID
     * @access public
     * @return void
     */
    public function edit(int $sonarqubeID)
    {
        $oldSonarQube = $this->loadModel('pipeline')->getByID($sonarqubeID);

        if($_POST)
        {
            $oldSonarQube->account  = $this->post->account;
            $oldSonarQube->password = $this->post->password;
            $this->checkToken($oldSonarQube, $sonarqubeID);

            $_POST['token'] = $oldSonarQube->token;
            $this->pipeline->update($sonarqubeID);
            $sonarqube = $this->pipeline->getByID($sonarqubeID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('sonarqube', $sonarqubeID, 'edited');
            $changes  = common::createChanges($oldSonarQube, $sonarqube);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title     = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->editServer;
        $this->view->sonarqube = $oldSonarQube;

        $this->display();
    }

    /**
     * Delete a sonarqube.
     *
     * @param  int    $sonarqubeID
     * @access public
     * @return void
     */
    public function delete(int $sonarqubeID)
    {
        $oldSonarQube = $this->loadModel('pipeline')->getByID($sonarqubeID);
        $this->loadModel('action');
        $actionID = $this->pipeline->deleteByObject($sonarqubeID, 'sonarqube');
        if($actionID)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->sonarqube->delError);

            return $this->send($response);
        }

        $sonarQube = $this->pipeline->getByID($sonarqubeID);
        $changes   = common::createChanges($oldSonarQube, $sonarQube);
        $this->action->logHistory($actionID, $changes);

        $response['load']   = $this->createLink('space', 'browse');
        $response['result'] = 'success';

        return $this->send($response);
    }

    /**
     * Exec job.
     *
     * @param  int    $jobID
     * @access public
     * @return void
     */
    public function execJob(int $jobID)
    {
        echo $this->fetch('job', 'exec', "jobID=$jobID");
    }

    /**
     * Browse sonarqube project.
     *
     * @param  int    $sonarqubeID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseProject(int $sonarqubeID, string $orderBy = 'name_desc', int $recPerPage = 15, int $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $keyword = fixer::input('post')->setDefault('keyword', '')->get('keyword');

        $sonarqubeProjectList = $this->sonarqube->apiGetProjects($sonarqubeID, $keyword);
        $projectKeyList       = array();
        foreach($sonarqubeProjectList as $sonarqubeProject)
        {
            if(!isset($sonarqubeProject->lastAnalysisDate)) $sonarqubeProject->lastAnalysisDate = '';
            $projectKeyList[] = $sonarqubeProject->key;
        }

         /* Data sort. */
        list($order, $sort) = explode('_', $orderBy);
        $orderList = array();
        foreach($sonarqubeProjectList as $sonarqubeProject) $orderList[] = $sonarqubeProject->$order;
        array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $sonarqubeProjectList);

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal = count($sonarqubeProjectList);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $sonarqubeProjectList = array_chunk($sonarqubeProjectList, $pager->recPerPage);

        /* Get success jobs of sonarqube.*/
        $projectJobPairs = $this->loadModel('job')->getJobBySonarqubeProject($sonarqubeID, $projectKeyList);
        $successJobs     = $this->loadModel('compile')->getSuccessJobs($projectJobPairs);
        $sonarqube       = $this->loadModel('pipeline')->getByID($sonarqubeID);
        $instance        = $this->loadModel('instance')->getByUrl($sonarqube->url);

        $sonarqube->instanceID = $sonarqube->id;
        $sonarqube->type       = 'external';
        if(!empty($instance->id))
        {
            $sonarqube->instanceID = $instance->id;
            $sonarqube->type       = 'store';
        }

        $this->view->sonarqube            = $sonarqube;
        $this->view->keyword              = urldecode(urldecode($keyword));
        $this->view->pager                = $pager;
        $this->view->title                = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->browseProject;
        $this->view->sonarqubeID          = $sonarqubeID;
        $this->view->sonarqubeProjectList = (empty($sonarqubeProjectList) or empty($sonarqubeProjectList[$pageID - 1])) ? array() : $sonarqubeProjectList[$pageID - 1];
        $this->view->projectJobPairs      = $projectJobPairs;
        $this->view->orderBy              = $orderBy;
        $this->view->successJobs          = $successJobs;
        $this->display();
    }

    /**
     * Creat a sonarqube project.
     *
     * @param  int     $sonarqubeID
     * @access public
     * @return void
     */
    public function createProject(int $sonarqubeID)
    {
        if($_POST)
        {
            $project = form::data($this->config->sonarqube->form->createProject)->get();
            $this->sonarqube->createProject($sonarqubeID, $project);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => inlink('browseProject', "sonarqubeID=$sonarqubeID"), 'closeModal' => true));
        }

        $this->view->title       = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->createProject;
        $this->view->sonarqubeID = $sonarqubeID;
        $this->display();
    }

    /**
     * Delete project.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return void
     */
    public function deleteProject(int $sonarqubeID, string $projectKey)
    {
        /* Fix error when request type is PATH_INFO and the tag name contains '-'.*/
        $projectKey = str_replace('*', '-', $projectKey);
        $reponse    = $this->sonarqube->apiDeleteProject($sonarqubeID, $projectKey);

        if(isset($reponse->errors)) return $this->sendError($reponse->errors[0]->msg);

        $this->loadModel('action')->create('sonarqubeproject', 0, 'deleted', '', $projectKey);
        return $this->sendSuccess(array('load' => true));
    }

    /**
     * Browse sonarqube issue.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @param  bool   $search
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseIssue($sonarqubeID, $projectKey = '', $search = false, $orderBy = 'severity_desc', $recPerPage = 100, $pageID = 1)
    {
        $projectKey = str_replace('*', '-', $projectKey);
        if(isset($_POST['keyword']))
        {
            $keyword = htmlspecialchars(trim($_POST['keyword']));
            $search  = true;
            $pageID  = 1;
            $this->session->set('sonarqubeIssueKeyword', $keyword);
        }
        else
        {
            $keyword = '';
            if($search) $keyword = $this->session->sonarqubeIssueKeyword;
        }

        ini_set('memory_limit', '1024M');

        $cacheFile = $this->sonarqube->getCacheFile($sonarqubeID, $projectKey);
        if(!$cacheFile or !file_exists($cacheFile) or (time() - filemtime($cacheFile)) / 60 > $this->config->sonarqube->cacheTime)
        {
            $sonarqubeIssueList = $this->sonarqube->apiGetIssues($sonarqubeID, $projectKey);
            foreach($sonarqubeIssueList as $key => $sonarqubeIssue)
            {
                if(!isset($sonarqubeIssue->line)) $sonarqubeIssue->line = '';
                if(!isset($sonarqubeIssue->effort)) $sonarqubeIssue->effort = '';
                $sonarqubeIssue->message      = htmlspecialchars($sonarqubeIssue->message);
                $sonarqubeIssue->creationDate = date('Y-m-d H:i:s', strtotime($sonarqubeIssue->creationDate));

                list(, $file) = explode(':', $sonarqubeIssue->component);
                $sonarqubeIssue->file = $file;
            }

            if($cacheFile && !file_exists($cacheFile . '.lock'))
            {
                touch($cacheFile . '.lock');
                file_put_contents($cacheFile, serialize($sonarqubeIssueList));
                unlink($cacheFile . '.lock');
            }
        }
        else
        {
            $sonarqubeIssueList = unserialize(file_get_contents($cacheFile));
        }

        /* Data search. */
        if($keyword)
        {
            foreach($sonarqubeIssueList as $key => $sonarqubeIssue)
            {
                if(strpos($sonarqubeIssue->message, $keyword) === false and strpos($sonarqubeIssue->file, $keyword) === false) unset($sonarqubeIssueList[$key]);
            }
            $sonarqubeIssueList = array_values($sonarqubeIssueList);
        }

         /* Data sort. */
        list($order, $sort) = explode('_', $orderBy);
        $orderList = array();
        foreach($sonarqubeIssueList as $sonarqubeIssue) $orderList[] = $sonarqubeIssue->$order;
        array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $sonarqubeIssueList);

        /* Get product. */
        $products  = $this->sonarqube->getLinkedProducts($sonarqubeID, $projectKey);
        $productID = current(explode(',', $products));

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal = count($sonarqubeIssueList);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $sonarqubeIssueList = array_chunk($sonarqubeIssueList, $pager->recPerPage);

        $this->view->projectKey         = $projectKey;
        $this->view->search             = $search;
        $this->view->keyword            = $keyword;
        $this->view->pager              = $pager;
        $this->view->title              = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->browseIssue;
        $this->view->sonarqubeID        = $sonarqubeID;
        $this->view->sonarqube          = $this->loadModel('pipeline')->getByID($sonarqubeID);
        $this->view->sonarqubeIssueList = (empty($sonarqubeIssueList) or empty($sonarqubeIssueList[$pageID - 1])) ? array() : $sonarqubeIssueList[$pageID - 1];
        $this->view->orderBy            = $orderBy;
        $this->view->productID          = $productID;
        $this->view->bugs               = $this->loadModel('bug')->getBySonarqubeID($sonarqubeID);
        $this->display();
    }
}
