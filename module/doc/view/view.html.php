<?php
/**
 * The view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
    <?php if($doc->version > 1):?>
    <small class='dropdown'>
      <a href='#' data-toggle='dropdown' class='text-muted'><?php echo '#' . $version;?> <span class='caret'></span></a>
        <ul class='dropdown-menu'>
        <?php
        for($i = $doc->version; $i >= 1; $i --)
        {
            $class = $i == $version ? " class='active'" : '';
            echo '<li' . $class .'>' . html::a(inlink('view', "docID=$doc->id&version=$i"), '#' . $i) . '</li>';
        }
        ?>
      </ul>
    </small>
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
        <legend><?php echo $lang->doc->content;?></legend>
        <div class='content'>
          <?php
          preg_match_all('/^<a +.*href="([^"]+)".*>[^<]+<\/a>$/Ui', trim($doc->content), $output);
          $href = isset($output[1][0]) ? $output[1][0] : '';
          ?>
          <?php echo $doc->content;?>
          <?php if($href):?>
          <!-- Remove referer -->
          <iframe src="javascript:location.replace('<?php echo $href;?>' + (parent.location.hash||''))" width='100%'frameborder='0' id='url-content'></iframe>
          <?php endif;?>

          <?php foreach($doc->files as $file):?>
          <?php if(in_array($file->extension, $config->file->imageExtensions)):?>
          <a href="<?php echo $file->webPath?>" target="_blank">
            <img onload="setImageSize(this,0)" src="<?php echo $file->webPath?>" alt="<?php echo $file->title?>" title="<?php echo $file->title?>">
          </a>
          <?php unset($doc->files[$file->id]);?>
          <?php endif;?>
          <?php endforeach;?>

          <?php if($doc->files):?>
          <div class='file-content'><?php echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'false'));?></div>
          <?php endif;?>
        </div>
      </fieldset>
      <div class='actions'><?php if(!$doc->deleted) echo $actionLinks;?></div>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->doc->digest;?></legend>
        <div><?php echo $doc->digest;?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->doc->keywords;?></legend>
        <div><?php echo $doc->keywords;?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->doc->basicInfo;?></legend>
        <table class='table table-data table-condensed table-borderless'>
          <?php if($doc->productName):?>
          <tr>
            <th><?php echo $lang->doc->product;?></th>
            <td><?php echo $doc->productName;?></td>
          </tr>
          <?php endif;?>
          <?php if($doc->projectName):?>
          <tr>
            <th><?php echo $lang->doc->project;?></th>
            <td><?php echo $doc->projectName;?></td>
          </tr>
          <?php endif;?>
         <tr>
            <th class='w-80px'><?php echo $lang->doc->lib;?></th>
            <td><?php echo $lib->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->module;?></th>
            <td><?php echo $doc->moduleName ? $doc->moduleName : '/';?></td>
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
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php js::set('type', $type)?>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
