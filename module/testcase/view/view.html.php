<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 5000 2013-07-03 08:20:57Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?> <strong><?php echo $case->id;?></strong></span>
    <strong><?php echo $case->title;?></strong>
    <?php if($case->deleted):?>
    <span class='label label-danger'><?php echo $lang->case->deleted;?></span>
    <?php endif; ?>
    <?php if($case->version > 1):?>
    <small class='dropdown'>
      <a href='#' data-toggle='dropdown' class='text-muted'><?php echo '#' . $version;?> <span class='caret'></span></a>
      <ul class='dropdown-menu'>
      <?php
      for($i = $case->version; $i >= 1; $i --)
      {
          $class = $i == $version ? " class='active'" : '';
          echo '<li' . $class .'>' . html::a(inlink('view', "caseID=$case->id&version=$i"), '#' . $i) . '</li>'; 
      }
      ?>
      </ul>
    </small>
    <?php endif; ?>
  </div>
  <div class='actions'>
    <?php
    $browseLink  = $app->session->caseList != false ? $app->session->caseList : $this->createLink('testcase', 'browse', "productID=$case->product");
    $actionLinks = '';
    if(!$case->deleted)
    {
        ob_start();

        echo "<div class='btn-group'>";
        common::printIcon('testtask', 'runCase', "runID=$runID&caseID=$case->id&version=$case->currentVersion", '', 'button', '', '', 'runCase');
        common::printIcon('testtask', 'results', "runID=$runID&caseID=$case->id&version=$case->version", '', 'button', '', '', 'results');

        if($case->lastRunResult == 'fail') common::printIcon('testcase', 'createBug', "product=$case->product&extra=caseID=$case->id,version=$case->version,runID=$runID", '', 'button', 'bug', '', 'iframe');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('testcase', 'edit',"caseID=$case->id");
        common::printCommentIcon('testcase');
        common::printIcon('testcase', 'create', "productID=$case->product&moduleID=$case->module&from=testcase&param=$case->id", '', 'button', 'copy');
        common::printIcon('testcase', 'delete', "caseID=$case->id", '', 'button', '', 'hiddenwin');
        echo '</div>';
        
        echo "<div class='btn-group'>";
        common::printRPN($browseLink, $preAndNext, inlink('view', "caseID=%s&version=0&testtask=$from&taskID=$taskID"));
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_clean();
        echo $actionLinks;
    }
    else
    {
        common::printRPN($browseLink);
    }
    ?>
  </div>
</div>

