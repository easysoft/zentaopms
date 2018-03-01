<?php
/**
 * The edit view file of entry module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/form.html.php';?>
<div class='container mw-800px'>
  <div id="titlebar">
    <div class="heading">
      <strong><?php echo $lang->entry->api;?></strong>
      <small class="text-muted"> <?php echo $lang->entry->edit;?></small>
    </div>
  </div>
  <form id='entryForm' method='post' class='ajaxForm'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->entry->name;?></th>
        <td><?php echo html::input('name', $entry->name, "class='form-control' title='{$lang->entry->note->name}' placeholder='{$lang->entry->note->name}'");?></td>
        <td class='w-120px'></td>
      </tr>
      <tr>
        <th><?php echo $lang->entry->code;?></th>
        <td><?php echo html::input('code', $entry->code, "class='form-control' title='{$lang->entry->note->code}' placeholder='{$lang->entry->note->code}'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->entry->key;?></th>
        <td><?php echo html::input('key', $entry->key, "class='form-control' readonly='readonly'");?></td>
        <td><span class="help-inline"><?php echo html::a('javascript:void(0)', $lang->entry->createKey, '', 'onclick="createKey()" tabIndex="-1"')?></span></td>
      </tr>
      <tr>
        <th><?php echo $lang->entry->ip;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::input('ip', $entry->ip, "class='form-control' title='{$lang->entry->note->ip}' placeholder='{$lang->entry->note->ip}'");?>
            <div class='input-group-addon'>
              <label class="checkbox-inline"><input type="checkbox" id="allIP" name="allIP" value="1"><?php echo $lang->entry->note->allIP;?></label>
            </div>
          </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->entry->desc;?></th>
        <td><?php echo html::textarea('desc', $entry->desc, "rows='3' class='form-control'");?></td>
        <td></td>
      </tr>
      <tr>
        <th></th>
        <td>
          <?php echo html::submitButton();?>
          <?php echo html::a($config->entry->help, $lang->entry->help, '_blank', "class='help'");?>
        </td>
        <td></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
