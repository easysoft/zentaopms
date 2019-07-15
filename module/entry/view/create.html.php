<?php
/**
 * The create view file of entry module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<?php include '../../common/view/form.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-800px center-block-sm'>
    <div class="main-header">
      <h2>
        <?php echo $lang->entry->common;?>
        <small><?php echo $lang->arrow . ' ' . $lang->entry->create;?></small>
      </h2>
    </div>
    <form id='entryForm' method='post' class='form-ajax'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->entry->name;?></th>
          <td><?php echo html::input('name', '', "class='form-control' title='{$lang->entry->note->name}' placeholder='{$lang->entry->note->name}'");?></td>
          <td class='w-200px'></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->code;?></th>
          <td><?php echo html::input('code', '', "class='form-control' title='{$lang->entry->note->code}' placeholder='{$lang->entry->note->code}'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->freePasswd;?></th>
          <td><?php echo html::radio('freePasswd', $lang->entry->freePasswdList, '');?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->account;?></th>
          <td><?php echo html::select("account", $users, '', "class='form-control chosen' data-placeholder='{$lang->entry->note->account}'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->key;?></th>
          <td><?php echo html::input('key', md5(rand()), "class='form-control' readonly='readonly'");?></td>
          <td><span class="help-inline"><?php echo html::a('javascript:void(0)', $lang->entry->createKey, '', 'onclick="createKey()" tabIndex="-1" class="btn"')?></span></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->ip;?></th>
          <td><?php echo html::input('ip', '', "class='form-control' title='{$lang->entry->note->ip}' placeholder='{$lang->entry->note->ip}'");?></td>
          <td>
            <div class='checkbox-primary'>
              <input type="checkbox" id="allIP" name="allIP" value="1" />
              <label for='allIP'><?php echo $lang->entry->note->allIP;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->desc;?></th>
          <td><?php echo html::textarea('desc', '', "rows='3' class='form-control'");?></td>
          <td></td>
        </tr>
        <tr>
          <th></th>
          <td>
            <?php echo html::submitButton();?>
            <?php echo html::a($lang->entry->helpLink, $lang->entry->help, '_blank', "class='help'");?>
            <?php echo html::a($lang->entry->notifyLink, $lang->entry->notify, '_blank', "class='help'");?>
          </td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
