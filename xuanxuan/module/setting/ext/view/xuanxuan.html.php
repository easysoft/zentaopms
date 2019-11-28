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
  <div class="btn-toolbar pull-left"><?php common::printAdminSubMenu('xuanxuan');?></div>
</div>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <div class='heading'>
        <h4><?php echo $lang->im->settings;?></h4>
      </div>
    </div>
    <form method='post' id='ajaxForm' class='form-ajax'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->im->turnon;?></th>
          <td class='w-p30'><?php echo $type == 'edit' ? html::radio('turnon', $lang->im->turnonList, $turnon) : zget($lang->im->turnonList, $config->xuanxuan->turnon);?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->version;?></th>
          <td><?php echo $config->xuanxuan->version;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->key;?></th>
          <td>
            <?php $key = zget($config->xuanxuan, 'key', '');?>
            <?php echo $type == 'edit' ? html::input('key', $key, "class='form-control' readonly='readonly'") : $key;?>
          </td>
          <td><?php echo $type == 'edit' ? html::a('javascript:void(0)', $lang->im->createKey, '', 'onclick="createKey()"') : '';?></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->debug;?></th>
          <td><?php echo $type == 'edit' ? html::radio('debug', $lang->im->debugStatus, zget($config->xuanxuan, 'debug', 0)) : zget($lang->im->debugStatus, zget($config->xuanxuan, 'debug', 0));?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->xxdServer;?></th>
          <td><?php echo $type == 'edit' ? html::input('server', $domain, "class='form-control'") : $domain;?></td>
          <td><?php if($type == 'edit') echo $lang->im->xxdServerTip;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->xxd->ip;?></th>
          <td><?php echo $type == 'edit' ? html::input('ip', zget($config->xuanxuan, 'ip', '0.0.0.0'), "class='form-control' placeholder='{$lang->im->placeholder->xxd->ip}'") : zget($config->xuanxuan, 'ip', '0.0.0.0');?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->xxd->chatPort;?></th>
          <td><?php echo $type == 'edit' ? html::input('chatPort', zget($config->xuanxuan, 'chatPort', 11444), "placeholder='{$lang->im->placeholder->xxd->chatPort}' class='form-control'") : zget($config->xuanxuan, 'chatPort', 11444);?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->xxd->commonPort;?></th>
          <td><?php echo $type == 'edit' ? html::input('commonPort', zget($config->xuanxuan, 'commonPort', 11443), "placeholder='{$lang->im->placeholder->xxd->commonPort}' class='form-control'") : zget($config->xuanxuan, 'commonPort', 11443);?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->xxd->uploadFileSize;?></th>
          <td>
            <?php if($type == 'edit'):?>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->im->xxd->max;?></span>
              <?php echo html::input('uploadFileSize', zget($config->xuanxuan, 'uploadFileSize', 20), "class='form-control' placeholder='{$lang->im->placeholder->xxd->uploadFileSize}' ");?>
              <span class='input-group-addon'>M</span>
            </div>
            <?php else:?>
            <?php echo $lang->im->xxd->max . zget($config->xuanxuan, 'uploadFileSize', 20) . 'M';?>
            <?php endif;?>
          </td>
          <td></td>
        </tr>
        <?php if($type == 'edit'):?>
        <tr>
          <th><?php echo $lang->im->xxdIsHttps;?></th>
          <td>
            <?php echo html::hidden('https', $https);?>
            <?php echo $type ? html::radio('https', $lang->im->httpsOptions, $https, "class='checkbox'") : $lang->im->httpsOptions[$https];?>
          </td>
          <td></td>
        </tr>
        <?php else:?>
        <tr>
          <th><?php echo $lang->im->xxdIsHttps;?></th>
          <td><?php echo zget($lang->im->httpsOptions, $https, '');?> </td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr class='sslTR <?php if($https == 'off' || empty($type)) echo 'hide';?>'>
          <th><?php echo $lang->im->xxd->sslcrt;?></th>
          <td><?php echo html::textarea('sslcrt',  zget($config->xuanxuan, 'sslcrt', ''), "placeholder='{$lang->im->placeholder->xxd->sslcrt}' class='form-control'");?></td>
          <td></td>
        </tr>
        <tr class='sslTR <?php if($https == 'off' || empty($type)) echo 'hide';?>'>
          <th><?php echo $lang->im->xxd->sslkey;?></th>
          <td><?php echo html::textarea('sslkey',  zget($config->xuanxuan, 'sslkey', ''), "placeholder='{$lang->im->placeholder->xxd->sslkey}' class='form-control'");?></td>
          <td></td>
        </tr>
        <?php if(!$type):?>
        <tr>
          <th><?php echo $lang->im->xxd->os;?></th>
          <td><?php echo html::select('os', $lang->im->osList, zget($config->xuanxuan, $os), "class='form-control chosen'");?></td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th></th>
          <td colspan='2'>
            <?php if($type == 'edit'):?>
              <?php echo html::submitButton();?>
              <?php echo html::a(helper::createLink('setting', 'xuanxuan'), $lang->goback, '', 'class="btn" style="min-width: 120px"');?>
            <?php else:?>
              <?php $disabled = $config->xuanxuan->turnon ? '' : 'disabled';?>
              <?php echo html::a(helper::createLink('setting', 'downloadXXD', 'type=package'), $lang->im->downloadXXD, 'hiddenwin', "class='btn btn-primary download download-package $disabled'");?>
              <?php echo html::a(helper::createLink('setting', 'downloadXXD', 'type=config'), $lang->im->downloadConfig, 'hiddenwin', "class='btn btn-primary download $disabled'");?>
              <?php echo html::a(helper::createLink('setting', 'xuanxuan', 'type=edit'), $lang->im->changeSetting, '', "class='btn'");?>
              <?php echo html::a('http://www.zentao.net/book/zentaopmshelp/302.html', $lang->im->help, '_blank', "class='btn'");?>
            <?php endif;?>
          </td>
        </tr>
      </table>
    </form>
    <?php if($type != 'edit'):?>
    <div class='alert alert-info mg-0'><?php echo $lang->im->info;?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../../common/view/footer.html.php';?>
