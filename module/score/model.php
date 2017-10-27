<?php
/**
 * The model file of score module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id: model.php $
 * @link        http://www.zentao.net
 */
class scoreModel extends model
{
    /**
     * get user score logs
     *
     * @param $pager
     *
     * @access public
     * @return array|bool
     */
    public function getScores($pager)
    {
        $scores = $this->dao->select('*')->from(TABLE_SCORE)->where('account')->eq($this->app->user->account)->orderBy('time_desc,id_desc')->page($pager)->fetchAll();
        return empty($scores) ? array() : $scores;
    }

    /**
     * add score logs
     *
     * @param string $model
     * @param string $method
     * @param string $param
     *
     * @access public
     * @return bool
     */
    public function score($model = '', $method = '', $param = '')
    {
        if(empty($this->config->score->model[$model][$method])) return true;
        $rule = $this->config->score->model[$model][$method];
        $desc = $this->lang->score->methods[$model][$method];
        switch($model)
        {
            case 'user':
                if($method == 'login') $desc = $this->lang->score->methods[$model][$method] . 'IP:' . helper::getRemoteIp();
                if($method == 'changePassword')
                {
                    if(!empty($rule['other'][$param])) $rule['score'] = $rule['score'] + $rule['other'][$param];
                    $desc = $this->lang->score->methods[$model][$method];
                }
                break;
            case 'doc':
                if($method == 'create') $desc = $this->lang->score->models[$model] . 'ID:' . $param;
                break;
            case 'todo':
                if($method == 'create') $desc = $this->lang->score->methods[$model][$method] . 'ID:' . $param;
                break;
            case 'story':
                $desc = $this->lang->score->methods[$model][$method] . 'ID:' . $param;
                if($method == 'close')
                {
                    $createUser = $this->dao->findById($param)->from(TABLE_STORY)->fetch();
                    if(!empty($createUser))
                    {
                        $newRule          = $rule;
                        $newRule['score'] = $rule['other']['createID'];
                        $this->saveScore($createUser->openedBy, $newRule, $model, $method, $desc);
                        unset($newRule);
                    }
                }
                break;
        }
        $this->saveScore($this->app->user->account, $rule, $model, $method, $desc);
    }

    /**
     * save user score
     *
     * @param string $account
     * @param array  $rule
     * @param string $model
     * @param string $method
     * @param string $desc
     *
     * @access private
     * @return bool
     */
    private function saveScore($account = '', $rule = array(), $model = '', $method = '', $desc = '')
    {
        if(!empty($rule['num']) || !empty($rule['time']))
        {
            if(empty($rule['time']))
            {
                $count = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)->andWhere('model')->eq($model)->andWhere('method')->eq($method)->count();
                if($count >= $rule['num']) return true;
            }
            else
            {
                $count = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)->andWhere('time')->between(date('Y-m-d 0:0:0'), date('Y-m-d 23:59:59'))->andWhere('model')->eq($model)->andWhere('method')->eq($method)->count();
                if($count >= $rule['num']) return true;
            }
        }

        $this->dao->begin();
        try
        {
            $user = $this->loadModel('user')->getById($account);

            $data          = new stdClass();
            $data->account = $account;
            $data->model   = $model;
            $data->method  = $method;
            $data->desc    = $desc;
            $data->before  = $user->score;
            $data->score   = $rule['score'];
            $data->after   = $user->score + $rule['score'];
            $data->time    = helper::now();
            $this->dao->insert(TABLE_SCORE)->data($data)->exec();
            $this->dao->query("UPDATE " . TABLE_USER . " SET `score`=`score` + " . $rule['score'] . ",`score_level`=`score_level` + " . $rule['score'] . " WHERE `account`='" . $account . "'");
            $this->dao->commit();
        }
        catch(ErrorException $e)
        {
            $this->dao->rollBack();
        }
    }

    /**
     * reset all user score and level score
     * @access public
     * @return void
     */
    public function resetScore()
    {

    }
}