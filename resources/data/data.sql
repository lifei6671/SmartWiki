/*****************************************
* 文档表
******************************************/
CREATE TABLE IF NOT EXISTS `wk_document`(
  `doc_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(200) NOT NULL COMMENT '文档名称',
  `parent_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '父ID',
  `project_id` int(11) NOT NULL COMMENT '所属项目',
  `doc_sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `doc_content` LONGTEXT DEFAULT NULL COMMENT '文档内容',
  `create_time` datetime DEFAULT NULL,
  `create_at` int(11) NOT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_at` int(11) DEFAULT NULL,
  `version` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '当前时间戳',
  PRIMARY KEY (`doc_id`),
  KEY `project_id_index` (`project_id`),
  KEY `doc_sort_index` (`doc_sort`),
  UNIQUE KEY `wk_document_id_uindex` (`doc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档表';

/*****************************************
* 文档编辑历史记录表
******************************************/
CREATE TABLE  IF NOT EXISTS `wk_document_history` (
  `history_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `doc_id` bigint(20) NOT NULL COMMENT '文档ID',
  `doc_name` varchar(200) NOT NULL COMMENT '文档名称',
  `parent_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '父ID',
  `doc_content` longtext COMMENT '文档内容',
  `modify_time` datetime DEFAULT NULL,
  `modify_at` int(11) DEFAULT NULL,
  `version` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '当前时间戳',
  `create_time` DATETIME  COMMENT '历史记录创建时间',
  `create_at` INT NOT NULL COMMENT '历史记录创建人',
  PRIMARY KEY (`history_id`),
  UNIQUE KEY `history_id` (`history_id`),
  KEY `doc_id_index` (`doc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档编辑历史记录表';

/*****************************************
* 项目表
******************************************/
CREATE TABLE  IF NOT EXISTS `wk_project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(200) NOT NULL COMMENT '项目名称',
  `description` varchar(2000) DEFAULT NULL COMMENT '项目描述',
  `doc_tree` text COMMENT '当前项目的文档树',
  `project_open_state` tinyint(4) DEFAULT '0' COMMENT '项目公开状态：0 私密，1 完全公开，2 加密公开',
  `project_password` varchar(255) DEFAULT NULL COMMENT '项目密码',
  `doc_count` int(11) DEFAULT '0' COMMENT '文档数据量',
  `create_time` datetime DEFAULT NULL,
  `create_at` int(11) NOT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_at` int(11) DEFAULT NULL,
  `version` varchar(50) NOT NULL DEFAULT '0.1' COMMENT '版本号',
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `project_id_uindex` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目表';

/*****************************************
* 项目成员表表
******************************************/
CREATE TABLE IF NOT EXISTS `wk_relationship`(
  `rel_id` INT NOT NULL AUTO_INCREMENT,
  `member_id` INT NOT NULL ,
  `project_id` INT NOT NULL,
  `role_type` TINYINT DEFAULT 0 COMMENT '项目角色：0 参与者，1 所有者',
  PRIMARY KEY (`rel_id`),
  KEY `member_id_index` (`member_id`),
  KEY `project_id_index` (`project_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目成员表表';

/*****************************************
* 用户信息表
******************************************/
CREATE TABLE IF NOT EXISTS `wk_member`(
  `member_id` INT NOT NULL AUTO_INCREMENT,
  `account` VARCHAR(100) NOT NULL COMMENT '账号',
  `member_passwd` VARCHAR(2000) NOT NULL COMMENT '密码',
  `nickname` VARCHAR(50) DEFAULT NULL COMMENT '昵称',
  `description` TEXT DEFAULT NULL COMMENT '描述',
  `group_level` INT NOT NULL DEFAULT 1 COMMENT '用户基本：0 超级管理员，1 普通用户，2 访客',
  `email` VARCHAR(100) DEFAULT NULL COMMENT '用户邮箱',
  `phone` VARCHAR(50) DEFAULT NULL COMMENT '手机号码',
  `headimgurl` VARCHAR(1000) DEFAULT NULL COMMENT '用户头像',
  `remember_token` VARCHAR(200) DEFAULT NULL COMMENT '用户session',
  `state` tinyint(4) DEFAULT '0' COMMENT '用户状态：0 正常，1 禁用',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `create_at` INT DEFAULT NULL COMMENT '创建人',
  `modify_time` datetime DEFAULT NULL COMMENT '修改时间',
  `last_login_time` DATETIME DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` VARCHAR(100) DEFAULT NULL COMMENT '最后登录IP',
  `user_agent` VARCHAR(200) DEFAULT NULL COMMENT '最后登录浏览器信息',
  `version` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '当前时间戳',
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `account_uindex` (`account`),
  UNIQUE KEY `email_uindex` (`email`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息表';

/*****************************************
* 附件表
******************************************/
CREATE TABLE IF NOT EXISTS `wk_attachment`(
  `attachment_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT '主键',
  `doc_id` BIGINT DEFAULT 0 COMMENT '文档ID',
  `file_name` VARCHAR(200) NOT NULL COMMENT '文件名称',
  `file_size` FLOAT DEFAULT 0 COMMENT '文件大小',
  `create_time` DATETIME NOT NULL COMMENT '创建日期',
  `create_at` INT NOT NULL COMMENT '上传人',
  `comment` TEXT DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`attachment_id`),
  UNIQUE `attachment_id_uindex` (`attachment_id`),
  KEY `page_id_index` (`doc_id`)
)ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='附件表';

/*****************************************
* 配置表
******************************************/
CREATE TABLE IF NOT EXISTS `wk_config`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR(100) NOT NULL COMMENT '名称',
  `key` VARCHAR(200) NOT NULL COMMENT '键',
  `value` text DEFAULT NULL COMMENT '值',
  `config_type` varchar(20) DEFAULT 'user' COMMENT '变量类型：system 系统内置/user 用户定义',
  `remark` text DEFAULT NULL COMMENT '备注',
  `create_time` DATETIME NOT NULL COMMENT '创建时间',
  `modify_time` DATETIME DEFAULT NULL COMMENT '修改时间',
  UNIQUE `key_uindex` (`key`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配置表';

INSERT wk_config(name, `key`, value, config_type, remark, create_time) VALUES ('启用文档历史','ENABLED_HISTORY','0','system','是否启用文档历史记录：0 否/1 是',now());
INSERT wk_config(name, `key`, value, config_type, remark, create_time) VALUES ('站点名称','SITE_NAME','SmartWiki','system','站点名称',now());
INSERT wk_config(name, `key`, value, config_type, remark, create_time) VALUES ('邮件有效期','MAIL_TOKEN_TIME','3600','system','找回密码邮件有效期,单位为秒',now());
INSERT wk_config(name, `key`, value, config_type, remark, create_time) VALUES ('启用匿名访问','ENABLE_ANONYMOUS','0','system','是否启用匿名访问：0 否/1 是',now());
INSERT wk_config(name, `key`, value, config_type, remark, create_time) VALUES ('启用登录验证码','ENABLED_CAPTCHA','0','system','是否启用登录验证码：0 否/1 是',now());


/*****************************************
* 系统日志表
******************************************/
CREATE TABLE IF NOT EXISTS `wk_logs`(
  `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `original_data` TEXT DEFAULT NULL COMMENT '操作前的原数据',
  `present_data` TEXT DEFAULT NULL  COMMENT '操作后的数据',
  `content` TEXT DEFAULT NULL COMMENT '日志内容',
  `create_time` DATETIME NOT NULL COMMENT '创建时间',
  `create_at` INT NOT NULL COMMENT '创建人'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统日志表';

/*****************************************
* 找回密码表
******************************************/
CREATE TABLE `wk_passwords` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `token` varchar(200) NOT NULL COMMENT '唯一认证码',
  `email` varchar(200) NOT NULL COMMENT '收件的邮箱',
  `is_valid` int(11) DEFAULT '0' COMMENT '是否有效：0 是/1 否',
  `create_time` datetime NOT NULL COMMENT '记录创建时间',
  `user_address` varchar(200) DEFAULT NULL COMMENT '用户IP地址',
  `send_time` datetime DEFAULT NULL COMMENT '邮件发送时间',
  `valid_time` datetime default null comment '校验时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wk_passwords_id_uindex` (`id`),
  UNIQUE KEY `wk_passwords_token_uindex` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='找回密码';

/**********************************************
 * 项目分类表
 *********************************************/
CREATE TABLE `wk_project_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `type_name` varchar(200) NOT NULL COMMENT '文档名称',
  `type_sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` datetime DEFAULT NULL,
  `create_at` int(11) NOT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_at` int(11) DEFAULT NULL,
  `version` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '当前时间戳',
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `wk_project_types_id_uindex` (`type_id`),
  KEY `type_id_index` (`type_id`),
  KEY `type_sort_index` (`type_sort`),
  KEY `wk_project_types_parent_id_index` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目分类表';