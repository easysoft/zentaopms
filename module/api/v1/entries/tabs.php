<?php
/**
 * The tabs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class tabsEntry extends baseEntry
{
    /**
     * Get tabs.
     *
     * @param  string    $moduleName    work|
     * @access public
     * @return string
     */
    public function get($moduleName)
    {
        $menus = array();
        if($moduleName == 'work')
        {
            $this->app->loadLang('my');
            $tabs = array('calendar', 'task', 'bug', 'story', 'issue', 'risk', 'myMeeting');

            foreach($tabs as $menuKey)
            {
                if(!isset($this->lang->my->$menuKey)) continue;
                if(!common::hasPriv('my', 'work')) continue;
                $label = $this->lang->my->$menuKey;
                if($menuKey == 'calendar') $label = $this->lang->my->calendarAction;

                $menu = new stdclass();
                $menu->code = $menuKey;
                $menu->name = $label;

                $menus[] = $menu;
            }
        }
        elseif($moduleName == 'product')
        {
            $this->app->loadLang('product');
            $tabs = array('story', 'plan', 'project', 'release', 'requirement', 'doc', 'view');

            foreach($tabs as $menuKey)
            {
                if($menuKey == 'requirement' and empty($this->config->URAndSR)) continue;
                if(isset($this->lang->product->menu->$menuKey))
                {
                    $menuName = $this->lang->product->menu->$menuKey;
                    if(!isset($menuName['link'])) continue;

                    list($label, $module, $method) = explode('|', $menuName['link']);
                    if(!common::hasPriv($module, $method)) continue;
                }
                else
                {
                    if(!common::hasPriv('product', $menuKey)) continue;
                }

                $label = zget($this->lang->product, $menuKey, '');
                if($menuKey == 'view')        $label = $this->lang->overview;
                if($menuKey == 'doc')         $label = $this->lang->doc->common;
                if($menuKey == 'project')     $label = $this->lang->project->common;
                if($menuKey == 'story')       $label = $this->lang->createObjects['story'];
                if($menuKey == 'requirement') $label = $this->lang->URCommon;

                $menu = new stdclass();
                $menu->code = $menuKey;
                $menu->name = $label;

                $menus[] = $menu;
            }
        }

        return $this->send(200, array('tabs' => $menus));
    }
}
