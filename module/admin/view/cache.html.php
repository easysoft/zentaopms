<?php
/**
 * The cache view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='mw-500px'>
    <?php if(!function_exists('date_default_timezone_set')):?>
    <div class='alert alert-warning'><?php echo $lang->custom->notice->cannotSetTimezone;?></div>
    <?php else:?>
    <form class="load-indicator main-form form-ajax" method='post'>
      <table class='table table-form'>
        <tr>
          <th class='w-100px'><?php echo $lang->admin->daoCache;?></th>
          <td class='w-400px'><?php echo html::radio('enable', $lang->admin->cacheStatusList, $config->cache->dao->enable);?></td>
          <td class='w-400px'></td>
        </tr>
        <?php if($helper::isAPCuEnabled()):?>
        <tr>
          <th><?php echo $lang->admin->memory;?></th>
          <td>
            <div class='progress' style='margin-bottom: 0px;'>
              <div class="progress-bar progress-bar-<?php echo $rate <= 50 ? 'success' : ($rate <= 80 ? 'warning' : 'danger');?>" role="progressbar" aria-valuenow="<?php echo $rate;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $rate;?>%"></div>
            </div>
          </td>
          <td>
            <span style='margin-left: 20px;'><?php echo $rate . '%';?></span>
            <span style='margin-left: 20px;'><?php printf($lang->admin->usedMemory, $total, $used);?></span>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th></th>
          <td colspan='2' class='form-actions text-left'>
            <?php echo html::submitButton();?>
            <?php if(helper::isAPCuEnabled() && $config->cache->dao->enable) echo html::a(inlink('ajaxClearCache'), $lang->admin->clearCache, '', "class='btn btn-warning btn-wide' id='clearCache'");?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
    <?php endif;?>
  </div>
</div>
<script>
$('#clearCache').click(function()
{
    const url = $(this).attr('href');
    bootbox.confirm("<?php echo $lang->admin->clearConfirm;?>", function(result)
    {
        if(result)
        {
            $.getJSON(url, function(response)
            {
                if(response.result == 'fail' && response.message) bootbox.alert(response.message);
                else window.location.reload();
            });
        }
    });
    return false;
});
</script>
<?php include '../../common/view/footer.html.php';?>
