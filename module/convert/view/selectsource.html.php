<?php
/**
 * The html template file of select source method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' action='<?php echo inlink('setConfig');?>'>
  <table align='center' class='table-5 f-14px'>
    <caption><?php echo $lang->convert->selectSource;?></caption>
    <tr>
      <th class='w-p20'><?php echo $lang->convert->source;?></th>
      <th><?php echo $lang->convert->version;?></th>
    </tr>
    <?php foreach($lang->convert->sourceList as $name => $versions):?>
    <tr>
      <th class='rowhead'><?php echo $name;?></th>
      <td><?php echo html::radio('source', $versions);?></td>
    </tr>
    <?php endforeach;?>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
