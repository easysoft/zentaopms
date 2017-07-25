<?php
/**
 * The action module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id: zh-cn.php 4955 2013-07-02 01:47:21Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->action->common     = '系统日志';
$lang->action->product    = $lang->productCommon;
$lang->action->project    = $lang->projectCommon;
$lang->action->objectType = '对象类型';
$lang->action->objectID   = '对象ID';
$lang->action->objectName = '对象名称';
$lang->action->actor      = '操作者';
$lang->action->action     = '动作';
$lang->action->actionID   = '记录ID';
$lang->action->date       = '日期';

$lang->action->trash       = '回收站';
$lang->action->undelete    = '还原';
$lang->action->hideOne     = '隐藏';
$lang->action->hideAll     = '全部隐藏';
$lang->action->editComment = '修改备注';

$lang->action->trashTips      = '提示：为了保证系统的完整性，禅道系统的删除都是标记删除。';
$lang->action->textDiff       = '文本格式';
$lang->action->original       = '原始格式';
$lang->action->confirmHideAll = '您确定要全部隐藏这些记录吗？';
$lang->action->needEdit       = '要还原%s的名称或代号已经存在，请编辑更改。';
$lang->action->historyEdit    = '历史记录编辑不能为空。';

$lang->action->dynamic = new stdclass();
$lang->action->dynamic->today      = '今天';
$lang->action->dynamic->yesterday  = '昨天';
$lang->action->dynamic->twoDaysAgo = '前天';
$lang->action->dynamic->thisWeek   = '本周';
$lang->action->dynamic->lastWeek   = '上周';
$lang->action->dynamic->thisMonth  = '本月';
$lang->action->dynamic->lastMonth  = '上月';
$lang->action->dynamic->all        = '所有';
$lang->action->dynamic->hidden     = '已隐藏';
$lang->action->dynamic->search     = '搜索';

$lang->action->objectTypes['product']     = $lang->productCommon;
$lang->action->objectTypes['story']       = '需求';
$lang->action->objectTypes['productplan'] = '计划';
$lang->action->objectTypes['release']     = '发布';
$lang->action->objectTypes['project']     = $lang->projectCommon;
$lang->action->objectTypes['task']        = '任务';
$lang->action->objectTypes['build']       = 'Build';
$lang->action->objectTypes['bug']         = 'Bug';
$lang->action->objectTypes['case']        = '用例';
$lang->action->objectTypes['caseresult']  = '用例结果';
$lang->action->objectTypes['stepresult']  = '用例步骤';
$lang->action->objectTypes['testtask']    = '测试任务';
$lang->action->objectTypes['user']        = '用户';
$lang->action->objectTypes['doc']         = '文档';
$lang->action->objectTypes['doclib']      = '文档库';
$lang->action->objectTypes['todo']        = '待办';
$lang->action->objectTypes['branch']      = '分支';
$lang->action->objectTypes['module']      = '模块';
$lang->action->objectTypes['testsuite']   = '套件';
$lang->action->objectTypes['caselib']     = '用例库';
$lang->action->objectTypes['testreport']  = '报告';

/* 用来描述操作历史记录。*/
$lang->action->desc = new stdclass();
$lang->action->desc->common         = '$date, <strong>$action</strong> by <strong>$actor</strong>。' . "\n";
$lang->action->desc->extra          = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>。' . "\n";
$lang->action->desc->opened         = '$date, 由 <strong>$actor</strong> 创建。' . "\n";
$lang->action->desc->created        = '$date, 由 <strong>$actor</strong> 创建。' . "\n";
$lang->action->desc->changed        = '$date, 由 <strong>$actor</strong> 变更。' . "\n";
$lang->action->desc->edited         = '$date, 由 <strong>$actor</strong> 编辑。' . "\n";
$lang->action->desc->assigned       = '$date, 由 <strong>$actor</strong> 指派给 <strong>$extra</strong>。' . "\n";
$lang->action->desc->closed         = '$date, 由 <strong>$actor</strong> 关闭。' . "\n";
$lang->action->desc->deleted        = '$date, 由 <strong>$actor</strong> 删除。' . "\n";
$lang->action->desc->deletedfile    = '$date, 由 <strong>$actor</strong> 删除了附件：<strong><i>$extra</i></strong>。' . "\n";
$lang->action->desc->editfile       = '$date, 由 <strong>$actor</strong> 编辑了附件：<strong><i>$extra</i></strong>。' . "\n";
$lang->action->desc->erased         = '$date, 由 <strong>$actor</strong> 删除。' . "\n";
$lang->action->desc->undeleted      = '$date, 由 <strong>$actor</strong> 还原。' . "\n";
$lang->action->desc->hidden         = '$date, 由 <strong>$actor</strong> 隐藏。' . "\n";
$lang->action->desc->commented      = '$date, 由 <strong>$actor</strong> 添加备注。' . "\n";
$lang->action->desc->activated      = '$date, 由 <strong>$actor</strong> 激活。' . "\n";
$lang->action->desc->blocked        = '$date, 由 <strong>$actor</strong> 阻塞。' . "\n";
$lang->action->desc->moved          = '$date, 由 <strong>$actor</strong> 移动，之前为 "$extra"。' . "\n";
$lang->action->desc->confirmed      = '$date, 由 <strong>$actor</strong> 确认需求变动，最新版本为<strong>#$extra</strong>。' . "\n";
$lang->action->desc->caseconfirmed  = '$date, 由 <strong>$actor</strong> 确认用例变动，最新版本为<strong>#$extra</strong>。' . "\n";
$lang->action->desc->bugconfirmed   = '$date, 由 <strong>$actor</strong> 确认Bug。' . "\n";
$lang->action->desc->frombug        = '$date, 由 <strong>$actor</strong> Bug转化而来，Bug编号为 <strong>$extra</strong>。';
$lang->action->desc->started        = '$date, 由 <strong>$actor</strong> 启动。' . "\n";
$lang->action->desc->restarted      = '$date, 由 <strong>$actor</strong> 继续。' . "\n";
$lang->action->desc->delayed        = '$date, 由 <strong>$actor</strong> 延期。' . "\n";
$lang->action->desc->suspended      = '$date, 由 <strong>$actor</strong> 挂起。' . "\n";
$lang->action->desc->recordestimate = '$date, 由 <strong>$actor</strong> 记录工时，消耗 <strong>$extra</strong> 小时。';
$lang->action->desc->editestimate   = '$date, 由 <strong>$actor</strong> 编辑工时。';
$lang->action->desc->deleteestimate = '$date, 由 <strong>$actor</strong> 删除工时。';
$lang->action->desc->canceled       = '$date, 由 <strong>$actor</strong> 取消。' . "\n";
$lang->action->desc->svncommited    = '$date, 由 <strong>$actor</strong> 提交代码，版本为<strong>#$extra</strong>。' . "\n";
$lang->action->desc->gitcommited    = '$date, 由 <strong>$actor</strong> 提交代码，版本为<strong>#$extra</strong>。' . "\n";
$lang->action->desc->finished       = '$date, 由 <strong>$actor</strong> 完成。' . "\n";
$lang->action->desc->paused         = '$date, 由 <strong>$actor</strong> 暂停。' . "\n";
$lang->action->desc->diff1          = '修改了 <strong><i>%s</i></strong>，旧值为 "%s"，新值为 "%s"。<br />' . "\n";
$lang->action->desc->diff2          = '修改了 <strong><i>%s</i></strong>，区别为：' . "\n" . "<blockquote>%s</blockquote>" . "\n<div class='hidden'>%s</div>";
$lang->action->desc->diff3          = '将文件名 %s 改为 %s 。' . "\n";

