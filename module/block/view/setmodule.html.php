<?php
/**
 * The setmodule view of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id: html.php 7488 2013-12-26 07:26:10Z zhujinyong $
 * @link        http://www.zentao.net
 */
?>
<?php
$webRoot   = $config->webRoot;
$jsRoot    = $webRoot . "js/";
$themeRoot = $webRoot . "theme/";
?>
<?php if(!empty($params) && is_array($params) && isset($params['type'])):?>
<?php $param = $params['type'];?>
<div class='form-group'>
  <label for='type' class='col-sm-3'><?php echo $param['name']?></label>
  <div class='col-sm-7'>
    <?php
    $control = $param['control'];
    $attr    = empty($param['attr']) ? '' : $param['attr'];
    $default = $block ? (isset($block->params->type) ? $block->params->type : '') : (isset($param['default']) ? $param['default'] : '');
    $options = isset($param['options']) ? $param['options'] : array();
    echo html::$control("params[type]", $options, $default, "class='form-control chosen' $attr");
    ?>
  </div>
</div>
<?php unset($params['type']);?>
<?php endif;?>
<?php include './publicform.html.php'?>
<?php echo html::hidden('actionLink', $this->createLink('block', 'set', "id=$id&type=$type&source=$source"));?>
<?php if(!empty($params) && is_array($params)):?>
<?php foreach($params as $key => $param):?>
<div class='form-group'>
  <label for='<?php echo $key?>' class='col-sm-3'><?php echo $param['name']?></label>
  <div class='col-sm-7'>
    <?php
    if(!isset($param['control'])) $param['control'] = 'input';
    if(!method_exists('html', $param['control'])) $param['control'] = 'input';

    $control = $param['control'];
    $attr    = empty($param['attr']) ? '' : $param['attr'];
    $default = $block ? (isset($block->params->$key) ? $block->params->$key : '') : (isset($param['default']) ? $param['default'] : '');
    $options = isset($param['options']) ? $param['options'] : array();
    if($control == 'select' or $control == 'radio' or $control == 'checkbox')
    {
        $chosen = $control == 'select' ? 'chosen' : '';
        if(strpos($attr, 'multiple') !== false)
        {
            echo html::$control("params[$key][]", $options, $default, "class='form-control " . $chosen . "' $attr");
        }
        else
        {
            echo html::$control("params[$key]", $options, $default, "class='form-control " . $chosen . "' $attr");
        }
    }
    else
    {
        echo html::$control("params[$key]", $default, "class='form-control' $attr");
    }
    ?>
  </div>
</div>
<?php endforeach;?>
<?php endif;?>
<?php if(!isset($block->name)):?>
<script>
$(function()
{
    var module = $('#modules').find('option:selected').text();
    var block  = $('#moduleBlock').find('option:selected').text();
    if($('#title').val() == '')
    {
        if(module) $('#title').val(module);
        if(block)  $('#title').val(block);
    }
})
</script>
<?php endif;?>
