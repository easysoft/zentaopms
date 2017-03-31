<?php
/**
 * The project block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php $jsRoot = $this->config->webRoot . 'js/';?>
<?php include '../../common/view/sparkline.html.php';?>
<?php $projectboxId = 'projectbox-' . rand(); ?>
<div id='<?php echo $projectboxId ?>'>
  <table class='table tablesorter table-data table-hover table-striped table-fixed block-project'>
    <thead>
      <tr class='text-center'>
        <th class='text-left'><?php echo $lang->project->name;?></th>
        <th width='80'><?php echo $lang->project->end;?></th>
        <th width='50'><?php echo $lang->statusAB;?></th>
        <th width='45'><?php echo $lang->project->totalEstimate;?></th>
        <th width='45'><?php echo $lang->project->totalConsumed;?></th>
        <th width='45'><?php echo $lang->project->totalLeft;?></th>
        <th width='115'><?php echo $lang->project->progess;?></th>
        <th width='100' class='{sorter: false}'><?php echo $lang->project->burn;?></th>
      </tr>
    </thead>
    <tbody>
     <?php $id = 0; ?>
     <?php foreach($projectStats as $project):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn text-center' data-id='{$this->get->entry}'" : "class='text-center'";
      $viewLink = $this->createLink('project', 'task', 'project=' . $project->id);
      ?>
      <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <td class='text-left' title='<?php echo $project->name;?>'><?php echo html::a($this->createLink('project', 'task', 'project=' . $project->id), $project->name, '', "title='$project->name'");?></td>
        <td><?php echo $project->end;?></td>
        <?php if(isset($project->delay)):?>
        <td><?php echo $lang->project->delayed;?></td>
        <?php else:?>
        <td class='status-<?php echo $project->status?>'><?php echo $lang->project->statusList[$project->status];?></td>
        <?php endif;?>
        <td><?php echo $project->hours->totalEstimate;?></td>
        <td><?php echo $project->hours->totalConsumed;?></td>
        <td><?php echo $project->hours->totalLeft;?></td>
        <td class='text-left'>
          <img class='progressbar' src='<?php echo $this->app->getWebRoot();?>theme/default/images/main/green.png' alt='' height='16' width='<?php echo $project->hours->progress == 0 ? 1 : round($project->hours->progress * 0.7);?>'>
          <small><?php echo $project->hours->progress;?>%</small>
        </td>
        <td id='spark-<?php echo $id++?>' class='spark text-left pd-0' values='<?php echo join(',', $project->burns);?>'></td>
     </tr>
     <?php endforeach;?>
    </tbody>
  </table>
</div>
<script>
$(function()
{
    var $projectbox = $('#<?php echo $projectboxId ?>');
    var $sparks = $projectbox.find('.spark');
    $sparks.filter(':lt(6)').addClass('sparked').projectLine();
    $sparks = $sparks.not('.sparked');
    var rowHeight = $sparks.first().closest('tr').outerHeight() - ($.zui.browser.ie === 8 ? 0.3 : 0);

    var scrollFn = false, scrollStart = 6, i, id, $spark;
    $projectbox.parent().on('scroll.spark', function(e)
    {
        if(!$sparks.length)
        {
            $projectbox.off('scroll.spark');
            return;
        }
        if(scrollFn) clearTimeout(scrollFn);

        scrollFn = setTimeout(function()
        {
            for(i = scrollStart; i <= scrollStart + 10; i++)
            {
                id = '#spark-' + i;
                $spark = $(id);
                if($spark.hasClass('sparked')) continue;
                $spark.addClass('sparked').projectLine();
                $sparks = $sparks.not(id);
            }
            scrollStart += 10;
        },100);
    });
});
</script>
