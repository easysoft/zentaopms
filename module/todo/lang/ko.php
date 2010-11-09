<?php
/**
 * The todo module Korean file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: en.php 996 2010-08-02 14:19:13Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->todo->common    = '해야할 일';
$lang->todo->index     = "색인";
$lang->todo->create    = "만들기";
$lang->todo->edit      = "편집";
$lang->todo->view      = "정보";
$lang->todo->viewAB    = "정보";
$lang->todo->markDone  = "취소할";
$lang->todo->markWait  = "완료";
$lang->todo->markDoing = "뭐";
$lang->todo->mark      = "변경 상태";
$lang->todo->delete    = "삭제";
$lang->todo->browse    = "찾아보기";
$lang->todo->import2Today = "오늘 가져오기";
$lang->todo->changeStatus = "변화";

$lang->todo->id          = '신분증';
$lang->todo->account     = '소유자';
$lang->todo->date        = '날짜';
$lang->todo->begin       = '시작 시간';
$lang->todo->beginAB     = '시작';
$lang->todo->end         = '종료 시간';
$lang->todo->endAB       = '끝';
$lang->todo->beginAndEnd = '시작과 끝';
$lang->todo->type        = '유형';
$lang->todo->pri         = '우선';
$lang->todo->name        = '이름';
$lang->todo->status      = '지위';
$lang->todo->desc        = '내림차순';
$lang->todo->private     = '비공개';
$lang->todo->idvalue     = '작업 또는 버그';

$lang->todo->week  = '(나)';  // date function's param.
$lang->todo->today = '오늘';
$lang->todo->weekDateList = '';
$lang->todo->dayInFeature = '기능';
$lang->todo->confirmBug   = 'This todo linked to bug #%s，chang it also?';
$lang->todo->confirmTask  = 'This todo linked to task #%s，chang it also?';

$lang->todo->statusList['wait']     = '기다리는';
$lang->todo->statusList['doing']    = '뭐';
$lang->todo->statusList['done']     = '완료';
//$lang->todo->statusList['cancel']   = '已 取消';
//$lang->todo->statusList['postpone'] = '已 延期';

$lang->todo->priList[3] = '3';
$lang->todo->priList[1] = '1';
$lang->todo->priList[2] = '2';
$lang->todo->priList[4] = '4';

$lang->todo->typeList->custom = '사용자 정의';
$lang->todo->typeList->bug    = '곤충';
$lang->todo->typeList->task   = '태스크';

$lang->todo->confirmDelete  = "당신이 먹이고을 삭제하시겠습니까?";
$lang->todo->successMarked  = "성공적으로 상태를 변경";;
$lang->todo->thisIsPrivate  = '이건 사적인 먹이고있다. :)';
$lang->todo->lblDisableDate = '설정 시간 최근';

$lang->todo->thisWeekTodos = '이번 주';
$lang->todo->lastWeekTodos = '지난 주';
$lang->todo->allDaysTodos  = '전체 먹이고';
$lang->todo->allUndone     = '취소할';
$lang->todo->todayTodos    = '오늘';

$lang->todo->action->marked = array('main' => '$date, Change status to <stong>$extra</strong> by <strong>$actor</strong>。', 'extra' => $lang->todo->statusList);
