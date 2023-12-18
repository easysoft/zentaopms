<?php
/**
 * The control file of score module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id: control.php $
 * @link        https://www.zentao.net
 */
class score extends control
{
    /**
     * 创建积分。
     * javascript use : $.get(createLink('score', 'ajax', "method=method"));
     *
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajax(string $method = '')
    {
        $this->score->create('ajax', $method);
    }

    /**
     * Show score rule.
     *
     * @access public
     * @return void
     */
    public function rule()
    {
        $this->app->loadLang('my');

        $this->view->title = $this->lang->my->scoreRule;
        $this->view->rules = $this->score->buildRules();
        $this->display();
    }

    /**
     * Initialize score.
     *
     * @param int $lastID
     *
     * @access public
     * @return void
     */
    public function reset($lastID = 0)
    {
        if(helper::isAjaxRequest())
        {
            $result = $this->score->reset($lastID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($result['status'] == 'finish')
            {
                return $this->send(array('result' => 'finished', 'message' => $this->lang->score->resetFinish));
            }
            else
            {
                return $this->send(array('result' => 'unfinished', 'message' => $this->lang->score->resetLoading, 'lastID' => $result['lastID'], 'total' => $result['number']));
            }
        }
        $this->display();
    }
}
