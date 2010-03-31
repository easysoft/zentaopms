<?php
/**
 * The control file of search module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     search
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class search extends control
{
    public function buildForm($module, $searchFields, $fieldParams, $actionURL)
    {
        $this->search->initSession($module, $searchFields, $fieldParams);

        $this->assign('module',       $module);
        $this->assign('groupItems',   $this->config->search->groupItems);
        $this->assign('searchFields', $searchFields);
        $this->assign('actionURL',    $actionURL);
        $this->assign('fieldParams',  $this->search->setDefaultParams($searchFields, $fieldParams));
        $this->display();
    }

    public function buildQuery()
    {
        $this->search->buildQuery();
        die(js::locate($this->post->actionURL, 'parent'));
    }
}
