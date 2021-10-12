<?php

/**
 * The model file of api module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class apiModel extends model
{
    /* Status. */
    const STATUS_DOING = 'doing';
    const STATUS_DONE = 'done';
    const STATUS_HIDDEN = 'hidden';

    /* Scope. */
    const SCOPE_QUERY = 'query';
    const SCOPE_FORM_DATA = 'formData';
    const SCOPE_PATH = 'path';
    const SCOPE_BODY = 'body';
    const SCOPE_HEADER = 'header';
    const SCOPE_COOKIE = 'cookie';

    /* Params. */
    const PARAMS_TYPE_CUSTOM = 'custom';

    /**
     * Create an api doc.
     *
     * @param  object $params
     * @access public
     * @return int
     */
    public function create($params)
    {
        $this->dao->insert(TABLE_API)->data($params)
            ->autoCheck()
            ->batchCheck($this->config->api->create->requiredFields, 'notempty')
            ->exec();

        return dao::isError() ? false : $this->dao->lastInsertID();
    }

    /**
     * Create a global struct
     *
     * @param  object $data
     * @access public
     * @return int
     */
    public function createStruct($data)
    {
        $this->dao->insert(TABLE_APISTRUCT)->data($data)
            ->autoCheck()
            ->batchCheck($this->config->api->struct->requiredFields, 'notempty')
            ->exec();

        return dao::isError() ? 0 : $this->dao->lastInsertID();
    }

    /**
     * Update a struct.
     *
     * @param  int    $id
     * @param  object $data
     * @access public
     * @return array
     */
    public function updateStruct($id, $data)
    {
        $old = $this->dao->findByID($id)->from(TABLE_APISTRUCT)->fetch();

        unset($data->addedBy);
        unset($data->addedDate);

        $this->dao->update(TABLE_APISTRUCT)
            ->data($data)->autoCheck()
            ->batchCheck($this->config->api->struct->requiredFields, 'notempty')
            ->where('id')->eq($id)
            ->exec();

        if(dao::isError()) return false;

        return common::createChanges($old, $data);
    }

    /**
     * Delete a struct.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function deleteStruct($id)
    {
        $this->dao->update(TABLE_APISTRUCT)
            ->set('deleted')->eq(1)
            ->where('id')->eq($id)
            ->exec();
    }

    /**
     * Update an api doc.
     *
     * @param  int    $id
     * @param  object $data
     * @access public
     * @return void
     */
    public function update($id, $data)
    {
        $oldApi = $this->dao->findByID($id)->from(TABLE_API)->fetch();

        $data->id      = $oldApi->id;
        $data->version = $oldApi->version + 1;
        $apiSpec       = $this->getApiSpecByData($data);

        $this->dao->replace(TABLE_API_SPEC)->data($apiSpec)->exec();

        unset($data->id);
        $this->dao->update(TABLE_API)
            ->data($data)
            ->autoCheck()
            ->batchCheck($this->config->api->edit->requiredFields, 'notempty')
            ->where('id')->eq($id)
            ->exec();
    }

    /**
     * Get struct list by api doc id.
     *
     * @param  int $id
     * @access public
     * @return array
     */
    public function getStructListByLibID($id)
    {
        $res = $this->dao->select('*')
            ->from(TABLE_APISTRUCT)
            ->where('lib')->eq($id)
            ->fetchAll();

        array_map(function ($item) {
            $item->attribute = json_decode(htmlspecialchars_decode($item->attribute), true);
            return $item;
        }, $res);
        return $res;
    }

    /**
     * Get a struct info.
     *
     * @param  int $id
     * @access public
     * @return object
     */
    public function getStructByID($id)
    {
        $model = $this->dao->select('*')
            ->from(TABLE_APISTRUCT)
            ->where('id')->eq($id)
            ->fetch();

        if($model) $model->attribute = json_decode(htmlspecialchars_decode($model->attribute), true);

        return $model;
    }


    /**
     * Get api doc by id.
     *
     * @param  int $id
     * @access public
     * @return object
     */
    public function getLibById($id, $version = 0)
    {

        if($version)
        {
            $fields = 'spec.*,api.id,api.product,api.lib,api.version,doc.name as libName,module.name as moduleName';
        }
        else
        {
            $fields = 'api.*,doc.name as libName,module.name as moduleName';
        }

        $model = $this->dao
            ->select($fields)
            ->from(TABLE_API)->alias('api')
            ->beginIF($version)->leftJoin(TABLE_API_SPEC)->alias('spec')->on('api.id = spec.doc')->fi()
            ->leftJoin(TABLE_DOCLIB)->alias('doc')->on('api.lib = doc.id')
            ->leftJoin(TABLE_MODULE)->alias('module')->on('api.module = module.id')
            ->where('api.id')->eq($id)
            ->beginIF($version)->andWhere('spec.version')->eq($version)->fi()
            ->fetch();

        if($model)
        {
            $model->params   = json_decode(htmlspecialchars_decode($model->params), true);
            $model->response = json_decode(htmlspecialchars_decode($model->response), true);
        }
        return $model;
    }

    /**
     * Get api doc list by module id
     *
     * @param  int $libID
     * @param  int $moduleID
     * @return array $list
     * @author thanatos thanatos915@163.com
     */
    public function getListByModuleId($libID = 0, $moduleID = 0)
    {
        if($moduleID > 0)
        {
            $sub   = $this->dao->select('id')->from(TABLE_MODULE)->where('FIND_IN_SET(' . $moduleID . ', path)')->processSQL();
            $where = 'module in (' . $sub . ')';
        }
        else
        {
            $where = 'lib = ' . $libID;
        }
        $list = $this->dao->select('*')
            ->from(TABLE_API)
            ->where($where)
            ->andWhere('deleted')->eq(0)
            ->fetchAll();
        array_map(function ($item) {
            $item->params = json_decode(htmlspecialchars_decode($item->params), true);
            return $item;
        }, $list);
        return $list;
    }

    /**
     * Get status text by status.
     *
     * @param  string $status
     * @access public
     * @return string
     */
    public static function getApiStatusText($status)
    {
        global $lang;
        switch($status)
        {
            case static::STATUS_DOING:
            {
                return $lang->api->doing;
            }
            case static::STATUS_DONE:
            {
                return $lang->api->done;
            }
        }
    }

    /**
     * @param  int    $libID
     * @param  string $pager
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStructByQuery($libID, $pager = '', $orderBy = '')
    {
        return $this->dao->select('*')->from(TABLE_APISTRUCT)
            ->where('deleted')->eq(0)
            ->andWhere('lib')->eq($libID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get struct tree by lib id
     *
     * @param  int $libID
     * @param  int $structID
     * @access public
     * @return string
     */
    public function getStructTreeByLib($libID = 0, $structID = 0)
    {
        $list = $this->getStructListByLibID($libID);

        $html = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-lib'>";
        foreach($list as $item)
        {
            $class = array('catalog');
            if($structID && $structID == $item->id)
            {
                $class[] = 'active';
            }
            else
            {
                $class[] = 'doc';
            }

            $html .= '<li class="' . implode(' ', $class) . '">';
            $html .= html::a(helper::createLink('api', 'struct', "libID=$libID&structID=$item->id"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $item->name, '', "data-app='{$this->app->tab}' class='doc-title' title='{$item->name}'");
            $html .= "</li>";
        }
        $html .= "</ul>";

        return $html;
    }

    /**
     * Get the details of the method by file path.
     *
     * @param  string $filePath
     * @param  string $ext
     * @access public
     * @return object
     */
    public function getMethod($filePath, $ext = '')
    {
        $fileName  = dirname($filePath);
        $className = basename(dirname(dirname($filePath)));
        if(!class_exists($className)) helper::import($fileName);
        $methodName = basename($filePath);

        $method           = new ReflectionMethod($className . $ext, $methodName);
        $data             = new stdClass();
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
     * @return array
     */
    public function request($moduleName, $methodName, $action)
    {
        $host  = common::getSysURL();
        $param = '';
        if($action == 'extendModel')
        {
            if(!isset($_POST['noparam']))
            {
                foreach($_POST as $key => $value) $param .= ',' . $key . '=' . $value;
                $param = ltrim($param, ',');
            }
            $url = rtrim($host, '/') . inlink('getModel', "moduleName=$moduleName&methodName=$methodName&params=$param", 'json');
            $url .= $this->config->requestType == "PATH_INFO" ? '?' : '&';
            $url .= $this->config->sessionVar . '=' . session_id();
        }
        else
        {
            if(!isset($_POST['noparam']))
            {
                foreach($_POST as $key => $value) $param .= '&' . $key . '=' . $value;
                $param = ltrim($param, '&');
            }
            $url = rtrim($host, '/') . helper::createLink($moduleName, $methodName, $param, 'json');
            $url .= $this->config->requestType == "PATH_INFO" ? '?' : '&';
            $url .= $this->config->sessionVar . '=' . session_id();
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
     * @param  string $sql
     * @param  string $keyField
     * @access public
     * @return array
     */
    public function sql($sql, $keyField = '')
    {
        if(!$this->config->features->apiSQL) return sprintf($this->lang->api->error->disabled, '$config->features->apiSQL');

        $sql = trim($sql);
        if(strpos($sql, ';') !== false) $sql = substr($sql, 0, strpos($sql, ';'));

        $result            = array();
        $result['status']  = 'fail';
        $result['message'] = '';

        if(empty($sql)) return $result;

        if(stripos($sql, 'select ') !== 0)
        {
            $result['message'] = $this->lang->api->error->onlySelect;
            return $result;
        }

        try
        {
            $stmt = $this->dbh->query($sql);

            $rows = array();
            if(empty($keyField))
            {
                $rows = $stmt->fetchAll();
            }
            else
            {
                while($row = $stmt->fetch()) $rows[$row->$keyField] = $row;
            }

            $result['status'] = 'success';
            $result['data']   = $rows;
        } catch(PDOException $e)
        {
            $result['status']  = 'fail';
            $result['message'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * Get spec of api.
     *
     * @param  object $data
     * @access private
     * @return array
     */
    private function getApiSpecByData($data)
    {
        return array(
            'doc'          => $data->id,
            'module'       => $data->module,
            'title'        => $data->title,
            'path'         => $data->path,
            'protocol'     => $data->protocol,
            'method'       => $data->method,
            'requestType'  => $data->requestType,
            'responseType' => isset($data->responseType) ? $data->responseType : '',
            'status'       => $data->status,
            'owner'        => $data->owner,
            'desc'         => $data->desc,
            'version'      => $data->version,
            'params'       => $data->params,
            'response'     => $data->response,
            'addedBy'      => $this->app->user->account,
            'addedDate'    => helper::now(),
        );
    }
}
