<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong 
 * @package     repo
 * @version     $Id: blame.html.php $
 */
?>
<?php 
include '../../common/view/header.html.php';
js::import($jsRoot  . 'misc/highlight/highlight.pack.js');
css::import($jsRoot . 'misc/highlight/styles/github.css');
?>
<?php if(!isonlybody()):?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php
    $backURI = $this->session->repoView ? $this->session->repoView : $this->session->repoList;
    if($backURI)
    {
        echo html::a($backURI, "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-link'");
    }
    else
    {
        echo html::backButton("<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', 'btn btn-link');
    }
    ?>
    <div class="divider"></div>
    <div class="page-title">
      <strong>
        <?php
        echo html::a($this->repo->createLink('browse', "repoID=$repoID"), $repo->name);
        $paths= explode('/', $entry);
        $fileName = array_pop($paths);
        $postPath = '';
        foreach($paths as $pathName)
        {
            $postPath .= $pathName . '/';
            echo '/' . ' ' . html::a($this->repo->createLink('browse', "repoID=$repoID", "path=" . $this->repo->encodePath($postPath)), trim($pathName, '/'));
        }
        echo '/' . ' ' . $fileName;
        echo " <span class='label label-info'>" . $revisionName . '</span>';
        ?>
      </strong>
    </div>
  </div>
</div>
<?php endif;?>

<div class="code panel">
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $entry;?></div>
    <?php $encodePath = $this->repo->encodePath($entry);?>
    <div class='panel-actions'>
      <div class='btn-group'>
        <?php echo html::commonButton(zget($lang->repo->encodingList, $encoding, $lang->repo->encoding) . "<span class='caret'></span>", "id='encoding' data-toggle='dropdown'", 'btn dropdown-toggle')?>
        <ul class='dropdown-menu' role='menu' aria-labelledby='encoding'>
          <?php foreach($lang->repo->encodingList as $key => $val):?>
          <li><?php echo html::a($this->repo->createLink('blame', "repoID=$repoID&entry=&revision=$revision&encoding=$key", "entry=$encodePath"), $val)?></li>
          <?php endforeach;?>
        </ul>
      </div>
    </div>
  </div>
  <div class="content">
    <table class="blame table table-form table-fixed">
      <thead>
        <tr>
          <td class='w-70px'><?php echo $lang->repo->revision?></td>
          <?php if($repo->SCM == 'Git'):?>
          <td class='w-50px'><?php echo $lang->repo->commit?></td>
          <?php endif;?>
          <td class='w-100px'><?php echo $lang->repo->committer?></td>
          <td class="w-40px"><?php echo $lang->repo->line?></td>
          <td><?php echo $lang->repo->code?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach($blames as $blame):?>
        <tr<?php if(isset($blame['lines'])) echo " class='topLine'";?>>
          <?php 
          if(isset($blame['lines']))
          {
              $rowspan = $blame['lines'];
              echo '<td rowspan="' . $rowspan . '" class="info" title="' . $blame['revision'] . '">';
              echo $repo->SCM == 'Git' ? substr($blame['revision'], 0, 10) : $blame['revision'];
              echo '</td>';
              if($repo->SCM == 'Git') echo '<td rowspan="' . $rowspan . '" class="info">' . zget($historys, $blame['revision'], '') . '</td>';
              echo '<td rowspan="' . $rowspan . '" class="info">' . $blame['committer'] . '</td>'; 
          }
          ?>
          <td class="line"><?php echo $blame['line'];?></td>
          <td><pre><?php echo htmlspecialchars($blame['content']);?></pre></td>
        </tr>
        <?php endforeach?>
      </tbody> 
    </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
