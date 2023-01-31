<?php
/**
 * The admin module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: zh-cn.php 4767 2013-05-05 06:10:13Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->admin->index           = '后台管理首页';
$lang->admin->checkDB         = '检查数据库';
$lang->admin->sso             = 'ZDOO集成';
$lang->admin->ssoAction       = 'ZDOO集成';
$lang->admin->safeIndex       = '安全';
$lang->admin->checkWeak       = '弱口令检查';
$lang->admin->certifyMobile   = '认证手机';
$lang->admin->certifyEmail    = '认证邮箱';
$lang->admin->ztCompany       = '认证公司';
$lang->admin->captcha         = '验证码';
$lang->admin->getCaptcha      = '获取验证码';
$lang->admin->register        = '登记';
$lang->admin->resetPWDSetting = '重置密码设置';
$lang->admin->tableEngine     = '表引擎';
$lang->admin->setModuleIndex  = '系统功能配置';

$lang->admin->api                  = '接口';
$lang->admin->log                  = '日志';
$lang->admin->setting              = '设置';
$lang->admin->pluginRecommendation = '插件推荐';
$lang->admin->zentaoInfo           = '禅道信息';
$lang->admin->officialAccount      = '官方公众号';
$lang->admin->openClass            = '公开课';
$lang->admin->days                 = '日志保存天数';
$lang->admin->resetPWDByMail       = '通过邮箱重置密码';

$lang->admin->changeEngine   = "更换到InnoDB";
$lang->admin->changingTable  = '正在更换数据表%s引擎...';
$lang->admin->changeSuccess  = '已经更换数据表%s引擎为InnoDB。';
$lang->admin->changeFail     = "更换数据表%s引擎失败，原因：<span class='text-red'>%s</span>。";
$lang->admin->errorInnodb    = '您当前的数据库不支持使用InnoDB数据表引擎。';
$lang->admin->changeFinished = "更换数据库引擎完毕。";
$lang->admin->engineInfo     = "表<strong>%s</strong>的引擎是<strong>%s</strong>。";
$lang->admin->engineSummary['hasMyISAM'] = "有%s个表不是InnoDB引擎";
$lang->admin->engineSummary['allInnoDB'] = "所有的表都是InnoDB引擎了";

$lang->admin->info = new stdclass();
$lang->admin->info->version = '当前系统的版本是%s，';
$lang->admin->info->links   = '您可以访问以下链接：';
$lang->admin->info->account = "您的禅道社区账户为%s。";
$lang->admin->info->log     = '超出存天数的日志会被删除，需要开启计划任务。';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "友情提示：您还未在禅道社区(www.zentao.net)登记，%s进行登记，以及时获得禅道最新信息。";
$lang->admin->notice->ignore   = "不再提示";
$lang->admin->notice->int      = "『%s』应当是正整数。";

$lang->admin->registerNotice = new stdclass();
$lang->admin->registerNotice->common     = '注册新帐号绑定';
$lang->admin->registerNotice->caption    = '禅道社区登记';
$lang->admin->registerNotice->click      = '点击此处';
$lang->admin->registerNotice->lblAccount = '请设置您的用户名，英文字母和数字的组合，三位以上。';
$lang->admin->registerNotice->lblPasswd  = '请设置您的密码。数字和字母的组合，六位以上。';
$lang->admin->registerNotice->submit     = '登记';
$lang->admin->registerNotice->bind       = "绑定已有帐号";
$lang->admin->registerNotice->success    = "登记账户成功";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = '关联社区帐号';
$lang->admin->bind->success = "关联账户成功";

$lang->admin->setModule = new stdclass();
$lang->admin->setModule->module         = '功能点';
$lang->admin->setModule->optional       = '可选功能';
$lang->admin->setModule->opened         = '已开启';
$lang->admin->setModule->closed         = '已关闭';

$lang->admin->setModule->product        = '产品';
$lang->admin->setModule->scrum          = '敏捷项目';
$lang->admin->setModule->waterfall      = '瀑布项目';
$lang->admin->setModule->assetlib       = '资产库';
$lang->admin->setModule->other          = '通用功能';

$lang->admin->setModule->repo           = '代码';
$lang->admin->setModule->issue          = '问题';
$lang->admin->setModule->risk           = '风险';
$lang->admin->setModule->opportunity    = '机会';
$lang->admin->setModule->process        = '过程';
$lang->admin->setModule->measrecord     = '度量';
$lang->admin->setModule->auditplan      = 'QA';
$lang->admin->setModule->meeting        = '会议';
$lang->admin->setModule->roadmap        = '路线图';
$lang->admin->setModule->track          = '矩阵';
$lang->admin->setModule->UR             = '用户需求';
$lang->admin->setModule->researchplan   = '调研';
$lang->admin->setModule->gapanalysis    = '培训';
$lang->admin->setModule->storylib       = '需求库';
$lang->admin->setModule->caselib        = '用例库';
$lang->admin->setModule->issuelib       = '问题库';
$lang->admin->setModule->risklib        = '风险库';
$lang->admin->setModule->opportunitylib = '机会库';
$lang->admin->setModule->practicelib    = '最佳实践库';
$lang->admin->setModule->componentlib   = '组件库';
$lang->admin->setModule->devops         = 'DevOps';
$lang->admin->setModule->kanban         = '通用看板';
$lang->admin->setModule->OA             = '办公';
$lang->admin->setModule->deploy         = '运维';
$lang->admin->setModule->traincourse    = '学堂';

$lang->admin->safe = new stdclass();
$lang->admin->safe->common                   = '安全策略';
$lang->admin->safe->set                      = '密码安全设置';
$lang->admin->safe->password                 = '密码安全';
$lang->admin->safe->weak                     = '常用弱口令';
$lang->admin->safe->reason                   = '类型';
$lang->admin->safe->checkWeak                = '弱口令扫描';
$lang->admin->safe->changeWeak               = '修改弱口令密码';
$lang->admin->safe->loginCaptcha             = '登录使用验证码';
$lang->admin->safe->modifyPasswordFirstLogin = '首次登录修改密码';
$lang->admin->safe->passwordStrengthWeak     = '密码强度小于系统设置';

$lang->admin->safe->modeList[0] = '不检查';
$lang->admin->safe->modeList[1] = '中';
$lang->admin->safe->modeList[2] = '强';

$lang->admin->safe->modeRuleList[1] = '6位及以上，包含大小写字母，数字。';
$lang->admin->safe->modeRuleList[2] = '10位及以上，包含字母，数字，特殊字符。';

$lang->admin->safe->reasonList['weak']     = '常用弱口令';
$lang->admin->safe->reasonList['account']  = '与帐号相同';
$lang->admin->safe->reasonList['mobile']   = '与手机相同';
$lang->admin->safe->reasonList['phone']    = '与电话相同';
$lang->admin->safe->reasonList['birthday'] = '与生日相同';

$lang->admin->safe->modifyPasswordList[1] = '必须修改';
$lang->admin->safe->modifyPasswordList[0] = '不强制';

$lang->admin->safe->loginCaptchaList[1] = '是';
$lang->admin->safe->loginCaptchaList[0] = '否';

$lang->admin->safe->resetPWDList[1] = '开启';
$lang->admin->safe->resetPWDList[0] = '关闭';

$lang->admin->safe->noticeMode     = '系统会在创建和修改用户、修改密码的时候检查用户口令。';
$lang->admin->safe->noticeWeakMode = '系统会在登录、创建和修改用户、修改密码的时候检查用户口令。';
$lang->admin->safe->noticeStrong   = '密码长度越长，含有大写字母或数字或特殊符号越多，密码字母越不重复，安全度越强！';
$lang->admin->safe->noticeGd       = '系统检测到您的服务器未安装GD模块，无法使用验证码功能，请安装后使用。';

$lang->admin->menuList = new stdclass();
$lang->admin->menuList->setting['name']  = '系统设置';
$lang->admin->menuList->setting['desc']  = '';
$lang->admin->menuList->setting['order'] = 1;

$lang->admin->menuList->user['name']  = '人员管理';
$lang->admin->menuList->user['desc']  = '';
$lang->admin->menuList->user['order'] = 2;

$lang->admin->menuList->switch['name']  = '功能开关';
$lang->admin->menuList->switch['desc']  = '';
$lang->admin->menuList->switch['order'] = 3;

$lang->admin->menuList->model['name']  = '模型配置';
$lang->admin->menuList->model['desc']  = '';
$lang->admin->menuList->model['order'] = 4;

$lang->admin->menuList->feature['name']  = '功能配置';
$lang->admin->menuList->feature['desc']  = '';
$lang->admin->menuList->feature['order'] = 5;

$lang->admin->menuList->template['name']  = '文档模板';
$lang->admin->menuList->template['desc']  = '';
$lang->admin->menuList->template['order'] = 6;

$lang->admin->menuList->message['name']  = '通知设置';
$lang->admin->menuList->message['desc']  = '';
$lang->admin->menuList->message['order'] = 7;

$lang->admin->menuList->extension['name']  = '插件管理';
$lang->admin->menuList->extension['desc']  = '';
$lang->admin->menuList->extension['link']  = 'extension|browse';
$lang->admin->menuList->extension['order'] = 8;

$lang->admin->menuList->dev['name']  = '二次开发';
$lang->admin->menuList->dev['desc']  = '';
$lang->admin->menuList->dev['order'] = 9;

$lang->admin->menuList->convert['name']  = '数据导入';
$lang->admin->menuList->convert['desc']  = '';
$lang->admin->menuList->convert['link']  = 'convert|convertjira';
$lang->admin->menuList->convert['order'] = 10;

$lang->admin->menuList->setting['subMenu']['mode']        = array('link' => "模式|custom|mode|");
$lang->admin->menuList->setting['subMenu']['backup']      = array('link' => "备份|backup|index|", 'subModule' => 'backup');
$lang->admin->menuList->setting['subMenu']['trash']       = array('link' => "回收站|action|trash|", 'subModule' => 'action');
$lang->admin->menuList->setting['subMenu']['safe']        = array('link' => "安全|admin|safe|", 'alias' => 'checkweak,resetpwdsetting');
$lang->admin->menuList->setting['subMenu']['timezone']    = array('link' => "时区|custom|timezone|");
$lang->admin->menuList->setting['subMenu']['buildindex']  = array('link' => "重建索引|search|buildindex|", 'subModule' => 'search');
$lang->admin->menuList->setting['subMenu']['tableengine'] = array('link' => "表引擎|admin|tableengine|");

$lang->admin->menuList->setting['menuOrder']['5']  = 'mode';
$lang->admin->menuList->setting['menuOrder']['10'] = 'backup';
$lang->admin->menuList->setting['menuOrder']['15'] = 'trash';
$lang->admin->menuList->setting['menuOrder']['30'] = 'safe';
$lang->admin->menuList->setting['menuOrder']['35'] = 'timezone';
$lang->admin->menuList->setting['menuOrder']['40'] = 'buildindex';
$lang->admin->menuList->setting['menuOrder']['45'] = 'tableengine';

$lang->admin->menuList->setting['dividerMenu'] = ',safe,';

$lang->admin->menuList->user['subMenu']['dept']  = array('link' => "部门|dept|browse|", 'subModule' => 'dept');
$lang->admin->menuList->user['subMenu']['user']  = array('link' => "用户|company|browse|", 'subModule' => 'company');
$lang->admin->menuList->user['subMenu']['group'] = array('link' => "权限|group|browse|", 'subModule' => 'group');

$lang->admin->menuList->user['menuOrder']['5']  = 'dept';
$lang->admin->menuList->user['menuOrder']['10'] = 'user';
$lang->admin->menuList->user['menuOrder']['15'] = 'group';

$lang->admin->menuList->switch['subMenu']['setmodule'] = array('link' => "功能设置|admin|setmodule|");

$lang->admin->menuList->switch['menuOrder']['5'] = 'setmodule';

$lang->admin->menuList->model['subMenu']['common']    = array('link' => "通用|custom|required|module=project", 'subModule' => 'custom');
$lang->admin->menuList->model['subMenu']['scrum']     = array('link' => "敏捷模型|auditcl|scrumbrowse|", 'subModule' => 'auditcl');
$lang->admin->menuList->model['subMenu']['waterfall'] = array('link' => "瀑布模型|stage|settype|", 'subModule' => 'stage');

$lang->admin->menuList->model['menuOrder']['5']  = 'common';
$lang->admin->menuList->model['menuOrder']['10'] = 'scrum';
$lang->admin->menuList->model['menuOrder']['15'] = 'waterfall';

$lang->admin->menuList->feature['subMenu']['my']          = array('link' => "地盘|custom|set|module=todo");
$lang->admin->menuList->feature['subMenu']['product']     = array('link' => "{$lang->productCommon}|custom|product|");
$lang->admin->menuList->feature['subMenu']['execution']   = array('link' => "{$lang->execution->common}|custom|execution|");
$lang->admin->menuList->feature['subMenu']['qa']          = array('link' => "测试|custom|required|module=bug");
$lang->admin->menuList->feature['subMenu']['kanban']      = array('link' => "看板|custom|kanban|");
$lang->admin->menuList->feature['subMenu']['doc']         = array('link' => "文档|custom|required|module=doc");
$lang->admin->menuList->feature['subMenu']['feedback']    = array('link' => "反馈|custom|set|module=feedback");
$lang->admin->menuList->feature['subMenu']['approval']    = array('link' => "审批|approvalflow|browse|", 'subModule' => 'approvalflow');
$lang->admin->menuList->feature['subMenu']['measure']     = array('link' => "度量|measurement|settips|", 'subModule' => 'measurement');
$lang->admin->menuList->feature['subMenu']['user']        = array('link' => "用户|custom|set|module=user");
$lang->admin->menuList->feature['subMenu']['meetingroom'] = array('link' => "会议室|meetingroom|browse|", 'subModule' => 'meetingroom');

$lang->admin->menuList->feature['menuOrder']['5']  = 'my';
$lang->admin->menuList->feature['menuOrder']['10'] = 'product';
$lang->admin->menuList->feature['menuOrder']['15'] = 'execution';
$lang->admin->menuList->feature['menuOrder']['20'] = 'qa';
$lang->admin->menuList->feature['menuOrder']['25'] = 'kanban';
$lang->admin->menuList->feature['menuOrder']['30'] = 'doc';
$lang->admin->menuList->feature['menuOrder']['35'] = 'feedback';
$lang->admin->menuList->feature['menuOrder']['40'] = 'approval';
$lang->admin->menuList->feature['menuOrder']['45'] = 'measure';
$lang->admin->menuList->feature['menuOrder']['50'] = 'user';
$lang->admin->menuList->feature['menuOrder']['55'] = 'meetingroom';

$lang->admin->menuList->feature['dividerMenu'] = ',user,';

$lang->admin->menuList->template['subMenu']['type']     = array('link' => "模版类型|custom|set|module=baseline&field=objectList", 'subModule' => 'custom');
$lang->admin->menuList->template['subMenu']['template'] = array('link' => "文档模版|baseline|template|", 'subModule' => 'baseline');
$lang->admin->menuList->template['subMenu']['catalog']  = array('link' => "文档目录|baseline|catalog|", 'subModule' => 'baseline');

$lang->admin->menuList->template['menuOrder']['5']  = 'type';
$lang->admin->menuList->template['menuOrder']['10'] = 'template';
$lang->admin->menuList->template['menuOrder']['15'] = 'catalog';

$lang->admin->menuList->message['subMenu']['mail']    = array('link' => "邮件|mail|edit|", 'subModule' => 'mail');
$lang->admin->menuList->message['subMenu']['webhook'] = array('link' => "Webhook|webhook|browse|", 'subModule' => 'webhook');
$lang->admin->menuList->message['subMenu']['sms']     = array('link' => "短信|sms|index|", 'subModule' => 'sms');
$lang->admin->menuList->message['subMenu']['message'] = array('link' => "浏览器|message|browser|");
$lang->admin->menuList->message['subMenu']['setting'] = array('link' => "设置|message|setting|");

$lang->admin->menuList->message['menuOrder']['5']  = 'mail';
$lang->admin->menuList->message['menuOrder']['10'] = 'webhook';
$lang->admin->menuList->message['menuOrder']['15'] = 'sms';
$lang->admin->menuList->message['menuOrder']['20'] = 'message';
$lang->admin->menuList->message['menuOrder']['25'] = 'setting';

$lang->admin->menuList->dev['subMenu']['api']    = array('link' => "API|dev|api|");
$lang->admin->menuList->dev['subMenu']['db']     = array('link' => "数据库|dev|db|");
$lang->admin->menuList->dev['subMenu']['editor'] = array('link' => "编辑器|dev|editor|");
$lang->admin->menuList->dev['subMenu']['entry']  = array('link' => "应用|entry|browse|", 'subModule' => 'entry');

$lang->admin->menuList->dev['menuOrder']['5']  = 'api';
$lang->admin->menuList->dev['menuOrder']['10'] = 'db';
$lang->admin->menuList->dev['menuOrder']['15'] = 'editor';
$lang->admin->menuList->dev['menuOrder']['20'] = 'entry';
