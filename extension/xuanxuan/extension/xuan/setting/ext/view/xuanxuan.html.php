<?php
/**
 * The configure xuanxuan view file of setting module of RanZhi.
 *
 * @copyright   Copyright 2009-2017 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     setting
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $this->app->loadLang('client')?>
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
          <th class='w-130px'><?php echo $lang->im->version;?></th>
          <td class='w-p30'><?php echo $config->xuanxuan->version;?></td>
        </tr>
        <tr>
          <th class='w-130px'><?php echo $lang->im->turnon;?></th>
          <td class='w-p30'><?php echo $type == 'edit' ? html::radio('turnon', $lang->im->turnonList, $turnon) : zget($lang->im->turnonList, $config->xuanxuan->turnon);?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->key;?></th>
          <td class='code'>
            <?php $key   = zget($config->xuanxuan, 'key', '');?>
            <?php $style = (!$key or $key == str_repeat(8, 32)) ? "style='margin-bottom: 0px; border-color: rgb(149, 59, 57);'" : '';?>
            <?php echo $type == 'edit' ? html::input('key', zget($config->xuanxuan, 'key', ''), "class='form-control' readonly='readonly' $style") : zget($config->xuanxuan, 'key', '');?>
            <?php if($type == 'edit' && (!$key or $key == str_repeat(8, 32))):?>
            <span id="keyLabel" for="key" class="text-error red"><?php echo !$key ? $lang->im->errorKey : $lang->im->defaultKey;?></span>
            <?php endif;?>
          </td>
          <td><?php echo $type == 'edit' ? html::a('javascript:void(0)', $lang->im->createKey, '', 'onclick="createKey()"') : '';?></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->backendLang;?></th>
          <td><?php echo $type == 'edit' ? html::select('backendLang', $config->langs, $config->xuanxuan->backendLang, "class='form-control'") : zget($lang->setting->langs, $config->xuanxuan->backendLang, '');?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->xxdServer;?></th>
          <td><?php echo $type == 'edit' ? html::input('server', $domain, "class='form-control'") : $domain;?></td>
          <td class='text-muted'><?php if($type == 'edit') echo $lang->im->xxdServerTip;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->pollingInterval;?></th>
          <td><?php echo $type == 'edit' ? html::input('pollingInterval', zget($config->xuanxuan, 'pollingInterval', 60), " class='form-control'") : zget($config->xuanxuan, 'pollingInterval', 60) . $lang->im->secs;?></td>
          <td class='text-muted'><?php if($type == 'edit') echo $lang->im->xxdPollIntTip;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->xxd->ip;?></th>
          <td class='code'><?php echo $type == 'edit' ? html::input('ip', zget($config->xuanxuan, 'ip', '0.0.0.0'), "class='form-control' placeholder='{$lang->im->placeholder->xxd->ip}'") : zget($config->xuanxuan, 'ip', '0.0.0.0');?></td>
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
        <tr>
          <th><?php echo $lang->im->xxd->aes;?></th>
          <td>
            <?php $aes = zget($config->xuanxuan, 'aes', 'on');?>
            <?php echo $type == 'edit' ? html::radio('aes', $lang->im->aesOptions, $aes, "class='checkbox'") : zget($lang->im->aesOptions, $aes);?>
          </td>
          <td class='text-muted'><?php if($type == 'edit') echo $lang->im->xxdAESTip;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->im->xxd->https;?></th>
          <td>
            <?php $https = zget($config->xuanxuan, 'https', 'off');?>
            <?php echo $type == 'edit' ? html::radio('https', $lang->im->httpsOptions, $https, "class='checkbox'") : zget($lang->im->httpsOptions, $https);?>
          </td>
          <td></td>
        </tr>
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
        <tr>
          <th><?php echo $lang->im->debug;?></th>
          <td>
            <?php $debug = zget($config->xuanxuan, 'debug', 0);?>
            <?php echo $type == 'edit' ? html::radio('debug', $lang->im->debugStatus, $debug) : zget($lang->im->debugStatus, $debug);?>
          </td>
          <td></td>
        </tr>
        <?php if($type != 'edit'):?>
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
              <?php if(common::hasPriv('im', 'downloadXxdPackage')) echo html::a(helper::createLink('setting', 'downloadXXD', 'type=package'), $lang->im->downloadXXD, 'hiddenwin', "class='btn btn-primary download download-package $disabled'" . " $disabled");?>
              <?php echo html::a(helper::createLink('setting', 'downloadXXD', 'type=config'), $lang->im->downloadConfig, 'hiddenwin', "class='btn btn-primary download $disabled'" . " $disabled");?>
              <?php echo html::a(helper::createLink('setting', 'xuanxuan', 'type=edit'), $lang->im->changeSetting, '', "class='btn'");?>
              <?php if($debug and common::hasPriv('im', 'debug')) echo html::a(helper::createLink('im', 'debug', 'source=setting'), $lang->im->viewDebug, '', "class='btn viewDebug iframe' data-height=220");?>
              <?php echo html::a('http://www.zentao.net/book/zentaopmshelp/302.html', $lang->im->help, '_blank', "class='btn'");?>
            <?php endif;?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
