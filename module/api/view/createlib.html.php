<?php
/**
 * The createlib view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
          <h2><?php echo $type == 'normal' ? $lang->api->createLib : $lang->api->createDemo;?></h2>
        </div>
        <form class='load-indicator main-form' id="apiForm" method='post' enctype='multipart/form-data'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->api->name?></th>
              <td style="width: 80%"><?php echo html::input('name', $type == 'demo' ? $lang->api->zentaoAPI : '', "class='form-control'")?></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->baseUrl?></th>
              <td style="width: 80%"><?php echo html::input('baseUrl', $type == 'demo' ? ('http://' . $_SERVER['HTTP_HOST'] . $this->app->config->webRoot . 'api.php/v1') : '', "class='form-control' placeholder='" . $lang->api->baseUrlDesc . "'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->control;?></th>
              <td>
                <?php $isDisabled = $type == 'demo' ? 'disabled' : '';?>
                <span><?php echo html::radio('acl', $lang->api->aclList, 'open', "onchange='toggleAcl(this.value, \"lib\")' $isDisabled")?></span>
                <span class='text-info' id='noticeAcl'><?php echo $lang->api->noticeAcl['open'];?></span>
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
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->api->desc;?></th>
              <td colspan='2'>
                  <?php echo html::textarea('desc', '', "rows='8' class='form-control kindeditor' hidefocus='true' tabindex=''");?>
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
<?php js::set('noticeAcl', $lang->api->noticeAcl);?>
<?php include '../../common/view/footer.lite.html.php';?>
