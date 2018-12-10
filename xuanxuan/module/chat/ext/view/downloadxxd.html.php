<?php
/**
 * The downloadxxd view file of chat module of Zentao.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     chart
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<form id='jaxForm' class='form-horizontal' action='<?php echo inlink('downloadxxd')?>' method='post' target='_blank'>
  <table class='table table-form'>
    <tr>
      <th class='w-110px'><?php echo $lang->chat->xxd->os;?></th>
      <td><?php echo html::select('os', $lang->chat->osList, zget($this->config->xxd, 'os', 'linux64'), "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th class='w-110px'><?php echo $lang->chat->xxd->ip;?></th>
      <td><?php echo html::input('ip', zget($this->config->xxd, 'ip', '0.0.0.0'), "class='form-control' placeholder='{$lang->chat->placeholder->xxd->ip}'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->chat->xxd->chatPort;?></th>
      <td><?php echo html::input('chatPort', zget($this->config->xxd, 'chatPort', 11444), "placeholder='{$lang->chat->placeholder->xxd->chatPort}' class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->chat->xxd->commonPort;?></th>
      <td><?php echo html::input('commonPort',  zget($this->config->xxd, 'commonPort', 11443), "placeholder='{$lang->chat->placeholder->xxd->commonPort}' class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->chat->xxd->isHttps;?></th>
      <td><?php echo html::radio('isHttps', $lang->chat->httpsOptions, zget($this->config->xxd, 'isHttps', '1'), "class='checkbox'");?></td>
    </tr>
    <tr class='sslTR'>
      <th><?php echo $lang->chat->xxd->sslcrt;?></th>
      <td><?php echo html::textarea('sslcrt',  zget($this->config->xxd, 'sslcrt', ''), "placeholder='{$lang->chat->placeholder->xxd->sslcrt}' class='form-control'");?></td>
    </tr>
    <tr class='sslTR'>
      <th><?php echo $lang->chat->xxd->sslkey;?></th>
      <td><?php echo html::textarea('sslkey',  zget($this->config->xxd, 'sslkey', ''), "placeholder='{$lang->chat->placeholder->xxd->sslkey}' class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->chat->xxd->uploadFileSize;?></th>
      <td>
        <div class='input-group'>
          <?php echo html::input('uploadFileSize', zget($this->config->xxd, 'uploadFileSize', 20), "class='form-control' placeholder='{$lang->chat->placeholder->xxd->chatPort}' ");?>
          <span class='input-group-addon'>M</span>
        </div>
      </td>
    </tr>
    <tr>
      <th></th>
      <td>
        <?php echo html::a('javascript:;', $lang->chat->downloadConfig,  '', "class='btn btn-primary btn-download' data-type='config'");?>
        <?php echo html::a('javascript:;', $lang->chat->downloadPackage, '', "class='btn btn-primary btn-download' data-type='package'");?>
        <?php echo html::hidden('downloadType', "config");?>
        <?php echo html::submitButton('', '', "hide");?>
      </td>
    </tr>
  </table>
</form>
<script>
$(function()
{
    $('input[name^=isHttps]').change(function()
    {   
        $('.sslTR').toggle();
    })
    $('.btn-download').click(function()
    {
        $('#downloadType').val($(this).data('type'));
        $(this).parent().find('#submit').click();
    });    
})
</script>
