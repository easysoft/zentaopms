<?php
/**
 * The mail file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: sendmail.html.php 4626 2013-04-10 05:34:36Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
$onlybody = isonlybody() ? true : false;
if($onlybody) $_GET['onlybody'] = 'no';
?>
<table width='98%' align='center'>
  <tr class='header'>
    <td>
      BUG #<?php echo $bug->id . "=>{$users[$bug->assignedTo]} " . html::a(common::getSysURL() . $this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset>
      <legend><?php echo $lang->bug->legendSteps;?></legend>
      <div class='content'><?php echo $bug->steps;?></div>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td><?php include '../../common/view/mail.html.php';?></td>
  </tr>
</table>
<?php if($onlybody) $_GET['onlybody'] = 'yes';?>
