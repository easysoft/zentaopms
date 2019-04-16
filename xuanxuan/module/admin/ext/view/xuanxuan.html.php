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
    <form method='post' id='ajaxForm' class='form-ajax'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->chat->turnon;?></th>
          <td class='w-p20'><?php echo $type == 'edit' ? html::radio('turnon', $lang->chat->turnonList, $turnon) : zget($lang->chat->turnonList, $config->xuanxuan->turnon);?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->chat->version;?></th>
          <td><?php echo $config->xuanxuan->version;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->chat->key;?></th>
          <td><?php echo zget($config->xuanxuan, 'key', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->chat->xxdServer;?></th>
          <td><?php echo $type == 'edit' ? html::input('server', $domain, "class='form-control'") : $domain;?></td>
          <td><?php if($type == 'edit') echo $lang->chat->xxdServerTip;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->chat->xxd->ip;?></th>
          <td><?php echo $type == 'edit' ? html::input('ip', zget($config->xuanxuan, 'ip', '0.0.0.0'), "class='form-control' placeholder='{$lang->chat->placeholder->xxd->ip}'") : zget($config->xuanxuan, 'ip', '0.0.0.0');?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->chat->xxd->chatPort;?></th>
          <td><?php echo $type == 'edit' ? html::input('chatPort', zget($config->xuanxuan, 'chatPort', 11444), "placeholder='{$lang->chat->placeholder->xxd->chatPort}' class='form-control'") : zget($config->xuanxuan, 'chatPort', 11444);?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->chat->xxd->commonPort;?></th>
          <td><?php echo $type == 'edit' ? html::input('commonPort', zget($config->xuanxuan, 'commonPort', 11443), "placeholder='{$lang->chat->placeholder->xxd->commonPort}' class='form-control'") : zget($config->xuanxuan, 'commonPort', 11443);?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->chat->xxd->uploadFileSize;?></th>
          <td>
            <?php if($type == 'edit'):?>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->chat->xxd->max;?></span>
              <?php echo html::input('uploadFileSize', zget($config->xuanxuan, 'uploadFileSize', 20), "class='form-control' placeholder='{$lang->chat->placeholder->xxd->uploadFileSize}' ");?>
              <span class='input-group-addon'>M</span>
            </div>
            <?php else:?>
            <?php echo $lang->chat->xxd->max . zget($config->xuanxuan, 'uploadFileSize', 20) . 'M';?>
            <?php endif;?>
          </td>
          <td></td>
        </tr>
        <?php if($type == 'edit'):?>
        <tr>
          <th><?php echo $lang->chat->xxd->isHttps;?></th>
          <td>
            <?php echo html::hidden('isHttps', $isHttps);?>
            <?php echo $type ? html::radio('https', $lang->chat->httpsOptions, $isHttps, "class='checkbox'") : $lang->chat->httpsOptions[$isHttps];?>
          </td>
          <td></td>
        </tr>
        <?php else:?>
        <tr>
          <th><?php echo $lang->chat->xxd->isHttps;?></th>
          <td><?php echo zget($lang->chat->httpsOptions, $isHttps, '');?> </td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr class='sslTR <?php if($isHttps == 'off' || empty($type)) echo 'hide';?>'>
          <th><?php echo $lang->chat->xxd->sslcrt;?></th>
          <td><?php echo html::textarea('sslcrt',  zget($config->xuanxuan, 'sslcrt', ''), "placeholder='{$lang->chat->placeholder->xxd->sslcrt}' class='form-control'");?></td>
          <td></td>
        </tr>
        <tr class='sslTR <?php if($isHttps == 'off' || empty($type)) echo 'hide';?>'>
          <th><?php echo $lang->chat->xxd->sslkey;?></th>
          <td><?php echo html::textarea('sslkey',  zget($config->xuanxuan, 'sslkey', ''), "placeholder='{$lang->chat->placeholder->xxd->sslkey}' class='form-control'");?></td>
          <td></td>
        </tr>
        <?php if(!$type):?>
        <tr>
          <th><?php echo $lang->chat->xxd->os;?></th>
          <td><?php echo html::select('os', $lang->chat->osList, zget($config->xuanxuan, $os), "class='form-control chosen'");?></td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th></th>
          <td colspan='2'>
            <?php if($type == 'edit'):?>
              <?php echo html::submitButton();?>
              <?php echo html::a(helper::createLink('admin', 'xuanxuan'), $lang->goback, '', 'class="btn" style="min-width: 120px"');?>
            <?php else:?>
              <?php echo html::a(helper::createLink('admin', 'downloadXXD', 'type=package'), $lang->chat->downloadXXD, 'hiddenwin', "class='btn btn-primary download download-package'");?>
              <?php echo html::a(helper::createLink('admin', 'downloadXXD', 'type=config'), $lang->chat->downloadConfig, 'hiddenwin', "class='btn btn-primary download'");?>
              <?php echo html::a(helper::createLink('admin', 'xuanxuan', 'type=edit'), $lang->chat->changeSetting, '', "class='btn'");?>
              <?php echo html::a('http://www.zentao.net/book/zentaopmshelp/302.html', $lang->chat->help, '_blank', "class='btn'");?>
            <?php endif;?>
          </td>
        </tr>
      </table>
    </form>
    <?php if($type != 'edit'):?>
    <div class='alert alert-info mg-0'><?php echo $lang->chat->info;?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../../common/view/footer.html.php';?>
