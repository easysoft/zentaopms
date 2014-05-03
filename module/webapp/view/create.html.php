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
<div class='container mw-600px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['app']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->webapp->create;?></strong>
    </div>
  </div>
  <form class='form-condensed mw-500px' method='post' target='hiddenwin' enctype='multipart/form-data'>
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->webapp->module?></th>
        <td><?php echo html::select('module', $modules, '', "class='form-control'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->webapp->name?></th>
        <td><?php echo html::input('name', '', "class='form-control'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->webapp->url?></th>
        <td><?php echo html::input('url', '', "class='form-control'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->webapp->target?></th>
        <td><?php echo html::select('target', $lang->webapp->targetList, '', "class='form-control'")?></td>
      </tr>
      <tr class="size hide">
        <th><?php echo $lang->webapp->size?></th>
        <td><?php echo html::select('size', $lang->webapp->sizeList, '', "class='form-control'")?></td>
      </tr>
      <tr class="customSize hide">
        <th><?php echo $lang->webapp->custom?></th>
        <td><?php echo html::input('customWidth', '', "class='w-40px'") . 'px X ' . html::input('customHeight', '', "class='w-40px'") . 'px';?></td>
      </tr>
      <tr>
        <th><?php echo $lang->webapp->abstract?></th>
        <td><?php echo html::input('abstract', '', "class='form-control' maxlength='30'")?> <span class='help-block'><?php echo $lang->webapp->noticeAbstract?></span></td>
      </tr>
      <tr>
        <th><?php echo $lang->webapp->desc?></th>
        <td><?php echo html::textarea('desc', '', "class='form-control' rows='5'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->webapp->icon?></th>
        <td><?php echo html::file('files', "class='form-control' size='57'")?><span class='help-block'><?php echo $lang->webapp->noticeIcon?></span></td>
      </tr>
      <tr><td colspan='2' align='center'><?php echo html::submitButton()?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
