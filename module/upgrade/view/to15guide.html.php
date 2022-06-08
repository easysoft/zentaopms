<?php
/**
 * The to20 view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='panel' style='padding:50px; margin:50px 300px;'>
    <form method='post'>
      <div class='panel-title text-center'>
        <?php
        if($config->edition == 'max')
        {
            echo $lang->upgrade->toMAXGuide;
        }
        elseif($config->edition == 'biz')
        {
            echo $lang->upgrade->toBIZ5Guide;
        }
        else
        {
            echo $lang->upgrade->toPMS15Guide;
        }
        ?>
      </div>
      <div class='panel-body'>
        <div style='max-width:900px; margin: auto;'>
          <?php echo $lang->upgrade->to15Desc;?>
          <?php $systemMode = isset($lang->upgrade->to15Mode['classic']) ? 'classic' : 'new';?>
          <?php echo html::radio('mode', $lang->upgrade->to15Mode, $systemMode);?>
          <div id='selectedModeTips' class='text-info'><?php echo $lang->upgrade->selectedModeTips[$systemMode];?></div>
        </div>
      </div>
      <hr/>
      <div class='panel-footer text-center'>
        <div id='upgradeTips' class='text-danger hidden'><?php echo $lang->upgrade->upgradeTips;?></div>
        <?php echo html::submitButton($lang->upgrade->start . (strpos($this->app->getClientLang(), 'zh') === false ? ' ' : '') . $lang->upgrade->common);?>
      </div>
    </form>
  </div>
</div>
<?php js::set('selectedModeTips', $lang->upgrade->selectedModeTips);?>
<script>
$(function()
{
    $('[name=mode]').change(function()
    {
        $('#selectedModeTips').html(selectedModeTips[$(this).val()]);
        $(this).val() == 'new' ? $('#upgradeTips').removeClass('hidden') : $('#upgradeTips').addClass('hidden');
    })
})
</script>
<?php include '../../common/view/footer.lite.html.php';?>
