<?php
/**
 * The model file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class giteaModel extends model
{

    const HOOK_PUSH_EVENT = 'Push Hook';

    /* Gitlab access level. */
    public $noAccess         = 0;
    public $developerAccess  = 30;
    public $maintainerAccess = 40;

    /**
     * Get a gitea by id.
     *
     * @param  int $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->loadModel('pipeline')->getByID($id);
    }

    /**
     * Get gitea list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        $giteaList = $this->loadModel('pipeline')->getList('gitea', $orderBy, $pager);

        return $giteaList;
    }

    /**
     * Get gitea pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs()
    {
        return $this->loadModel('pipeline')->getPairs('gitea');
    }

    /**
     * Get gitea api base url by gitea id.
     *
     * @param  int    $giteaID
     * @param  bool   $sudo
     * @access public
     * @return string
     */
    public function getApiRoot($giteaID, $sudo = true)
    {
        $gitea = $this->getByID($giteaID);
        if(!$gitea) return '';

        $sudoParam = '';
        if($sudo == true and !$this->app->user->admin)
        {
            $openID = $this->getUserIDByZentaoAccount($giteaID, $this->app->user->account);
            if($openID) $sudoParam = "&sudo={$openID}";
        }

        return rtrim($gitea->url, '/') . '/api/v1%s' . "?token={$gitea->token}" . $sudoParam;
    }

    /**
     * Create a gitea.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        return $this->loadModel('pipeline')->create('gitea');
    }

    /**
     * Update a gitea.
     *
     * @param  int $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        return $this->loadModel('pipeline')->update($id);
    }

    /**
     * Api error handling.
     *
     * @param  object $response
     * @access public
     * @return bool
     */
    public function apiErrorHandling($response)
    {
        if(!empty($response->error))
        {
            dao::$errors[] = $response->error;
            return false;
        }
        if(!empty($response->message))
        {
            if(is_string($response->message))
            {
                $errorKey = array_search($response->message, $this->lang->gitea->apiError);
                dao::$errors[] = $errorKey === false ? $response->message : zget($this->lang->gitea->errorLang, $errorKey);
            }
            else
            {
                foreach($response->message as $field => $fieldErrors)
                {
                    if(is_string($fieldErrors))
                    {
                        $errorKey = array_search($fieldErrors, $this->lang->gitea->apiError);
                        if($fieldErrors) dao::$errors[$field][] = $errorKey === false ? $fieldErrors : zget($this->lang->gitea->errorLang, $errorKey);
                    }
                    else
                    {
                        foreach($fieldErrors as $error)
                        {
                            $errorKey = array_search($error, $this->lang->gitea->apiError);
                            if($error) dao::$errors[$field][] = $errorKey === false ? $error : zget($this->lang->gitea->errorLang, $errorKey);
                        }
                    }
                }
            }
        }

        if(!$response) dao::$errors[] = false;
        return false;
    }

    /**
     * Check user access.
     *
     * @param  int    $giteaID
     * @param  int    $projectID
     * @param  object $project
     * @param  string $maxRole
     * @access public
     * @return bool
     */
    public function checkUserAccess($giteaID, $projectID = 0, $project = null, $groupIDList = array(), $maxRole = 'maintainer')
    {
        if($this->app->user->admin) return true;

        if($project == null) $project = $this->apiGetSingleProject($giteaID, $projectID);
        if(!isset($project->id)) return false;

        $accessLevel = $this->config->gitea->accessLevel[$maxRole];

        if(isset($project->permissions->project_access->access_level) and $project->permissions->project_access->access_level >= $accessLevel) return true;
        if(isset($project->permissions->group_access->access_level) and $project->permissions->group_access->access_level >= $accessLevel) return true;
        if(!empty($project->shared_with_groups))
        {
            if(empty($groupIDList))
            {
                $groups = $this->apiGetGroups($giteaID, 'name_asc', $maxRole);
                foreach($groups as $group) $groupIDList[] = $group->id;
            }

            foreach($project->shared_with_groups as $group)
            {
                if($group->group_access_level < $accessLevel) continue;
                if(in_array($group->group_id, $groupIDList)) return true;
            }
        }

        return false;
    }

    /**
     * Check token access.
     *
     * @param  string $url
     * @param  string $token
     * @access public
     * @return void
     */
    public function checkTokenAccess($url = '', $token = '')
    {
        $apiRoot  = rtrim($url, '/') . '/api/v1%s' . "?token={$token}";
        $url      = sprintf($apiRoot, "/admin/users") . "&limit=1";
        $httpData = commonModel::httpWithHeader($url);
        $users    = json_decode($httpData['body']);
        if(empty($users)) return false;
        if(isset($users->message) or isset($users->error)) return null;
        return true;
    }

    /**
     * Get Gitea id list by user account.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getGiteaListByAccount($account = '')
    {
        if(!$account) $account = $this->app->user->account;

        return $this->dao->select('providerID,openID')->from(TABLE_OAUTH)
            ->where('providerType')->eq('gitea')
            ->andWhere('account')->eq($account)
            ->fetchPairs('providerID');
    }

    /**
     * Get project by api.
     *
     * @param  int    $giteaID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function apiGetSingleProject($giteaID, $projectID)
    {
        $apiRoot = $this->getApiRoot($giteaID);
        if(!$apiRoot) return array();

        $url = sprintf($apiRoot, "/repos/$projectID");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get projects by api.
     *
     * @param  int    $giteaID
     * @param  bool   $sudo
     * @access public
     * @return array
     */
    public function apiGetProjects($giteaID, $sudo = 'true')
    {
        $apiRoot = $this->getApiRoot($giteaID, $sudo);
        if(!$apiRoot) return array();

        $url     = sprintf($apiRoot, "/repos/search");
        $results = json_decode(commonModel::http($url));

        return $results->data;
    }
}
