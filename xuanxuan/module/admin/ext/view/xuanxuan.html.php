<?php
/**
 * The configure xuanxuan view file of setting module of RanZhi.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     setting
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left"><?php common::printAdminSubMenu('sso');?></div>
</div>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <div class='heading'>
        <h4><?php echo $lang->chat->settings;?></h4>
      </div>
    </div>
    <form id='dataform' method='post' class='form-ajax'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->chat->version;?></th>
          <td><?php echo $config->xuanxuan->version;?></td>
        </tr>
        <tr>
          <th class='w-80px'><?php echo $lang->chat->turnon;?></th>
          <td><?php echo html::radio('turnon', $lang->chat->turnonList, $turnon);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->chat->xxdServer;?></th>
          <td class='w-200px'><?php echo html::input('server', zget($config->xuanxuan, 'server', ''), "class='form-control' placeholder={$lang->chat->xxdServerTip}");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->chat->key;?></th>
          <td><?php echo html::input('key', $config->xuanxuan->key, "class='form-control'");?></td>
          <td><?php echo html::a('javascript:void(0)', $lang->chat->createKey, "", 'onclick="createKey()"');?></td>
        </tr>
        <tr>
          <th></th>
          <td><?php echo html::submitButton();?> <?php echo html::a(helper::createLink('chat', 'downloadxxd'), $lang->chat->downloadXXD, '', "class='btn btn-primary' data-toggle='modal'");?> </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../../common/view/footer.html.php';?>
