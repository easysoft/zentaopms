<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<div id='titlebar' <?php if($case->deleted) echo "class='deleted'";?>>
  <div id='main'>CASE #<?php echo $case->id . ' ' . $case->title;?></div>
  <div>
    <?php
    $browseLink  = $app->session->caseList != false ? $app->session->caseList : $this->createLink('testcase', 'browse', "productID=$case->product");
    $actionLinks = '';
    if(!$case->deleted)
    {
        ob_start();

        common::printIcon('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->currentVersion");
        common::printIcon('testtask', 'results', "runID=0&caseID=$case->id&version=$case->version");

        common::printIcon('testcase', 'createBug', "product=$case->product&extra=caseID=$case->id,version=$case->version,runID=", '', 'button', 'createBug');

        common::printDivider();
        common::printIcon('testcase', 'edit',"caseID=$case->id");
        common::printCommentIcon('testcase');
        common::printIcon('testcase', 'create', "productID=$case->product&moduleID=$case->module&from=testcase&param=$case->id", '', 'button', 'copy');
        common::printIcon('testcase', 'delete', "caseID=$case->id", '', 'button', '', 'hiddenwin');
        
        common::printDivider();
        common::printRPN($browseLink, $preAndNext);

        $actionLinks = ob_get_contents();
        ob_clean();
        echo $actionLinks;
    }
    ?>
  </div>
</div>

<table class='cont-rt5'>
  <tr valign='top'>
    <td>
      <fieldset>
        <legend><?php echo $lang->testcase->precondition;?></legend>
        <?php echo $case->precondition;?>
      </fieldset>
      <table class='table-1 colored'>
        <tr class='colhead'>
          <th class='w-30px'><?php echo $lang->testcase->stepID;?></th>
          <th class='w-p70'><?php echo $lang->testcase->stepDesc;?></th>
          <th><?php echo $lang->testcase->stepExpect;?></th>
        </tr> 
        <?php
        foreach($case->steps as $stepID => $step)
        {
            $stepID += 1;
            echo "<tr><th class='rowhead w-id a-center strong'>$stepID</th>";
            echo "<td>" . nl2br($step->desc) . "</td>";
            echo "<td>" . nl2br($step->expect) . "</td>";
            echo "</tr>";
        }
        ?>
      </table>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $case->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='a-center actionlink'><?php echo $actionLinks;?></div>
      <div id='comment' class='hidden'>
        <fieldset>
          <legend><?php echo $lang->comment;?></legend>
          <form method='post' action='<?php echo inlink('edit', "caseID=$case->id&comment=true")?>'>
            <table align='center' class='table-1'>
            <tr><td><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></td></tr>
            <tr><td><?php echo html::submitButton() . html::resetButton();?></td></tr>
            </table>
          </form>
        </fieldset>
      </div>
    </td>
    <td class='divider'></td>
    <td class='side'>
      <fieldset>
        <legend><?php echo $lang->testcase->legendBasicInfo;?></legend>
        <table class='table-1 a-left fixed'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->testcase->product;?></td>
            <td><?php if(!common::printLink('testcase', 'browse', "productID=$case->product", $productName)) echo $productName;?></td>
          </tr>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->testcase->module;?></td>
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
            <td class='rowhead'><?php echo $lang->testcase->story;?></td>
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
            <td class='rowhead w-p20'><?php echo $lang->testcase->type;?></td>
            <td><?php echo $lang->testcase->typeList[$case->type];?></td>
          </tr>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->testcase->stage;?></td>
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
            <td class='rowhead'><?php echo $lang->testcase->pri;?></td>
            <td><?php echo $case->pri;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->status;?></td>
            <td><?php echo $lang->testcase->statusList[$case->status];?></td>
          </tr>
           <tr>
            <td class='rowhead'><?php echo $this->app->loadLang('testtask')->testtask->lastRunTime;?></td>
            <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo $case->lastRunDate;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $this->app->loadLang('testtask')->testtask->lastRunResult;?></td>
            <td><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->keywords;?></td>
            <td><?php echo $case->keywords;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->linkCase;?></td>
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
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->testcase->fromBug;?></td>
            <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$case->fromBug"), $case->fromBugTitle);?></td>
          </tr>
          <tr>
            <td valign="top" class='rowhead w-p20'><?php echo $lang->testcase->toBug;?></td>
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
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->testcase->openedBy;?></td>
            <td><?php echo $case->openedBy . $lang->at . $case->openedDate;?></td>
          </tr>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->testcase->lblLastEdited;?></td>
            <td><?php if($case->lastEditedBy) echo $case->lastEditedBy . $lang->at . $case->lastEditedDate;?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->testcase->legendVersion;?></legend>
        <div>
          <?php for($i = $case->version; $i >= 1; $i --) echo html::a(inlink('view', "caseID=$case->id&version=$i"), '#' . $i) . ' ';?>    
        </div>
      </fieldset>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
