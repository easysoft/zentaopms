<?php
/**
 * The group module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: zh-tw.php 4719 2013-05-03 02:20:28Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->group->common             = '權限分組';
$lang->group->browse             = '瀏覽分組';
$lang->group->create             = '新增分組';
$lang->group->edit               = '編輯分組';
$lang->group->copy               = '複製分組';
$lang->group->delete             = '刪除分組';
$lang->group->manageView         = '視野維護';
$lang->group->managePriv         = '權限維護';
$lang->group->managePrivByGroup  = '權限維護';
$lang->group->managePrivByModule = '按模組分配權限';
$lang->group->byModuleTips       = '（可以按住Shift或者Ctrl鍵進行多選）';
$lang->group->manageMember       = '成員維護';
$lang->group->manageProjectAdmin = '維護項目管理員';
$lang->group->confirmDelete      = '您確定刪除該用戶分組嗎？';
$lang->group->successSaved       = '成功保存';
$lang->group->errorNotSaved      = '沒有保存，請確認選擇了權限數據。';
$lang->group->viewList           = '可訪問視圖';
$lang->group->manageProject      = '可管理項目';
$lang->group->programList        = '可訪問項目集';
$lang->group->productList        = '可訪問' . $lang->productCommon;
$lang->group->projectList        = '可訪問項目';
$lang->group->executionList      = "可訪問{$lang->execution->common}";
$lang->group->dynamic            = '可查看動態';
$lang->group->noticeVisit        = '空代表沒有訪問限制';
$lang->group->noneProgram        = "暫時沒有項目集";
$lang->group->noneProduct        = "暫時沒有{$lang->productCommon}";
$lang->group->noneExecution      = "暫時沒有{$lang->execution->common}";
$lang->group->project            = '項目';
$lang->group->group              = '分組';
$lang->group->noneProject        = '暫時沒有項目';

$lang->group->id       = '編號';
$lang->group->name     = '分組名稱';
$lang->group->desc     = '分組描述';
$lang->group->role     = '角色';
$lang->group->acl      = '權限';
$lang->group->users    = '用戶列表';
$lang->group->module   = '模組';
$lang->group->method   = '方法';
$lang->group->priv     = '權限';
$lang->group->option   = '選項';
$lang->group->inside   = '組內用戶';
$lang->group->outside  = '組外用戶';
$lang->group->limited  = '受限用戶組';
$lang->group->general  = '通用';
$lang->group->all      = '所有權限';

$lang->group->copyOptions['copyPriv'] = '複製權限';
$lang->group->copyOptions['copyUser'] = '複製用戶';

$lang->group->versions['']           = '修改歷史';
$lang->group->versions['16_5_beta1'] = '禪道16.5.beta1';
$lang->group->versions['16_4']       = '禪道16.4';
$lang->group->versions['16_3']       = '禪道16.3';
$lang->group->versions['16_2']       = '禪道16.2';
$lang->group->versions['16_1']       = '禪道16.1';
$lang->group->versions['16_0']       = '禪道16.0';
$lang->group->versions['16_0_beta1'] = '禪道16.0.beta1';
$lang->group->versions['15_8']       = '禪道15.8';
$lang->group->versions['15_7']       = '禪道15.7';
$lang->group->versions['15_0_rc1']   = '禪道15.0.rc1';
$lang->group->versions['12_5']       = '禪道12.5';
$lang->group->versions['12_3']       = '禪道12.3';
$lang->group->versions['11_6_2']     = '禪道11.6.2';
$lang->group->versions['10_6']       = '禪道10.6';
$lang->group->versions['10_1']       = '禪道10.1';
$lang->group->versions['10_0_alpha'] = '禪道10.0.alpha';
$lang->group->versions['9_8']        = '禪道9.8';
$lang->group->versions['9_6']        = '禪道9.6';
$lang->group->versions['9_5']        = '禪道9.5';
$lang->group->versions['9_2']        = '禪道9.2';
$lang->group->versions['9_1']        = '禪道9.1';
$lang->group->versions['9_0']        = '禪道9.0';
$lang->group->versions['8_4']        = '禪道8.4';
$lang->group->versions['8_3']        = '禪道8.3';
$lang->group->versions['8_2_beta']   = '禪道8.2.beta';
$lang->group->versions['8_0_1']      = '禪道8.0.1';
$lang->group->versions['8_0']        = '禪道8.0';
$lang->group->versions['7_4_beta']   = '禪道7.4.beta';
$lang->group->versions['7_3']        = '禪道7.3';
$lang->group->versions['7_2']        = '禪道7.2';
$lang->group->versions['7_1']        = '禪道7.1';
$lang->group->versions['6_4']        = '禪道6.4';
$lang->group->versions['6_3']        = '禪道6.3';
$lang->group->versions['6_2']        = '禪道6.2';
$lang->group->versions['6_1']        = '禪道6.1';
$lang->group->versions['5_3']        = '禪道5.3';
$lang->group->versions['5_1']        = '禪道5.1';
$lang->group->versions['5_0_beta2']  = '禪道5.0.beta2';
$lang->group->versions['5_0_beta1']  = '禪道5.0.beta1';
$lang->group->versions['4_3_beta']   = '禪道4.3.beta';
$lang->group->versions['4_2_beta']   = '禪道4.2.beta';
$lang->group->versions['4_1']        = '禪道4.1';
$lang->group->versions['4_0_1']      = '禪道4.0.1';
$lang->group->versions['4_0']        = '禪道4.0';
$lang->group->versions['4_0_beta2']  = '禪道4.0.beta2';
$lang->group->versions['4_0_beta1']  = '禪道4.0.beta1';
$lang->group->versions['3_3']        = '禪道3.3';
$lang->group->versions['3_2_1']      = '禪道3.2.1';
$lang->group->versions['3_2']        = '禪道3.2';
$lang->group->versions['3_1']        = '禪道3.1';
$lang->group->versions['3_0_beta2']  = '禪道3.0.beta2';
$lang->group->versions['3_0_beta1']  = '禪道3.0.beta1';
$lang->group->versions['2_4']        = '禪道2.4';
$lang->group->versions['2_3']        = '禪道2.3';
$lang->group->versions['2_2']        = '禪道2.2';
$lang->group->versions['2_1']        = '禪道2.1';
$lang->group->versions['2_0']        = '禪道2.0';
$lang->group->versions['1_5']        = '禪道1.5';
$lang->group->versions['1_4']        = '禪道1.4';
$lang->group->versions['1_3']        = '禪道1.3';
$lang->group->versions['1_2']        = '禪道1.2';
$lang->group->versions['1_1']        = '禪道1.1';
$lang->group->versions['1_0_1']      = '禪道1.0.1';

include (dirname(__FILE__) . '/resource.php');
