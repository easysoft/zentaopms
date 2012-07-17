<?php
/**
 * The upgrade module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->upgrade->common  = '升级';
$lang->upgrade->result  = '升级结果';
$lang->upgrade->fail    = '升级失败';
$lang->upgrade->success = '升级成功';
$lang->upgrade->tohome  = '返回首页';
$lang->upgrade->warnning= '警告';
$lang->upgrade->warnningContent = <<<EOT
警告！升级有危险，请先备份数据库，以防万一。<br />
备份方法：<br />
1. 可以通过phpMyAdmin进行备份。<br />
2. 使用mysql命令行的工具。<br />
   # mysqldump -u <span class='red'>username</span> -p <span class='red'>dbname</span> > <span class='red'>filename</span> <br />
   要将上面红色的部分分别替换成对应的用户名和禅道系统的数据库名。<br />
   比如： mysqldump -u root -p zentao >zentao.bak
EOT;
$lang->upgrade->setStatusFile = '<h4>基于安全考虑，升级程序会检查<strong>%s</strong>文件的最后更新时间, 以确保安全！</h4>
                                 <strong style="color:red">该文件不存在或者已经过期，请用下面的命令来创建或者更新它。</strong>
                                 <ul>
                                 <li>linux下面的命令：<strong>touch %s;</strong></li>
                                 <li>windows打开命令行，执行<strong>echo ok > %s</strong></li>
                                 <li>或者删掉原来的ok文件，重新创建一个ok文件，不需要扩展名，不需要内容。</li>
                                 </ul>
                                 <strong style="color:red">我已经仔细阅读上面提示且完成上述工作，<a href="upgrade.php">继续更新</a></strong>';
$lang->upgrade->selectVersion = '选择版本';
$lang->upgrade->noteVersion   = "务必选择正确的版本，否则会造成数据丢失。";
$lang->upgrade->fromVersion   = '原来的版本';
$lang->upgrade->toVersion     = '升级到';
$lang->upgrade->confirm       = '确认要执行的SQL语句';
$lang->upgrade->sureExecute   = '确认执行';

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
