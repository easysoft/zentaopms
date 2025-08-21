<?php
/**
 * 获取待审批的需求。
 * Get reviewing stories.
 *
 * @param  string     $orderBy
 * @param  bool       $checkExists
 * @access public
 * @return array|bool
 */
public function getReviewingStories(string $orderBy = 'id_desc', bool $checkExists = false, $type = 'story'): array|bool
{
    $this->app->loadLang($type);
    $stories = $this->dao->select("t1.id, t1.title, 'story' AS type, t1.openedDate AS time, t1.status, t1.product, 0 AS project, t1.parent")->from(TABLE_STORY)->alias('t1')
        ->leftJoin(TABLE_STORYREVIEW)->alias('t2')->on('t1.id = t2.story and t1.version = t2.version')
        ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t1.id = t3.story')
        ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t3.project = t4.id')
        ->where('t1.deleted')->eq(0)
        ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
        ->andWhere('t2.reviewer')->eq($this->app->user->account)
        ->andWhere('t2.result')->eq('')
        ->andWhere('t1.type')->eq($type)
        ->andWhere('t1.vision')->eq($this->config->vision)
        ->andWhere('t1.status')->eq('reviewing')
        ->andWhere('t4.deleted')->eq('0')
        ->orderBy($orderBy)
        ->beginIF($checkExists)->limit(1)->fi()
        ->fetchAll('id');

    if($checkExists)
    {
        return !empty($stories);
    }

    $actions = $this->dao->select('objectID,`date`')->from(TABLE_ACTION)->where('objectType')->eq('story')->andWhere('objectID')->in(array_keys($stories))->andWhere('action')->eq('submitreview')->orderBy('`date`')->fetchPairs();
    foreach($actions as $storyID => $date) $stories[$storyID]->time = $date;
    return array_values($stories);
}
