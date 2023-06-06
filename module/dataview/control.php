<?php
/**
 * The control file of dataview module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: control.php 4157 2013-01-20 07:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
class dataview extends control
{
    /**
     * Browse page.
     *
     * @param  string $type table|view
     * @access public
     * @return void
     */
    public function browse($type = 'view', $table = '')
    {
        $this->session->set('dataViewList', $this->app->getURI(true));

        $this->loadModel('dev');
        $this->loadModel('tree');

        $dataview = $type == 'view' ? $this->dataview->getByID($table) : null;
        if(!empty($table)) $fields = $type == 'table' ? $this->dev->getFields($table) : $this->dataview->getFields($table);

        $this->view->title         = $this->lang->dataview->common;
        $this->view->tab           = 'db';
        $this->view->tables        = $this->dev->getTables();
        $this->view->selectedTable = $table;
        $this->view->dataview      = $dataview;
        $this->view->dataTitle     = $type == 'view' ? (!empty($dataview->name) ? $dataview->name : '') : $this->dataview->getTableName($table);
        $this->view->fields        = !empty($fields) ? $fields : array();
        $this->view->data          = !empty($fields) ? $this->dataview->getTableData($table, $type) : array();
        $this->view->type          = $type;
        $this->view->groups        = $this->tree->getOptionMenu(0, 'dataview');
        $this->view->groupTree     = $this->tree->getGroupTree(0, 'dataview');
        $this->view->originTable   = $this->dataview->getOriginTreeMenu($table);
        $this->view->clientLang    = $this->app->getClientLang();
        $this->display();
    }

    /**
     * Create a dataview.
     *
     * @param  string $step
     * @access public
     * @return void
     */
    public function create($step = 'query')
    {
        if($step == 'query')
        {
            $this->view->title         = $this->lang->dataview->create;
            $this->view->saveLink      = inlink('create', "step=create", '', true);
            $this->view->backLink      = inlink('browse', "type=view");
            $this->view->data          = null;
            $this->view->fieldSettings = new stdclass();
            $this->display('dataview', 'query');
        }
        else
        {
            if(!empty($_POST))
            {
                $viewID = $this->dataview->create();
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                $this->loadModel('action')->create('dataview', $viewID, 'opened');

                $callback = array('target' => 'parent', 'name' => 'locate', 'params' => array('browse', "type=view&viewID=$viewID"));

                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
            }

            $this->view->title  = $this->lang->dataview->create;
            $this->view->groups = $this->loadModel('tree')->getOptionMenu(0, 'dataview');
            $this->display();
        }
    }

    /**
     * Query a dataview.
     *
     * @param int $viewID
     * @access public
     * @return void
     */
    public function query($viewID)
    {
        if(!empty($_POST))
        {
            $this->dataview->querySave($viewID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('dataview', $viewID, 'designed');

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "type=view&table=$viewID")));
        }

        $dataview = $this->dataview->getByID($viewID);

        $this->view->title         = $this->lang->dataview->design;
        $this->view->saveLink      = inlink('query', "viewID=$viewID");
        $this->view->backLink      = inlink('browse', "type=view&table=$viewID");
        $this->view->data          = $dataview;
        $this->view->fieldSettings = isset($dataview->fieldSettings) ? $dataview->fieldSettings : new stdclass();
        $this->display();
    }

    /**
     * Edit a dataview.
     *
     * @param  int    $dataviewID
     * @access public
     * @return void
     */
    public function edit($dataviewID)
    {
        if(!empty($_POST))
        {
            $changes = $this->dataview->update($dataviewID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('dataview', $dataviewID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $callback = array('target' => 'parent', 'name' => 'locate', 'params' => array('dataview', 'browse', "type=view&viewID=$dataviewID"));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $dataview = $this->dataview->getByID($dataviewID);

        $this->view->title    = $this->lang->dataview->edit;
        $this->view->groups   = $this->loadModel('tree')->getOptionMenu(0, 'dataview');
        $this->view->dataview = $dataview;
        $this->display();
    }

    /**
     * Ajax query, get fields and result.
     *
     * @access public
     * @return void
     */
    public function ajaxQuery()
    {
        $this->loadModel('chart');
        $filters    = (isset($_POST['filters']) and is_array($this->post->filters)) ? $this->post->filters : array();
        $recPerPage = isset($_POST['recPerPage']) ? $this->post->recPerPage : 20;
        $pageID     = isset($_POST['pageID'])     ? $this->post->pageID     : 1;

        foreach($filters as $index => $filter)
        {
            if(empty($filter['default'])) continue;

            $filters[$index]['default'] = $this->loadModel('pivot')->processDateVar($filter['default']);
        }
        $querySQL = $this->chart->parseSqlVars($this->post->sql, $filters);

        // check origin sql error.
        try
        {
            $rows = $this->dbh->query($querySQL)->fetchAll();
        }
        catch(Exception $e)
        {

            return $this->send(array('result' => 'fail', 'message' => $e));
        }

        $columns      = $this->dataview->getColumns($querySQL);
        $columnFields = array();
        foreach($columns as $column => $type) $columnFields[$column] = $column;

        $tableAndFields = $this->chart->getTables($querySQL);
        $tables   = $tableAndFields['tables'];
        $fields   = $tableAndFields['fields'];
        $querySQL = $tableAndFields['sql'];

        $moduleNames = array();
        if($tables) $moduleNames = $this->dataview->getModuleNames($tables);

        list($fieldPairs, $relatedObject) = $this->dataview->mergeFields($columnFields, $fields, $moduleNames);

        $this->app->loadClass('sqlparser', true);
        $parser = new sqlparser($querySQL);

        if(count($parser->statements) == 0) return $this->send(array('result' => 'fail', 'message' => $this->lang->dataview->empty));
        if(count($parser->statements) > 1)  return $this->send(array('result' => 'fail', 'message' => $this->lang->dataview->onlyOne));

        $statement = $parser->statements[0];
        if($statement instanceof PhpMyAdmin\SqlParser\Statements\SelectStatement == false) return $this->send(array('result' => 'fail', 'message' => $this->lang->dataview->allowSelect));

        $sql = $statement->build();

        /* Limit 100. */
        if(!$statement->limit)
        {
            $statement->limit = new stdclass();
        }
        $statement->limit->offset   = $recPerPage * ($pageID - 1);
        $statement->limit->rowCount = $recPerPage;

        $limitSql = $statement->build();

        try
        {
            $rows      = $this->dbh->query($limitSql)->fetchAll();
            $rowsCount = $this->dbh->query($sql)->fetchAll();
        }
        catch(Exception $e)
        {
            return $this->send(array('result' => 'fail', 'message' => $e));
        }

        return $this->send(array('result' => 'success', 'rows' => $rows, 'fields' => $fieldPairs, 'columns' => $columns, 'filters' => $filters, 'lineCount' => count($rowsCount), 'columnCount' => count($fieldPairs), 'relatedObject' => $relatedObject, 'recPerPage' => $recPerPage, 'pageID' => $pageID));
    }

    /**
     * Ajax get type options.
     *
     * @param  string   $objectName
     * @access public
     * @return void
     */
    public function ajaxGetTypeOptions($objectName)
    {
        $options = $this->dataview->getTypeOptions($objectName);
        return $this->send(array('result' => 'success', 'options' => $options));
    }

    /**
     * Delete a dataview.
     *
     * @param  int    $dataviewID
     * @param  string $confirm  yes|no
     * @access public
     * @return int
     */
    public function delete($dataviewID, $confirm = 'no')
    {
        $dataview = $this->dataview->getByID($dataviewID);

        if($confirm == 'no')
        {
            $warningTip = $dataview->used ? $this->lang->dataview->error->warningDelete : $this->lang->dataview->confirmDelete;
            return print(js::confirm($warningTip, inlink('delete', "id=$dataviewID&confirm=yes")));
        }
        else
        {
            $this->dataview->delete(TABLE_DATAVIEW, $dataviewID);
            $this->dataview->deleteViewInDB($dataview->view);

            if(isonlybody()) return print(js::reload('parent.parent'));

            $locateLink = $this->session->dataviewList ? $this->session->dataviewList : inlink('browse', 'type=view');
            return print(js::locate($locateLink, 'parent'));
        }
    }

    /**
     * AJAX: Get field name.
     *
     * @access public
     * @return void
     */
    public function ajaxGetFieldName()
    {
        $fields        = $this->post->fields;
        $fieldSettings = $this->post->fieldSettings;

        foreach($fields as $field => $fieldName)
        {
            if(isset($fieldSettings[$field]))
            {
                if(empty($fieldSettings[$field]['object']) or empty($fieldSettings[$field]['field'])) continue;

                $relatedObject = $fieldSettings[$field]['object'];
                $relatedField  = $fieldSettings[$field]['field'];

                $this->app->loadLang($relatedObject);
                $fields[$field] = isset($this->lang->$relatedObject->$relatedField) ? $this->lang->$relatedObject->$relatedField : $field;
            }
        }

        return $this->send(array('result' => 'success', 'fields' => $fields));
    }
}
