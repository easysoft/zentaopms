<?php
/**
 * The model file of api module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class apiModel extends model
{
    public function getMethod($filePath, $ext = '')
    {
        $fileName  = dirname($filePath);
        $className = basename(dirname(dirname($filePath)));
        if(!class_exists($className)) include($fileName);
        $methodName = basename($filePath);

        $method = new ReflectionMethod($className . $ext, $methodName);
        $data   = new stdClass();
        $data->startLine  = $method->getStartLine();
        $data->endLine    = $method->getEndLine();
        $data->comment    = $method->getDocComment();
        $data->parameters = $method->getParameters();
        $data->className  = $className;
        $data->methodName = $methodName;
        $data->fileName   = $fileName;
        $data->post       = false;

        $file = file($fileName);
        for($i = $data->startLine - 1; $i <= $data->endLine; $i++)
        {
            if(strpos($file[$i], '$this->post') or strpos($file[$i], 'fixer::input') or strpos($file[$i], '$_POST'))
            {
                $data->post = true; 
            }
        }
        return $data;
    }

    /**
     * Request the api.
     * 
     * @param  string $moduleName 
     * @param  string $methodName 
     * @param  string $action 
     * @access public
     * @return void
     */
    public function request($moduleName, $methodName, $action)
    {
        $host  = common::getSysURL() . $this->config->webRoot;
        $param = '';
        if($action == 'extendModel')
        {
            if(!isset($_POST['noparam']))
            {
                foreach($_POST as $key => $value) $param .= ',' . $key . '=' . $value;
                $param = ltrim($param, ',');
            }
            $url   = rtrim($host, '/') . inlink('getModel',  "moduleName=$moduleName&methodName=$methodName&params=$param", 'json');
            $url  .= $this->config->requestType == "PATH_INFO" ? '?' : '&';
            $url  .= $this->config->sessionVar . '=' . session_id();
        }
        else
        {
            if(!isset($_POST['noparam']))
            {
                foreach($_POST as $key => $value) $param .= '&' . $key . '=' . $value;
                $param = ltrim($param, '&');
            }
            $url   = rtrim($host, '/') . helper::createLink($moduleName, $methodName, $param, 'json');
            $url  .= $this->config->requestType == "PATH_INFO" ? '?' : '&';
            $url  .= $this->config->sessionVar . '=' . session_id();
        }

        /* Unlock session. After new request, restart session. */
        session_write_close();
        $content = file_get_contents($url);
        session_start();

        return array('url' => $url, 'content' => $content);
    }

    /**
     * Query sql. 
     * 
     * @param  string    $sql 
     * @param  string    $keyField 
     * @access public
     * @return array
     */
    public function sql($sql, $keyField = '')
    {
        $sql  = trim($sql);
        if(strpos($sql, ';') !== false) $sql = substr($sql, 0, strpos($sql, ';'));
        a($sql);
        if(empty($sql)) return '';

        if(stripos($sql, 'select ') !== 0)
        {
            return $this->lang->api->error->onlySelect;
        }
        else
        {
            try
            {
                $stmt = $this->dao->query($sql);
                if(empty($keyField)) return $stmt->fetchAll();
                $rows = array();
                while($row = $stmt->fetch()) $rows[$row->$keyField] = $row;
                return $rows;
            }
            catch(PDOException $e)
            {
                return $e->getMessage();
            }
        }
    }
}
