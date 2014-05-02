<?php
/**
 * The view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: view.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php echo css::internal($keTableCSS);?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='DOC'><?php echo html::icon($lang->icons['doc']);?> <strong><?php echo $doc->id;?></strong></span>
    <strong><?php echo $doc->title;?></strong>
    <?php if($doc->deleted):?>
    <span class='label label-danger'><?php echo $lang->doc->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
    <?php
    $browseLink = $this->session->docList ? $this->session->docList : inlink('browse');
    $params     = "docID=$doc->id";
    if(!$doc->deleted)
    {
        ob_start();
        echo "<div class='btn-group'>";
        common::printIcon('doc', 'edit', $params);
        common::printIcon('doc', 'delete', $params, '', 'button', '', 'hiddenwin');
        echo '</div>';
        echo "<div class='btn-group'>";
        common::printRPN($browseLink, $preAndNext);
        echo '</div>';
        $actionLinks = ob_get_contents();
        ob_end_clean();
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
        <legend><?php echo $lang->doc->digest;?></legend>
        <div><?php echo $doc->digest;?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->doc->keywords;?></legend>
        <div><?php echo $doc->keywords;?></div>
      </fieldset>
      <?php if($doc->type == 'url'):?>
      <fieldset>
        <legend><?php echo $lang->doc->url;?></legend>
        <div><?php echo html::a(urldecode($doc->url), '', '_blank');?></div>
      </fieldset>
      <?php endif;?>
      <?php if($doc->type == 'text'):?>
      <fieldset>
        <legend><?php echo $lang->doc->content;?></legend>
        <div class='content'><?php echo $doc->content;?></div>
      </fieldset>
      <?php endif;?>
      <?php if($doc->type == 'file'):?>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'true'));?>
      <?php endif;?>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'><?php if(!$doc->deleted) echo $actionLinks;?></div>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->doc->basicInfo;?></legend>
        <table class='table table-data table-condensed table-borderless table-fixed'>
         <tr>
            <th class='w-60px'><?php echo $lang->doc->lib;?></th>
            <td><?php echo $lib;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->module;?></th>
            <td><?php echo $doc->moduleName ? $doc->moduleName : '/';?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->type;?></th>
            <td><?php echo $lang->doc->types[$doc->type];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->addedBy;?></th>
            <td><?php echo $users[$doc->addedBy];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->addedDate;?></th>
            <td><?php echo $doc->addedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->editedBy;?></th>
            <td><?php echo $users[$doc->editedBy];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->editedDate;?></th>
            <td><?php echo $doc->editedDate;?></td>
          </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
