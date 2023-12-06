<?php
/**
 * The view view of stakeholder module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('projectID', $projectID)?>
<?php js::set('programID', $programID)?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->stakeholder->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='c-from'><?php echo $lang->stakeholder->from;?></th>
            <td><?php echo html::radio('from', $lang->stakeholder->fromList, "team");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->stakeholder->isKey;?></th>
            <td><?php echo html::radio('key', $lang->stakeholder->keyList);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->stakeholder->user;?></th>
            <td>
              <div class='input-group'>
              <?php echo html::select('user', $members, '', "class='form-control chosen'");?>
              <span class='input-group-addon hidden'><?php echo html::checkBox('newUser', $lang->stakeholder->add);?></span>
              </div>
            </td>
          </tr>
          <tr class='user-info hidden'>
            <th><?php echo $lang->stakeholder->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control'");?></td>
          </tr>
          <tr class='user-info hidden'>
            <th><?php echo $lang->stakeholder->phone;?></th>
            <td><?php echo html::input('phone', '', "class='form-control'");?></td>
          </tr>
          <tr class='user-info hidden'>
            <th><?php echo $lang->stakeholder->qq;?></th>
            <td><?php echo html::input('qq', '', "class='form-control'");?></td>
          </tr>
          <tr class='user-info hidden'>
            <th><?php echo $lang->stakeholder->weixin;?></th>
            <td><?php echo html::input('weixin', '', "class='form-control'");?></td>
          </tr>
          <tr class='user-info hidden'>
            <th><?php echo $lang->stakeholder->email;?></th>
            <td><?php echo html::input('email', '', "class='form-control'");?></td>
          </tr>
          <tr class='user-info hidden'>
            <th><?php echo $lang->stakeholder->company;?></th>
            <td>
              <div class='input-group'>
              <?php echo html::select('company', $companys, '', "class='form-control chosen'");?>
              <span class='input-group-addon'><?php echo html::checkBox('new', $lang->stakeholder->add);?></span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->stakeholder->nature;?></th>
            <td colspan='3'><?php echo html::textarea('nature', '', "class='form-control kindeditor'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->stakeholder->analysis;?></th>
            <td colspan='3'><?php echo html::textarea('analysis', '', "class='form-control kindeditor'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->stakeholder->strategy;?></th>
            <td colspan='3'><?php echo html::textarea('strategy', '', "class='form-control kindeditor'");?></td>
          </tr>
          <tr>
            <td colspan='4' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
