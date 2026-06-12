ALTER TABLE `zt_ai_agent` ADD COLUMN `displayPosition` varchar(20) NOT NULL DEFAULT '' COMMENT '显示位置，目前包括：详情页（detail）、表单页（form）' AFTER `module`;
ALTER TABLE `zt_ai_agent` ADD COLUMN `actionPurpose` varchar(100) NOT NULL DEFAULT '' COMMENT '操作目的编码' AFTER `displayPosition`;

CREATE TABLE `zt_ai_skill` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `name` VARCHAR(255) NOT NULL COMMENT 'Skill名称',
    `type` VARCHAR(255) NOT NULL DEFAULT 'private' COMMENT '类型：个人/公开',
    `category` INT NOT NULL COMMENT '技能类型(模块ID)',
    `status` VARCHAR(255) NOT NULL COMMENT '状态',
    `fromID` INT NOT NULL COMMENT '来源ID（可将公开技能复制成个人技能）',
    `desc` TEXT NULL COMMENT '描述',
    `createdBy` VARCHAR(255) NOT NULL COMMENT '创建人',
    `createdDate` DATETIME NULL DEFAULT NULL COMMENT '创建时间',
    `editedBy` VARCHAR(255) UNSIGNED NULL COMMENT '最后修改人ID',
    `editedDate` DATETIME NULL DEFAULT NULL COMMENT '最后修改时间',
    `deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '是否删除',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;