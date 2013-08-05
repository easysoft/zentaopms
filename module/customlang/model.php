<?php
/**
 * The model file of xxx module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     xxx
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class customlangModel extends model
{
    public function getLang($id, $lang, $object, $key, $value)
    {
        return $this->dao->select('*')->from(TABLE_CUSTOMLANG)
            ->where('1=1')
            ->beginIF($id)->andWhere('id')->eq($id)->fi()
            ->beginIF($lang)->andWhere('lang')->eq($lang)->fi()
            ->beginIF($object)->andWhere('object')->eq($object)->fi()
            ->beginIF($key)->andWhere('`key`')->eq($key)->fi()
            ->beginIF($value)->andWhere('value')->eq($value)->fi()
            ->fetch();
    }

    public function update($object, $field)
    {
        $item         = new stdClass();
        $item->lang   = $this->app->getClientLang();
        $item->object = $object;
        $item->key    = $field;
        $item->value  = serialize($this->post->$field);
        $oldItem = $this->getLang('', $item->lang, $item->object, $item->key);
        if($oldItem)
        {
            return $this->dao->update(TABLE_CUSTOMLANG)->set('value')->eq($item->value)->where('id')->eq($oldItem->id)->exec();
        }
        return $this->dao->insert(TABLE_CUSTOMLANG)->data($item)->exec();
    }
}

