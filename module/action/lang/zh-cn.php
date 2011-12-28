<?php
/**
 * The action module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->action->common   = '系统日志';
$lang->action->trash    = '回收站';
$lang->action->undelete = '还原';

$lang->action->product    = '产品';
$lang->action->project    = '项目';
$lang->action->objectType = '对象类型';
$lang->action->objectID   = '对象ID';
$lang->action->objectName = '对象名称';
$lang->action->actor      = '操作者';
$lang->action->action     = '动作';
$lang->action->actionID   = '记录ID';
$lang->action->date       = '日期';
$lang->action->trashTips  = '提示：为了保证系统的完整性，禅道系统的删除都是标记删除。';

/* Object type. */
$lang->action->search->objectTypeList['']            = '';    
$lang->action->search->objectTypeList['product']     = '产品';    
$lang->action->search->objectTypeList['project']     = '项目';    
$lang->action->search->objectTypeList['bug']         = 'Bug';
$lang->action->search->objectTypeList['case']        = '用例'; 
$lang->action->search->objectTypeList['story']       = '需求';  
$lang->action->search->objectTypeList['task']        = '任务'; 
$lang->action->search->objectTypeList['testtask']    = '测试任务';     
$lang->action->search->objectTypeList['user']        = '用户'; 
$lang->action->search->objectTypeList['doc']         = '文档';
$lang->action->search->objectTypeList['doclib']      = '文档库';   
$lang->action->search->objectTypeList['todo']        = 'TODO'; 
$lang->action->search->objectTypeList['build']       = 'Build';  
$lang->action->search->objectTypeList['release']     = '发布';    
$lang->action->search->objectTypeList['productplan'] = '计划';        

$lang->action->dynamic->today     = '今天';
$lang->action->dynamic->yesterday = '昨天';
$lang->action->dynamic->twoDaysAgo= '前天';
$lang->action->dynamic->thisWeek  = '本周';
$lang->action->dynamic->lastWeek  = '上周';
$lang->action->dynamic->thisMonth = '本月';
$lang->action->dynamic->lastMonth = '上月';
$lang->action->dynamic->all       = '所有';
$lang->action->dynamic->search    = '搜索';

$lang->action->objectTypes['product']     = '产品';
$lang->action->objectTypes['story']       = '需求';
$lang->action->objectTypes['productplan'] = '产品计划';
$lang->action->objectTypes['release']     = '发布';
$lang->action->objectTypes['project']     = '项目';
$lang->action->objectTypes['task']        = '任务';
$lang->action->objectTypes['build']       = 'Build';
$lang->action->objectTypes['bug']         = 'Bug';
$lang->action->objectTypes['case']        = '用例';
$lang->action->objectTypes['testtask']    = '测试任务';
$lang->action->objectTypes['user']        = '用户';
$lang->action->objectTypes['doc']         = '文档';
$lang->action->objectTypes['doclib']      = '文档库';
$lang->action->objectTypes['todo']        = 'TODO';

/* 用来描述操作历史记录。*/
$lang->action->desc->common      = '$date, <strong>$action</strong> by <strong>$actor</strong>' . "\n";
$lang->action->desc->extra       = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>' . "\n";
$lang->action->desc->opened      = '$date, 由 <strong>$actor</strong> 创建。' . "\n";
$lang->action->desc->created     = '$date, 由 <strong>$actor</strong> 创建。' . "\n";
$lang->action->desc->changed     = '$date, 由 <strong>$actor</strong> 变更。' . "\n";
$lang->action->desc->edited      = '$date, 由 <strong>$actor</strong> 编辑。' . "\n";
$lang->action->desc->assigned    = '$date, 由 <strong>$actor</strong> 指派给 <strong>$extra</strong>' . "\n";
$lang->action->desc->closed      = '$date, 由 <strong>$actor</strong> 关闭。' . "\n";
$lang->action->desc->deleted     = '$date, 由 <strong>$actor</strong> 删除。' . "\n";
$lang->action->desc->deletedfile = '$date, 由 <strong>$actor</strong> 删除了附件：<strong><i>$extra</i></strong>' . "\n";
$lang->action->desc->editfile    = '$date, 由 <strong>$actor</strong> 编辑了附件：<strong><i>$extra</i></strong>' . "\n";
$lang->action->desc->erased      = '$date, 由 <strong>$actor</strong> 删除。' . "\n";
$lang->action->desc->undeleted   = '$date, 由 <strong>$actor</strong> 还原。' . "\n";
$lang->action->desc->commented   = '$date, 由 <strong>$actor</strong> 添加备注。' . "\n";
$lang->action->desc->activated   = '$date, 由 <strong>$actor</strong> 激活。' . "\n";
$lang->action->desc->moved       = '$date, 由 <strong>$actor</strong> 移动，之前为 "$extra"' . "\n";
$lang->action->desc->confirmed   = '$date, 由 <strong>$actor</strong> 确认需求变动，最新版本为<strong>#$extra</strong>' . "\n";
$lang->action->desc->bugconfirmed= '$date, 由 <strong>$actor</strong> 确认Bug' . "\n";
$lang->action->desc->frombug     = '$date, 由 <strong>$actor</strong> Bug转化而来，Bug编号为 <strong>$extra</strong>。';
$lang->action->desc->started     = '$date, 由 <strong>$actor</strong> 启动。' . "\n";
$lang->action->desc->canceled    = '$date, 由 <strong>$actor</strong> 取消。' . "\n";
$lang->action->desc->svncommited = '$date, 由 <strong>$actor</strong> 提交代码，版本为<strong>#$extra</strong>' . "\n";
$lang->action->desc->finished    = '$date, 由 <strong>$actor</strong> 完成。' . "\n";
$lang->action->desc->diff1       = '修改了 <strong><i>%s</i></strong>，旧值为 "%s"，新值为 "%s"。<br />' . "\n";
$lang->action->desc->diff2       = '修改了 <strong><i>%s</i></strong>，区别为：' . "\n" . '<blockquote>%s</blockquote>' . "\n";
$lang->action->desc->diff3       = '将文件名 %s 改为 %s ' . "\n";

