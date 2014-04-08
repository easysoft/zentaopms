<?php
/**
 * The custom seting fields view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['bug']);?></span>
    <strong><?php echo $lang->bug->customFields;?></strong>
    <small class='text-muted'><?php echo html::icon($lang->icons['customFields']);?></small>
  </div>
</div>
<form class='form-condensed' method='post' class='mt-20px'>
  <table class='table table-form' align='center'>
    <tr>
      <th colspan='2' class='text-left'><?php echo $lang->bug->lblAllFields;?></th>
      <th colspan='2' class='text-left'><?php echo $lang->bug->lblCustomFields;?></th>
    </tr>
    <tr>
      <td>
        <?php 
        echo html::select('allFields[]', $allFields, '', 'class=form-control size=10 multiple');
        echo html::select('defaultFields[]', $defaultFields, '', 'class=hidden');
        ?>
      </td>
      <td class='text-middle w-80px'>
        <a class='btn btn-block' onclick="addItem('allFields', 'customFields')"><i class="icon-chevron-right"></i></a>
        <a class='btn btn-block' onclick="delItem('customFields')"><i class="icon-chevron-left"></i></a>
      </td>
      <td><?php echo html::select('customFields[]', $customFields, '', 'class=form-control size=10 multiple');?></td>
      <td class='text-middle w-80px'>
        <a class='btn btn-block' onclick="upItem('customFields')"><i class="icon-chevron-up"></i></a>
        <a class='btn btn-block' onclick="downItem('customFields')"><i class="icon-chevron-down"></i></a>
        <a class='btn btn-block' onclick='restoreDefault()'><?php echo $lang->bug->restoreDefault;?></a>
      </td>
    </tr>  
    <tr><td></td><td><?php echo html::submitButton('', 'onclick=selectItem("customFields")', 'btn-block btn-primary');?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
