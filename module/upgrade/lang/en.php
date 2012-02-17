<?php
/**
 * The upgrade module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->upgrade->common  = 'Upgrade';
$lang->upgrade->result  = 'Result';
$lang->upgrade->fail    = 'Fail';
$lang->upgrade->success = 'Success';
$lang->upgrade->tohome  = 'Go to index';
$lang->upgrade->warnning= 'Warning';
$lang->upgrade->warnningContent = <<<EOT
Warning! Upgradinng is dangeous, backup your database first.<br />
EOT;






$lang->upgrade->setStatusFile = "<p>For security reason, we will check file <strong>%s</strong><br />
                                 But this file doesn't exist or out of date. You can use the flowing command to create(update)it <br />
                                 For linux:<strong>touch %s;</strong> <br />
                                 For windows:<strong>echo ok > %s</strong></p>
                                 I have done this work, <a href='upgrade.php'>continue upgrade</a>";



$lang->upgrade->selectVersion = 'Select version';
$lang->upgrade->noteVersion   = "Must select the correct version";
$lang->upgrade->fromVersion   = 'From version';
$lang->upgrade->toVersion     = 'To version';
$lang->upgrade->confirm       = 'Confirm the sql to executed.';
$lang->upgrade->sureExecute   = 'Execute';

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
$lang->upgrade->fromVersions['3_0_beta1'] = '3.0.beta1';
