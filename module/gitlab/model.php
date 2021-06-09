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
     * Get projects of one gitlab.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function getProjectsByID($id)
    {
        $gitlab = $this->getByID($id);
        $host   = rtrim($gitlab->url, '/');
		$host .= '/api/v4/projects';

		$allResults = array();
		for($page = 1; true; $page ++)
		{
			$results = json_decode(file_get_contents($host . "?private_token={$gitlab->token}&simple=true&membership=true&page={$page}&per_page=100"));
			if(empty($results) or $page > 10) break;
			$allResults = $allResults + $results;
		}
		return $allResults;
    }
}