<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->testcase->precondition;?></legend>
        <?php echo nl2br($case->precondition);?>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->testcase->stepDesc;?></legend>
        <table class='table table-condensed table-hover table-striped'>
          <thead>
            <tr>
              <th class='w-40px'><?php echo $lang->testcase->stepID;?></th>
              <th class='w-p70'><?php echo $lang->testcase->stepDesc;?></th>
              <th><?php echo $lang->testcase->stepExpect;?></th>
            </tr>
          </thead>
          <?php
          foreach($case->steps as $stepID => $step)
          {
              $stepID += 1;
              echo "<tr><th class='w-id text-center strong'>$stepID</th>";
              echo "<td>" . nl2br($step->desc) . "</td>";
              echo "<td>" . nl2br($step->expect) . "</td>";
              echo "</tr>";
          }
          ?>
        </table>
      </fieldset>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $case->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'><?php echo $actionLinks;?></div>
      <fieldset id='commentBox' class='hide'>
        <legend><?php echo $lang->comment;?></legend>
        <form method='post' action='<?php echo inlink('edit', "caseID=$case->id&comment=true")?>'>
          <div class="form-group"><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></div>
          <?php echo html::submitButton() . html::backButton();?>
        </form>
      </fieldset>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->testcase->legendBasicInfo;?></legend>
        <table class='table table-data table-condensed table-borderless table-fixed'>
          <tr>
            <th class='w-60px'><?php echo $lang->testcase->product;?></th>
            <td><?php if(!common::printLink('testcase', 'browse', "productID=$case->product", $productName)) echo $productName;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->module;?></th>
            <td>
              <?php 
              if(empty($modulePath))
              {
                  echo "/";
              }
              else
              {
                 foreach($modulePath as $key => $module)
                 {
                     if(!common::printLink('testcase', 'browse', "productID=$case->product&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
                     if(isset($modulePath[$key + 1])) echo $lang->arrow;
                 }
              }
              ?>
            </td>
          </tr>
          <tr class='nofixed'>
            <th><?php echo $lang->testcase->story;?></th>
            <td>
                <?php
                if(isset($case->storyTitle)) echo html::a($this->createLink('story', 'view', "storyID=$case->story"), "#$case->story:$case->storyTitle");
                if($case->story and $case->storyStatus == 'active' and $case->latestStoryVersion > $case->storyVersion)
                {
                    echo "(<span class='warning'>{$lang->story->changed}</span> ";
                    echo html::a($this->createLink('testcase', 'confirmStoryChange', "caseID=$case->id"), $lang->confirm, 'hiddenwin');
                    echo ")";
                }
                ?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->type;?></th>
            <td><?php echo $lang->testcase->typeList[$case->type];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->stage;?></th>
            <td>
              <?php 
              if($case->stage)
              {
                  $stags = explode(',', $case->stage);
                  foreach($stags as $stage)
                  {
                      isset($lang->testcase->stageList[$stage]) ? print($lang->testcase->stageList[$stage]) : print($stage);
                      echo "<br />";
                  }
              }
              ?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->pri;?></th>
            <td><?php echo $case->pri;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->status;?></th>
            <td>
              <?php 
              echo $lang->testcase->statusList[$case->status];
              if($case->version > $case->currentVersion and $from == 'testtask')
              {
                  echo " (<span class='warning'>{$lang->testcase->changed}</span> ";
                  echo html::a($this->createLink('testcase', 'confirmchange', "caseID=$case->id"), $lang->confirm, 'hiddenwin');
                  echo ")";
              }
              ?>
            </td>
          </tr>
           <tr>
            <th><?php echo $this->app->loadLang('testtask')->testtask->lastRunTime;?></th>
            <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo $case->lastRunDate;?></td>
          </tr>
          <tr>
            <th><?php echo $this->app->loadLang('testtask')->testtask->lastRunResult;?></th>
            <td><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->keywords;?></th>
            <td><?php echo $case->keywords;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->linkCase;?></th>
            <td>
              <?php
              if(isset($case->linkCaseTitles))
              {
                  foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
                  {
                      echo html::a($this->createLink('testcase', 'view', "caseID=$linkCaseID"), "#$linkCaseID $linkCaseTitle", '_blank') . '<br />';
                  }
              }
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->testcase->legendLinkBugs;?></legend>
        <table class='table table-data table-condensed table-borderless'>
          <tr>
            <th class='w-60px'><?php echo $lang->testcase->fromBug;?></th>
            <td><?php if($case->fromBug) echo html::a($this->createLink('bug', 'view', "bugID=$case->fromBug"), $case->fromBugTitle);?></td>
          </tr>
          <tr>
            <th valign="top"><?php echo $lang->testcase->toBug;?></th>
            <td>
            <?php 
            foreach($case->toBugs as $bugID => $bugTitle) 
            {
                echo '<p style="margin-bottom:0;">' . html::a($this->createLink('bug', 'view', "bugID=$bugID"), $bugTitle) . '</p>';
            }
            ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->testcase->legendOpenAndEdit;?></legend>
        <table class='table table-data table-condensed table-borderless'>
          <tr>
            <th class='w-60px'><?php echo $lang->testcase->openedBy;?></th>
            <td><?php echo $users[$case->openedBy] . $lang->at . $case->openedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->lblLastEdited;?></th>
            <td><?php if($case->lastEditedBy) echo $users[$case->lastEditedBy] . $lang->at . $case->lastEditedDate;?></td>
          </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
