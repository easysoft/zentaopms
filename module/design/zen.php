<?php
declare(strict_types=1);
/**
 * The control file of desgin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     desgin
 * @link        https://www.zentao.net
 */
class designZen extends control
{
    /**
     * 设置设计导航。
     * Set design menu.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type
     * @access public
     * @return void
     */
    public function setMenu(int $projectID, int $productID = 0, string $type = ''): void
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if(empty($project)) return;

        if(!empty($project) and (in_array($project->model,  array('waterfall', 'ipd')))) $typeList = 'typeList';
        if(!empty($project) and $project->model == 'waterfallplus') $typeList = 'plusTypeList';
        if(!isset($typeList)) return;

        /* Show custom design types. */
        $this->lang->waterfall->menu->design['subMenu'] = new stdclass();
        $this->lang->waterfall->menu->design['subMenu']->all = array('link' => "{$this->lang->all}|design|browse|projectID=%s&productID={$productID}&browseType=all", 'exclude' => $type == 'all' ? '' : 'design', 'alias' => $type == 'all' ? $this->app->rawMethod : '');
        $count = 1;
        foreach(array_filter($this->lang->design->{$typeList}) as $key => $value)
        {
            $key     = strtolower((string)$key);
            $exclude = $type == $key ? '' : 'design';
            $alias   = $type == $key ? $this->app->rawMethod : '';

            if($count <= 4) $this->lang->waterfall->menu->design['subMenu']->$key = array('link' => "{$value}|design|browse|projectID=%s&productID={$productID}&browseType={$key}", 'exclude' => $exclude, 'alias' => $alias);
            if($count == 5)
            {
                $this->lang->waterfall->menu->design['subMenu']->more = array('link' => "{$this->lang->design->more}|design|browse|projectID=%s&productID={$productID}&browseType={$key}", 'class' => 'dropdown dropdown-hover', 'exclude' => $exclude, 'alias' => $alias);
                $this->lang->waterfall->menu->design['subMenu']->more['dropMenu'] = new stdclass();
            }
            if($count >= 5) $this->lang->waterfall->menu->design['subMenu']->more['dropMenu']->$key = array('link' => "{$value}|design|browse|projectID=%s&productID={$productID}&browseType={$key}", 'exclude' => $exclude, 'alias' => $alias);

            $count ++;
        }

        if($this->config->edition == 'ipd') $this->lang->ipd->menu->design = $this->lang->waterfall->menu->design;
    }
}
