<?php
/**
 * The config email view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include '../../common/view/header.html.php';
?>
<table class='table-6' align='center'>
<caption><?php echo $lang->mail->configInfo ?></caption>
<tr>
<td><? echo html::textArea('', $config, "rows='15' class='area-1 f-12px'");?>
</tr>
<tr>
<td><?php echo $lang->mail->saveConfig . $configPath . 'zzzemail.php' ?></td>
</tr>
<tr>
<td><?php echo $lang->mail->createFile ?></td>
</tr>
</table>
