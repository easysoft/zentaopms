<?php
/**
 * The upgrade module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: zh-cn.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->upgrade->common  = '升级';
$lang->upgrade->result  = '升级结果';
$lang->upgrade->fail    = '升级失败';
$lang->upgrade->success = '升级成功';
$lang->upgrade->tohome  = '访问禅道';
$lang->upgrade->license = '禅道项目管理软件已更换授权协议至 Z PUBLIC LICENSE(ZPL) 1.2';
$lang->upgrade->warnning= '警告';
$lang->upgrade->checkExtension  = '检查插件';
$lang->upgrade->warnningContent = <<<EOT
<p>升级有危险，请先备份数据库，以防万一。</p>
<pre>
1. 可以通过phpMyAdmin进行备份。
2. 使用mysql命令行的工具。
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span> 
   要将上面红色的部分分别替换成对应的用户名和禅道系统的数据库名。
   比如： mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;
$lang->upgrade->createFileWinCMD   = '打开命令行，执行<strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = '在命令行执行: <strong style="color:#ed980f">touch %s;</strong>';
$lang->upgrade->setStatusFile      = '<h4>升级之前请先完成下面的操作：</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>或者删掉"<strong style="color:#ed980f">%s</strong>" 这个文件 ，重新创建一个<strong style="color:#ed980f">ok.txt</strong>文件，不需要内容。</li>
                                      </ul>
                                      <p><strong style="color:red">我已经仔细阅读上面提示且完成上述工作，<a href="upgrade.php">继续更新</a></strong></p>';
$lang->upgrade->selectVersion = '选择版本';
$lang->upgrade->continue      = '继续';
$lang->upgrade->noteVersion   = "务必选择正确的版本，否则会造成数据丢失。";
$lang->upgrade->fromVersion   = '原来的版本';
$lang->upgrade->toVersion     = '升级到';
$lang->upgrade->confirm       = '确认要执行的SQL语句';
$lang->upgrade->sureExecute   = '确认执行';
$lang->upgrade->forbiddenExt  = '以下插件与新版本不兼容，已经自动禁用：';
$lang->upgrade->updateFile    = '需要更新附件信息。';

$lang->upgrade->fromVersions['0_3beta']   = '0.3 BETA';
$lang->upgrade->fromVersions['0_4beta']   = '0.4 BETA';
$lang->upgrade->fromVersions['0_5beta']   = '0.5 BETA';
$lang->upgrade->fromVersions['0_6beta']   = '0.6 BETA';
$lang->upgrade->fromVersions['1_0beta']   = '1.0 BETA';
$lang->upgrade->fromVersions['1_0rc1']    = '1.0 RC1';
$lang->upgrade->fromVersions['1_0rc2']    = '1.0 RC2';
$lang->upgrade->fromVersions['1_0']       = '1.0 STABLE';
$lang->upgrade->fromVersions['1_0_1']     = '1.0.1';
$lang->upgrade->fromVersions['1_1']       = '1.1';
$lang->upgrade->fromVersions['1_2']       = '1.2';
$lang->upgrade->fromVersions['1_3']       = '1.3';
$lang->upgrade->fromVersions['1_4']       = '1.4';
$lang->upgrade->fromVersions['1_5']       = '1.5';
$lang->upgrade->fromVersions['2_0']       = '2.0';
$lang->upgrade->fromVersions['2_1']       = '2.1';
$lang->upgrade->fromVersions['2_2']       = '2.2';
$lang->upgrade->fromVersions['2_3']       = '2.3';
$lang->upgrade->fromVersions['2_4']       = '2.4';
$lang->upgrade->fromVersions['3_0_beta1'] = '3.0 BETA1';
$lang->upgrade->fromVersions['3_0_beta2'] = '3.0 BETA2';
$lang->upgrade->fromVersions['3_0']       = '3.0 STABLE';
$lang->upgrade->fromVersions['3_1']       = '3.1';
$lang->upgrade->fromVersions['3_2']       = '3.2';
$lang->upgrade->fromVersions['3_2_1']     = '3.2.1';
$lang->upgrade->fromVersions['3_3']       = '3.3';
$lang->upgrade->fromVersions['4_0_beta1'] = '4.0 BETA1';
$lang->upgrade->fromVersions['4_0_beta2'] = '4.0 BETA2';
$lang->upgrade->fromVersions['4_0']       = '4.0';
$lang->upgrade->fromVersions['4_0_1']     = '4.0.1';
$lang->upgrade->fromVersions['4_1']       = '4.1';
$lang->upgrade->fromVersions['4_2_beta']  = '4.2.beta';
$lang->upgrade->fromVersions['4_3_beta']  = '4.3.beta';
$lang->upgrade->fromVersions['5_0_beta1'] = '5.0.beta1';
$lang->upgrade->fromVersions['5_0_beta2'] = '5.0.beta2';
$lang->upgrade->fromVersions['5_0']       = '5.0';
$lang->upgrade->fromVersions['5_1']       = '5.1';
$lang->upgrade->fromVersions['5_2']       = '5.2';
$lang->upgrade->fromVersions['5_2_1']     = '5.2.1';
$lang->upgrade->fromVersions['5_3']       = '5.3';
$lang->upgrade->fromVersions['6_0_beta1'] = '6.0.beta1';
$lang->upgrade->fromVersions['6_0']       = '6.0';
$lang->upgrade->fromVersions['6_1']       = '6.1';
$lang->upgrade->fromVersions['6_2']       = '6.2';
$lang->upgrade->fromVersions['6_3']       = '6.3';
$lang->upgrade->fromVersions['6_4']       = '6.4';
$lang->upgrade->fromVersions['7_0']       = '7.0';
$lang->upgrade->fromVersions['7_1']       = '7.1';
$lang->upgrade->fromVersions['7_2']       = '7.2';
$lang->upgrade->fromVersions['7_2_4']     = '7.2.4';
$lang->upgrade->fromVersions['7_2_5']     = '7.2.5';
$lang->upgrade->fromVersions['7_3']       = '7.3';
$lang->upgrade->fromVersions['7_4_beta']  = '7.4.beta';
$lang->upgrade->fromVersions['8_0']       = '8.0';
$lang->upgrade->fromVersions['8_0_1']     = '8.0.1';
$lang->upgrade->fromVersions['8_1']       = '8.1';
$lang->upgrade->fromVersions['8_1_3']     = '8.1.3';
$lang->upgrade->fromVersions['8_2_beta']  = '8.2.beta';
$lang->upgrade->fromVersions['8_2']       = '8.2';
$lang->upgrade->fromVersions['8_2_1']     = '8.2.1';
$lang->upgrade->fromVersions['8_2_2']     = '8.2.2';
$lang->upgrade->fromVersions['8_2_3']     = '8.2.3';
$lang->upgrade->fromVersions['8_2_4']     = '8.2.4';
$lang->upgrade->fromVersions['8_2_5']     = '8.2.5';
$lang->upgrade->fromVersions['8_2_6']     = '8.2.6';
$lang->upgrade->fromVersions['8_3']       = '8.3';
$lang->upgrade->fromVersions['8_3_1']     = '8.3.1';
$lang->upgrade->fromVersions['8_4']       = '8.4';
$lang->upgrade->fromVersions['8_4_1']     = '8.4.1';
$lang->upgrade->fromVersions['9_0_beta']  = '9.0.beta';
$lang->upgrade->fromVersions['9_0']       = '9.0';
$lang->upgrade->fromVersions['9_0_1']     = '9.0.1';
$lang->upgrade->fromVersions['9_1']       = '9.1';
$lang->upgrade->fromVersions['9_1_1']     = '9.1.1';
$lang->upgrade->fromVersions['9_1_2']     = '9.1.2';
$lang->upgrade->fromVersions['9_2']       = '9.2';
$lang->upgrade->fromVersions['9_2_1']     = '9.2.1';
$lang->upgrade->fromVersions['9_3_beta']  = '9.3.beta';