/* 关联用例和移除用例时的历史操作记录。*/
$lang->action->desc->linkrelatedcase   = '$date, 由 <strong>$actor</strong> 关联相关用例 <strong>$extra</strong>。' . "\n";
$lang->action->desc->unlinkrelatedcase = '$date, 由 <strong>$actor</strong> 移除相关用例 <strong>$extra</strong>。' . "\n";

/* 用来显示动态信息。*/
$lang->action->label = new stdclass();
$lang->action->label->created             = '创建';
$lang->action->label->opened              = '创建';
$lang->action->label->changed             = '变更了';
$lang->action->label->edited              = '编辑了';
$lang->action->label->assigned            = '指派了';
$lang->action->label->closed              = '关闭了';
$lang->action->label->deleted             = '删除了';
$lang->action->label->deletedfile         = '删除附件';
$lang->action->label->editfile            = '编辑附件';
$lang->action->label->erased              = '删除了';
$lang->action->label->undeleted           = '还原了';
$lang->action->label->hidden              = '隐藏了';
$lang->action->label->commented           = '评论了';
$lang->action->label->activated           = '激活了';
$lang->action->label->blocked             = '阻塞了';
$lang->action->label->resolved            = '解决了';
$lang->action->label->reviewed            = '评审了';
$lang->action->label->moved               = '移动了';
$lang->action->label->confirmed           = '确认了需求，';
$lang->action->label->bugconfirmed        = '确认了';
$lang->action->label->tostory             = '转需求';
$lang->action->label->frombug             = '转需求';
$lang->action->label->fromlib             = '从用例库导入';
$lang->action->label->totask              = '转任务';
$lang->action->label->svncommited         = '提交代码';
$lang->action->label->gitcommited         = '提交代码';
$lang->action->label->linked2plan         = '关联计划';
$lang->action->label->unlinkedfromplan    = '移除计划';
$lang->action->label->changestatus        = '修改状态';
$lang->action->label->marked              = '编辑了';
$lang->action->label->linked2project      = "关联{$lang->projectCommon}";
$lang->action->label->unlinkedfromproject = "移除{$lang->projectCommon}";
$lang->action->label->unlinkedfrombuild   = "移除版本";
$lang->action->label->linked2release      = "关联发布";
$lang->action->label->unlinkedfromrelease = "移除发布";
$lang->action->label->linkrelatedbug      = "关联了相关Bug";
$lang->action->label->unlinkrelatedbug    = "移除了相关Bug";
$lang->action->label->linkrelatedcase     = "关联了相关用例";
$lang->action->label->unlinkrelatedcase   = "移除了相关用例";
$lang->action->label->linkrelatedstory    = "关联了相关需求";
$lang->action->label->unlinkrelatedstory  = "移除了相关需求";
$lang->action->label->subdividestory      = "细分了需求";
$lang->action->label->unlinkchildstory    = "移除了细分需求";
$lang->action->label->started             = '开始了';
$lang->action->label->restarted           = '继续了';
$lang->action->label->recordestimate      = '记录了工时';
$lang->action->label->editestimate        = '编辑了工时';
$lang->action->label->canceled            = '取消了';
$lang->action->label->finished            = '完成了';
$lang->action->label->paused              = '暂停了';
$lang->action->label->delayed             = '延期';
$lang->action->label->suspended           = '挂起';
$lang->action->label->login               = '登录系统';
$lang->action->label->logout              = "退出登录";
$lang->action->label->deleteestimate      = "删除了工时";
$lang->action->label->linked2build        = "关联版本";
$lang->action->label->linked2bug          = "关联BUG";

