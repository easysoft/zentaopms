<?php
/**
 * The edit view file of webapp module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin' enctype='multipart/form-data'>
<table class='table-5' align='center'>
  <caption><?php echo $lang->webapp->create?></caption>
  <tr>
    <th align='left'><?php echo $lang->webapp->module?></th>
    <td><?php echo html::select('module', $modules, '', "class='select-3'")?></td>
  </tr>
  <tr>
    <th align='left'><?php echo $lang->webapp->name?></th>
    <td><?php echo html::input('name', '', "class='text-3'")?></td>
  </tr>
  <tr>
    <th align='left'><?php echo $lang->webapp->url?></th>
    <td><?php echo html::input('url', '', "class='text-3'")?></td>
  </tr>
  <tr>
    <th align='left'><?php echo $lang->webapp->target?></th>
    <td><?php echo html::select('target', $lang->webapp->targetList, '', "class='select-3'")?></td>
  </tr>
  <tr class="size hidden">
    <th align='left'><?php echo $lang->webapp->size?></th>
    <td><?php echo html::select('size', $lang->webapp->sizeList, '', "class='select-3'")?></td>
  </tr>
  <tr class="customSize hidden">
    <th align='left'><?php echo $lang->webapp->custom?></th>
    <td><?php echo html::input('customWidth', '', "class='w-40px'") . 'px X ' . html::input('customHeight', '', "class='w-40px'") . 'px';?></td>
  </tr>
  <tr>
    <th align='left'><?php echo $lang->webapp->abstract?></th>
    <td><?php echo html::input('abstract', '', "class='text-3' maxlength='30'")?> <span><?php echo $lang->webapp->noticeAbstract?></span></td>
  </tr>
  <tr>
    <th align='left'><?php echo $lang->webapp->desc?></th>
    <td><?php echo html::textarea('desc', '', "class='area-1' rows='5'")?></td>
  </tr>
  <tr>
    <th align='left'><?php echo $lang->webapp->icon?></th>
    <td><?php echo html::file('files', "class='text-1' size='57'")?><br /><span><?php echo $lang->webapp->noticeIcon?></span></td>
  </tr>
  <tr><td colspan='2' align='center'><?php echo html::submitButton()?></td></tr>
</table>
</form>
<?php include '../../common/view/footer.html.php';?>
