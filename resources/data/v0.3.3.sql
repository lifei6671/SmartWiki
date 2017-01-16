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

INSERT wk_config(name, `key`, value, config_type, remark, create_time) VALUES ('启用登录验证码','ENABLED_CAPTCHA','0','system','是否启用登录验证码：0 否/1 是',now());
