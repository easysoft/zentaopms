<?php
/**
 * The createlib view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: createlib.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="main">
  <div class="container">
    <div id='mainContent' class='main-content'>
      <div class='center-block'>
        <div class='main-header'>
          <h2><?php echo $lang->api->createLib;?></h2>
        </div>
        <form class='load-indicator main-form' id="apiForm" method='post' enctype='multipart/form-data'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->api->libType;?></th>
              <td>
                <span><?php echo html::radio('libType', $lang->api->libTypeList, $type, "onchange='toggleLibType(this.value)'")?></span>
              </td>
            </tr>
            <tr id='productBox' class="<?php if($type != 'product') echo 'hidden';?>">
              <th><?php echo $lang->api->product;?></th>
              <td class='required'>
                <span><?php echo html::select('product', $products, $objectID, "class='form-control chosen'")?></span>
              </td>
            </tr>
            <tr id='projectBox' class="<?php if($type != 'project') echo 'hidden';?>">
              <th><?php echo $lang->api->project;?></th>
              <td class='required'>
                <span><?php echo html::select('project', $projects, $objectID, "class='form-control chosen'")?></span>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->api->name?></th>
              <td style="width: 80%"><?php echo html::input('name', '', "class='form-control'")?></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->baseUrl?></th>
              <td style="width: 80%"><?php echo html::input('baseUrl', '', "class='form-control' placeholder='" . $lang->api->baseUrlDesc . "'");?></td>
            </tr>
            <tr id='aclBox'>
              <th><?php echo $lang->api->control;?></th>
              <td>
                <span><?php echo html::radio('acl', $lang->api->aclList, 'open', "onchange='toggleAcl(this.value, \"lib\")'", 'block');?></span>
              </td>
            </tr>
            <tr id='whiteListBox' class='hidden'>
              <th><?php echo $lang->api->whiteList;?></th>
              <td>
                <div class='input-group'>
                  <span class='input-group-addon groups-addon'><?php echo $lang->api->group?></span>
                    <?php echo html::select('groups[]', $groups, '', "class='form-control chosen' multiple")?>
                </div>
                <div class='input-group'>
                  <span class='input-group-addon'><?php echo $lang->api->user?></span>
                    <?php echo html::select('users[]', $users, '', "class='form-control chosen' multiple")?>
                    <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=users&attr=data-drop_direction='up'");?>
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
<?php js::set('productLang', $lang->productCommon);?>
<?php js::set('projectLang', $lang->projectCommon);?>
<?php js::set('libType', $type);?>
<?php include '../../common/view/footer.lite.html.php';?>
