<?php
/**
 * The setmodule view of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block 
 * @version     $Id: html.php 7488 2013-12-26 07:26:10Z zhujinyong $
 * @link        http://www.ranzhico.com
 */
?>
<?php
$webRoot   = $config->webRoot;
$jsRoot    = $webRoot . "js/";
$themeRoot = $webRoot . "theme/";
?>
<form method='post' id='blockForm' target='hiddenwin' class='form form-horizontal' action='<?php echo $this->createLink('block', 'set', "id=$id&type=$type&source=$source")?>'>
  <table class='table table-form'>
    <tbody>
      <?php include './publicform.html.php'?>
      <?php foreach($params as $key => $param):?>
      <tr>
        <th><?php echo $param['name']?></th>
        <td>
        <?php
          if(!isset($param['control'])) $param['control'] = 'input';
          if(!method_exists('html', $param['control'])) $param['control'] = 'input';

          $control = $param['control'];
          $attr    = empty($param['attr']) ? '' : $param['attr'];
          $default = $block ? (isset($block->params->$key) ? $block->params->$key : '') : (isset($param['default']) ? $param['default'] : '');
          $options  = isset($param['options']) ? $param['options'] : array();
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
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr><th></th><td><?php echo html::submitButton()?></td></tr>
    </tfoot>
  </table>
</form>
<?php if(!isset($block->name)):?>
<script>
$(function()
{
    options = $('#moduleBlock').find("option").text();
    if($('#title').val() == '') $('#title').val($('#moduleBlock').find("option:selected").text());
})
</script>
<?php endif;?>
