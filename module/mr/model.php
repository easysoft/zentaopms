<?php
/**
 * The model file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     mr
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class mrModel extends model
{
    /**
     * The construct method, to do some auto things.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('gitlab');
    }

    public function getGitlabProjectByRepo($repoID)
    {
        $repo = $this->loadModel('repo')->getRepoByID($repoID);

        $gitlab = new stdclass;
        $gitlab->product   = explode(',', $repo->product);
        $gitlab->gitlabID  = $repo->gitlab;
        $gitlab->projectID = $repo->project;

        return $gitlab;
    }

    /**
     * Get a MR by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_MR)->where('id')->eq($id)->fetch();
    }

    /**
     * Get MR list of gitlab project.
     *
     * @param  int    $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')
                         ->from(TABLE_MR)
                         ->where('deleted')->eq('0')
                         ->orderBy($orderBy)
                         ->page($pager)
                         ->fetchAll('id');
    }

    /**
     * Get gitlab pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs($repoID)
    {
        $mr = $this->dao->select('id,title')
                        ->from(TABLE_MR)
                        ->where('deleted')->eq('0')
                        ->AndWhere('repoID')->eq($repoID)
                        ->orderBy('id')->fetchPairs('id', 'title');
        return array('' => '') + $mr;
    }

    public function create()
    {
        $gitlabID  = $this->post->gitlabID;
        $projectID = $this->post->projectID;

        $sourceProject = $this->post->sourceProject;
        $sourceBranch  = $this->post->sourceBranch;
        $targetProject = $this->post->targetProject;
        $targetBranch  = $this->post->targetBranch;

        if($projectID != $sourceProject) return false;

    }

    /**
     * Get gitlab api base url by gitlab ID.
     *
     * @param  int    $gitlabID
     * @access public
     * @return string
     */
    public function getApiRoot($gitlabID)
    {
        return $this->gitlab->getApiRoot($gitlabID);
    }

    /**
     * Get Forks of a project.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return object
     * @docs   https://docs.gitlab.com/ee/api/projects.html#list-forks-of-a-project
     */
    public function apiGetForks($gitlabID, $projectID)
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/forks");
        $mr  = new stdclass;
        return json_decode(commonModel::http($url));
    }

    /**
     * Create MR by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $params
     * @access public
     * @return object
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#create-mr
     */
    public function apiCreateMR($gitlabID, $projectID, $params)
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/merge_requests");
        $mr  = new stdclass;
        return json_decode(commonModel::http($url, $mr));
    }

    /**
     * Get MR list by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return object
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#list-project-merge-requests
     */
    public function apiGetMRList($gitlabID, $projectID)
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/merge_requests");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get single MR by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $mrID
     * @access public
     * @return object
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#get-single-mr
     */
    public function apiGetSingleMR($gitlabID, $projectID, $mrID)
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$mrID");
        return json_decode(commonModel::http($url));
    }

    /**
     * Update MR by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $mrID
     * @param  object $params
     * @access public
     * @return object
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#update-mr
     */
    public function apiUpdateMR($gitlabID, $projectID, $mrID, $params)
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$mrID");
        $mr  = new stdclass;
        return json_decode(commonModel::http($url, $mr, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * Delete MR by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $mrID
     * @access public
     * @return object
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#delete-a-merge-request
     */
    public function apiDeleteMR($gitlabID, $projectID, $mrID)
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$mrID");
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

    /**
     * Accept MR by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $mrID
     * @access public
     * @return object
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#accept-mr
     */
    public function apiAcceptMR($gitlabID, $projectID, $mrID)
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$mrID");
        return json_decode(commonModel::http($url, $data, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * Get MR diff versions by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $mrID
     * @access public
     * @return object
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#get-mr-diff-versions
     */
    public function apiGetDiffVersions($gitlabID, $projectID, $mrID)
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$mrID");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get single diff version by API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $mrID
     * @param  int    $versionID
     * @access public
     * @return object
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#get-a-single-mr-diff-version
     */
    public function apiGetSingleDiffVersion($gitlabID, $projectID, $mrID, $versionID)
    {
        $url = sprintf($this->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$mrID/versions/$versionID");
        return json_decode(commonModel::http($url));
    }
}

