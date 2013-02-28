<?php
/**
 * The search module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: zh-tw.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->search = new stdclass();
$lang->search->common        = '搜索';
$lang->search->reset         = '重置';
$lang->search->more          = '更多';
$lang->search->lite          = '簡潔';
$lang->search->saveQuery     = '保存';
$lang->search->myQuery       = '我的查詢';
$lang->search->group1        = '第一組';
$lang->search->group2        = '第二組';
$lang->search->buildForm     = '搜索表單';
$lang->search->buildQuery    = '執行搜索';
$lang->search->saveQuery     = '保存查詢';
$lang->search->deleteQuery   = '刪除查詢';
$lang->search->setQueryTitle = '請輸入查詢標題（保存之前請先查詢）：';
$lang->search->storyTitle    = '需求名稱';
$lang->search->taskTitle     = '任務名稱';
$lang->search->select        = '需求/任務篩選';
$lang->search->me            = '自己';

$lang->search->operators['=']          = '=';
$lang->search->operators['!=']         = '!=';
$lang->search->operators['>']          = '>';
$lang->search->operators['>=']         = '>=';
$lang->search->operators['<']          = '<';
$lang->search->operators['<=']         = '<=';
$lang->search->operators['include']    = '包含';
$lang->search->operators['between']    = '介於';
$lang->search->operators['notinclude'] = '不包含';
$lang->search->operators['belong']     = '從屬於';

$lang->search->andor['and']         = '並且';
$lang->search->andor['or']          = '或者';

$lang->search->null = '空';

$lang->userquery = new stdclass();
$lang->userquery->title     = '查詢標題';
$lang->userquery->myQueries = '我的查詢';
$lang->userquery->execut    = '執行';
$lang->userquery->delete    = '刪除';
