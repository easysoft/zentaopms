<?php
declare(strict_types=1);
/**
 * The zen file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang<yidong@easycorp.ltd>
 * @package     file
 * @link        https://www.zentao.net
 */
class fileZen extends file
{
    /**
     * 获取下载方式，是下载文件还是直接在页面打开查看。
     * Get download mode
     *
     * @param  object    $file
     * @param  string    $mouse
     * @access protected
     * @return string    down|open
     */
    protected function getDownloadMode(object $file, string $mouse): string
    {
        $mode      = 'down';
        $fileTypes = 'txt|jpg|jpeg|gif|png|bmp|xml|html|mp4';
        if(stripos($fileTypes, $file->extension) !== false && $mouse == 'left') $mode = 'open';

        return $mode;
    }

    /**
     * 构建下载列表表格。
     * Build download table.
     *
     * @param  array     $fields
     * @param  array     $rows
     * @param  string    $kind
     * @param  array     $rowspans
     * @param  array     $colspans
     * @access protected
     * @return string
     */
    protected function buildDownloadTable(array $fields, array $rows, string $kind, array $rowspans = array(), array $colspans = array()): string
    {
        $rows = array_values($rows);
        $host = common::getSysURL();

        $output  = "<table><tr>";
        $output .= implode("\n", array_map(function($fieldLabel){return "<th><nobr>$fieldLabel</nobr></th>";}, $fields));
        $output .= "</tr>";

        foreach($rows as $i => $row)
        {
            if(in_array($kind, array('story', 'bug', 'testcase'))) $row->title = html::a($host . $this->createLink($kind, 'view', "{$kind}ID=$row->id"), $row->title);
            if($kind == 'task') $row->name = html::a($host . $this->createLink('task', 'view', "taskID=$row->id"), $row->name);

            $output    .= "<tr valign='top'>\n";
            $col        = 0;
            $endColspan = 0;
            foreach($fields as $fieldName => $fieldLabel)
            {
                $col ++;
                if(!empty($endColspan) && $col < $endColspan) continue;
                if(isset($endRowspan[$fieldName]) && $i < $endRowspan[$fieldName]) continue;

                $fieldValue = zget($row, $fieldName, '');

                $rowspan = '';
                if(isset($rowspans[$i]) && isset($rowspans[$i]['rows'][$fieldName]))
                {
                    $rowspan = "rowspan='{$rowspans[$i]['rows'][$fieldName]}'";
                    $endRowspan[$fieldName] = $i + $rowspans[$i]['rows'][$fieldName];
                }

                $colspan = '';
                if(isset($colspans[$i]) && isset($colspans[$i]['cols'][$fieldName]))
                {
                    $colspan    = "colspan='{$colspans[$i]['cols'][$fieldName]}'";
                    $endColspan = $col + $colspans[$i]['cols'][$fieldName];
                }

                if($fieldValue && is_string($fieldValue)) $fieldValue = preg_replace('/ src="{([0-9]+)(\.(\w+))?}" /', ' src="' . $host . helper::createLink('file', 'read', "fileID=$1", "$3") . '" ', $fieldValue);
                $output .= "<td $rowspan $colspan><nobr>$fieldValue</nobr></td>\n";
            }
            $output .= "</tr>\n";
        }
        $output .= "</table>";

        return $output;
    }

    /**
     * 删除真实文件。
     * Unlink real file.
     *
     * @param  object    $file
     * @access protected
     * @return void
     */
    protected function unlinkRealFile(object $file): void
    {
        $fileRecord = $this->dao->select('id')->from(TABLE_FILE)->where('pathname')->eq($file->pathname)->fetch();
        if(empty($fileRecord)) $this->file->unlinkFile($file);
    }

    /**
     * 更新 fileName 字段。
     * Update fileName field.
     *
     * @param  int       $fileID
     * @access protected
     * @return array
     */
    protected function updateFileName(int $fileID): array
    {
        $file = $this->file->getByID($fileID);
        $data = fixer::input('post')->get();
        if(validater::checkLength($data->fileName, 80, 1) == false) return array('result' => 'fail', 'message' => sprintf($this->lang->error->length[1], $this->lang->file->title, 80, 1));

        $fileName = $data->fileName . '.' . $data->extension;
        $this->dao->update(TABLE_FILE)->set('title')->eq($fileName)->where('id')->eq($fileID)->exec();

        $actionID  = $this->loadModel('action')->create($file->objectType, $file->objectID, 'editfile', '', $fileName);
        $changes[] = array('field' => 'fileName', 'old' => $file->title, 'new' => $fileName, 'diff' => '');
        $this->action->logHistory($actionID, $changes);

        /* Update test case version for test case synchronization. */
        if($file->objectType == 'testcase' and $file->title != $fileName) $this->file->updateTestcaseVersion($file);

        return array('result' => 'success');
    }
}
