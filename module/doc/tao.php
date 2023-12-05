<?php
declare(strict_types=1);
/**
 * The tao file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
class docTao extends docModel
{
    /**
     * 获取编辑过的文档ID列表。
     * Get the list of doc id list that have been edited.
     *
     * @access protected
     * @return array
     */
    protected function getEditedDocIdList(): array
    {
        return $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('doc')
            ->andWhere('action')->in('edited')
            ->andWhere('actor')->eq($this->app->user->account)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetchPairs();
    }

    /**
     * 获取已排序的执行数据。
     * Get ordered executions.
     *
     * @param  int       $append
     * @access protected
     * @return array
     */
    protected function getOrderedExecutions(int $append = 0): array
    {
        $myObjects    = $normalObjects = $closedObjects = array();
        $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchPairs('id');
        $executions   = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('type')->in('sprint,stage')
            ->andWhere('multiple')->eq('1')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($append)->orWhere('id')->eq($append)->fi()
            ->orderBy('order_asc')
            ->fetchAll('id');

        foreach($executions as $id => $execution)
        {
            if($execution->type == 'stage' && $execution->grade != 1)
            {
                $parentExecutions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($execution->path, ','))->andWhere('type')->in('stage,sprint')->orderBy('grade')->fetchPairs();
                $execution->name  = implode('/', $parentExecutions);
            }
            $execution->name = zget($projectPairs, $execution->project) . ' / ' . $execution->name;

            if($execution->status != 'done' && $execution->status != 'closed' && $execution->PM == $this->app->user->account)
            {
                $myObjects[$id] = $execution->name;
            }
            elseif($execution->status != 'done' && $execution->status != 'closed' && !($execution->PM == $this->app->user->account))
            {
                $normalObjects[$id] = $execution->name;
            }
            elseif(in_array($execution->status, array('done', 'closed')))
            {
                $closedObjects[$id] = $execution->name;
            }
        }

        return array($myObjects, $normalObjects, $closedObjects);
    }
}
