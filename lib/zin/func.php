<?php
declare(strict_types=1);
/**
 * The functions of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'core' . DS . 'zin.func.php';
require_once __DIR__ . DS . 'core' . DS . 'render.func.php';
require_once __DIR__ . DS . 'zui' . DS . 'zui.func.php';
require_once __DIR__ . DS . 'zentao' . DS . 'zentao.func.php';
require_once __DIR__ . DS . 'zentao' . DS . 'field.func.php';
require_once __DIR__ . DS . 'zentao' . DS . 'bind.class.php';

function input(): input {return createWg('input', func_get_args());}
function textarea(): textarea {return createWg('textarea', func_get_args());}
function radio(): radio {return createWg('radio', func_get_args());}
function switcher(): switcher {return createWg('switcher', func_get_args());}
function checkbox(): checkbox {return createWg('checkbox', func_get_args());}
function checkboxGroup(): checkboxGroup {return createWg('checkboxGroup', func_get_args());}
function formBase(): formBase {return createWg('formBase',  func_get_args());}
function form(): form {return createWg('form',  func_get_args());}
function formPanel(): formPanel {return createWg('formPanel', func_get_args());}
function formGridPanel(): formGridPanel {return createWg('formGridPanel', func_get_args());}
function formBatch(): formBatch {return createWg('formBatch', func_get_args());}
function formBatchItem(): formBatchItem {return createWg('formBatchItem', func_get_args());}
function formBatchPanel(): formBatchPanel {return createWg('formBatchPanel', func_get_args());}
function batchActions(): batchActions {return createWg('batchActions', func_get_args());}
function content(): content {return createWg('content', func_get_args());}
function idLabel(): idLabel {return createWg('idLabel', func_get_args());}
function listItem(): listItem {return createWg('listitem', func_get_args());}
function simpleList(): simpleList {return createWg('simplelist', func_get_args());}
function entityList(): entityList {return createWg('entityList', func_get_args());}
function breadcrumb(): breadcrumb {return createWg('breadcrumb', func_get_args());}
function datalist(): datalist {return createWg('datalist', func_get_args());}
function control(): control {return createWg('control', func_get_args());}
function select(): select {return createWg('select', func_get_args());}
function formLabel(): formLabel {return createWg('formLabel', func_get_args());}
function formGroup(): formGroup {return createWg('formGroup', func_get_args());}
function formRow(): formRow {return createWg('formRow', func_get_args());}
function formRowGroup(): formRowGroup {return createWg('formRowGroup', func_get_args());}
function inputControl(): inputControl {return createWg('inputControl', func_get_args());}
function inputGroup(): inputGroup {return createWg('inputGroup', func_get_args());}
function inputGroupAddon(): inputGroupAddon {return createWg('inputGroupAddon', func_get_args());}
function checkList(): checkList {return createWg('checkList', func_get_args());}
function radioList(): radioList {return createWg('radioList', func_get_args());}
function checkBtn(): checkBtn {return createWg('checkBtn', func_get_args());}
function checkBtnGroup(): checkBtnGroup {return createWg('checkBtnGroup', func_get_args());}
function checkColorGroup(): checkColorGroup {return createWg('checkColorGroup', func_get_args());}
function colorPicker(): colorPicker {return createWg('colorPicker', func_get_args());}
function datePicker(): datePicker {return createWg('datePicker', func_get_args());}
function datetimePicker(): datetimePicker {return createWg('datetimePicker', func_get_args());}
function timePicker(): timePicker {return createWg('timePicker', func_get_args());}
function fileInput(): fileInput {return createWg('fileInput', func_get_args());}
function pageForm(): pageForm {return createWg('pageForm', func_get_args());}
function icon(): icon {return createWg('icon', func_get_args());}
function btn(): btn {return createWg('btn', func_get_args());}
function pageBase(): pageBase {return createWg('pageBase', func_get_args());}
function page(): page {return createWg('page',    func_get_args());}
function fragment(): fragment {return createWg('fragment',    func_get_args());}
function btnGroup(): btnGroup {return createWg('btnGroup', func_get_args());}
function row(): row {return createWg('row', func_get_args());}
function col(): col {return createWg('col', func_get_args());}
function center(): center {return createWg('center', func_get_args());}
function cell(): cell {return createWg('cell', func_get_args());}
function divider(): divider {return createWg('divider', func_get_args());}
function actionItem(): actionItem {return createWg('actionItem', func_get_args());}
function nav(): nav {return createWg('nav', func_get_args());}
function label(): label {return createWg('label', func_get_args());}
function statusLabel(): statusLabel {return createWg('statusLabel', func_get_args());}
function dtable(): dtable {return createWg('dtable', func_get_args());}
function menu(): menu {return createWg('menu', func_get_args());}
function dropdown(): dropdown {return createWg('dropdown', func_get_args());}
function header(): header {return createWg('header', func_get_args());}
function heading(): heading {return createWg('heading', func_get_args());}
function navbar(): navbar {return createWg('navbar', func_get_args());}
function dropmenu(): dropmenu {return createWg('dropmenu', func_get_args());}
function main(): main {return createWg('main', func_get_args());}
function sidebar(): sidebar {return createWg('sidebar', func_get_args());}
function featureBar(): featureBar {return createWg('featureBar', func_get_args());}
function avatar(): avatar {return createWg('avatar', func_get_args());}
function userAvatar(): userAvatar {return createWg('userAvatar', func_get_args());}
function pager(): pager {return createWg('pager', func_get_args());}
function modal(): modal {return createWg('modal', func_get_args());}
function modalTrigger(): modalTrigger {return createWg('modalTrigger', func_get_args());}
function modalHeader(): modalHeader {return createWg('modalHeader', func_get_args());}
function modalDialog(): modalDialog {return createWg('modalDialog', func_get_args());}
function tabs(): tabs {return createWg('tabs', func_get_args());}
function tabPane(): tabPane {return createWg('tabPane', func_get_args());}
function panel(): panel {return createWg('panel', func_get_args());}
function pasteDialog(): pasteDialog {return createWg('pasteDialog', func_get_args());}
function tooltip(): tooltip {return createWg('tooltip', func_get_args());}
function toolbar(): toolbar {return createWg('toolbar', func_get_args());}
function searchToggle(): searchToggle {return createWg('searchToggle', func_get_args());}
function searchForm(): searchForm {return createWg('searchForm', func_get_args());}
function programMenu(): programMenu {return createWg('programMenu', func_get_args());}
function productMenu(): productMenu {return createWg('productMenu', func_get_args());}
function moduleMenu(): moduleMenu {return createWg('moduleMenu', func_get_args());}
function docMenu(): docMenu {return createWg('docMenu', func_get_args());}
function tree(): Tree {return createWg('tree', func_get_args());}
function treeEditor(): TreeEditor {return createWg('treeEditor', func_get_args());}
function fileList(): fileList {return createWg('fileList', func_get_args());}
function history(): history {return createWg('history', func_get_args());}
function floatToolbar(): floatToolbar {return createWg('floatToolbar', func_get_args());}
function formItemDropdown(): formItemDropdown {return createWg('formItemDropdown', func_get_args());}
function editor(): editor {return createWg('editor', func_get_args());}
function commentBtn(): commentBtn {return createWg('commentBtn', func_get_args());}
function commentDialog(): commentDialog {return createWg('commentDialog', func_get_args());}
function commentForm(): commentForm {return createWg('commentForm', func_get_args());}
function priLabel(): priLabel {return createWg('priLabel', func_get_args());}
function riskLabel(): riskLabel {return createWg('riskLabel', func_get_args());}
function severityLabel(): severityLabel {return createWg('severityLabel', func_get_args());}
function dashboard(): dashboard {return createWg('dashboard', func_get_args());}
function blockPanel(): blockPanel {return createWg('blockPanel', func_get_args());}
function section(): section {return createWg('section', func_get_args());}
function sectionCard(): sectionCard {return createWg('sectionCard', func_get_args());}
function sectionList(): sectionList {return createWg('sectionList', func_get_args());}
function entityTitle(): entityTitle {return createWg('entityTitle',func_get_args());}
function entityLabel(): entityLabel {return createWg('entityLabel',func_get_args());}
function tableData(): tableData {return createWg('tableData',func_get_args());}
function detail(): detail {return createWg('detail', func_get_args());}
function detailCard(): detailCard {return createWg('detailCard', func_get_args());}
function detailHeader(): detailHeader {return createWg('detailHeader', func_get_args());}
function detailSide(): detailSide {return createWg('detailSide', func_get_args());}
function detailBody(): detailBody {return createWg('detailBody', func_get_args());}
function echarts(): echarts {return createWg('echarts', func_get_args());}
function popovers(): popovers {return createWg('popovers', func_get_args());}
function backBtn(): backBtn {return createWg('backBtn', func_get_args());}
function collapseBtn(): collapseBtn {return createWg('collapseBtn', func_get_args());}
function mainNavbar(): mainNavbar {return createWg('mainNavbar', func_get_args());}
function floatPreNextBtn(): floatPreNextBtn {return createWg('floatPreNextBtn', func_get_args());}
function fileSelector(): fileSelector {return createWg('fileSelector', func_get_args());}
function imageSelector(): imageSelector {return createWg('imageSelector', func_get_args());}
function upload(): upload {return createWg('upload', func_get_args());}
function uploadImgs(): uploadImgs {return createWg('uploadImgs', func_get_args());}
function burn(): burn {return createWg('burn', func_get_args());}
function monaco(): monaco {return createWg('monaco', func_get_args());}
function dynamic(): dynamic {return createWg('dynamic', func_get_args());}
function formSettingBtn(): formSettingBtn {return createWg('formSettingBtn', func_get_args());}
function overviewBlock(): overviewBlock {return createWg('overviewBlock', func_get_args());}
function statisticBlock(): statisticBlock {return createWg('statisticBlock', func_get_args());}
function picker(): picker {return createWg('picker', func_get_args());}
function priPicker(): priPicker {return createWg('priPicker', func_get_args());}
function severityPicker(): severityPicker {return createWg('severityPicker', func_get_args());}
function hr(): hr {return createWg('hr', func_get_args());}
function globalSearch(): globalSearch {return createWg('globalSearch', func_get_args());}
function stepsEditor(): stepsEditor {return createWg('stepsEditor', func_get_args());}
function tableChart(): tableChart {return createWg('tableChart', func_get_args());}
function password(): password {return createWg('password', func_get_args());}
function mindmap(): mindmap {return createWg('mindmap', func_get_args());}
function treemap(): treemap {return createWg('treemap', func_get_args());}
function imgCutter(): imgCutter {return createWg('imgCutter', func_get_args());}
function modalNextStep(): modalNextStep {return createWg('modalNextStep', func_get_args());}
function navigator(): navigator {return createWg('navigator', func_get_args());}
function gantt(): gantt {return createWg('gantt', func_get_args());}
function roadMap(): roadMap {return createWg('roadmap', func_get_args());}
function progressBar(): progressBar {return createWg('progressBar', func_get_args());}
function progressCircle(): progressCircle {return createWg('progressCircle', func_get_args());}
function filter(): filter {return createWg('filter', func_get_args());}
function resultFilter(): resultFilter {return createWg('resultFilter', func_get_args());}
function contactList(): contactList {return createWg('contactList', func_get_args());}
function users(): users {return createWg('users', func_get_args());}
function mailto(): mailto {return createWg('mailto', func_get_args());}
function whitelist(): whitelist {return createWg('whitelist', func_get_args());}
function modulePicker(): modulePicker {return createWg('modulePicker', func_get_args());}
function visionSwitcher(): visionSwitcher {return createWg('visionSwitcher', func_get_args());}
function chatBtn(): chatBtn {return createWg('chatBtn', func_get_args());}
function storyList(): storyList {return createWg('storyList', func_get_args());}
function linkedStoryList(): linkedStoryList {return createWg('linkedStoryList', func_get_args());}
function twinsStoryList(): twinsStoryList {return createWg('twinsStoryList', func_get_args());}
function executionTaskList(): executionTaskList {return createWg('executionTaskList', func_get_args());}
function relatedList(): relatedList {return createWg('relatedList', func_get_args());}
function storyRelatedList(): storyRelatedList {return createWg('storyRelatedList', func_get_args());}
function storyBasicInfo(): storyBasicInfo {return createWg('storyBasicInfo', func_get_args());}
function storyLifeInfo(): storyLifeInfo {return createWg('storyLifeInfo', func_get_args());}
