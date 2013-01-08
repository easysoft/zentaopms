<?php
/**
 * The view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
  <div id='main' <?php if($doc->deleted) echo "class='deleted'";?>>DOC #<?php echo $doc->id . ' ' . $doc->title;?></div>
  <div>
    <div class='a-center f-16px strong'>
      <?php
      $browseLink = $this->session->docList ? $this->session->docList : inlink('browse');
      $params     = "docID=$doc->id";
      if(!$doc->deleted)
      {
          ob_start();
          common::printIcon('doc', 'edit', $params);
          common::printIcon('doc', 'delete', $params, '', 'button', '', 'hiddenwin');
          common::printDivider();
          common::printRPN($browseLink, $preAndNext);

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
</div>

<table class='cont-rt5'>
  <tr valign='top'>
    <td>
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
      <div class='a-center actionlink'><?php if(!$doc->deleted) echo $actionLinks;?></div>
    </td>
    <td class='divider'></td>
    <td class='side'>
      <fieldset>
      <legend><?php echo $lang->doc->basicInfo;?></legend>
      <table class='table-1 a-left fixed'>
       <tr>
          <th class='rowhead w-200'><?php echo $lang->doc->lib;?></th>
          <td><?php echo $lib;?></td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->doc->module;?></th>
          <td><?php echo $doc->moduleName ? $doc->moduleName : '/';?></td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->doc->type;?></th>
          <td><?php echo $lang->doc->types[$doc->type];?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->doc->addedBy;?></th>
          <td><?php echo $users[$doc->addedBy];?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->doc->addedDate;?></th>
          <td><?php echo $doc->addedDate;?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->doc->editedBy;?></th>
          <td><?php echo $users[$doc->editedBy];?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->doc->editedDate;?></th>
          <td><?php echo $doc->editedDate;?></td>
        </tr>  
      </table>
    </td>
  </tr>
</table>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include './footer.html.php';?>
