<?php
/**
 * The browse view file of client module of XXB.
 *
 * @copyright   Copyright 2009-2017 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     client
 * @version     $Id$
 * @link        http://xuan.im
 */
$this->app->loadLang('im');
$position[] = html::a($this->createLink('admin', 'xuanxuan'), $this->lang->im->common);
$position[] = $this->lang->client->browse;
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <div class='pull-right'>
      <?php common::printLink('client', 'create', '', $lang->client->create, '', "class='btn' data-toggle='modal'");?>
    </div>
    <div class='heading'>
      <h4><?php echo $lang->client->browseVersion;?></h4>
    </div>
  </div>
  <table class='table table-hover'>
    <thead>
    <tr class="text-center">
      <th class="w-80px"><?php echo $lang->client->id?></th>
      <th class="w-150px"><?php echo $lang->client->version?></th>
      <th class="text-left"><?php echo $lang->client->desc?></th>
      <th class="w-150px"><?php echo $lang->client->strategy?></th>
      <th class="w-150px"><?php echo $lang->client->releaseStatus?></th>
      <th class="w-120px"><?php echo $lang->actions?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($clients as $client):?>
    <tr class="text-center">
      <td><?php echo $client->id?></td>
      <td><?php echo $client->version?></td>
      <td class="text-left" title='<?php echo $client->desc;?>'><?php echo $client->desc?></td>
      <td><?php echo $lang->client->strategies[$client->strategy]?></td>
      <td><?php echo $lang->client->status[$client->status]?></td>
      <td>
        <?php commonModel::printLink('client', 'edit', "id={$client->id}", $lang->edit, '', "data-toggle='modal'");?>
        <?php commonModel::printLink('client', 'delete', "id={$client->id}", $lang->delete, '', "class='deleter'");?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php if($this->config->edition == 'open'):?>
<script>
$(function()
{
    $('.deleter').on('click', function()
    {
        $deleter = $(this);
        if(confirm('<?php echo $lang->confirmDelete?>'))
        {
            $.getJSON($deleter.attr('href'), function(data)
            {
                if(data.result != 'success') alert(data.message);
                return location.reload();
            });
        }
        return false;
    });
})
</script>
<?php endif;?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