/* 用来生成相应对象的链接。*/
$lang->action->label->product     = $lang->productCommon . '|product|view|productID=%s';
$lang->action->label->productplan = '计划|productplan|view|productID=%s';
$lang->action->label->release     = '发布|release|view|productID=%s';
$lang->action->label->story       = '需求|story|view|storyID=%s';
$lang->action->label->project     = "{$lang->projectCommon}|project|view|projectID=%s";
$lang->action->label->task        = '任务|task|view|taskID=%s';
$lang->action->label->build       = 'Build|build|view|buildID=%s';
$lang->action->label->bug         = 'Bug|bug|view|bugID=%s';
$lang->action->label->case        = '用例|testcase|view|caseID=%s';
$lang->action->label->testtask    = '测试任务|testtask|view|caseID=%s';
$lang->action->label->testsuite   = '测试套件|testsuite|view|suiteID=%s';
$lang->action->label->caselib     = '测试库|testsuite|libview|libID=%s';
$lang->action->label->todo        = 'todo|todo|view|todoID=%s';
$lang->action->label->doclib      = '文档库|doc|browse|libID=%s';
$lang->action->label->doc         = '文档|doc|view|docID=%s';
$lang->action->label->user        = '用户|user|view|account=%s';
$lang->action->label->testreport  = '报告|testreport|view|report=%s';
$lang->action->label->space       = ' ';

