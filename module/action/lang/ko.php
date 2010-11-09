<?php
/**
 * The action module Korean file of ZenTaoMS.
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
 * @package     action
 * @version     $Id: en.php 1000 2010-08-03 01:49:25Z yuren_@126.com $
 * @link        http://www.zentao.net
 */
$lang->action->common   = '로그';
$lang->action->trash    = '휴지통';
$lang->action->undelete = '복원';

$lang->action->objectType = '대상';
$lang->action->objectID   = '신분증';
$lang->action->objectName = '이름';
$lang->action->actor      = '배우';
$lang->action->date       = '날짜';

$lang->action->objectTypes['product']     = '제품';
$lang->action->objectTypes['story']       = '이야기';
$lang->action->objectTypes['productplan'] = '플랑';
$lang->action->objectTypes['release']     = '공개 동의서';
$lang->action->objectTypes['project']     = '프로젝트';
$lang->action->objectTypes['task']        = '작업 내용';
$lang->action->objectTypes['build']       = '빌드';
$lang->action->objectTypes['bug']         = '곤충';
$lang->action->objectTypes['case']        = '케이스';
$lang->action->objectTypes['testtask']    = '테스트 작업';
$lang->action->objectTypes['user']        = '사용자';
$lang->action->objectTypes['doc']         = '덕';
$lang->action->objectTypes['doclib']      = 'DocLib';

/* 用来描述操作历史记录.*/
$lang->action->desc->common      = '$date, <strong>$action</strong> by <strong>$actor</strong>';
$lang->action->desc->extra       = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>';
$lang->action->desc->opened      = '$date, Opened by <strong>$actor</strong>.';
$lang->action->desc->created     = '$date, Created by <strong>$actor</strong>.';
$lang->action->desc->changed     = '$date, Changed by <strong>$actor</strong>.';
$lang->action->desc->edited      = '$date, Edited by <strong>$actor</strong>.';
$lang->action->desc->closed      = '$date, Closed by <strong>$actor</strong>.';
$lang->action->desc->deleted     = '$date, Deleted by <strong>$actor</strong>.';
$lang->action->desc->deletedfile = '$date, Deleted file by <strong>$actor</strong>, the file is <strong><i>$extra</i></strong>';
$lang->action->desc->erased      = '$date, Erased by <strong>$actor</strong>.';
$lang->action->desc->undeleted   = '$date, Restored by <strong>$actor</strong>.';
$lang->action->desc->commented   = '$date, Commented by <strong>$actor</strong>.';
$lang->action->desc->activated   = '$date, Activated by <strong>$actor</strong>.';
$lang->action->desc->moved       = '$date, Moved by <strong>$actor</strong>, previouse is "$extra"';
$lang->action->desc->confirmed   = '$date, Confirmed by <strong>$actor</strong>, version is<strong>#$extra</strong>';
$lang->action->desc->started     = '$date, Started by <strong>$actor</strong>.';
$lang->action->desc->canceled    = '$date, Canceled by <strong>$actor</strong>.';
$lang->action->desc->finished    = '$date, Finished by <strong>$actor</strong>.';
$lang->action->desc->diff1       = 'Changed <strong><i>%s</i></strong>, old is "%s", new is "%s".<br />';
$lang->action->desc->diff2       = 'Changed <strong><i>%s</i></strong>, the diff is：<blockquote>%s</blockquote>';

/* 用来显示动态信息.*/
$lang->action->label->created             = '생성';
$lang->action->label->opened              = '오픈';
$lang->action->label->changed             = '변경됨';
$lang->action->label->edited              = '편집';
$lang->action->label->closed              = '폐쇄';
$lang->action->label->deleted             = '삭제';
$lang->action->label->deletedfile         = '파일을 삭제';
$lang->action->label->erased              = '삭제';
$lang->action->label->undeleted           = '복원';
$lang->action->label->commented           = '논평';
$lang->action->label->activated           = '활성화';
$lang->action->label->resolved            = '해결';
$lang->action->label->reviewed            = '검토';
$lang->action->label->moved               = 'moded';
$lang->action->label->confirmed           = ', 확인';
$lang->action->label->linked2plan         = '링크 계획';
$lang->action->label->unlinkedfromplan    = '계획에서 연결을 해제';
$lang->action->label->linked2project      = '링크는 프로젝트에';
$lang->action->label->unlinkedfromproject = '프로젝트에서 unlik';
$lang->action->label->marked              = '편집';
$lang->action->label->started             = '시작';
$lang->action->label->canceled            = '취소';
$lang->action->label->finished            = '완료';
$lang->action->label->login               = '로그인';
$lang->action->label->logout              = "로그아웃";

/* 用来生成相应对象的链接.*/
$lang->action->label->product     = 'product|product|view|productID=%s';
$lang->action->label->productplan = 'plan|productplan|view|productID=%s';
$lang->action->label->release     = 'release|release|view|productID=%s';
$lang->action->label->story       = 'story|story|view|storyID=%s';
$lang->action->label->project     = 'project|project|view|projectID=%s';
$lang->action->label->task        = 'task|task|view|taskID=%s';
$lang->action->label->build       = 'build|build|view|buildID=%s';
$lang->action->label->bug         = 'bug|bug|view|bugID=%s';
$lang->action->label->case        = 'case|testcase|view|caseID=%s';
$lang->action->label->testtask    = 'test task|testtask|view|caseID=%s';
$lang->action->label->todo        = 'todo|todo|view|todoID=%s';
$lang->action->label->doclib      = 'doc library|doc|browse|libID=%s';
$lang->action->label->doc         = 'doc|doc|view|docID=%s';
$lang->action->label->user        = '사용자';

$lang->action->label->space     = ' ';
