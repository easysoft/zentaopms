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
     * Get sonarqube list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        $sonarqubeList = $this->loadModel('pipeline')->getList('sonarqube', $orderBy, $pager);

        return $sonarqubeList;
    }

    /**
     * Get sonarqube pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs()
    {
        return $this->loadModel('pipeline')->getPairs('sonarqube');
    }

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
        if(!$sonarqube) return '';

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
     * Get all project list.
     * 
     * @param  int    $sonarqubeID 
     * @access public
     * @return array 
     */
    public function getAllProjectList($sonarqubeID)
    {
        list($url, $header) = $this->getApiBase($sonarqubeID);
        $url    = sprintf($url, 'projects/search?ps=500&p=1');
        $result = json_decode(commonModel::http($url, null, array(), $header[0]));

        $projectList = zget($result, 'components', array());
        $total       = isset($result->paging->total) ? $result->paging->total : 0;
        for($i = 2; $i <= $total; $i++)
        {
            $url          = sprintf($url, 'projects/search?ps=500&p=' . $i);
            $pageData     = json_decode(commonModel::http($url, null, array(), $header));
            $projectList += zget($pageData, 'components', array());
        }
        return (array)$projectList;
    }
}