/* Object type. */
$lang->action->search->objectTypeList['']            = '';    
$lang->action->search->objectTypeList['product']     = $lang->productCommon;
$lang->action->search->objectTypeList['project']     = $lang->projectCommon;
$lang->action->search->objectTypeList['bug']         = 'Bug';
$lang->action->search->objectTypeList['case']        = '用例'; 
$lang->action->search->objectTypeList['caseresult']  = '用例结果';
$lang->action->search->objectTypeList['stepresult']  = '用例步骤';
$lang->action->search->objectTypeList['story']       = '需求';  
$lang->action->search->objectTypeList['task']        = '任务'; 
$lang->action->search->objectTypeList['testtask']    = '测试任务';     
$lang->action->search->objectTypeList['user']        = '用户'; 
$lang->action->search->objectTypeList['doc']         = '文档';
$lang->action->search->objectTypeList['doclib']      = '文档库';
$lang->action->search->objectTypeList['todo']        = '待办';
$lang->action->search->objectTypeList['build']       = 'Build';
$lang->action->search->objectTypeList['release']     = '发布';
$lang->action->search->objectTypeList['productplan'] = '计划';
$lang->action->search->objectTypeList['branch']      = '分支';
$lang->action->search->objectTypeList['testsuite']   = '套件';
$lang->action->search->objectTypeList['caselib']     = '公共库';
$lang->action->search->objectTypeList['testreport']  = '报告';

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
$lang->action->search->label['hidden']              = $lang->action->label->hidden;
$lang->action->search->label['commented']           = $lang->action->label->commented;
$lang->action->search->label['activated']           = $lang->action->label->activated;
$lang->action->search->label['blocked']             = $lang->action->label->blocked;
$lang->action->search->label['resolved']            = $lang->action->label->resolved;
$lang->action->search->label['reviewed']            = $lang->action->label->reviewed;
$lang->action->search->label['moved']               = $lang->action->label->moved;
$lang->action->search->label['confirmed']           = $lang->action->label->confirmed;
$lang->action->search->label['bugconfirmed']        = $lang->action->label->bugconfirmed;
$lang->action->search->label['tostory']             = $lang->action->label->tostory;
$lang->action->search->label['frombug']             = $lang->action->label->frombug;
$lang->action->search->label['totask']              = $lang->action->label->totask;
$lang->action->search->label['svncommited']         = $lang->action->label->svncommited;
$lang->action->search->label['gitcommited']         = $lang->action->label->gitcommited;
$lang->action->search->label['linked2plan']         = $lang->action->label->linked2plan;
$lang->action->search->label['unlinkedfromplan']    = $lang->action->label->unlinkedfromplan;
$lang->action->search->label['changestatus']        = $lang->action->label->changestatus;
$lang->action->search->label['marked']              = $lang->action->label->marked;
$lang->action->search->label['linked2project']      = $lang->action->label->linked2project;
$lang->action->search->label['unlinkedfromproject'] = $lang->action->label->unlinkedfromproject;
$lang->action->search->label['started']             = $lang->action->label->started;
$lang->action->search->label['restarted']           = $lang->action->label->restarted;
$lang->action->search->label['recordestimate']      = $lang->action->label->recordestimate;
$lang->action->search->label['editestimate']        = $lang->action->label->editestimate;
$lang->action->search->label['canceled']            = $lang->action->label->canceled;
$lang->action->search->label['finished']            = $lang->action->label->finished;
$lang->action->search->label['paused']              = $lang->action->label->paused;
$lang->action->search->label['login']               = $lang->action->label->login;
$lang->action->search->label['logout']              = $lang->action->label->logout;
