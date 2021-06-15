<?php
/**
 * The model file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

/* enable JMESPath */
require __DIR__ . '/../../vendor/autoload.php';

class gitlabModel extends model
{
    /**
     * Get a gitlab by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->loadModel('pipeline')->getByID($id);
    }

    /**
     * Get gitlab list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->loadModel('pipeline')->getList('gitlab', $orderBy, $pager);
    }

    /**
     * Get gitlab pairs
     *
     * @return array
     */
    public function getPairs()
    {
        return $this->loadModel('pipeline')->getPairs('gitlab');
    }

    /**
     * Create a gitlab.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        return $this->loadModel('pipeline')->create('gitlab');
    }

    /**
     * Update a gitlab.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        return $this->loadModel('pipeline')->update($id);
    }

    /**
     * Get gitlab token permissions.
     *
     * @param  string   $host
     * @param  string   $token
     * @access public
     * @return array
     */
    public function getPermissionsByToken($host, $token)
    {
        if(strpos($host, 'http') !== 0) return array('result' => 'fail', 'message' => array('url' => array($this->lang->gitlab->hostError)));
        if(!$this->post->token) return array('result' => 'fail', 'message' => array('token' => array($this->lang->gitlab->tokenError)));
        $host = rtrim($host, '/') . "/api/v4/user?private_token=$token";
        $response = json_decode(commonModel::http($host));

        if(!is_object($response)) return array('result' => 'fail', 'message' => array('url' => array($this->lang->gitlab->hostError)));
        if(isset($response->is_admin) and $response->is_admin == true) return array('result' => 'success');
        return array('result' => 'fail', 'message' => array('token' => array($this->lang->gitlab->tokenError)));
    }


    /**
     * Get gitlab user list.
     *
     * @param  string   $host
     * @param  string   $token
     * @access public
     * @return array
     */

    public function getUserBindList($host, $token)
    {
        $host  = rtrim($host, '/') .'/api/v4/users?private_token='.$token ;
        $response = json_decode(commonModel::http($host),true);

        if (!$response) return array();
        $localUsersList = $this->dao->select('id,account,email,realname')->from(TABLE_USER)->fetchAll();

        $responseAllId    = array();
        $matchUserIds     = array();
        $responseMatchIds = array();
        foreach ($response as $i => $gitlabId) {

            $responseAllId[] = $gitlabId['id'];

            foreach ($localUsersList as $local) {

                if ( $gitlabId['email'] == $local->email && $gitlabId['username'] == $local->realname &&  $gitlabId['name'] == $local->account)
                {
                    $matchUserIds[$i][$local->realname] = $local->id .'-'. $gitlabId['id'];
                    $responseMatchIds[] = $gitlabId['id'];
                }
            }
        }
        $notMatchUserIds = array_diff($responseAllId,$responseMatchIds);

        foreach ($notMatchUserIds as $k => $v)
        {
            $notMatchUserId[$k]['Not matched'] = 0 .'-'. $v;
        }
        return array_merge($notMatchUserId,$matchUserIds);
    }

    /**
     * Get projects of one gitlab.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function getProjectsByID($id)
    {   
        $gitlab = $this->getByID($id);
        if(!$gitlab) return array();
        $host   = rtrim($gitlab->url, '/');
        $host .= '/api/v4/projects';

        $allResults = array();
        for($page = 1; true; $page ++) 
        {   
            $results = json_decode(commonModel::http($host . "?private_token={$gitlab->token}&simple=true&membership=true&page={$page}&per_page=100"));
            if(empty($results) or $page > 10) break;
            $allResults = $allResults + $results;
        }   
        return $allResults;
    }

    /**
     * Get gitlab api base url with access_token
     * 
     * @param  int    $id 
     * @access public
     * @return string gitlab api base url with access_token
     */
    public function getApiRoot($id)
    {
        $gitlab = $this->getByID($id);
        if(!$gitlab) return "";
        $gitlab_url = rtrim($gitlab->url, '/').'/api/v4%s'."?private_token={$gitlab->token}";
        return $gitlab_url; 
    }


    public function getHooksOfProject($gitlab_id, $project_id)
    {
        $host = $this->getApiRoot($gitlab_id);
        $api_path = sprintf('/projects/%s/hooks', $project_id);
        $host = sprintf($host, $api_path);
        $api_json = commonModel::http($host);
        return $api_json;
    }

    public function ListHooks($gitlab_id, $project_id)
    {
        return $this->getHooksOfProject();
    }

    public function GetHook($gitlab_id, $project_id, $hook_id)
    {
        return;
    }  

    public function CreateHooks($gitlab_id, $project_id, $url, $token)
    {
        return;
    }

    public function DeleteHooks($gitlab_id, $project_id, $hook_id)
    {
        return;
    }

    public function UpdateHooks($gitlab_id, $project_id, $hook_id)
    {
        return;
    }

}
