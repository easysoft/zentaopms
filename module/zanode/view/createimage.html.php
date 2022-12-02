<?php
/**
 * The close file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: cancel.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span><?php echo $lang->zanode->createImage;?></span>
      </h2>
    </div>
    <?php if($task):?>
    <h5 class='text-center'><?php echo $lang->zanode->createImaging;?></h5>
    <div class="progress progress-striped">
      <div class="progress-bar progress-bar-success rate" role="progressbar" aria-valuenow="<?php echo $task->rate;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $task->rate;?>%">
      </div>
    </div>
    <?php $link = helper::createLink('zanode', 'create');?>
    <h6 class='hide text-center success'><?php echo sprintf($lang->zanode->createImageSuccess, $link)?></h6>
    <?php else:?>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->zanode->imageName;?></th>
          <td><?php echo html::input('name', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->zanode->desc;?></th>
          <td><?php echo html::textarea('desc', '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'>
            <?php echo html::submitButton('', "onclick='if(confirm(\"{$lang->zanode->createImageNotice}\")==false) return false;'");?>
          </td>
        </tr>
      </table>
    </form>
    <?php endif;?>
  </div>
</div>

<script>
var i = 1;
var int = self.setInterval("refreshProgress()",100);
function refreshProgress()
{
    $('.rate').css('width', i + '%');
    if(i == 100)
    {
        console.log($('.rate').css);
        debugger;
        $('.success').removeClass('hide');
        clearInterval(int);
    }
    i ++  ;
}

function getTaskProgress()
{
    var url = createLink('zanode', 'ajaxGetTaskStatus', 'extranet=' + extranet + '&taskID=' + taskID + '&type=exportVm');
    $.get(url, function(data)
    {

    });
}
</script>
<?php include '../../common/view/footer.html.php';?>
