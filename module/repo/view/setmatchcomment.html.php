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
  <form class='main-form form-ajax' method='post'>
    <table class='table table-form'>
      <thead>
        <tr>
          <th class='w-90px'><?php echo $lang->repo->module;?></th>
          <th><?php echo $lang->repo->setMatchComment;?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><?php echo $lang->repo->module;?></th>
          <td>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['module'] as $module => $match):?>
              <span class='input-group-addon'><?php echo $lang->{$module}->common;?></span>
              <?php echo html::input("matchComment[module][{$module}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->task->common;?></th>
          <td>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['task'] as $method => $match):?>
              <span class='input-group-addon'><?php echo $lang->task->$method;?></span>
              <?php echo html::input("matchComment[task][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->common;?></th>
          <td>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['bug'] as $method => $match):?>
              <span class='input-group-addon'><?php echo $lang->bug->$method;?></span>
              <?php echo html::input("matchComment[bug][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->common;?></th>
          <td>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['testtask'] as $method => $match):?>
              <span class='input-group-addon'><?php echo $lang->testtask->$method;?></span>
              <?php echo html::input("matchComment[testtask][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->idAB;?></th>
          <td>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['id'] as $method => $match):?>
              <span class='input-group-addon'><?php echo $lang->repo->$method;?></span>
              <?php echo html::input("matchComment[id][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->mark;?></th>
          <td>
            <div class='input-group'>
              <?php foreach($config->repo->matchComment['mark'] as $method => $match):?>
              <span class='input-group-addon'><?php echo isset($lang->task->$method) ? $lang->task->$method : $lang->bug->$method;?></span>
              <?php echo html::input("matchComment[mark][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->matchComment->exampleLabel;?></th>
          <td id='example'></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'>
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
    replaceExample();
    $('input').keyup(function(){replaceExample()});
})

function replaceExample()
{
    exampleTaskStart = matchCommentExample['task']['start'].replace('%start%', $('[id*=start').val())
        .replace('%task%', $('[id*=module][id*=task]').val())
        .replace('%id%', $('[id*=id][id*=mark]').val())
        .replace('%split%', $('[id*=id][id*=split]').val())
        .replace('%cost%', $('[id*=task][id*=consumed]').val())
        .replace('%consumedmark%', $('[id*=mark][id*=consumed]').val())
        .replace('%left%', $('[id*=task][id*=left]').val())
        .replace('%leftmark%', $('[id*=mark][id*=left]').val());
    exampleTaskFinish = matchCommentExample['task']['finish'].replace('%finish%', $('[id*=finish]').val())
        .replace('%task%', $('[id*=module][id*=task]').val())
        .replace('%id%', $('[id*=id][id*=mark]').val())
        .replace('%split%', $('[id*=id][id*=split]').val())
        .replace('%cost%', $('[id*=task][id*=consumed]').val())
        .replace('%consumedmark%', $('[id*=mark][id*=consumed]').val());
    exampleBugResolve = matchCommentExample['bug']['resolve'].replace('%resolve%', $('[id*=bug][id*="resolve\]"]').val())
        .replace('%bug%', $('[id*=module][id*=bug]').val())
        .replace('%id%', $('[id*=id][id*=mark]').val())
        .replace('%split%', $('[id*=id][id*=split]').val())
        .replace('%resolvedBuild%', $('[id*=bug][id*=resolvedBuild]').val())
        .replace('%buildmark%', $('[id*=mark][id*=resolvedBuild]').val());

    html  = exampleTaskStart;
    html += '<br />' + exampleTaskFinish;
    html += '<br />' + exampleBugResolve;
    $('#example').html(html);
}
</script>
<?php include '../../common/view/footer.html.php';?>
