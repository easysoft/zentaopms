/*
 Navicat Premium Data Transfer

 Source Server         : ubuntu
 Source Server Type    : MySQL
 Source Server Version : 50729
 Source Host           : 192.168.192.147:3306
 Source Schema         : zentaoqc

 Target Server Type    : MySQL
 Target Server Version : 50729
 File Encoding         : 65001

 Date: 01/06/2020 14:07:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for zt_action
-- ----------------------------
DROP TABLE IF EXISTS `zt_action`;
CREATE TABLE `zt_action`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `objectID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `product` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `project` mediumint(9) NOT NULL,
  `actor` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `action` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `date` datetime(0) NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `extra` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `read` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `efforted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `date`(`date`) USING BTREE,
  INDEX `actor`(`actor`) USING BTREE,
  INDEX `project`(`project`) USING BTREE,
  INDEX `objectID`(`objectID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 210 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_asset
-- ----------------------------
DROP TABLE IF EXISTS `zt_asset`;
CREATE TABLE `zt_asset`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `group` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_attend
-- ----------------------------
DROP TABLE IF EXISTS `zt_attend`;
CREATE TABLE `zt_attend`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` date NOT NULL,
  `signIn` time(0) NOT NULL,
  `signOut` time(0) NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `device` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `client` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `manualIn` time(0) NOT NULL,
  `manualOut` time(0) NOT NULL,
  `reason` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `reviewedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `attend`(`date`, `account`) USING BTREE,
  INDEX `account`(`account`) USING BTREE,
  INDEX `date`(`date`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `reason`(`reason`) USING BTREE,
  INDEX `reviewStatus`(`reviewStatus`) USING BTREE,
  INDEX `reviewedBy`(`reviewedBy`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_attendstat
-- ----------------------------
DROP TABLE IF EXISTS `zt_attendstat`;
CREATE TABLE `zt_attendstat`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `month` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `normal` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `late` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `early` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `absent` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `trip` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `egress` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `lieu` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `paidLeave` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `unpaidLeave` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `timeOvertime` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `restOvertime` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `holidayOvertime` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `deserve` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `actual` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `attend`(`month`, `account`) USING BTREE,
  INDEX `account`(`account`) USING BTREE,
  INDEX `month`(`month`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_baseline
-- ----------------------------
DROP TABLE IF EXISTS `zt_baseline`;
CREATE TABLE `zt_baseline`  (
  `id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `objectType` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `versionType` enum('reviewed','taged') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectData` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedTo` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `deadline` date NOT NULL,
  `result` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for zt_basicmeas
-- ----------------------------
DROP TABLE IF EXISTS `zt_basicmeas`;
CREATE TABLE `zt_basicmeas`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `object` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `unit` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `source` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `method` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `params` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `configure` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `collectType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `collectConf` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `execTime` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `collectedBy` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_block
-- ----------------------------
DROP TABLE IF EXISTS `zt_block`;
CREATE TABLE `zt_block`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `module` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `source` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `block` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `params` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `grid` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `height` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `accountModuleOrder`(`account`, `module`, `order`) USING BTREE,
  INDEX `account`(`account`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_branch
-- ----------------------------
DROP TABLE IF EXISTS `zt_branch`;
CREATE TABLE `zt_branch`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` smallint(5) UNSIGNED NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_budget
-- ----------------------------
DROP TABLE IF EXISTS `zt_budget`;
CREATE TABLE `zt_budget`  (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) NOT NULL,
  `stage` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subject` mediumint(8) NOT NULL,
  `amount` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `lastEditedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastEditedDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_bug
-- ----------------------------
DROP TABLE IF EXISTS `zt_bug`;
CREATE TABLE `zt_bug`  (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `injection` mediumint(8) UNSIGNED NOT NULL,
  `identify` mediumint(8) UNSIGNED NOT NULL,
  `branch` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `module` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `project` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `plan` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `story` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `storyVersion` smallint(6) NOT NULL DEFAULT 1,
  `task` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `toTask` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `toStory` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `severity` tinyint(4) NOT NULL DEFAULT 0,
  `pri` tinyint(3) UNSIGNED NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `os` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `browser` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `hardware` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `found` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `steps` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` enum('active','resolved','closed') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'active',
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `color` char(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `activatedCount` smallint(6) NOT NULL,
  `activatedDate` datetime(0) NOT NULL,
  `mailto` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `openedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `openedDate` datetime(0) NOT NULL,
  `openedBuild` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `assignedDate` datetime(0) NOT NULL,
  `deadline` date NOT NULL,
  `resolvedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `resolution` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `resolvedBuild` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `resolvedDate` datetime(0) NOT NULL,
  `closedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `closedDate` datetime(0) NOT NULL,
  `duplicateBug` mediumint(8) UNSIGNED NOT NULL,
  `linkBug` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `case` mediumint(8) UNSIGNED NOT NULL,
  `caseVersion` smallint(6) NOT NULL DEFAULT 1,
  `feedback` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `result` mediumint(8) UNSIGNED NOT NULL,
  `repo` mediumint(8) UNSIGNED NOT NULL,
  `entry` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lines` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `v1` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `v2` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `repoType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `testtask` mediumint(8) UNSIGNED NOT NULL,
  `lastEditedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `lastEditedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `project`(`project`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `plan`(`plan`) USING BTREE,
  INDEX `story`(`story`) USING BTREE,
  INDEX `case`(`case`) USING BTREE,
  INDEX `assignedTo`(`assignedTo`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_build
-- ----------------------------
DROP TABLE IF EXISTS `zt_build`;
CREATE TABLE `zt_build`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `branch` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `project` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `name` char(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `scmPath` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `filePath` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` date NOT NULL,
  `stories` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bugs` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `builder` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `project`(`project`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_burn
-- ----------------------------
DROP TABLE IF EXISTS `zt_burn`;
CREATE TABLE `zt_burn`  (
  `project` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `task` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `estimate` float NOT NULL,
  `left` float NOT NULL,
  `consumed` float NOT NULL,
  `storyPoint` float NOT NULL,
  PRIMARY KEY (`project`, `date`, `task`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_case
-- ----------------------------
DROP TABLE IF EXISTS `zt_case`;
CREATE TABLE `zt_case`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `branch` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `lib` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `module` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `path` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `story` mediumint(30) UNSIGNED NOT NULL DEFAULT 0,
  `storyVersion` smallint(6) NOT NULL DEFAULT 1,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `precondition` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pri` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  `auto` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no',
  `frame` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `stage` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `howRun` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `scriptedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `scriptedDate` date NOT NULL,
  `scriptStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `scriptLocation` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `color` char(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `frequency` enum('1','2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  `order` tinyint(30) UNSIGNED NOT NULL DEFAULT 0,
  `openedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `openedDate` datetime(0) NOT NULL,
  `reviewedBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewedDate` date NOT NULL,
  `lastEditedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `lastEditedDate` datetime(0) NOT NULL,
  `version` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `linkCase` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fromBug` mediumint(8) UNSIGNED NOT NULL,
  `fromCaseID` mediumint(8) UNSIGNED NOT NULL,
  `fromCaseVersion` mediumint(8) UNSIGNED NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `lastRunner` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastRunDate` datetime(0) NOT NULL,
  `lastRunResult` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `story`(`story`) USING BTREE,
  INDEX `module`(`module`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_casestep
-- ----------------------------
DROP TABLE IF EXISTS `zt_casestep`;
CREATE TABLE `zt_casestep`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `case` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `version` smallint(3) UNSIGNED NOT NULL DEFAULT 0,
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'step',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `expect` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `case`(`case`) USING BTREE,
  INDEX `version`(`version`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_company
-- ----------------------------
DROP TABLE IF EXISTS `zt_company`;
CREATE TABLE `zt_company`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(120) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phone` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `fax` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `address` char(120) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `zipcode` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `website` char(120) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `backyard` char(120) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `guest` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `admins` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_compile
-- ----------------------------
DROP TABLE IF EXISTS `zt_compile`;
CREATE TABLE `zt_compile`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `job` mediumint(8) UNSIGNED NOT NULL,
  `queue` mediumint(8) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `logs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `atTime` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `testtask` mediumint(8) UNSIGNED NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `updateDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_config
-- ----------------------------
DROP TABLE IF EXISTS `zt_config`;
CREATE TABLE `zt_config`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `section` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `key` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique`(`owner`, `module`, `section`, `key`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 30 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_cron
-- ----------------------------
DROP TABLE IF EXISTS `zt_cron`;
CREATE TABLE `zt_cron`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `m` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `h` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dom` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mon` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dow` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `command` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `buildin` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastTime` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `lastTime`(`lastTime`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_deploy
-- ----------------------------
DROP TABLE IF EXISTS `zt_deploy`;
CREATE TABLE `zt_deploy`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `begin` datetime(0) NOT NULL,
  `end` datetime(0) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `owner` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `members` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `notify` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cases` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `result` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_deployproduct
-- ----------------------------
DROP TABLE IF EXISTS `zt_deployproduct`;
CREATE TABLE `zt_deployproduct`  (
  `deploy` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `release` mediumint(8) UNSIGNED NOT NULL,
  `package` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE INDEX `deploy_product_release`(`deploy`, `product`, `release`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_deployscope
-- ----------------------------
DROP TABLE IF EXISTS `zt_deployscope`;
CREATE TABLE `zt_deployscope`  (
  `deploy` mediumint(8) UNSIGNED NOT NULL,
  `service` mediumint(8) UNSIGNED NOT NULL,
  `hosts` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `remove` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `add` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_deploystep
-- ----------------------------
DROP TABLE IF EXISTS `zt_deploystep`;
CREATE TABLE `zt_deploystep`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `deploy` mediumint(8) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `begin` datetime(0) NOT NULL,
  `end` datetime(0) NOT NULL,
  `stage` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedTo` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `finishedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `finishedDate` datetime(0) NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_dept
-- ----------------------------
DROP TABLE IF EXISTS `zt_dept`;
CREATE TABLE `zt_dept`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `path` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `grade` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `order` smallint(4) UNSIGNED NOT NULL DEFAULT 0,
  `position` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `function` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `manager` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parent`(`parent`) USING BTREE,
  INDEX `path`(`path`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_derivemeas
-- ----------------------------
DROP TABLE IF EXISTS `zt_derivemeas`;
CREATE TABLE `zt_derivemeas`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purpose` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `aim` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `definition` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `collectType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `collectConf` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `execTime` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `analyst` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `analysisMethod` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `scope` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_designspec
-- ----------------------------
DROP TABLE IF EXISTS `zt_designspec`;
CREATE TABLE `zt_designspec`  (
  `design` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for zt_doc
-- ----------------------------
DROP TABLE IF EXISTS `zt_doc`;
CREATE TABLE `zt_doc`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `project` mediumint(8) UNSIGNED NOT NULL,
  `lib` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `template` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `templateType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `chapterType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `path` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `grade` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `views` smallint(5) UNSIGNED NOT NULL,
  `collector` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `acl` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open',
  `groups` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `users` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `project`(`project`) USING BTREE,
  INDEX `lib`(`lib`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_doccontent
-- ----------------------------
DROP TABLE IF EXISTS `zt_doccontent`;
CREATE TABLE `zt_doccontent`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `doc` mediumint(8) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `digest` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `files` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `doc_version`(`doc`, `version`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_doclib
-- ----------------------------
DROP TABLE IF EXISTS `zt_doclib`;
CREATE TABLE `zt_doclib`  (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `project` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `acl` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open',
  `groups` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `users` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `main` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `collector` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` tinyint(5) UNSIGNED NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `project`(`project`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_durationestimation
-- ----------------------------
DROP TABLE IF EXISTS `zt_durationestimation`;
CREATE TABLE `zt_durationestimation`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `stage` mediumint(9) NOT NULL,
  `workload` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `worktimeRate` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `people` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_effort
-- ----------------------------
DROP TABLE IF EXISTS `zt_effort`;
CREATE TABLE `zt_effort`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` mediumint(8) UNSIGNED NOT NULL,
  `product` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `project` mediumint(9) UNSIGNED NOT NULL,
  `account` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `work` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `date` date NOT NULL,
  `left` float NOT NULL,
  `consumed` float NOT NULL,
  `begin` smallint(4) UNSIGNED ZEROFILL NOT NULL,
  `end` smallint(4) UNSIGNED ZEROFILL NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `project`(`project`) USING BTREE,
  INDEX `objectID`(`objectID`) USING BTREE,
  INDEX `date`(`date`) USING BTREE,
  INDEX `account`(`account`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_entry
-- ----------------------------
DROP TABLE IF EXISTS `zt_entry`;
CREATE TABLE `zt_entry`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `account` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `freePasswd` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `ip` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `calledTime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_extension
-- ----------------------------
DROP TABLE IF EXISTS `zt_extension`;
CREATE TABLE `zt_extension`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `author` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `license` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'extension',
  `site` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `zentaoCompatible` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `installedTime` datetime(0) NOT NULL,
  `depends` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dirs` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `files` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE,
  INDEX `name`(`name`) USING BTREE,
  INDEX `installedTime`(`installedTime`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_faq
-- ----------------------------
DROP TABLE IF EXISTS `zt_faq`;
CREATE TABLE `zt_faq`  (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `module` mediumint(9) NOT NULL,
  `product` mediumint(9) NOT NULL,
  `question` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `answer` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedtime` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_feedback
-- ----------------------------
DROP TABLE IF EXISTS `zt_feedback`;
CREATE TABLE `zt_feedback`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `module` mediumint(8) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `public` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `notify` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `likes` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `result` mediumint(8) UNSIGNED NOT NULL,
  `faq` mediumint(8) UNSIGNED NOT NULL,
  `openedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `openedDate` datetime(0) NOT NULL,
  `reviewedBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewedDate` datetime(0) NOT NULL,
  `processedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `processedDate` datetime(0) NOT NULL,
  `closedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `closedDate` datetime(0) NOT NULL,
  `closedReason` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedTo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `mailto` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_feedbackview
-- ----------------------------
DROP TABLE IF EXISTS `zt_feedbackview`;
CREATE TABLE `zt_feedbackview`  (
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL,
  UNIQUE INDEX `account_product`(`account`, `product`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Table structure for zt_file
-- ----------------------------
DROP TABLE IF EXISTS `zt_file`;
CREATE TABLE `zt_file`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pathname` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `extension` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `objectType` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `addedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `addedDate` datetime(0) NOT NULL,
  `downloads` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `objectType`(`objectType`) USING BTREE,
  INDEX `objectID`(`objectID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_activity
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_activity`;
CREATE TABLE `zt_flow_activity`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `process` mediumint(9) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `optional` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `order` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `activitylistBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `activitylistDate` datetime(0) NOT NULL,
  `outputlistBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `outputlistDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 101 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_audit
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_audit`;
CREATE TABLE `zt_flow_audit`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `practiceArea` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectType` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` int(10) NULL DEFAULT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for zt_flow_auditcl
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_auditcl`;
CREATE TABLE `zt_flow_auditcl`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `practiceArea` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectType` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` int(10) NULL DEFAULT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_auditplan
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_auditplan`;
CREATE TABLE `zt_flow_auditplan`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dateType` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `config` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `objectID` mediumint(9) NOT NULL,
  `objectType` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `process` mediumint(9) NOT NULL,
  `processType` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `checkDate` datetime(0) NOT NULL,
  `checkedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `realCheckDate` date NOT NULL,
  `result` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `program` mediumint(9) NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `checkBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_auditresult
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_auditresult`;
CREATE TABLE `zt_flow_auditresult`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `auditplan` mediumint(8) NOT NULL,
  `listID` mediumint(8) NOT NULL,
  `result` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `checkedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `checkedDate` date NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_cmcl
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_cmcl`;
CREATE TABLE `zt_flow_cmcl`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` int(11) NOT NULL,
  `contents` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` int(11) NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_design
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_design`;
CREATE TABLE `zt_flow_design`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `commit` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `project` mediumint(9) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `commitBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `commitDate` datetime(0) NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `program` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `story` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` smallint(6) NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_designspec
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_designspec`;
CREATE TABLE `zt_flow_designspec`  (
  `design` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `files` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE INDEX `design`(`design`, `version`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_issue
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_issue`;
CREATE TABLE `zt_flow_issue`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `resolvedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `program` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pri` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `severity` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `effectedArea` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deadline` date NOT NULL,
  `resolution` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `resolutionComment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `resolvedDate` date NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `activateBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `activateDate` date NOT NULL,
  `closeBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `closedDate` date NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_output
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_output`;
CREATE TABLE `zt_flow_output`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `activity` mediumint(30) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `optional` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `order` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 137 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_process
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_process`;
CREATE TABLE `zt_flow_process`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `abbr` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` mediumint(9) NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `activitylistBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `activitylistDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_reviewcl
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_reviewcl`;
CREATE TABLE `zt_flow_reviewcl`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `object` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_reviewlist
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_reviewlist`;
CREATE TABLE `zt_flow_reviewlist`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `object` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_risk
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_risk`;
CREATE TABLE `zt_flow_risk`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project` mediumint(9) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `source` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `strategy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `impact` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `probability` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `riskindex` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pri` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `identifiedDate` date NOT NULL,
  `prevention` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `remedy` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `plannedClosedDate` date NOT NULL,
  `actualClosedDate` date NOT NULL,
  `addedDate` date NOT NULL,
  `resolution` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `resolvedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastCheckedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastCheckedDate` date NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `checkedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `checkedDate` datetime(0) NOT NULL,
  `cancelBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cancelDate` date NOT NULL,
  `cancelReason` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hangupBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hangupDate` date NOT NULL,
  `activateBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `activateDate` date NOT NULL,
  `closeBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `closedDate` date NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `program` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_stage
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_stage`;
CREATE TABLE `zt_flow_stage`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `percent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_flow_workloadbudget
-- ----------------------------
DROP TABLE IF EXISTS `zt_flow_workloadbudget`;
CREATE TABLE `zt_flow_workloadbudget`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `stage` mediumint(9) NOT NULL,
  `workload` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `worktimeRate` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `people` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `duration` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_group
-- ----------------------------
DROP TABLE IF EXISTS `zt_group`;
CREATE TABLE `zt_group`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `role` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `acl` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `developer` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_grouppriv
-- ----------------------------
DROP TABLE IF EXISTS `zt_grouppriv`;
CREATE TABLE `zt_grouppriv`  (
  `group` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `module` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `method` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  UNIQUE INDEX `group`(`group`, `module`, `method`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_history
-- ----------------------------
DROP TABLE IF EXISTS `zt_history`;
CREATE TABLE `zt_history`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `field` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `old` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `new` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `diff` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `action`(`action`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 93 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_holiday
-- ----------------------------
DROP TABLE IF EXISTS `zt_holiday`;
CREATE TABLE `zt_holiday`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `type` enum('holiday','working') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'holiday',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `year` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `year`(`year`) USING BTREE,
  INDEX `name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_host
-- ----------------------------
DROP TABLE IF EXISTS `zt_host`;
CREATE TABLE `zt_host`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `assetID` mediumint(8) UNSIGNED NOT NULL,
  `serverRoom` mediumint(8) UNSIGNED NOT NULL,
  `cabinet` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `serverModel` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hardwareType` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hostType` enum('physical','virtual') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cpuBrand` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cpuModel` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cpuNumber` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cpuCores` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cpuRate` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `memory` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `diskType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `diskSize` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `privateIP` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `publicIP` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nic` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mac` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `osName` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `osVersion` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `webserver` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `database` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `language` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` enum('online','offline') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_im_chat
-- ----------------------------
DROP TABLE IF EXISTS `zt_im_chat`;
CREATE TABLE `zt_im_chat`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gid` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'group',
  `admins` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `committers` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `subject` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `public` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `createdDate` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `editedDate` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastActiveTime` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dismissDate` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `gid`(`gid`) USING BTREE,
  INDEX `name`(`name`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `public`(`public`) USING BTREE,
  INDEX `createdBy`(`createdBy`) USING BTREE,
  INDEX `editedBy`(`editedBy`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_im_chatuser
-- ----------------------------
DROP TABLE IF EXISTS `zt_im_chatuser`;
CREATE TABLE `zt_im_chatuser`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cgid` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `user` mediumint(8) NOT NULL DEFAULT 0,
  `order` smallint(5) NOT NULL DEFAULT 0,
  `star` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `hide` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `mute` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `freeze` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `join` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `quit` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `chatuser`(`cgid`, `user`) USING BTREE,
  INDEX `cgid`(`cgid`) USING BTREE,
  INDEX `user`(`user`) USING BTREE,
  INDEX `order`(`order`) USING BTREE,
  INDEX `star`(`star`) USING BTREE,
  INDEX `hide`(`hide`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_im_client
-- ----------------------------
DROP TABLE IF EXISTS `zt_im_client`;
CREATE TABLE `zt_im_client`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `changeLog` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `strategy` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `downloads` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `editedDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `status` enum('released','wait') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_im_conference
-- ----------------------------
DROP TABLE IF EXISTS `zt_im_conference`;
CREATE TABLE `zt_im_conference`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rid` char(24) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `cgid` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `status` enum('closed','open') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'closed',
  `participants` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `openedBy` mediumint(8) NOT NULL DEFAULT 0,
  `openedDate` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_im_conferenceaction
-- ----------------------------
DROP TABLE IF EXISTS `zt_im_conferenceaction`;
CREATE TABLE `zt_im_conferenceaction`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rid` char(24) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `type` enum('create','join','leave','close') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'create',
  `user` mediumint(8) NOT NULL DEFAULT 0,
  `date` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_im_message
-- ----------------------------
DROP TABLE IF EXISTS `zt_im_message`;
CREATE TABLE `zt_im_message`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gid` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `cgid` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `user` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `date` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `order` bigint(8) UNSIGNED NOT NULL,
  `type` enum('normal','broadcast','notify') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'normal',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `contentType` enum('text','plain','emotion','image','file','object','code') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'text',
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `mgid`(`gid`) USING BTREE,
  INDEX `mcgid`(`cgid`) USING BTREE,
  INDEX `muser`(`user`) USING BTREE,
  INDEX `mtype`(`type`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_im_messagestatus
-- ----------------------------
DROP TABLE IF EXISTS `zt_im_messagestatus`;
CREATE TABLE `zt_im_messagestatus`  (
  `user` mediumint(8) NOT NULL DEFAULT 0,
  `message` int(11) UNSIGNED NOT NULL,
  `status` enum('waiting','sent','readed','deleted') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'waiting',
  UNIQUE INDEX `user`(`user`, `message`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_im_queue
-- ----------------------------
DROP TABLE IF EXISTS `zt_im_queue`;
CREATE TABLE `zt_im_queue`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addDate` datetime(0) NOT NULL,
  `processDate` datetime(0) NOT NULL,
  `result` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_jenkins
-- ----------------------------
DROP TABLE IF EXISTS `zt_jenkins`;
CREATE TABLE `zt_jenkins`  (
  `id` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `account` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_job
-- ----------------------------
DROP TABLE IF EXISTS `zt_job`;
CREATE TABLE `zt_job`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `repo` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `frame` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `jkHost` mediumint(8) UNSIGNED NOT NULL,
  `jkJob` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `triggerType` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `svnDir` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `atDay` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `atTime` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `lastExec` datetime(0) NULL DEFAULT NULL,
  `lastStatus` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lastTag` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_lang
-- ----------------------------
DROP TABLE IF EXISTS `zt_lang`;
CREATE TABLE `zt_lang`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lang` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `section` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `key` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `system` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `lang`(`lang`, `module`, `section`, `key`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_leave
-- ----------------------------
DROP TABLE IF EXISTS `zt_leave`;
CREATE TABLE `zt_leave`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `year` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `start` time(0) NOT NULL,
  `finish` time(0) NOT NULL,
  `hours` float(4, 1) UNSIGNED NOT NULL DEFAULT 0.0,
  `backDate` datetime(0) NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `reviewedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewedDate` datetime(0) NOT NULL,
  `level` tinyint(3) NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewers` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `backReviewers` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `year`(`year`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `createdBy`(`createdBy`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_lieu
-- ----------------------------
DROP TABLE IF EXISTS `zt_lieu`;
CREATE TABLE `zt_lieu`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `year` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `start` time(0) NOT NULL,
  `finish` time(0) NOT NULL,
  `hours` float(4, 1) UNSIGNED NOT NULL DEFAULT 0.0,
  `overtime` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `trip` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `reviewedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewedDate` datetime(0) NOT NULL,
  `level` tinyint(3) NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewers` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `year`(`year`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `createdBy`(`createdBy`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_log
-- ----------------------------
DROP TABLE IF EXISTS `zt_log`;
CREATE TABLE `zt_log`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` mediumint(8) UNSIGNED NOT NULL,
  `action` mediumint(8) UNSIGNED NOT NULL,
  `date` datetime(0) NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `contentType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `result` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `objectType`(`objectType`) USING BTREE,
  INDEX `obejctID`(`objectID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_measqueue
-- ----------------------------
DROP TABLE IF EXISTS `zt_measqueue`;
CREATE TABLE `zt_measqueue`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mid` mediumint(8) UNSIGNED NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `logs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `execTime` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `params` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `updateDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_measrecords
-- ----------------------------
DROP TABLE IF EXISTS `zt_measrecords`;
CREATE TABLE `zt_measrecords`  (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mid` mediumint(8) NOT NULL,
  `program` mediumint(8) NOT NULL,
  `product` mediumint(8) NOT NULL,
  `project` mediumint(8) NOT NULL,
  `year` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `month` char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `week` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `day` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `project`(`project`) USING BTREE,
  INDEX `time`(`year`, `month`, `day`, `week`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_meastemplate
-- ----------------------------
DROP TABLE IF EXISTS `zt_meastemplate`;
CREATE TABLE `zt_meastemplate`  (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_module
-- ----------------------------
DROP TABLE IF EXISTS `zt_module`;
CREATE TABLE `zt_module`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `root` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `branch` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `name` char(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `parent` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `path` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `grade` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `owner` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `collector` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `short` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `root`(`root`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `path`(`path`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_nc
-- ----------------------------
DROP TABLE IF EXISTS `zt_nc`;
CREATE TABLE `zt_nc`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `auditplan` mediumint(8) NOT NULL,
  `listID` mediumint(8) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'active',
  `severity` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deadline` date NOT NULL,
  `resolvedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `resolution` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `resolvedDate` date NOT NULL,
  `closedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `closedDate` date NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_notify
-- ----------------------------
DROP TABLE IF EXISTS `zt_notify`;
CREATE TABLE `zt_notify`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `objectType` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` mediumint(8) UNSIGNED NOT NULL,
  `action` mediumint(9) NOT NULL,
  `toList` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ccList` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `sendTime` datetime(0) NOT NULL,
  `status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait',
  `failReason` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_oauth
-- ----------------------------
DROP TABLE IF EXISTS `zt_oauth`;
CREATE TABLE `zt_oauth`  (
  `account` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `openID` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `providerType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `providerID` mediumint(8) UNSIGNED NOT NULL,
  INDEX `account`(`account`) USING BTREE,
  INDEX `providerType`(`providerType`) USING BTREE,
  INDEX `providerID`(`providerID`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_object
-- ----------------------------
DROP TABLE IF EXISTS `zt_object`;
CREATE TABLE `zt_object`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) NOT NULL,
  `product` mediumint(8) NOT NULL,
  `from` mediumint(8) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` enum('reviewed','taged') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `range` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_overtime
-- ----------------------------
DROP TABLE IF EXISTS `zt_overtime`;
CREATE TABLE `zt_overtime`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `year` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `start` time(0) NOT NULL,
  `finish` time(0) NOT NULL,
  `hours` float(4, 1) UNSIGNED NOT NULL DEFAULT 0.0,
  `leave` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `rejectReason` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `reviewedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewedDate` datetime(0) NOT NULL,
  `level` tinyint(3) NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewers` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `year`(`year`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `createdBy`(`createdBy`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_product
-- ----------------------------
DROP TABLE IF EXISTS `zt_product`;
CREATE TABLE `zt_product`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `line` mediumint(8) NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'normal',
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `PO` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `QD` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `RD` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `feedback` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `acl` enum('open','private','custom') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open',
  `whitelist` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `createdVersion` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` mediumint(8) UNSIGNED NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `acl`(`acl`) USING BTREE,
  INDEX `order`(`order`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_productplan
-- ----------------------------
DROP TABLE IF EXISTS `zt_productplan`;
CREATE TABLE `zt_productplan`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `branch` mediumint(8) UNSIGNED NOT NULL,
  `parent` mediumint(9) NOT NULL DEFAULT 0,
  `title` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `order` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `end`(`end`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_programactivity
-- ----------------------------
DROP TABLE IF EXISTS `zt_programactivity`;
CREATE TABLE `zt_programactivity`  (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) NOT NULL,
  `process` mediumint(8) NOT NULL,
  `activity` mediumint(8) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `result` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `linkedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 83 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_programoutput
-- ----------------------------
DROP TABLE IF EXISTS `zt_programoutput`;
CREATE TABLE `zt_programoutput`  (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) NOT NULL,
  `process` mediumint(8) NOT NULL,
  `activity` mediumint(8) NOT NULL,
  `output` mediumint(8) NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `result` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `linkedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 128 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_programplan
-- ----------------------------
DROP TABLE IF EXISTS `zt_programplan`;
CREATE TABLE `zt_programplan`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `percent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `isDev` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `isMilestone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `planStarted` date NOT NULL,
  `realStarted` date NOT NULL,
  `planFinish` date NOT NULL,
  `realFinish` date NOT NULL,
  `output` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_programprocess
-- ----------------------------
DROP TABLE IF EXISTS `zt_programprocess`;
CREATE TABLE `zt_programprocess`  (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) NOT NULL,
  `process` mediumint(8) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `abbr` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `linkedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_programreport
-- ----------------------------
DROP TABLE IF EXISTS `zt_programreport`;
CREATE TABLE `zt_programreport`  (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `template` mediumint(8) NOT NULL,
  `program` mediumint(8) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `params` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_project
-- ----------------------------
DROP TABLE IF EXISTS `zt_project`;
CREATE TABLE `zt_project`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `isCat` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `catID` mediumint(8) UNSIGNED NOT NULL,
  `template` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `realDuration` int(11) NOT NULL,
  `planDuration` int(11) NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'waterfall',
  `category` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'single',
  `program` mediumint(8) NOT NULL DEFAULT 0,
  `parent` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `attribute` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `realStarted` date NOT NULL,
  `realFinished` date NOT NULL,
  `days` smallint(5) UNSIGNED NOT NULL,
  `budget` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `budgetUnit` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yuan',
  `percent` float UNSIGNED NOT NULL DEFAULT 0,
  `milestone` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `output` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `statge` enum('1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  `pri` enum('1','2','3','4') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` smallint(6) NOT NULL,
  `parentVersion` smallint(6) NOT NULL,
  `openedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `openedDate` datetime(0) NOT NULL,
  `openedVersion` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `closedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `closedDate` datetime(0) NOT NULL,
  `canceledBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `canceledDate` datetime(0) NOT NULL,
  `PO` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `PM` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `QD` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `RD` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `team` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `acl` enum('open','private','custom') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'open',
  `whitelist` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` mediumint(8) UNSIGNED NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parent`(`parent`) USING BTREE,
  INDEX `begin`(`begin`) USING BTREE,
  INDEX `end`(`end`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `acl`(`acl`) USING BTREE,
  INDEX `order`(`order`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_projectproduct
-- ----------------------------
DROP TABLE IF EXISTS `zt_projectproduct`;
CREATE TABLE `zt_projectproduct`  (
  `project` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `branch` mediumint(8) UNSIGNED NOT NULL,
  `plan` mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`project`, `product`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_projectspec
-- ----------------------------
DROP TABLE IF EXISTS `zt_projectspec`;
CREATE TABLE `zt_projectspec`  (
  `project` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `milestone` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `begin` date NOT NULL,
  `end` date NOT NULL,
  UNIQUE INDEX `project`(`project`, `version`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_projectstory
-- ----------------------------
DROP TABLE IF EXISTS `zt_projectstory`;
CREATE TABLE `zt_projectstory`  (
  `project` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `story` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `version` smallint(6) NOT NULL DEFAULT 1,
  `order` smallint(6) UNSIGNED NOT NULL,
  UNIQUE INDEX `project`(`project`, `story`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_relation
-- ----------------------------
DROP TABLE IF EXISTS `zt_relation`;
CREATE TABLE `zt_relation`  (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) NOT NULL,
  `product` mediumint(8) NOT NULL,
  `project` mediumint(8) NOT NULL,
  `AType` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `AID` mediumint(8) NOT NULL,
  `AVersion` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `relation` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `BType` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `BID` mediumint(8) NOT NULL,
  `BVersion` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `extra` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `relation`(`relation`, `AType`, `BType`, `AID`, `BID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_relationoftasks
-- ----------------------------
DROP TABLE IF EXISTS `zt_relationoftasks`;
CREATE TABLE `zt_relationoftasks`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) UNSIGNED NOT NULL,
  `pretask` mediumint(8) UNSIGNED NOT NULL,
  `condition` enum('begin','end') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `task` mediumint(8) UNSIGNED NOT NULL,
  `action` enum('begin','end') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `relationoftasks`(`project`, `task`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_release
-- ----------------------------
DROP TABLE IF EXISTS `zt_release`;
CREATE TABLE `zt_release`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `branch` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `build` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `marker` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `stories` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bugs` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `leftBugs` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'normal',
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `build`(`build`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_repo
-- ----------------------------
DROP TABLE IF EXISTS `zt_repo`;
CREATE TABLE `zt_repo`  (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `prefix` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `encoding` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `SCM` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `client` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `commits` mediumint(8) UNSIGNED NOT NULL,
  `account` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `encrypt` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'plain',
  `acl` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `synced` tinyint(1) NOT NULL DEFAULT 0,
  `lastSync` datetime(0) NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_repobranch
-- ----------------------------
DROP TABLE IF EXISTS `zt_repobranch`;
CREATE TABLE `zt_repobranch`  (
  `repo` mediumint(8) UNSIGNED NOT NULL,
  `revision` mediumint(8) UNSIGNED NOT NULL,
  `branch` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE INDEX `repo_revision_branch`(`repo`, `revision`, `branch`) USING BTREE,
  INDEX `branch`(`branch`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_repofiles
-- ----------------------------
DROP TABLE IF EXISTS `zt_repofiles`;
CREATE TABLE `zt_repofiles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `repo` mediumint(8) UNSIGNED NOT NULL,
  `revision` mediumint(8) UNSIGNED NOT NULL,
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `action` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `path`(`path`) USING BTREE,
  INDEX `parent`(`parent`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_repohistory
-- ----------------------------
DROP TABLE IF EXISTS `zt_repohistory`;
CREATE TABLE `zt_repohistory`  (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `repo` mediumint(9) NOT NULL,
  `revision` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `commit` mediumint(8) UNSIGNED NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `committer` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `repo`(`repo`, `revision`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_report
-- ----------------------------
DROP TABLE IF EXISTS `zt_report`;
CREATE TABLE `zt_report`  (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `module` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sql` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vars` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `langs` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `params` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `step` tinyint(1) NOT NULL DEFAULT 2,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 30 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_review
-- ----------------------------
DROP TABLE IF EXISTS `zt_review`;
CREATE TABLE `zt_review`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `object` mediumint(8) NOT NULL,
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewedBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `auditedBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `deadline` date NOT NULL,
  `lastReviewedBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lastReviewedDate` date NOT NULL,
  `lastAuditedBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastAuditedDate` date NOT NULL,
  `lastEditedBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastEditedDate` date NOT NULL,
  `result` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `auditResult` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 44 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_reviewissue
-- ----------------------------
DROP TABLE IF EXISTS `zt_reviewissue`;
CREATE TABLE `zt_reviewissue`  (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `review` mediumint(8) NOT NULL,
  `injection` mediumint(8) NOT NULL,
  `identify` mediumint(8) NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'review',
  `listID` mediumint(8) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `opinion` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `opinionDate` date NOT NULL,
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `resolution` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `resolutionBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `resolutionDate` date NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_reviewresult
-- ----------------------------
DROP TABLE IF EXISTS `zt_reviewresult`;
CREATE TABLE `zt_reviewresult`  (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `review` mediumint(8) NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'review',
  `result` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `opinion` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewer` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` date NOT NULL,
  `consumed` float NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `reviewer`(`review`, `reviewer`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_score
-- ----------------------------
DROP TABLE IF EXISTS `zt_score`;
CREATE TABLE `zt_score`  (
  `id` bigint(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `method` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `before` int(11) NOT NULL DEFAULT 0,
  `score` int(11) NOT NULL DEFAULT 0,
  `after` int(11) NOT NULL DEFAULT 0,
  `time` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `account`(`account`) USING BTREE,
  INDEX `model`(`module`) USING BTREE,
  INDEX `method`(`method`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_searchdict
-- ----------------------------
DROP TABLE IF EXISTS `zt_searchdict`;
CREATE TABLE `zt_searchdict`  (
  `key` smallint(5) UNSIGNED NOT NULL,
  `value` char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_searchindex
-- ----------------------------
DROP TABLE IF EXISTS `zt_searchindex`;
CREATE TABLE `zt_searchindex`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `objectType` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedDate` datetime(0) NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `object`(`objectType`, `objectID`) USING BTREE,
  INDEX `addedDate`(`addedDate`) USING BTREE,
  FULLTEXT INDEX `content`(`content`),
  FULLTEXT INDEX `title`(`title`)
) ENGINE = MyISAM AUTO_INCREMENT = 40 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_serverroom
-- ----------------------------
DROP TABLE IF EXISTS `zt_serverroom`;
CREATE TABLE `zt_serverroom`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `city` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `line` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bandwidth` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `provider` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `owner` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_service
-- ----------------------------
DROP TABLE IF EXISTS `zt_service`;
CREATE TABLE `zt_service`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color` char(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `dept` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `devel` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `qa` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ops` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hosts` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `softName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `softVersion` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `parent` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `path` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `grade` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_solutions
-- ----------------------------
DROP TABLE IF EXISTS `zt_solutions`;
CREATE TABLE `zt_solutions`  (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) NOT NULL,
  `project` mediumint(8) NOT NULL,
  `contents` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '问题描述',
  `support` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '是否需要高层支持',
  `measures` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '解决建议',
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedDate` date NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_sqlview
-- ----------------------------
DROP TABLE IF EXISTS `zt_sqlview`;
CREATE TABLE `zt_sqlview`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sql` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_story
-- ----------------------------
DROP TABLE IF EXISTS `zt_story`;
CREATE TABLE `zt_story`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent` mediumint(9) NOT NULL DEFAULT 0,
  `product` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `branch` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `module` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `plan` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `source` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sourceNote` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fromBug` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `feedback` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'story',
  `pri` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `estimate` float UNSIGNED NOT NULL,
  `status` enum('','changed','active','draft','closed') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `color` char(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `stage` enum('','wait','planned','projected','developing','developed','testing','tested','verified','released','closed') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait',
  `stagedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mailto` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `openedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `openedDate` datetime(0) NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `assignedDate` datetime(0) NOT NULL,
  `lastEditedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `lastEditedDate` datetime(0) NOT NULL,
  `reviewedBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewedDate` date NOT NULL,
  `closedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `closedDate` datetime(0) NOT NULL,
  `closedReason` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `toBug` mediumint(8) UNSIGNED NOT NULL,
  `childStories` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `linkStories` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `duplicateStory` mediumint(8) UNSIGNED NOT NULL,
  `version` smallint(6) NOT NULL DEFAULT 1,
  `storyChanged` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `assignedTo`(`assignedTo`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_storyspec
-- ----------------------------
DROP TABLE IF EXISTS `zt_storyspec`;
CREATE TABLE `zt_storyspec`  (
  `story` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `spec` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `verify` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE INDEX `story`(`story`, `version`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_storystage
-- ----------------------------
DROP TABLE IF EXISTS `zt_storystage`;
CREATE TABLE `zt_storystage`  (
  `story` mediumint(8) UNSIGNED NOT NULL,
  `branch` mediumint(8) UNSIGNED NOT NULL,
  `stage` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `stagedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE INDEX `story_branch`(`story`, `branch`) USING BTREE,
  INDEX `story`(`story`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_suitecase
-- ----------------------------
DROP TABLE IF EXISTS `zt_suitecase`;
CREATE TABLE `zt_suitecase`  (
  `suite` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `case` mediumint(8) UNSIGNED NOT NULL,
  `version` smallint(5) UNSIGNED NOT NULL,
  UNIQUE INDEX `suitecase`(`suite`, `case`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_task
-- ----------------------------
DROP TABLE IF EXISTS `zt_task`;
CREATE TABLE `zt_task`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent` mediumint(8) NOT NULL DEFAULT 0,
  `project` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `module` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `story` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `design` mediumint(8) UNSIGNED NOT NULL,
  `storyVersion` smallint(6) NOT NULL DEFAULT 1,
  `designVersion` smallint(6) UNSIGNED NOT NULL,
  `fromBug` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `feedback` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pri` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `estimate` float UNSIGNED NOT NULL,
  `consumed` float UNSIGNED NOT NULL,
  `left` float UNSIGNED NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('wait','doing','done','pause','cancel','closed') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait',
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `color` char(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mailto` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` smallint(6) NOT NULL,
  `openedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `openedDate` datetime(0) NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedDate` datetime(0) NOT NULL,
  `estStarted` date NOT NULL,
  `realStarted` date NOT NULL,
  `finishedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `finishedDate` datetime(0) NOT NULL,
  `finishedList` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `canceledBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `canceledDate` datetime(0) NOT NULL,
  `closedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `closedDate` datetime(0) NOT NULL,
  `realDuration` int(11) NOT NULL,
  `planDuration` int(11) NOT NULL,
  `closedReason` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastEditedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastEditedDate` datetime(0) NOT NULL,
  `activatedDate` date NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `project`(`project`) USING BTREE,
  INDEX `story`(`story`) USING BTREE,
  INDEX `assignedTo`(`assignedTo`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_taskestimate
-- ----------------------------
DROP TABLE IF EXISTS `zt_taskestimate`;
CREATE TABLE `zt_taskestimate`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `task` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `left` float UNSIGNED NOT NULL DEFAULT 0,
  `consumed` float UNSIGNED NOT NULL,
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `work` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `task`(`task`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_taskspec
-- ----------------------------
DROP TABLE IF EXISTS `zt_taskspec`;
CREATE TABLE `zt_taskspec`  (
  `task` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `estStarted` date NOT NULL,
  `deadline` date NOT NULL,
  UNIQUE INDEX `task`(`task`, `version`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_team
-- ----------------------------
DROP TABLE IF EXISTS `zt_team`;
CREATE TABLE `zt_team`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `root` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `type` enum('project','task') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'project',
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `role` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `position` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `limited` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no',
  `join` date NOT NULL DEFAULT '0000-00-00',
  `days` smallint(5) UNSIGNED NOT NULL,
  `hours` float(2, 1) UNSIGNED NOT NULL DEFAULT 0.0,
  `estimate` decimal(12, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `consumed` decimal(12, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `left` decimal(12, 2) UNSIGNED NOT NULL DEFAULT 0.00,
  `order` tinyint(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `team`(`root`, `type`, `account`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_testreport
-- ----------------------------
DROP TABLE IF EXISTS `zt_testreport`;
CREATE TABLE `zt_testreport`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `project` mediumint(8) UNSIGNED NOT NULL,
  `tasks` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `builds` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `owner` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `members` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `stories` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bugs` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cases` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `report` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectType` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` mediumint(8) UNSIGNED NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_testresult
-- ----------------------------
DROP TABLE IF EXISTS `zt_testresult`;
CREATE TABLE `zt_testresult`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `run` mediumint(8) UNSIGNED NOT NULL,
  `case` mediumint(8) UNSIGNED NOT NULL,
  `version` smallint(5) UNSIGNED NOT NULL,
  `job` mediumint(8) UNSIGNED NOT NULL,
  `compile` mediumint(8) UNSIGNED NOT NULL,
  `caseResult` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `stepResults` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastRunner` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` datetime(0) NOT NULL,
  `duration` float NOT NULL,
  `xml` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deploy` mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `case`(`case`) USING BTREE,
  INDEX `version`(`version`) USING BTREE,
  INDEX `run`(`run`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_testrun
-- ----------------------------
DROP TABLE IF EXISTS `zt_testrun`;
CREATE TABLE `zt_testrun`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `task` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `case` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `version` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `assignedTo` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `lastRunner` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastRunDate` datetime(0) NOT NULL,
  `lastRunResult` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `task`(`task`, `case`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_testsuite
-- ----------------------------
DROP TABLE IF EXISTS `zt_testsuite`;
CREATE TABLE `zt_testsuite`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addedDate` datetime(0) NOT NULL,
  `lastEditedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastEditedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_testtask
-- ----------------------------
DROP TABLE IF EXISTS `zt_testtask`;
CREATE TABLE `zt_testtask`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product` mediumint(8) UNSIGNED NOT NULL,
  `project` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `build` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `owner` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pri` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `mailto` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `report` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` enum('blocked','doing','wait','done') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait',
  `auto` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no',
  `subStatus` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product`(`product`) USING BTREE,
  INDEX `build`(`build`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_todo
-- ----------------------------
DROP TABLE IF EXISTS `zt_todo`;
CREATE TABLE `zt_todo`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` date NOT NULL,
  `begin` smallint(4) UNSIGNED ZEROFILL NOT NULL,
  `end` smallint(4) UNSIGNED ZEROFILL NOT NULL,
  `feedback` mediumint(8) UNSIGNED NOT NULL,
  `type` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cycle` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `idvalue` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `pri` tinyint(3) UNSIGNED NOT NULL,
  `name` char(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` enum('wait','doing','done','closed') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait',
  `private` tinyint(1) NOT NULL,
  `config` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `assignedTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `assignedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `assignedDate` datetime(0) NOT NULL,
  `finishedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `finishedDate` datetime(0) NOT NULL,
  `closedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `closedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `account`(`account`) USING BTREE,
  INDEX `assignedTo`(`assignedTo`) USING BTREE,
  INDEX `finishedBy`(`finishedBy`) USING BTREE,
  INDEX `date`(`date`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_trip
-- ----------------------------
DROP TABLE IF EXISTS `zt_trip`;
CREATE TABLE `zt_trip`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('trip','egress') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'trip',
  `customers` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `year` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `start` time(0) NOT NULL,
  `finish` time(0) NOT NULL,
  `from` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `to` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `year`(`year`) USING BTREE,
  INDEX `createdBy`(`createdBy`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_user
-- ----------------------------
DROP TABLE IF EXISTS `zt_user`;
CREATE TABLE `zt_user`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dept` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `role` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `realname` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `nickname` char(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `commiter` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `avatar` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `gender` enum('f','m') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'f',
  `email` char(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `skype` char(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `qq` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mobile` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `phone` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `weixin` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `dingding` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `slack` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `whatsapp` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `address` char(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `zipcode` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `join` date NOT NULL DEFAULT '0000-00-00',
  `visits` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `ip` char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `last` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `fails` tinyint(5) NOT NULL DEFAULT 0,
  `locked` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `feedback` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `ranzhi` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `ldap` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `scoreLevel` int(11) NOT NULL DEFAULT 0,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `clientStatus` enum('online','away','busy','offline') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'offline',
  `clientLang` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'zh-cn',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE,
  INDEX `dept`(`dept`) USING BTREE,
  INDEX `email`(`email`) USING BTREE,
  INDEX `commiter`(`commiter`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_usercontact
-- ----------------------------
DROP TABLE IF EXISTS `zt_usercontact`;
CREATE TABLE `zt_usercontact`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `listName` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userList` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `account`(`account`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_usergroup
-- ----------------------------
DROP TABLE IF EXISTS `zt_usergroup`;
CREATE TABLE `zt_usergroup`  (
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `group` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  UNIQUE INDEX `account`(`account`, `group`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for zt_userquery
-- ----------------------------
DROP TABLE IF EXISTS `zt_userquery`;
CREATE TABLE `zt_userquery`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(90) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `form` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sql` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `shortcut` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `account`(`account`) USING BTREE,
  INDEX `module`(`module`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_usertpl
-- ----------------------------
DROP TABLE IF EXISTS `zt_usertpl`;
CREATE TABLE `zt_usertpl`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `public` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `account`(`account`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_userview
-- ----------------------------
DROP TABLE IF EXISTS `zt_userview`;
CREATE TABLE `zt_userview`  (
  `account` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `products` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `projects` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE INDEX `account`(`account`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_webhook
-- ----------------------------
DROP TABLE IF EXISTS `zt_webhook`;
CREATE TABLE `zt_webhook`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `contentType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'application/json',
  `sendType` enum('sync','async') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'sync',
  `products` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `projects` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `params` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `actions` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_weeklyreport
-- ----------------------------
DROP TABLE IF EXISTS `zt_weeklyreport`;
CREATE TABLE `zt_weeklyreport`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `weekStart` date NOT NULL,
  `pv` float(9, 2) NOT NULL,
  `ev` float(9, 2) NOT NULL,
  `ac` float(9, 2) NOT NULL,
  `sv` float(9, 2) NOT NULL,
  `cv` float(9, 2) NOT NULL,
  `staff` smallint(5) UNSIGNED NOT NULL,
  `progress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `workload` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `week`(`program`, `weekStart`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workestimation
-- ----------------------------
DROP TABLE IF EXISTS `zt_workestimation`;
CREATE TABLE `zt_workestimation`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) UNSIGNED NOT NULL,
  `scale` mediumint(8) UNSIGNED NOT NULL,
  `productivity` smallint(3) UNSIGNED NOT NULL,
  `duration` mediumint(8) UNSIGNED NOT NULL,
  `unitLaborCost` mediumint(8) UNSIGNED NOT NULL,
  `totalLaborCost` mediumint(8) UNSIGNED NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `dayHour` float(5, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflow
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflow`;
CREATE TABLE `zt_workflow`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `child` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'flow',
  `navigator` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `position` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `table` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `js` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `css` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` smallint(5) UNSIGNED NOT NULL,
  `buildin` tinyint(1) UNSIGNED NOT NULL,
  `administrator` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1.0',
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique`(`app`, `module`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `app`(`app`) USING BTREE,
  INDEX `module`(`module`) USING BTREE,
  INDEX `order`(`order`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 39 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowaction
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowaction`;
CREATE TABLE `zt_workflowaction`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` enum('single','batch') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'single',
  `batchMode` enum('same','different') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'different',
  `extensionType` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'override' COMMENT 'none | extend | override',
  `open` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `position` enum('menu','browseandview','browse','view') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'browseandview',
  `layout` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `show` enum('dropdownlist','direct') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'dropdownlist',
  `order` smallint(5) UNSIGNED NOT NULL,
  `buildin` tinyint(1) UNSIGNED NOT NULL,
  `conditions` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `verifications` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hooks` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `linkages` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `js` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `css` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `toList` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique`(`module`, `action`) USING BTREE,
  INDEX `module`(`module`) USING BTREE,
  INDEX `action`(`action`) USING BTREE,
  INDEX `order`(`order`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 463 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowdatasource
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowdatasource`;
CREATE TABLE `zt_workflowdatasource`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('system','sql','func','option','lang') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'option',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `datasource` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 42 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowfield
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowfield`;
CREATE TABLE `zt_workflowfield`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `field` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'varchar',
  `length` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `control` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `options` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `default` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rules` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `placeholder` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `canExport` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  `canSearch` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  `isKey` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `isValue` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `order` smallint(5) UNSIGNED NOT NULL,
  `buildin` tinyint(1) UNSIGNED NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `readonly` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique`(`module`, `field`) USING BTREE,
  INDEX `module`(`module`) USING BTREE,
  INDEX `field`(`field`) USING BTREE,
  INDEX `order`(`order`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 814 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowlabel
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowlabel`;
CREATE TABLE `zt_workflowlabel`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `params` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` tinyint(3) NOT NULL,
  `buildin` tinyint(1) UNSIGNED NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `module`(`module`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 95 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowlayout
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowlayout`;
CREATE TABLE `zt_workflowlayout`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `field` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` smallint(5) UNSIGNED NOT NULL,
  `width` smallint(5) NOT NULL,
  `position` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `readonly` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `mobileShow` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
  `totalShow` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `defaultValue` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `layoutRules` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique`(`module`, `action`, `field`) USING BTREE,
  INDEX `module`(`module`) USING BTREE,
  INDEX `action`(`action`) USING BTREE,
  INDEX `order`(`order`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2659 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowlinkdata
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowlinkdata`;
CREATE TABLE `zt_workflowlinkdata`  (
  `objectType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `objectID` mediumint(8) UNSIGNED NOT NULL,
  `linkedType` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `linkedID` mediumint(8) UNSIGNED NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  UNIQUE INDEX `unique`(`objectType`, `objectID`, `linkedType`, `linkedID`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowrelation
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowrelation`;
CREATE TABLE `zt_workflowrelation`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `prev` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `next` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `field` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `label` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `actions` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowrelationlayout
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowrelationlayout`;
CREATE TABLE `zt_workflowrelationlayout`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `prev` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `next` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `field` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order` smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique`(`prev`, `next`, `action`, `field`) USING BTREE,
  INDEX `prev`(`prev`) USING BTREE,
  INDEX `next`(`next`) USING BTREE,
  INDEX `action`(`action`) USING BTREE,
  INDEX `order`(`order`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowrule
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowrule`;
CREATE TABLE `zt_workflowrule`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('system','regex','func') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'regex',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rule` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowsql
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowsql`;
CREATE TABLE `zt_workflowsql`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `field` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sql` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vars` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `module`(`module`) USING BTREE,
  INDEX `field`(`field`) USING BTREE,
  INDEX `action`(`action`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zt_workflowversion
-- ----------------------------
DROP TABLE IF EXISTS `zt_workflowversion`;
CREATE TABLE `zt_workflowversion`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `version` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fields` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `actions` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `layouts` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sqls` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `labels` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `table` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `datas` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `moduleversion`(`module`, `version`) USING BTREE,
  INDEX `module`(`module`) USING BTREE,
  INDEX `version`(`version`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- View structure for ztv_dayactions
-- ----------------------------
DROP VIEW IF EXISTS `ztv_dayactions`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_dayactions` AS select count(0) AS `actions`,left(`zt_action`.`date`,10) AS `day` from `zt_action` group by left(`zt_action`.`date`,10);

-- ----------------------------
-- View structure for ztv_daybugopen
-- ----------------------------
DROP VIEW IF EXISTS `ztv_daybugopen`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_daybugopen` AS select count(0) AS `bugopen`,left(`zt_action`.`date`,10) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'bug') and (`zt_action`.`action` = 'opened')) group by left(`zt_action`.`date`,10);

-- ----------------------------
-- View structure for ztv_daybugresolve
-- ----------------------------
DROP VIEW IF EXISTS `ztv_daybugresolve`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_daybugresolve` AS select count(0) AS `bugresolve`,left(`zt_action`.`date`,10) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'bug') and (`zt_action`.`action` = 'resolved')) group by left(`zt_action`.`date`,10);

-- ----------------------------
-- View structure for ztv_dayeffort
-- ----------------------------
DROP VIEW IF EXISTS `ztv_dayeffort`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_dayeffort` AS select round(sum(`zt_effort`.`consumed`),1) AS `consumed`,`zt_effort`.`date` AS `date` from `zt_effort` group by `zt_effort`.`date`;

-- ----------------------------
-- View structure for ztv_daystoryclose
-- ----------------------------
DROP VIEW IF EXISTS `ztv_daystoryclose`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_daystoryclose` AS select count(0) AS `storyclose`,left(`zt_action`.`date`,10) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'story') and (`zt_action`.`action` = 'closed')) group by left(`zt_action`.`date`,10);

-- ----------------------------
-- View structure for ztv_daystoryopen
-- ----------------------------
DROP VIEW IF EXISTS `ztv_daystoryopen`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_daystoryopen` AS select count(0) AS `storyopen`,left(`zt_action`.`date`,10) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'story') and (`zt_action`.`action` = 'opened')) group by left(`zt_action`.`date`,10);

-- ----------------------------
-- View structure for ztv_daytaskfinish
-- ----------------------------
DROP VIEW IF EXISTS `ztv_daytaskfinish`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_daytaskfinish` AS select count(0) AS `taskfinish`,left(`zt_action`.`date`,10) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'task') and (`zt_action`.`action` = 'finished')) group by left(`zt_action`.`date`,10);

-- ----------------------------
-- View structure for ztv_daytaskopen
-- ----------------------------
DROP VIEW IF EXISTS `ztv_daytaskopen`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_daytaskopen` AS select count(0) AS `taskopen`,left(`zt_action`.`date`,10) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'task') and (`zt_action`.`action` = 'opened')) group by left(`zt_action`.`date`,10);

-- ----------------------------
-- View structure for ztv_dayuserlogin
-- ----------------------------
DROP VIEW IF EXISTS `ztv_dayuserlogin`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_dayuserlogin` AS select count(0) AS `userlogin`,left(`zt_action`.`date`,10) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'user') and (`zt_action`.`action` = 'login')) group by left(`zt_action`.`date`,10);

-- ----------------------------
-- View structure for ztv_productbugs
-- ----------------------------
DROP VIEW IF EXISTS `ztv_productbugs`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_productbugs` AS select `zt_bug`.`product` AS `product`,count(0) AS `bugs`,sum(if((`zt_bug`.`resolution` = ''),0,1)) AS `resolutions`,sum(if((`zt_bug`.`severity` <= 2),1,0)) AS `seriousBugs` from `zt_bug` where (`zt_bug`.`deleted` = '0') group by `zt_bug`.`product`;

-- ----------------------------
-- View structure for ztv_productstories
-- ----------------------------
DROP VIEW IF EXISTS `ztv_productstories`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_productstories` AS select `zt_story`.`product` AS `product`,count('*') AS `stories`,sum(if((`zt_story`.`status` = 'closed'),0,1)) AS `undone` from `zt_story` where (`zt_story`.`deleted` = '0') group by `zt_story`.`product`;

-- ----------------------------
-- View structure for ztv_projectbugs
-- ----------------------------
DROP VIEW IF EXISTS `ztv_projectbugs`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_projectbugs` AS select `zt_bug`.`project` AS `project`,count(0) AS `bugs`,sum(if((`zt_bug`.`resolution` = ''),0,1)) AS `resolutions`,sum(if((`zt_bug`.`severity` <= 2),1,0)) AS `seriousBugs` from `zt_bug` where (`zt_bug`.`deleted` = '0') group by `zt_bug`.`project`;

-- ----------------------------
-- View structure for ztv_projectstories
-- ----------------------------
DROP VIEW IF EXISTS `ztv_projectstories`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_projectstories` AS select `t1`.`project` AS `project`,count('*') AS `stories`,sum(if((`t2`.`status` = 'closed'),0,1)) AS `undone` from (`zt_projectstory` `t1` left join `zt_story` `t2` on((`t1`.`story` = `t2`.`id`))) where (`t2`.`deleted` = '0') group by `t1`.`project`;

-- ----------------------------
-- View structure for ztv_projectsummary
-- ----------------------------
DROP VIEW IF EXISTS `ztv_projectsummary`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_projectsummary` AS select `zt_task`.`project` AS `project`,sum(if((`zt_task`.`parent` >= '0'),`zt_task`.`estimate`,0)) AS `estimate`,sum(if((`zt_task`.`parent` >= '0'),`zt_task`.`consumed`,0)) AS `consumed`,sum(if(((`zt_task`.`status` <> 'cancel') and (`zt_task`.`status` <> 'closed') and (`zt_task`.`parent` >= '0')),`zt_task`.`left`,0)) AS `left`,count(0) AS `number`,sum(if(((`zt_task`.`status` <> 'done') and (`zt_task`.`status` <> 'closed')),1,0)) AS `undone`,sum((if((`zt_task`.`parent` >= '0'),`zt_task`.`consumed`,0) + if(((`zt_task`.`status` <> 'cancel') and (`zt_task`.`status` <> 'closed') and (`zt_task`.`parent` >= '0')),`zt_task`.`left`,0))) AS `totalReal` from `zt_task` where (`zt_task`.`deleted` = '0') group by `zt_task`.`project`;

-- ----------------------------
-- View structure for ztv_projectteams
-- ----------------------------
DROP VIEW IF EXISTS `ztv_projectteams`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `ztv_projectteams` AS select `zt_team`.`root` AS `project`,count('*') AS `teams` from `zt_team` where (`zt_team`.`type` = 'project') group by `zt_team`.`root`;

SET FOREIGN_KEY_CHECKS = 1;
