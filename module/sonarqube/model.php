<?php
/**
 * The model file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class sonarqubeModel extends model
{
    /**
     * Get sonarqube api base url and header by id.
     *
     * @param  int $sonarqubeID
     * @access public
     * @return array
     */
    public function getApiBase($sonarqubeID)
    {
        $sonarqube = $this->loadModel('pipeline')->getByID($sonarqubeID);
        if(!$sonarqube) return array('', array()); 

        $url      = rtrim($sonarqube->url, '/') . '/api/%s';
        $header[] = 'Authorization: Basic ' . $sonarqube->token;

        return array($url, $header);
    }

    /**
     * check sonarqube valid
     *
     * @param string $host
     * @param string $token
     * @access public
     * @return array
     */
    public function apiValidate($host, $token)
    {
        $url    = rtrim($host, '/') . "/api/authentication/validate";
        $header = 'Authorization: Basic ' . $token;
        return json_decode(commonModel::http($url, null, array(), $header));
    }

    /**
     * Get projects of one sonarqube.
     *
     * @param  int    $sonarqubeID
     * @param  string $keyword
     * @access public
     * @return array
     */
    public function apiGetProjects($sonarqubeID, $keyword = '')
    {
        list($apiRoot, $header) = $this->getApiBase($sonarqubeID);
        if(!$apiRoot) return array();

        $url = sprintf($apiRoot, "projects/search");

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $result = json_decode(commonModel::http($url. "?p={$page}&ps=500" . ($keyword ? "&q={$keyword}" : ''), null, array(), $header));
            if(!isset($result->components)) break;
            if(!empty($result->components)) $allResults = array_merge($allResults, $result->components);
            if(count($result->components) < 500) break;
        }

        return $allResults;
    }

    /**
     * Delete sonarqube project by api.
     *
     * @param  int    $sonarqubeID
     * @param  int    $projectKey
     * @access public
     * @return bool|object
     */
    public function apiDeleteProject($sonarqubeID, $projectKey)
    {
        list($apiRoot, $header) = $this->getApiBase($sonarqubeID);
        if(!$apiRoot) return false;

        $url    = sprintf($apiRoot, "projects/delete?project=$projectKey");
        $result = json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'POST'), $header));
        return $result;
    }
}
