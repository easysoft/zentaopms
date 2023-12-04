<?php
declare(strict_types=1);
class searchZen extends search
{
    /**
     * 设置列表 session，方便返回。
     * Set list in session, for come back search index page.
     *
     * @param  string    $uri
     * @param  string    $words
     * @param  string    $type
     * @access protected
     * @return void
     */
    protected function setSessionForIndex(string $uri, string $words, string|array $type): void
    {
        /* 设置列表 session. */
        /* Set session. */
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('docList',         $uri, 'doc');
        $this->session->set('productList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('programList',     $uri, 'program');
        $this->session->set('projectList',     $uri, 'project');
        $this->session->set('executionList',   $uri, 'execution');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('testtaskList',    $uri, 'qa');
        $this->session->set('todoList',        $uri, 'my');
        $this->session->set('effortList',      $uri, 'my');
        $this->session->set('reportList',      $uri, 'qa');
        $this->session->set('testsuiteList',   $uri, 'qa');
        $this->session->set('issueList',       $uri, 'project');
        $this->session->set('riskList',        $uri, 'project');
        $this->session->set('opportunityList', $uri, 'project');
        $this->session->set('trainplanList',   $uri, 'project');
        $this->session->set('caselibList',     $uri, 'qa');
        $this->session->set('searchIngWord',   $words);
        $this->session->set('searchIngType',   $type);

        if(strpos($this->server->http_referer, 'search') === false) $this->session->set('referer', $this->server->http_referer);
    }

    /**
     * 获取所有类型列表。
     * Get type list.
     *
     * @access protected
     * @return array
     */
    protected function getTypeList(): array
    {
        $typeCount = $this->search->getListCount();
        $typeList  = array('all' => $this->lang->search->modules['all']);
        foreach($typeCount as $objectType => $count)
        {
            if(!isset($this->lang->search->modules[$objectType])) continue;
            if($this->config->systemMode == 'light' && $objectType == 'program') continue;
            if(!helper::hasFeature('devops') && in_array($objectType, array('deploy', 'service', 'deploystep'))) continue;

            $typeList[$objectType] = $this->lang->search->modules[$objectType];
        }

        return $typeList;
    }

    /**
     * Set search form options.
     *
     * @param  array $fields
     * @param  array $fieldParams
     * @param  array $queries
     * @access public
     * @return object
     */
    protected function setOptions($fields, $fieldParams, $queries = array())
    {
        $options = new stdclass();
        $options->operators         = array();
        $options->fields            = array();
        $options->savedQueryTitle   = $this->lang->search->savedQuery;
        $options->andOr             = array();
        $options->groupName         = array($this->lang->search->group1, $this->lang->search->group2);
        $options->searchBtnText     = $this->lang->search->common;
        $options->resetBtnText      = $this->lang->search->reset;
        $options->saveSearchBtnText = $this->lang->search->saveCondition;
        foreach($this->lang->search->andor as $value => $title)
        {
            $andOr = new stdclass();
            $andOr->value = $value;
            $andOr->title = $title;

            $options->andOr[] = $andOr;
        }

        foreach($this->lang->search->operators as $value => $title)
        {
            $operator = new stdclass();
            $operator->value = $value;
            $operator->title = $title;

            $options->operators[] = $operator;
        }

        foreach($fieldParams as $field => $param)
        {
            $data = new stdclass();
            $data->label    = $fields[$field];
            $data->name     = $field;
            $data->control  = $param['control'];
            $data->operator = $param['operator'];

            if($field == 'id') $data->placeholder = $this->lang->search->queryTips;
            if(!empty($param['values']) and is_array($param['values'])) $data->values = $param['values'];

            $options->fields[] = $data;
        }

        $savedQuery = array();
        foreach($queries as $query)
        {
            if(empty($query->id)) continue;
            $savedQuery[] = $query;
        }

        if(!empty($savedQuery)) $options->savedQuery = $savedQuery;

        $options->formConfig  = new stdclass();
        $options->formConfig->method = 'post';
        $options->formConfig->action = helper::createLink('search', 'buildQuery');
        $options->formConfig->target = 'hiddenwin';

        $options->saveSearch = new stdclass();
        $options->saveSearch->text = $this->lang->search->saveCondition;

        return $options;
    }

}
