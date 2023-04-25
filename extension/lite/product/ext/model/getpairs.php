<?php
/**
 * Get product pairs for lite.
 *
 * @param  string       $mode
 * @param  string       $programID
 * @param  string|array $append
 * @param  string|int   $shadow         all | 0 | 1
 * @return array
 */
public function getPairs($mode = '', $programID = 0, $append = '', $shadow = 0)
{
    if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProductPairs();

    $projects        = $this->loadModel('project')->getPairsByProgram();
    $projectIdList   = array_keys($projects);

    $projectProducts = $this->dao->select('t1.branch, t1.plan, t2.*')
        ->from(TABLE_PROJECTPRODUCT)->alias('t1')
        ->leftJoin(TABLE_PRODUCT)->alias('t2')
        ->on('t1.product = t2.id')
        ->where('t2.deleted')->eq(0)
        ->andWhere('t1.project')->in($projectIdList)
        ->beginIF(!$this->app->user->admin and $this->config->vision == 'rnd')->andWhere('t2.id')->in($this->app->user->view->products)->fi()
        ->andWhere('t2.vision')->eq($this->config->vision)
        ->fetchPairs('id', 'id');

    $products = $this->dao->select("*,  IF(INSTR(' closed', status) < 2, 0, 1) AS isClosed")
        ->from(TABLE_PRODUCT)
        ->where(1)
        ->beginIF(strpos($mode, 'all') === false)->andWhere('deleted')->eq(0)->fi()
        ->beginIF($programID)->andWhere('program')->eq($programID)->fi()
        ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
        ->beginIF(!$this->app->user->admin and $this->config->vision == 'rnd')->andWhere('id')->in($this->app->user->view->products)->fi()
        ->beginIF($shadow !== 'all')->andWhere('shadow')->eq((int)$shadow)->fi()
        ->andWhere('vision')->eq($this->config->vision)
        ->andWhere('id')->in($projectProducts)
        ->fetchPairs('id', 'name');
    return $products;
}