/* 用来显示动态信息。*/
$lang->action->label->created             = '创建了';
$lang->action->label->opened              = '创建了';
$lang->action->label->changed             = '变更了';
$lang->action->label->edited              = '编辑了';
$lang->action->label->assigned            = '指派了';
$lang->action->label->closed              = '关闭了';
$lang->action->label->deleted             = '删除了';
$lang->action->label->deletedfile         = '删除附件';
$lang->action->label->editfile            = '编辑附件';
$lang->action->label->erased              = '删除了';
$lang->action->label->undeleted           = '还原了';
$lang->action->label->commented           = '评论了';
$lang->action->label->activated           = '激活了';
$lang->action->label->resolved            = '解决了';
$lang->action->label->reviewed            = '评审了';
$lang->action->label->moved               = '移动了';
$lang->action->label->confirmed           = '确认了需求，';
$lang->action->label->bugconfirmed        = '确认了';
$lang->action->label->tostory             = '转需求';
$lang->action->label->frombug             = '转需求';
$lang->action->label->totask              = '转任务';
$lang->action->label->svncommited         = '提交代码';
$lang->action->label->linked2plan         = '关联计划';
$lang->action->label->unlinkedfromplan    = '移除计划';
$lang->action->label->linked2project      = '关联项目';
$lang->action->label->unlinkedfromproject = '移除项目';
$lang->action->label->marked              = '编辑了';
$lang->action->label->started             = '开始了';
$lang->action->label->canceled            = '取消了';
$lang->action->label->finished            = '完成了';
$lang->action->label->login               = '登录系统';
$lang->action->label->logout              = "退出登录";

/* 用来生成相应对象的链接。*/
$lang->action->label->product     = '产品|product|view|productID=%s';
$lang->action->label->productplan = '计划|productplan|view|productID=%s';
$lang->action->label->release     = '发布|release|view|productID=%s';
$lang->action->label->story       = '需求|story|view|storyID=%s';
$lang->action->label->project     = '项目|project|view|projectID=%s';
$lang->action->label->task        = '任务|task|view|taskID=%s';
$lang->action->label->build       = 'Build|build|view|buildID=%s';
$lang->action->label->bug         = 'Bug|bug|view|bugID=%s';
$lang->action->label->case        = '用例|testcase|view|caseID=%s';
$lang->action->label->testtask    = '测试任务|testtask|view|caseID=%s';
$lang->action->label->todo        = 'todo|todo|view|todoID=%s';
$lang->action->label->doclib      = '文档库|doc|browse|libID=%s';
$lang->action->label->doc         = '文档|doc|view|docID=%s';
$lang->action->label->user        = '用户';

$lang->action->label->space     = '　';

/* 用来在动态显示中显示动作 */
$lang->action->search->label['']                    = '';
$lang->action->search->label['created']             = $lang->action->label->created;            
$lang->action->search->label['opened']              = $lang->action->label->opened;             
$lang->action->search->label['changed']             = $lang->action->label->changed;            
$lang->action->search->label['edited']              = $lang->action->label->edited;             
$lang->action->search->label['assigned']            = $lang->action->label->assigned;           
$lang->action->search->label['closed']              = $lang->action->label->closed;             
$lang->action->search->label['deleted']             = $lang->action->label->deleted;            
$lang->action->search->label['deletedfile']         = $lang->action->label->deletedfile;        
$lang->action->search->label['editfile']            = $lang->action->label->editfile;           
$lang->action->search->label['erased']              = $lang->action->label->erased;             
$lang->action->search->label['undeleted']           = $lang->action->label->undeleted;          
$lang->action->search->label['commented']           = $lang->action->label->commented;          
$lang->action->search->label['activated']           = $lang->action->label->activated;          
$lang->action->search->label['resolved']            = $lang->action->label->resolved;           
$lang->action->search->label['reviewed']            = $lang->action->label->reviewed;           
$lang->action->search->label['moved']               = $lang->action->label->moved;              
$lang->action->search->label['confirmed']           = $lang->action->label->confirmed;   
$lang->action->search->label['bugconfirmed']        = $lang->action->label->bugconfirmed;       
$lang->action->search->label['tostory']             = $lang->action->label->tostory;            
$lang->action->search->label['frombug']             = $lang->action->label->frombug;            
$lang->action->search->label['totask']              = $lang->action->label->totask;             
$lang->action->search->label['svncommited']         = $lang->action->label->svncommited;        
$lang->action->search->label['linked2plan']         = $lang->action->label->linked2plan;        
$lang->action->search->label['unlinkedfromplan']    = $lang->action->label->unlinkedfromplan;   
$lang->action->search->label['linked2project']      = $lang->action->label->linked2project;     
$lang->action->search->label['unlinkedfromproject'] = $lang->action->label->unlinkedfromproject;
$lang->action->search->label['marked']              = $lang->action->label->marked;             
$lang->action->search->label['started']             = $lang->action->label->started;            
$lang->action->search->label['canceled']            = $lang->action->label->canceled;           
$lang->action->search->label['finished']            = $lang->action->label->finished;           
$lang->action->search->label['login']               = $lang->action->label->login;              
$lang->action->search->label['logout']              = $lang->action->label->logout;             
