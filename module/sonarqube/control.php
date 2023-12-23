<?php
declare(strict_types=1);
/**
 * The control file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @version     $Id: ${FILE_NAME} 5144 2022/1/11 8:55 上午 caoyanyi@easycorp.ltd $
 * @link        https://www.zentao.net
 */
class sonarqube extends control
{
    /**
     * The mr constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct(string $moduleName = '', string $methodName = '')
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
     * sonarqube 列表。
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
     * 展示sonarqube报告。
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
     * ajax方式获取项目下拉。
     * Ajax get project select.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return void
     */
    public function ajaxGetProjectList(int $sonarqubeID, string $projectKey = '')
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
     * 创建sonarqube服务器。
     * create a sonarqube.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $sonarqube = form::data($this->config->sonarqube->form->create)->get();
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
     * 检查post数据。
     * Check post info.
     *
     * @param  int       $sonarqubeID
     * @access protected
     * @return void
     */
    protected function checkToken(object $sonarqube, int $sonarqubeID = 0)
    {
        $this->sonarqubeZen->checkTokenRequire($sonarqube);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

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
     * 编辑sonarqube服务器。
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

            $sonarqube = form::data($this->config->sonarqube->form->edit)->get();
            $sonarqube->token = $oldSonarQube->token;
            $this->pipeline->update($sonarqubeID, $sonarqube);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $sonarqube = $this->pipeline->getByID($sonarqubeID);
            $actionID  = $this->loadModel('action')->create('sonarqube', $sonarqubeID, 'edited');
            $changes   = common::createChanges($oldSonarQube, $sonarqube);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title     = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->editServer;
        $this->view->sonarqube = $oldSonarQube;

        $this->display();
    }

    /**
     * 删除sonarqube服务器。
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
     * 执行流水线。
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
     * soanrqube 项目列表。
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
        $keyword              = fixer::input('post')->setDefault('keyword', '')->get('keyword');
        $sonarqubeProjectList = $this->sonarqube->apiGetProjects($sonarqubeID, $keyword);
        $projectKeyList       = array();
        foreach($sonarqubeProjectList as $sonarqubeProject)
        {
            if(!isset($sonarqubeProject->lastAnalysisDate)) $sonarqubeProject->lastAnalysisDate = '';
            $projectKeyList[] = $sonarqubeProject->key;
        }

        $sonarqubeProjectList = $this->sonarqubeZen->sortAndPage($sonarqubeProjectList, $orderBy, $recPerPage, $pageID);

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
        $this->view->title                = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->browseProject;
        $this->view->sonarqubeID          = $sonarqubeID;
        $this->view->sonarqubeProjectList = (empty($sonarqubeProjectList) or empty($sonarqubeProjectList[$pageID - 1])) ? array() : $sonarqubeProjectList[$pageID - 1];
        $this->view->projectJobPairs      = $projectJobPairs;
        $this->view->orderBy              = $orderBy;
        $this->view->successJobs          = $successJobs;
        $this->display();
    }

    /**
     * 创建一个sonarqube 项目。
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
     * 删除一个sonarqube项目。
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
     * soanrqueb 项目问题列表。
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
    public function browseIssue(int $sonarqubeID, string $projectKey = '', bool $search = false, string $orderBy = 'severity_desc', int $recPerPage = 100, int $pageID = 1)
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

        $sonarqubeIssueList = $this->sonarqubeZen->getIssueList($sonarqubeID, $projectKey);

        /* Data search. */
        if($keyword)
        {
            foreach($sonarqubeIssueList as $key => $sonarqubeIssue)
            {
                if(strpos($sonarqubeIssue->message, $keyword) === false and strpos($sonarqubeIssue->file, $keyword) === false) unset($sonarqubeIssueList[$key]);
            }
            $sonarqubeIssueList = array_values($sonarqubeIssueList);
        }

        $sonarqubeIssueList = $this->sonarqubeZen->sortAndPage($sonarqubeIssueList, $orderBy, $recPerPage, $pageID);

        /* Get product. */
        $products  = $this->sonarqube->getLinkedProducts($sonarqubeID, $projectKey);
        $productID = current(explode(',', $products));

        $this->view->projectKey         = $projectKey;
        $this->view->search             = $search;
        $this->view->keyword            = $keyword;
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
