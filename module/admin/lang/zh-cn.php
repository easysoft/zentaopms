<?php
/**
 * The admin module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: zh-cn.php 4767 2013-05-05 06:10:13Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->admin->index           = '后台管理首页';
$lang->admin->sso             = 'ZDOO集成';
$lang->admin->ssoAction       = 'ZDOO集成';
$lang->admin->safeIndex       = '密码安全设置';
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

$lang->admin->mon              = '月';
$lang->admin->day              = '天';
$lang->admin->updateDynamics   = '更新动态';
$lang->admin->updatePatch      = '补丁更新';
$lang->admin->upgradeRecommend = '推荐升级';
$lang->admin->zentaoUsed       = '您已使用禅道';

$lang->admin->api                  = '接口';
$lang->admin->log                  = '日志';
$lang->admin->setting              = '设置';
$lang->admin->setFlow              = '流程配置';
$lang->admin->pluginRecommendation = '插件推荐';
$lang->admin->zentaoInfo           = '禅道信息';
$lang->admin->officialAccount      = '官方公众号';
$lang->admin->publicClass          = '公开课';
$lang->admin->days                 = '日志保存天数';
$lang->admin->resetPWDByMail       = '通过邮箱重置密码';
$lang->admin->followUs             = '扫码关注公众号';
$lang->admin->followUsContent      = '随时查看禅道动态、活动信息、也可获取帮助支持';

$lang->admin->changeEngine               = "更换到InnoDB";
$lang->admin->changingTable              = '正在更换数据表%s引擎...';
$lang->admin->changeSuccess              = '已经更换数据表%s引擎为InnoDB。';
$lang->admin->changeFail                 = "更换数据表%s引擎失败，原因：<span class='text-red'>%s</span>。";
$lang->admin->errorInnodb                = '您当前的数据库不支持使用InnoDB数据表引擎。';
$lang->admin->changeFinished             = "更换数据库引擎完毕。";
$lang->admin->engineInfo                 = "表<strong>%s</strong>的引擎是<strong>%s</strong>。";
$lang->admin->engineSummary['hasMyISAM'] = "有%s个表不是InnoDB引擎";
$lang->admin->engineSummary['allInnoDB'] = "所有的表都是InnoDB引擎了";

$lang->admin->info = new stdclass();
$lang->admin->info->version = '当前系统的版本是%s，';
$lang->admin->info->links   = '您可以访问以下链接：';
$lang->admin->info->account = "您的禅道社区账户为%s。";
$lang->admin->info->log     = '超出存天数的日志会被删除，需要开启计划任务。';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register                = "可%s禅道社区 www.zentao.net，及时获得禅道最新信息。";
$lang->admin->notice->ignore                  = "不再提示";
$lang->admin->notice->int                     = "『%s』应当是正整数。";
$lang->admin->notice->confirmDisableStoryType = "‘{type}’功能关闭后，系统会将项目和执行内已经关联的相应‘{type}’全部移除, 操作不可逆";
$lang->admin->notice->openDependFeature       = '使用“{source}”功能时需要同步开启“{target}”功能。';
$lang->admin->notice->closeDependFeature      = '关闭“{source}”功能时需要同步关闭“{target}”功能。';

$lang->admin->registerNotice = new stdclass();
$lang->admin->registerNotice->common     = '注册新帐号';
$lang->admin->registerNotice->caption    = '禅道社区登记';
$lang->admin->registerNotice->click      = '点击此处';
$lang->admin->registerNotice->lblAccount = '请设置您的用户名，英文字母和数字的组合，三位以上。';
$lang->admin->registerNotice->lblPasswd  = '请设置您的密码。数字和字母的组合，六位以上。';
$lang->admin->registerNotice->submit     = '登记';
$lang->admin->registerNotice->submitHere = '在此登记';
$lang->admin->registerNotice->bind       = "绑定已有帐号";
$lang->admin->registerNotice->success    = "登记账户成功";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = '关联社区帐号';
$lang->admin->bind->success = "关联账户成功";
$lang->admin->bind->submit  = "绑定";

$lang->admin->setModule = new stdclass();
$lang->admin->setModule->module         = '功能点';
$lang->admin->setModule->optional       = '可选功能';
$lang->admin->setModule->opened         = '已开启';
$lang->admin->setModule->closed         = '已关闭';

$lang->admin->setModule->my             = '地盘';
$lang->admin->setModule->product        = $lang->productCommon;
$lang->admin->setModule->project        = $lang->projectCommon;
$lang->admin->setModule->assetlib       = '资产库';
$lang->admin->setModule->other          = '其他功能';

$lang->admin->setModule->score          = '积分';
$lang->admin->setModule->repo           = '代码';
$lang->admin->setModule->issue          = '问题';
$lang->admin->setModule->risk           = '风险';
$lang->admin->setModule->opportunity    = '机会';
$lang->admin->setModule->process        = '过程';
$lang->admin->setModule->auditplan      = '质量保证';
$lang->admin->setModule->meeting        = '会议';
$lang->admin->setModule->roadmap        = '路线图';
$lang->admin->setModule->track          = '矩阵';
$lang->admin->setModule->ER             = $lang->ERCommon;
$lang->admin->setModule->UR             = $lang->URCommon;
$lang->admin->setModule->deliverable    = '交付物';
$lang->admin->setModule->cm             = '基线';
$lang->admin->setModule->change         = '项目变更';
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
$lang->admin->setModule->deliverable    = '交付物';
$lang->admin->setModule->kanban         = '通用看板';
$lang->admin->setModule->OA             = '办公';
$lang->admin->setModule->deploy         = '运维';
$lang->admin->setModule->traincourse    = '学堂';
$lang->admin->setModule->setCode        = '代号';
$lang->admin->setModule->measrecord     = '度量';

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
$lang->admin->safe->modeRuleList[2] = '10位及以上，包含大小写字母，数字，特殊字符。';

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
$lang->admin->safe->noticeGd       = '系统检测到您的服务器未安装GD模块或未启用FreeType支持，无法使用验证码功能，请安装后使用。';

$lang->admin->menuSetting['system']['name']        = '系统设置';
$lang->admin->menuSetting['system']['desc']        = '备份、聊天、安全等系统各要素配置。';
$lang->admin->menuSetting['user']['name']          = '人员管理';
$lang->admin->menuSetting['user']['desc']          = '维护部门、添加人员、分组配置权限。';
$lang->admin->menuSetting['switch']['name']        = '功能开关';
$lang->admin->menuSetting['switch']['desc']        = '打开、关闭系统部分功能。';
$lang->admin->menuSetting['feature']['name']       = '功能配置';
$lang->admin->menuSetting['feature']['desc']       = '按照功能菜单进行系统的要素配置。';
$lang->admin->menuSetting['template']['name']      = '文档模板';
$lang->admin->menuSetting['template']['desc']      = '配置文档的模板类型和模板内容。';
$lang->admin->menuSetting['message']['name']       = '通知设置';
$lang->admin->menuSetting['message']['desc']       = '配置通知路径，自定义需要通知的动作。';
$lang->admin->menuSetting['extension']['name']     = '插件管理';
$lang->admin->menuSetting['extension']['desc']     = '浏览、安装插件。';
$lang->admin->menuSetting['dev']['name']           = '二次开发';
$lang->admin->menuSetting['dev']['desc']           = '支持对系统进行二次开发。';
$lang->admin->menuSetting['convert']['name']       = '数据导入';
$lang->admin->menuSetting['convert']['desc']       = '第三方系统的数据导入。';
$lang->admin->menuSetting['adminregister']['name'] = '加入禅道社区';
$lang->admin->menuSetting['adminregister']['desc'] = '获取项目管理大礼包、技术支持服务、体验各版本Demo。';

$lang->admin->updateDynamics   = '更新动态';
$lang->admin->updatePatch      = '补丁更新';
$lang->admin->upgradeRecommend = '推荐升级';
$lang->admin->zentaoUsed       = '您已使用禅道';
$lang->admin->noPriv           = '您没有访问该区块的权限。';

$lang->admin->openTag = '禅道';
$lang->admin->bizTag  = '禅道企业版';
$lang->admin->maxTag  = '禅道旗舰版';
$lang->admin->ipdTag  = '禅道IPD版';

$lang->admin->bizInfoURL    = 'https://www.zentao.net/page/enterprise.html';
$lang->admin->maxInfoURL    = 'https://www.zentao.net/page/max.html';
$lang->admin->productDetail = '查看详情';
$lang->admin->productFeature['biz'][] = '工时管理、甘特图、导入导出';
$lang->admin->productFeature['biz'][] = '40+内置统计报表、自定义报表功能';
$lang->admin->productFeature['biz'][] = '强大的自定义工作流、反馈管理功能';
$lang->admin->productFeature['biz'][] = '价格厚道，专属技术支持服务';
$lang->admin->productFeature['max'][] = '120+概念，全面覆盖瀑布管理模型';
$lang->admin->productFeature['max'][] = '项目管理可视化，精准掌控项目进度';
$lang->admin->productFeature['max'][] = '资产库管理，为项目提供数据支撑';
$lang->admin->productFeature['max'][] = '严格权限控制，方式灵活安全';
$lang->admin->productFeature['ipd'][] = '内置需求池管理，用于需求收集分发';
$lang->admin->productFeature['ipd'][] = '完整支持产品路标规划和立项流程';
$lang->admin->productFeature['ipd'][] = '提供完整的市场管理、调研管理和报告管理';
$lang->admin->productFeature['ipd'][] = '提供完整的IPD研发流程，内置TR和DCP评审';

$lang->admin->community = new stdclass();
$lang->admin->community->registerTitle       = '加入禅道社区';
$lang->admin->community->skip                = '跳过';
$lang->admin->community->uxPlanTitle         = '禅道用户体验改进计划';
$lang->admin->community->loginFailed         = '登录失败';
$lang->admin->community->loginFailedMobile   = '请填写手机号';
$lang->admin->community->loginFailedCode     = '请填写验证码';
$lang->admin->community->officialWebsite     = '禅道官网 ';
$lang->admin->community->uxPlanWithBookTitle = '《禅道用户体验改进计划》';
$lang->admin->community->uxPlanStatusTitle   = '帮助我们了解产品使用情况。';
$lang->admin->community->mobile              = '手机号';
$lang->admin->community->smsCode             = '验证码';
$lang->admin->community->sendCode            = '获取验证码';
$lang->admin->community->join                = '加入';
$lang->admin->community->joinDesc            = '帮助我们了解产品使用情况。';
$lang->admin->community->captchaTip          = '请输入验证码';
$lang->admin->community->sure                = '<span style="font-size: 15px;">&nbsp;&nbsp;确定</span>';
$lang->admin->community->unBindText          = '解绑';
$lang->admin->community->welcome             = '加入禅道社区';
$lang->admin->community->welcomeForBound     = '您已加入禅道社区，您的账号为：';
$lang->admin->community->advantage1          = '项目管理大礼包';
$lang->admin->community->advantage2          = '技术支持服务';
$lang->admin->community->advantage3          = '体验各版本Demo';
$lang->admin->community->advantage4          = '禅道软件手册';
$lang->admin->community->goCommunity         = '前往社区';
$lang->admin->community->giftPackage         = '填信息领礼包';
$lang->admin->community->enterMobile         = '请输入手机号';
$lang->admin->community->enterCode           = '请输入验证码';
$lang->admin->community->goBack              = '返回';
$lang->admin->community->reSend              = '重新发送';
$lang->admin->community->unbindTitle         = '确认与禅道解绑吗';
$lang->admin->community->unbindContent       = '解绑后将无法通过禅道软件直接跳转禅道官网';
$lang->admin->community->cancelButton        = '取消';
$lang->admin->community->unbindButton        = '解绑';
$lang->admin->community->joinSuccess         = '加入禅道社区成功';
$lang->admin->community->receiveGiftPackage  = '领取项目礼包';
$lang->admin->community->giftPackageSuccess  = '提交成功';

$lang->admin->community->positionList['项目经理']    = '项目经理';
$lang->admin->community->positionList['研发主管']    = '研发主管';
$lang->admin->community->positionList['运营']       = '运营';
$lang->admin->community->positionList['采购']       = '采购';
$lang->admin->community->positionList['产品经理']    = '产品经理';
$lang->admin->community->positionList['UI/UX设计师'] = 'UI/UX设计师';
$lang->admin->community->positionList['前端开发']    = '前端开发';
$lang->admin->community->positionList['后端开发']    = '后端开发';
$lang->admin->community->positionList['全栈开发']    = '全栈开发';
$lang->admin->community->positionList['测试 / QA']  = '测试 / QA';
$lang->admin->community->positionList['架构师']      = '架构师';

$lang->admin->community->solvedProblems['产品管理']   = '产品管理';
$lang->admin->community->solvedProblems['项目管理']   = '项目管理';
$lang->admin->community->solvedProblems['BUG管理']   = 'BUG管理';
$lang->admin->community->solvedProblems['工作流管理'] = '工作流管理';
$lang->admin->community->solvedProblems['效能管理']   = '效能管理';
$lang->admin->community->solvedProblems['文档管理']   = '文档管理';
$lang->admin->community->solvedProblems['反馈管理']   = '反馈管理';
$lang->admin->community->solvedProblems['其他']      = '其他';

$lang->admin->community->giftPackageFormNickname = '如何称呼您';
$lang->admin->community->giftPackageFormPosition = '您的职位';
$lang->admin->community->giftPackageFormCompany  = '公司名称';
$lang->admin->community->giftPackageFormQuestion = '您想使用禅道解决哪些项目管理问题';

$lang->admin->community->giftPackageFailed         = '提交失败';
$lang->admin->community->giftPackageFailedNickname = '请填写称呼';
$lang->admin->community->giftPackageFailedPosition = '请填写职位';
$lang->admin->community->giftPackageFailedCompany  = '请填写公司名称';

$lang->admin->community->uxPlan = new stdclass();
$lang->admin->community->uxPlan->agree  = '已同意';
$lang->admin->community->uxPlan->cancel = '已取消';

$lang->admin->community->unBind = new stdclass();
$lang->admin->community->unBind->success = '已解绑';

$lang->admin->nickname       = '称呼';
$lang->admin->position       = '职位';
$lang->admin->company        = '公司名称';
$lang->admin->solvedProblems = '项目管理问题';

$lang->admin->mobile  = '手机号';
$lang->admin->code    = '短信验证码';
$lang->admin->agreeUX = '用户体验计划';

$lang->admin->metricLib = new stdclass();
$lang->admin->metricLib->startUpdate = '开始更新';
$lang->admin->metricLib->updating    = '正在更新';
$lang->admin->metricLib->updated     = '更新完成';
$lang->admin->metricLib->tips        = "由于度量库表数据量较大，更新索引可能需要较长时间，请在业务低峰期操作。在此期间您可以进行其他操作，但请勿关闭当前页面或浏览器。";

include dirname(__FILE__) . '/menu.php';
