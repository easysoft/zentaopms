<?php
/**
 * The custom seting fields view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form method='post' class='mt-20px'>
  <table class='table-4' align='center'> 
    <caption class='caption-tl'><?php echo $lang->bug->customFields;?></caption>
    <tr class='colhead'>
      <th><?php echo $lang->bug->lblAllFields;?></th>
      <th></th>
      <th><?php echo $lang->bug->lblCustomFields;?></th>
      <th></th>
    </tr>  
    <tr>
      <td>
        <?php 
        echo html::select('allFields[]', $allFields, '', 'class=select-2 size=10 multiple');
        echo html::select('defaultFields[]', $defaultFields, '', 'class=hidden');
        ?>
      </td>
      <td>
        <?php
        echo html::commonButton('>', "onclick=\"addItem('allFields', 'customFields')\"") . '<br />';
        echo html::commonButton('<', "onclick=delItem('customFields')")  . '<br />';
        ?>
      </td>
      <td><?php echo html::select('customFields[]', $customFields, '', 'class=select-2 size=10 multiple');?></td>
      <td>
        <?php
        echo html::commonButton('+', "onclick=upItem('customFields')")  . '<br />';
        echo html::commonButton('-', "onclick=downItem('customFields')")  . '<br />';
        echo html::commonButton($lang->bug->restoreDefault, "onclick=restoreDefault()")  . '<br />';
        ?>
      </td>
    </tr>  
    <tr><td colspan='4' class='a-center'><?php echo html::submitButton('', 'onclick=selectItem("customFields")');?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
