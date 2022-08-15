<tr>
  <td colspan='10' class='text-center form-actions'>
    <?php
    $submitText  = $isEndPage ? $this->lang->save : $this->lang->file->saveAndNext;
    $isStartPage = $pagerID == 1 ? true : false;
    if(!$this->session->insert)
    {
        echo "<button type='button' data-toggle='modal' data-target='#importNoticeModal' class='btn btn-primary btn-wide'>{$submitText}</button>";
    }
    else
    {
        echo html::submitButton($submitText);
        if($dataInsert !== '') echo html::hidden('insert', $dataInsert);
    }
    echo html::hidden('isEndPage', $isEndPage ? 1 : 0);
    echo html::hidden('pagerID', $pagerID);
    echo ' &nbsp; ' . html::a($backLink, $lang->goback, '', "class='btn btn-back btn-wide'");
    echo ' &nbsp; ' . sprintf($lang->file->importPager, $allCount, $pagerID, $allPager);
    ?>
  </td>
</tr>
