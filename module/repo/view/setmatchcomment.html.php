<?php
/**
 * The setMatchComment view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class="main-header">
    <h2><?php echo $lang->repo->setMatchComment;?></h2>
  </div>
  <form class='main-form form-ajax' method='post'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th class='w-100px'><?php echo $lang->repo->selectModule;?></th>
          <td class='w-200px'>
            <?php
            foreach($config->repo->matchComment['module'] as $module => $match) $modules[$module] = $lang->{$module}->common;
            echo html::select('selectModule', $modules, $selectModule, "class='form-control chosen'");
            ?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th class='w-100px'><?php echo $lang->repo->module;?></th>
          <td>
            <?php foreach($config->repo->matchComment['module'] as $module => $match):?>
            <div class='input-group'>
              <span class='input-group-addon hidden <?php echo $module . 'Item';?>'><?php echo $lang->{$module}->common;?></span>
              <?php echo html::input("matchComment[module][{$module}]", $match, "class='form-control hidden {$module}Item'");?>
            </div>
            <?php endforeach;?>
          </td>
        </tr>
        <tr class='taskItem hidden'>
          <th><?php echo $lang->task->common;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['task'] as $method => $match):?>
              <span class='input-group-addon'><?php echo $lang->task->$method;?></span>
              <?php echo html::input("matchComment[task][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr class='bugItem hidden'>
          <th><?php echo $lang->bug->common;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['bug'] as $method => $match):?>
              <span class='input-group-addon'><?php echo $lang->bug->$method;?></span>
              <?php echo html::input("matchComment[bug][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr class='integrationItem hidden'>
          <th><?php echo $lang->integration->common;?></th>
          <td>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['integration'] as $method => $match):?>
              <span class='input-group-addon'><?php echo $lang->integration->$method;?></span>
              <?php echo html::input("matchComment[integration][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->idAB;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['id'] as $method => $match):?>
              <span class='input-group-addon'><?php echo $lang->repo->$method;?></span>
              <?php echo html::input("matchComment[id][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr class='taskItem bugItem hidden'>
          <th><?php echo $lang->repo->mark;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['mark'] as $method => $match):?>
              <?php $module = isset($lang->task->$method) ? 'task' : 'bug';?>
              <span class='input-group-addon hidden <?php echo $module . 'Item';?>'><?php echo $lang->{$module}->$method?></span>
              <?php echo html::input("matchComment[mark][{$method}]", $match, "class='form-control hidden {$module}Item'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->matchComment->exampleLabel;?></th>
          <td colspan='2' id='example'></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php js::set('matchCommentExample', $lang->repo->matchComment->example);?>
<script>
$(function()
{
    $('#selectModule').change()
    $('input').keyup(function(){replaceExample()});
})

$('#selectModule').change(function()
{
    var module = $(this).val();
    $('[class*=Item]').addClass('hidden');
    $('.' + module + 'Item').removeClass('hidden');
    replaceExample();
})

function replaceExample()
{
    var module = $('#selectModule').val();
    var html   = '';
    if(module == 'story')
    {
        html = matchCommentExample['story']['common'].replace('%story%', $('[id*=module][id*=story]').val())
            .replace('%id%', $('[id*=id][id*=mark]').val())
            .replace('%split%', $('[id*=id][id*=split]').val());
    }
    else if(module == 'task')
    {
        html = matchCommentExample['task']['start'].replace('%start%', $('[id*=start').val())
            .replace('%task%', $('[id*=module][id*=task]').val())
            .replace('%id%', $('[id*=id][id*=mark]').val())
            .replace('%split%', $('[id*=id][id*=split]').val())
            .replace('%cost%', $('[id*=task][id*=consumed]').val())
            .replace('%consumedmark%', $('[id*=mark][id*=consumed]').val())
            .replace('%left%', $('[id*=task][id*=left]').val())
            .replace('%leftmark%', $('[id*=mark][id*=left]').val());
        html += '<br />' + matchCommentExample['task']['finish'].replace('%finish%', $('[id*=finish]').val())
            .replace('%task%', $('[id*=module][id*=task]').val())
            .replace('%id%', $('[id*=id][id*=mark]').val())
            .replace('%split%', $('[id*=id][id*=split]').val())
            .replace('%cost%', $('[id*=task][id*=consumed]').val())
            .replace('%consumedmark%', $('[id*=mark][id*=consumed]').val());
    }
    else if(module == 'bug')
    {
        html = matchCommentExample['bug']['resolve'].replace('%resolve%', $('[id*=bug][id*="resolve\]"]').val())
            .replace('%bug%', $('[id*=module][id*=bug]').val())
            .replace('%id%', $('[id*=id][id*=mark]').val())
            .replace('%split%', $('[id*=id][id*=split]').val())
            .replace('%resolvedBuild%', $('[id*=bug][id*=resolvedBuild]').val())
            .replace('%buildmark%', $('[id*=mark][id*=resolvedBuild]').val());
    }
    else if(module == 'integration')
    {
        html = matchCommentExample['integration']['start'].replace('%build%', $('[id*=integration][id*=start]').val())
            .replace('%integration%', $('[id*=module][id*=integration]').val())
            .replace('%id%', $('[id*=id][id*=mark]').val())
            .replace('%split%', $('[id*=id][id*=split]').val())
    }

    $('#example').html(html);
}
</script>
<?php include '../../common/view/footer.html.php';?>
