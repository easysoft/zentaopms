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
    $dataList = $this->dao->select('t1.*')->from(TABLE_STORY)->alias('t1')
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
        ->fetchAll();

    if($checkExists) return !empty($dataList);

    $stories = array();
    foreach($dataList as $data)
    {
        $story = new stdclass();
        $story->id        = $data->id;
        $story->title     = $data->title;
        $story->type      = 'story';
        $story->storyType = $data->type;
        $story->time      = $data->openedDate;
        $story->status    = $data->status;
        $story->product   = $data->product;
        $story->project   = 0;
        $story->parent    = $data->parent;
        $stories[$story->id] = $story;
    }

    $actions = $this->dao->select('objectID,`date`')->from(TABLE_ACTION)->where('objectType')->eq('story')->andWhere('objectID')->in(array_keys($stories))->andWhere('action')->eq('submitreview')->orderBy('`date`')->fetchPairs();
    foreach($actions as $storyID => $date) $stories[$storyID]->time = $date;
    return array_values($stories);
}
