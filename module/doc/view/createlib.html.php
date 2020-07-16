<?php
/**
 * The createlib view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: createlib.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<div id="main">
  <div class="container">
    <div id='mainContent' class='main-content'>
      <div class='center-block'>
        <div class='main-header'>
          <h2><?php echo $lang->doc->createLib;?></h2>
        </div>
        <form method='post' target='hiddenwin' >
          <table class='table table-form'>
            <tr>
              <th class='w-110px'><?php echo $lang->doc->libType?></th>
              <td><?php echo html::radio('type', $libTypeList, $type ? $type : key($libTypeList))?></td>
            </tr>
            <tr class='product'>
              <th><?php echo $lang->doc->product?></th>
              <td><?php echo html::select('product', $products, $type == 'product' ? $objectID : '', "class='form-control chosen' data-drop_direction='down'")?></td>
            </tr>
            <tr class='project hidden'>
              <th><?php echo $lang->doc->project?></th>
              <td><?php echo html::select('project', $projects, $type == 'project' ? $objectID : '', "class='form-control chosen' data-drop_direction='down'")?></td>
            </tr>
            <tr>
              <th><?php echo $lang->doclib->name?></th>
              <td><?php echo html::input('name', '', "class='form-control'")?></td>
            </tr>
            <tr>
              <th><?php echo $lang->doclib->control;?></th>
              <td>
                <span><?php echo html::radio('acl', $lang->doc->aclList, 'open', "onchange='toggleAcl(this.value, \"lib\")'")?></span>
                <span class='text-info' id='noticeAcl'><?php echo $lang->doc->noticeAcl['lib']['product']['default'];?></span>
              </td>
            </tr>
            <tr id='whiteListBox' class='hidden'>
              <th><?php echo $lang->doc->whiteList;?></th>
              <td>
                <div class='input-group'>
                  <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
                  <?php echo html::select('groups[]', $groups, '', "class='form-control chosen' multiple")?>
                </div>
                <div class='input-group'>
                  <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
                  <?php echo html::select('users[]', $users, '', "class='form-control chosen' multiple")?>
                </div>
              </td>
            </tr>
            <tr>
              <td class='text-center form-actions' colspan='2'><?php echo html::submitButton();?></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<div class='hidden'>
  <table>
    <tr id='aclBoxA'>
      <th><?php echo $lang->doclib->control;?></th>
      <td>
        <?php echo html::radio('acl', $lang->doclib->aclListA, 'default', "onchange='toggleAcl(this.value, \"lib\")'")?>
      </td>
    </tr>
    <tr id='aclBoxB'>
      <th><?php echo $lang->doclib->control;?></th>
      <td>
        <?php echo html::radio('acl', $lang->doclib->aclListB, 'open', "onchange='toggleAcl(this.value, \"lib\")'")?>
      </td>
    </tr>
  </table>
</div>
<?php js::set('noticeAcl', $lang->doc->noticeAcl['lib']);?>
<?php include '../../common/view/footer.lite.html.php';?>
