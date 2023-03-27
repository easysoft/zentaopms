<?php
/**
 * The createlib view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: createlib.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php js::set('libType', $type);?>
<div id="main">
  <div class="container">
    <div id='mainContent' class='main-content'>
      <div class='center-block'>
        <div class='main-header'>
          <h2><?php echo $lang->doc->createLib;?></h2>
        </div>
        <form method='post' target='hiddenwin' class='no-stash'>
          <table class='table table-form'>
            <?php if(in_array($type, array('product', 'project', 'execution'))):?>
            <tr class='objectBox'>
              <th><?php echo $lang->doc->{$type}?></th>
              <td class='required'><?php echo html::select($type, $objects, $objectID, "class='form-control chosen' data-drop_direction='down'")?></td>
            </tr>
            <tr>
              <th><?php echo $lang->doc->libType;?></th>
              <td>
                <span><?php echo html::radio('libType', $lang->doclib->type, 'wiki')?></span>
              </td>
            </tr>
            <?php endif;?>
            <tr class="normalLib">
              <th><?php echo $lang->doclib->name?></th>
              <td><?php echo html::input('name', '', "class='form-control'")?></td>
            </tr>
            <tr class="apilib hidden">
              <th><?php echo $lang->api->baseUrl?></th>
              <td><?php echo html::input('baseUrl', '', "class='form-control' placeholder='" . $lang->api->baseUrlDesc . "'");?></td>
            </tr>
            <tr id="aclBox">
              <th><?php echo $lang->doclib->control;?></th>
              <td>
                <?php echo html::radio('acl', $lang->doclib->aclList, $type == 'custom' ? 'open' : 'default', "onchange='toggleAcl(this.value, \"lib\")'", 'block')?>
              </td>
            </tr>
            <tr id='whiteListBox' class='hidden'>
              <th><?php echo $lang->doc->whiteList;?></th>
              <td>
                <div id='groupBox' class='input-group'>
                  <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
                  <?php echo html::select('groups[]', $groups, '', "class='form-control picker-select' multiple")?>
                </div>
                <div class='input-group'>
                  <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
                  <?php echo html::select('users[]', $users, '', "class='form-control picker-select' multiple")?>
                  <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=users&attr=data-drop_direction='up'");?>
                </div>
              </td>
            </tr>
            <tr class="apilib hidden">
              <th><?php echo $lang->api->desc;?></th>
              <td>
                  <?php echo html::textarea('desc', '', "rows='8' class='form-control kindeditor' hidefocus='true' tabindex=''");?>
              </td>
            </tr>
            <tr>
              <td class='text-center form-actions' colspan='2'>
                <?php echo html::submitButton();?>
                <?php echo html::hidden('type', $type);?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<div class='hidden'>
  <table>
    <tr id='aclAPIBox'>
      <th><?php echo $lang->doclib->control;?></th>
      <td>
        <?php echo html::radio('acl', $lang->doclib->aclListA, 'default', "onchange='toggleAcl(this.value, \"lib\")'")?>
      </td>
    </tr>
  </table>
</div>
<?php js::set('noticeAcl', $lang->doc->noticeAcl['lib']);?>
<?php js::set('libType', $type);?>
<?php include '../../common/view/footer.lite.html.php';?>
