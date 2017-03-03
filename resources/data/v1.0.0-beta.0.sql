/*************************************************
 * 接口分类表
 ***************************************************/

CREATE TABLE IF NOT EXISTS `wk_request_folder` (
  `classify_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `member_id` int(11) NOT NULL COMMENT '作者',
  `api_count` int(11) NOT NULL DEFAULT '0' COMMENT '接口数量',
  `classify_name` varchar(255) NOT NULL COMMENT '分类名称',
  `description` text COMMENT '分类描述',
  `classify_sort` int(11) DEFAULT '0' COMMENT '排序：越大排序越靠前',
  `parent_id` int(11) DEFAULT '0' COMMENT '父ID',
  PRIMARY KEY (`classify_id`),
  UNIQUE KEY `wk_api_classify_classify__id_uindex` (`classify_id`),
  KEY `wk_api_classify_parent_id_index` (`classify_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口分类表';


/*************************************************
 * 接口分享表
 ***************************************************/

CREATE TABLE IF NOT EXISTS `wk_request_share` (
  `share_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `classify_id` int(11) NOT NULL COMMENT '共享的分类',
  `member_id` int(11) NOT NULL COMMENT '用户ID',
  `role` int(11) NOT NULL DEFAULT '0' COMMENT '角色：0 管理者/ 1 开发者 / 2 观察者',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`share_id`),
  UNIQUE KEY `wk_api_share_share_id_uindex` (`share_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口分享表';

/*************************************************
 * 接口表
 ***************************************************/

CREATE TABLE IF NOT EXISTS `wk_requests` (
  `api_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `doc_id` int(11) DEFAULT NULL COMMENT '关联的文档ID',
  `classify_id` int(11) NOT NULL COMMENT '分类ID',
  `api_name` varchar(255) NOT NULL COMMENT '接口名称',
  `description` text COMMENT '接口描述',
  `method` varchar(20) NOT NULL DEFAULT 'GET' COMMENT '请求类型',
  `request_url` varchar(1000) NOT NULL COMMENT '请求地址',
  `authorization_classify` varchar(255) DEFAULT NULL COMMENT '认证方式',
  `authorization` text COMMENT '认证方式的内容json',
  `headers` text COMMENT '请求头json',
  `body` text COMMENT '请求内容json，如果是不支持body，则默认编码到URL中传递',
  `raw_data` text COMMENT 'raw格式数据',
  `enctype` varchar(200) DEFAULT 'x-www-form-urlencoded' COMMENT 'body的编码方式',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `create_at` int(11) NOT NULL COMMENT '创建人',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`api_id`),
  UNIQUE KEY `wk_api_api_id_uindex` (`api_id`),
  KEY `wk_api_classify_id_index` (`classify_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口表';