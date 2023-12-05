<?php
/**
 * The user module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: zh-cn.php 5053 2013-07-06 08:17:37Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->user->common           = '用户';
$lang->user->id               = '用户编号';
$lang->user->inside           = '内部人员';
$lang->user->outside          = '外部人员';
$lang->user->company          = '所属公司';
$lang->user->dept             = '部门';
$lang->user->account          = '用户名';
$lang->user->password         = '密码';
$lang->user->password1        = '密码';
$lang->user->password2        = '重复密码';
$lang->user->role             = '职位';
$lang->user->group            = '权限分组';
$lang->user->realname         = '姓名';
$lang->user->nickname         = '昵称';
$lang->user->commiter         = '源代码帐号';
$lang->user->birthyear        = '出生年';
$lang->user->gender           = '性别';
$lang->user->email            = '邮箱';
$lang->user->basicInfo        = '基本信息';
$lang->user->accountInfo      = '帐号信息';
$lang->user->verify           = '安全验证';
$lang->user->contactInfo      = '联系方式';
$lang->user->skype            = 'Skype';
$lang->user->qq               = 'QQ';
$lang->user->mobile           = '手机';
$lang->user->phone            = '电话';
$lang->user->weixin           = '微信';
$lang->user->dingding         = '钉钉';
$lang->user->slack            = 'Slack';
$lang->user->whatsapp         = 'WhatsApp';
$lang->user->address          = '通讯地址';
$lang->user->zipcode          = '邮编';
$lang->user->join             = '入职日期';
$lang->user->priv             = '权限';
$lang->user->visits           = '访问次数';
$lang->user->visions          = '界面类型';
$lang->user->ip               = '最后IP';
$lang->user->last             = '最后登录';
$lang->user->ranzhi           = 'ZDOO帐号';
$lang->user->ditto            = '同上';
$lang->user->originalPassword = '原密码';
$lang->user->newPassword      = '新密码';
$lang->user->verifyPassword   = '您的密码';
$lang->user->forgetPassword   = '忘记密码';
$lang->user->score            = '积分';
$lang->user->name             = '名称';
$lang->user->type             = '用户类型';
$lang->user->cropAvatar       = '剪切头像';
$lang->user->cropAvatarTip    = '拖拽选框来选择头像剪切范围';
$lang->user->cropImageTip     = '所使用的头像图片过小，建议图片大小至少为 48x48，当前图片大小为 %s';
$lang->user->captcha          = '验证码';
$lang->user->avatar           = '用户头像';
$lang->user->birthday         = '生日';
$lang->user->nature           = '性格特征';
$lang->user->analysis         = '影响分析';
$lang->user->strategy         = '应对策略';
$lang->user->fails            = '失败次数';
$lang->user->locked           = '锁住日期';
$lang->user->scoreLevel       = '积分等级';
$lang->user->clientStatus     = '登录状态';
$lang->user->clientLang       = '客户端语言';
$lang->user->programs         = '项目集';
$lang->user->products         = $lang->productCommon;
$lang->user->projects         = $lang->projectCommon;
$lang->user->sprints          = $lang->execution->common;
$lang->user->identity         = '身份';
$lang->user->switchVision     = '切换到 %s';
$lang->user->submit           = '提交';
$lang->user->resetPWD         = '重置密码';
$lang->user->resetPwdByAdmin  = '管理员重置密码';
$lang->user->resetPwdByMail   = '邮件重置密码';

$lang->user->abbr = new stdclass();
$lang->user->abbr->id        = '序号';
$lang->user->abbr->password2 = '请重复密码';
$lang->user->abbr->address   = '地址';
$lang->user->abbr->join      = '入职';

$lang->user->legendBasic        = '基本资料';
$lang->user->legendContribution = '个人贡献';

$lang->user->index         = "用户视图首页";
$lang->user->view          = "用户详情";
$lang->user->create        = "添加用户";
$lang->user->batchCreate   = "批量添加用户";
$lang->user->edit          = "编辑用户";
$lang->user->batchEdit     = "批量编辑";
$lang->user->unlock        = "解锁用户";
$lang->user->delete        = "删除用户";
$lang->user->unbind        = "解除ZDOO绑定";
$lang->user->login         = "用户登录";
$lang->user->bind          = "绑定已有账户";
$lang->user->oauthRegister = "注册新账号";
$lang->user->mobileLogin   = "手机访问";
$lang->user->editProfile   = "编辑档案";
$lang->user->deny          = "访问受限";
$lang->user->confirmDelete = "您确定删除该用户吗？";
$lang->user->confirmUnlock = "您确定解除该用户的锁定状态吗？";
$lang->user->confirmUnbind = "您确定解除该用户跟ZDOO的绑定吗？";
$lang->user->relogin       = "重新登录";
$lang->user->asGuest       = "游客访问";
$lang->user->goback        = "返回前一页";
$lang->user->deleted       = '(已删除)';
$lang->user->search        = '搜索';
$lang->user->else          = '其他';

$lang->user->saveTemplate          = '保存模板';
$lang->user->setPublic             = '设为公共模板';
$lang->user->deleteTemplate        = '删除模板';
$lang->user->setTemplateTitle      = '请输入模板标题';
$lang->user->applyTemplate         = '应用模板';
$lang->user->confirmDeleteTemplate = '您确认要删除该模板吗？';
$lang->user->setPublicTemplate     = '设为公共模板';
$lang->user->tplContentNotEmpty    = '模板内容不能为空!';
$lang->user->sendEmailSuccess      = '已发送一封邮件至您的邮箱，请注意查收。';
$lang->user->linkExpired           = '链接已过期，请重新申请。';

$lang->user->profile   = '档案';
$lang->user->project   = $lang->executionCommon;
$lang->user->execution = $lang->execution->common;
$lang->user->task      = '任务';
$lang->user->bug       = 'Bug';
$lang->user->test      = '测试';
$lang->user->testTask  = '测试单';
$lang->user->testCase  = '用例';
$lang->user->issue     = '问题';
$lang->user->risk      = '风险';
$lang->user->schedule  = '日程';
$lang->user->todo      = '待办';
$lang->user->story     = $lang->SRCommon;
$lang->user->dynamic   = '动态';

$lang->user->openedBy    = '由%s创建';
$lang->user->assignedTo  = '指派给%s';
$lang->user->finishedBy  = '由%s完成';
$lang->user->resolvedBy  = '由%s解决';
$lang->user->closedBy    = '由%s关闭';
$lang->user->reviewedBy  = '由%s评审';
$lang->user->canceledBy  = '由%s取消';

$lang->user->testTask2Him = '%s负责的';
$lang->user->case2Him     = '指派给%s';
$lang->user->caseByHim    = '由%s创建';

$lang->user->errorDeny    = "抱歉，您无权访问『<b>%s</b>』模块的『<b>%s</b>』功能。请联系管理员获取权限。请回到地盘或重新登录。";
$lang->user->errorView    = "抱歉，您无权访问『<b>%s</b>』视图。请联系管理员获取权限。请回到地盘或重新登录。";
$lang->user->loginFailed  = "登录失败，请检查您的用户名或密码是否填写正确。";
$lang->user->lockWarning  = "您还有%s次尝试机会。";
$lang->user->loginLocked  = "密码尝试次数太多，请联系管理员解锁，或%s分钟后重试。";
$lang->user->weakPassword = "您的密码强度小于系统设定。";
$lang->user->errorWeak    = "密码不能使用【%s】这些常用弱口令。";
$lang->user->errorCaptcha = "验证码不正确！";
$lang->user->loginExpired = '系统登录已过期，请重新登录：）';

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = '研发';
$lang->user->roleList['qa']     = '测试';
$lang->user->roleList['pm']     = '项目经理';
$lang->user->roleList['po']     = '产品经理';
$lang->user->roleList['td']     = '研发主管';
$lang->user->roleList['pd']     = '产品主管';
$lang->user->roleList['qd']     = '测试主管';
$lang->user->roleList['top']    = '高层管理';
$lang->user->roleList['others'] = '其他';

$lang->user->genderList['m'] = '男';
$lang->user->genderList['f'] = '女';

$lang->user->thirdPerson['m'] = '他';
$lang->user->thirdPerson['f'] = '她';

$lang->user->typeList['inside']  = $lang->user->inside;
$lang->user->typeList['outside'] = $lang->user->outside;

$lang->user->passwordStrengthList[0] = "<span style='color:red'>弱</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>中</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>强</span>";

$lang->user->statusList['active'] = '正常';
$lang->user->statusList['delete'] = '删除';

$lang->user->personalData['createdTodos']        = '创建的待办数';
$lang->user->personalData['createdRequirements'] = "创建的用需/史诗数";
$lang->user->personalData['createdStories']      = "创建的软需/故事数";
$lang->user->personalData['finishedTasks']       = '完成的任务数';
$lang->user->personalData['createdBugs']         = '提交的Bug数';
$lang->user->personalData['resolvedBugs']        = '解决的Bug数';
$lang->user->personalData['createdCases']        = '创建的用例数';
$lang->user->personalData['createdRisks']        = '创建的风险数';
$lang->user->personalData['resolvedRisks']       = '解决的风险数';
$lang->user->personalData['createdIssues']       = '创建的问题数';
$lang->user->personalData['resolvedIssues']      = '解决的问题数';
$lang->user->personalData['createdDocs']         = '创建的文档数';

$lang->user->keepLogin['on']   = '保持登录';
$lang->user->loginWithDemoUser = '使用demo帐号登录：';
$lang->user->scanToLogin       = '扫一扫登录';

$lang->user->tpl = new stdclass();
$lang->user->tpl->type    = '类型';
$lang->user->tpl->title   = '模板名';
$lang->user->tpl->content = '内容';
$lang->user->tpl->public  = '是否公开';

$lang->usertpl = new stdclass();
$lang->usertpl->title = '模板名称';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = '英文、数字和下划线的组合，三位以上';
$lang->user->placeholder->password1 = '六位以上';
$lang->user->placeholder->role      = '职位影响内容和用户列表的顺序。';
$lang->user->placeholder->group     = '分组决定用户的权限列表。';
$lang->user->placeholder->commiter  = '版本控制系统(subversion)中的帐号';
$lang->user->placeholder->verify    = '请输入您的系统登录密码';

$lang->user->placeholder->loginPassword = '请输入密码';
$lang->user->placeholder->loginAccount  = '请输入用户名';
$lang->user->placeholder->loginUrl      = '请输入禅道系统网址';
$lang->user->placeholder->email         = '请输入邮箱';

$lang->user->placeholder->passwordStrength[0] = '密码必须6位及以上。';
$lang->user->placeholder->passwordStrength[1] = '6位及以上，包含大小写字母，数字。';
$lang->user->placeholder->passwordStrength[2] = '10位及以上，包含大小写字母，数字，特殊字符。';

$lang->user->placeholder->passwordStrengthCheck[0] = '密码须6位及以上。';
$lang->user->placeholder->passwordStrengthCheck[1] = '密码必须6位及以上，且包含大小写字母、数字。';
$lang->user->placeholder->passwordStrengthCheck[2] = '密码必须10位及以上，且包含大小写字母、数字、特殊符号。';

$lang->user->error = new stdclass();
$lang->user->error->account        = '用户名应该为：三位以上的英文、数字或下划线的组合';
$lang->user->error->accountDupl    = '用户名已经存在';
$lang->user->error->realname       = '真实姓名必须填写';
$lang->user->error->visions        = '界面类型必须填写';
$lang->user->error->password       = '密码必须为六位及以上';
$lang->user->error->mail           = '地址不正确';
$lang->user->error->reserved       = '用户名已被系统预留';
$lang->user->error->weakPassword   = '密码强度小于系统设定。';
$lang->user->error->dangerPassword = "密码不能使用【%s】这些常用若口令。";

$lang->user->error->url              = "网址不正确，请联系管理员";
$lang->user->error->verify           = "用户名或密码错误";
$lang->user->error->verifyPassword   = "验证失败，请检查您的系统登录密码是否正确";
$lang->user->error->originalPassword = "原密码不正确";
$lang->user->error->companyEmpty     = "公司名称不能为空！";
$lang->user->error->noAccess         = "该人员和你不是同一部门，你无权访问该人员的工作信息。";
$lang->user->error->accountEmpty     = '用户名不能为空！';
$lang->user->error->emailEmpty       = '邮箱不能为空！';
$lang->user->error->noUser           = '用户不存在';
$lang->user->error->noEmail          = '该用户未绑定邮箱，请联系管理员以重置密码。';
$lang->user->error->errorEmail       = '用户名和邮箱不匹配，请重新输入。';
$lang->user->error->emailSetting     = '系统未配置发信邮箱，请联系管理员重置。';
$lang->user->error->sendMailFail     = '邮件发送失败，请重试！';
$lang->user->error->loginTimeoutTip  = '系统登录失败，请检查代理服务是否正常';

$lang->user->contactFieldList['phone']    = $lang->user->phone;
$lang->user->contactFieldList['mobile']   = $lang->user->mobile;
$lang->user->contactFieldList['qq']       = $lang->user->qq;
$lang->user->contactFieldList['dingding'] = $lang->user->dingding;
$lang->user->contactFieldList['weixin']   = $lang->user->weixin;
$lang->user->contactFieldList['skype']    = $lang->user->skype;
$lang->user->contactFieldList['slack']    = $lang->user->slack;
$lang->user->contactFieldList['whatsapp'] = $lang->user->whatsapp;

$lang->user->executionTypeList['stage']  = '阶段';
$lang->user->executionTypeList['sprint'] = $lang->iterationCommon;

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = '联系人';
$lang->user->contacts->listName = '列表名称';
$lang->user->contacts->userList = '用户列表';

$lang->usercontact = new stdclass;
$lang->usercontact->listName = '列表名称';
$lang->usercontact->userList = '用户列表';

$lang->user->contacts->manage        = '维护列表';
$lang->user->contacts->contactsList  = '已有列表';
$lang->user->contacts->selectedUsers = '选择用户';
$lang->user->contacts->selectList    = '选择列表';
$lang->user->contacts->createList    = '创建联系人';
$lang->user->contacts->noListYet     = '还没有创建任何列表，请先创建联系人列表。';
$lang->user->contacts->confirmDelete = '您确定要删除这个列表吗？';
$lang->user->contacts->or            = ' 或者 ';

$lang->user->resetFail        = "重置密码失败，检查用户名是否存在！";
$lang->user->resetSuccess     = "重置密码成功，请用新密码登录。";
$lang->user->noticeDelete     = "你确认要把“%s”从系统中删除吗？";
$lang->user->noticeHasDeleted = "该人员已经删除，如需查看，请到回收站还原后再查看。";
$lang->user->noticeResetFile  = "<h5>普通用户请联系管理员重置密码</h5>
    <h5>管理员请登录禅道所在的服务器，创建<span> %s </span>文件。</h5>
    <p>注意：</p>
    <ol>
    <li>文件内容为空。</li>
    <li>如果之前文件存在，删除之后重新创建。</li>
    </ol>";
$lang->user->notice4Safe = "警告：检测到一键安装包密码口令弱";
$lang->user->process4DIR = "检测到您可能在使用一键安装包环境，该环境中其他站点还在用简单密码，安全起见，如果不使用其他站点，请及时处理。将 %s 目录删除或改名。详情查看：<a href='https://www.zentao.net/book/zentaopmshelp/467.html' target='_blank'>https://www.zentao.net/book/zentaopmshelp/467.html</a>";
$lang->user->process4DB  = "检测到您可能在使用一键安装包环境，该环境中其他站点还在用简单密码，安全起见，如果不使用其他站点，请及时处理。请登录数据库，修改 %s 数据库的zt_user表的password字段。详情查看：<a href='https://www.zentao.net/book/zentaopmshelp/467.html' target='_blank'>https://www.zentao.net/book/zentaopmshelp/467.html</a>";
$lang->user->mkdirWin = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能创建临时目录，请确认目录<strong style='color:#ed980f'>%s</strong>是否存在并有操作权限。</div>
    <div>Can't create tmp directory, make sure the directory <strong style='color:#ed980f'>%s</strong> exists and has permission to operate.</div>
    </td></tr></table></body></html>
EOT;
$lang->user->mkdirLinux = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能创建临时目录，请确认目录<strong style='color:#ed980f'>%s</strong>是否存在并有操作权限。</div>
    <div style='margin-bottom:8px;'>命令为：<strong style='color:#ed980f'>chmod 777 -R %s</strong>。</div>
    <div>Can't create tmp directory, make sure the directory <strong style='color:#ed980f'>%s</strong> exists and has permission to operate.</div>
    <div style='margin-bottom:8px;'>Command: <strong style='color:#ed980f'>chmod 777 -R %s</strong>.</div>
    </td></tr></table></body></html>
EOT;

$lang->user->jumping = "<span id='time'>10</span>秒钟后页面将自动跳转登录页。 <a href='%s' id='redirect' class='btn primary'>立即跳转</a>";

$lang->user->zentaoapp = new stdclass();
$lang->user->zentaoapp->logout = '退出登录';

$lang->user->featureBar['todo']['all']             = '指派自己';
$lang->user->featureBar['todo']['before']          = '未完';
$lang->user->featureBar['todo']['future']          = '待定';
$lang->user->featureBar['todo']['thisWeek']        = '本周';
$lang->user->featureBar['todo']['thisMonth']       = '本月';
$lang->user->featureBar['todo']['thisYear']        = '本年';
$lang->user->featureBar['todo']['assignedToOther'] = '指派他人';
$lang->user->featureBar['todo']['cycle']           = '周期';

$lang->user->featureBar['dynamic']['all']       = '全部';
$lang->user->featureBar['dynamic']['today']     = '今天';
$lang->user->featureBar['dynamic']['yesterday'] = '昨天';
$lang->user->featureBar['dynamic']['thisWeek']  = '本周';
$lang->user->featureBar['dynamic']['lastWeek']  = '上周';
$lang->user->featureBar['dynamic']['thisMonth'] = '本月';
$lang->user->featureBar['dynamic']['lastMonth'] = '上月';
