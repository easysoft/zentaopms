<?php
/**
 * The html template file of select version method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form method='post' action='<?php echo inlink('confirm');?>'>
  <table align='center' class='table-5 f-14px'>
    <caption><?php echo $lang->upgrade->selectVersion;?></caption>
    <tr>
      <th class='w-p20 rowhead'><?php echo $lang->upgrade->fromVersion;?></th>
      <td><?php echo html::select('fromVersion', $lang->upgrade->fromVersions, $version) . "<span class='red'>{$lang->upgrade->noteVersion}</span>";?></td>
    </tr>
    <tr>
      <th class='w-p20 rowhead'><?php echo $lang->upgrade->toVersion;?></th>
      <td><?php echo $config->version;?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton($lang->upgrade->common);?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
