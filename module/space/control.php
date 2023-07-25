<?php
/**
 * The control file of space module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   space
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class space extends control
{
    /**
     * Browse departments and users of a space.
     *
     * @param  int    $param
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
       @access public
     * @return void
     */
    public function browse($spaceID = null, $browseType = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 24, $pageID = 1)
    {
        $this->app->loadLang('instance');
        $this->loadModel('instance');
        $this->loadModel('store');

        $spaceType = $this->cookie->spaceType ? $this->cookie->spaceType : 'bycard';

        $space = null;
        if($spaceID)      $space = $this->space->getByID($spaceID);
        if(empty($space)) $space = $this->space->defaultSpace($this->app->user->account);

        $search = '';
        if(!empty($_POST))
        {
            $conditions = fixer::input('post')
                ->trim('search')
                ->setDefault('search', '')
                ->get();
            $search = $conditions->search;
        }

        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $instances = $this->space->getSpaceInstances($space->id, $browseType, $search, $pager);
        $pipelines = $this->loadModel('pipeline')->getList('', 'id_desc', $pager);
        foreach($pipelines as $pipeline)
        {
            $pipeline->createdAt = $pipeline->createdDate;
            $pipeline->appName   = ucfirst($pipeline->type);
            $pipeline->status    = '';
            $pipeline->type      = 'external';
        }
        $allInstances = array_merge($instances, $pipelines);

        list($order, $sort) = explode('_', $orderBy);
        $createdColumn = array_column((array)$allInstances, $order == 'id' ? 'createdAt' : $order);
        array_multisort($createdColumn, $sort == 'desc' ? SORT_DESC : SORT_ASC, $allInstances);
        $allInstances = array_slice($allInstances, 0, $recPerPage);

        $this->view->title        = $this->lang->space->common;
        $this->view->position[]   = $this->lang->space->common;
        $this->view->pager        = $pager;
        $this->view->browseType   = $browseType;
        $this->view->spaceType    = $spaceType;
        $this->view->instances    = $allInstances;
        $this->view->currentSpace = $space;
        $this->view->searchName   = $search;

        $this->display();
    }

    public function createApplication()
    {
        $this->app->loadLang('gitlab');

        $this->display();
    }
}
