
DROP TABLE IF EXISTS `wu_business`;
CREATE TABLE `wu_business` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `merchant` varchar(255) NOT NULL DEFAULT '' COMMENT '商户店铺名',
   `account` varchar(50) NOT NULL DEFAULT '' COMMENT '商户账号',
   `password` varchar(255) NOT NULL DEFAULT '' COMMENT '商户密码',
   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人姓名',
   `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
   `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
   `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别0=>未知，1=>男，2=>女',
   `age` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
   `email` varchar(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
   `area` varchar(32) NOT NULL DEFAULT '' COMMENT '地区',
   `address` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺地址',
   `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '商户状态0=>商户已被禁用，1=>正常',
   `create_time` datetime NOT NULL COMMENT '创建时间',
   `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商户中心表';

DROP TABLE IF EXISTS `wu_user`;
CREATE TABLE `wu_user` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_name` varchar(255) NOT NULL DEFAULT '' COMMENT '真实姓名',
    `nick_name` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称',
    `password` varchar(100) NOT NULL DEFAULT '' COMMENT '密码',
    `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
    `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
    `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别0=>未知，1=>男，2=>女',
    `age` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
    `email` varchar(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
    `area` varchar(32) NOT NULL DEFAULT '' COMMENT '地区',
    `address` varchar(255) NOT NULL DEFAULT '' COMMENT '用户地址',
    `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户状态0=>禁止，1=>正常',
    `label` varchar(255) NOT NULL DEFAULT '' COMMENT '标签',
    `sign` varchar(255) NOT NULL DEFAULT '' COMMENT '签名',
    `bind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否绑定第三方',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='前台普通用户表';

DROP TABLE IF EXISTS `wu_user_oauth`;
CREATE TABLE `wu_user_oauth` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户id，0为未绑定',
   `platform` varchar(50) NOT NULL DEFAULT '' COMMENT '第三方名称，例如weibo,weixin,github',
   `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '第三方id',
   `oauth_nick` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方昵称',
   `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方头像',
   `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT 'AccessToken',
   `refresh_token` varchar(255) NOT NULL DEFAULT 'RefreshToken',
   `expires_in` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '有效期',
   `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '商户状态0=>商户已被禁用，1=>正常',
   `create_time` datetime NOT NULL COMMENT '创建时间',
   `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户第三方登录表';

# CREATE TABLE `wu_business_info` (
#   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
#   `bid` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '关联商户id',
#   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
#   `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
#   `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
#   `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别0=>未知，1=>男，2=>女',
#   `age` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
#   `email` varchar(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
#   `area` varchar(32) NOT NULL DEFAULT '' COMMENT '地区',
#   `address` varchar(255) NOT NULL DEFAULT '' COMMENT '用户地址',
#   `create_time` datetime NOT NULL COMMENT '创建时间',
#   `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
#   PRIMARY KEY (`id`),
#   KEY `idx_bid` (`bid`) USING BTREE
# ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商户详细信息表';

DROP TABLE IF EXISTS `wu_business_code`;
CREATE TABLE `wu_business_code` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `bid` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '关联商户id',
   `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
   `code` varchar(20) NOT NULL DEFAULT '' COMMENT '商户码',
   `address` varchar(255) NOT NULL DEFAULT '' COMMENT '商户分店地址',
   `class` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
   `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '商户码状态0=>已失效，1=>正常',
   `create_time` datetime NOT NULL COMMENT '创建时间',
   `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
   PRIMARY KEY (`id`),
   KEY `idx_bid` (`bid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商户码信息表';

DROP TABLE IF EXISTS `wu_product`;
CREATE TABLE `wu_product` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `no` varchar(50)  NOT NULL DEFAULT '' COMMENT '商品编号',
    `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '商户当前价格',
    `category_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '商品类别',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '商品标题',
    `tags` varchar(255) NOT NULL DEFAULT '' COMMENT '商品标签',
    `marque` varchar(255) NOT NULL DEFAULT '' COMMENT '商品型号',
    `img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品主图片链接',
    `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '商品关键字',
    `brand` varchar(255) NOT NULL DEFAULT '' COMMENT '商品品牌名称',
    `unit` varchar(255) NOT NULL DEFAULT '' COMMENT '商品单位',
    `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '商品简介',
    `discount` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '折扣',
    `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '商品状态0=>下架，1=>上架，2=>已删除',
    `barcode` varchar(255) NOT NULL DEFAULT '' COMMENT '仓库条码',
    `stock` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '库存量',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `idx_no` (`no`)USING BTREE,
    KEY `categoryid` (`category_id`)USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品主信息表';

DROP TABLE IF EXISTS `wu_product_content`;
CREATE TABLE `wu_product_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联商品id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '图文标题',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '商品简介',
  `content_pics` varchar(255) NOT NULL DEFAULT '' COMMENT '图片逗号隔开',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品文字详情表';

DROP TABLE IF EXISTS `wu_product_img`;
CREATE TABLE `wu_product_img` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联商品id',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '图片描述',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '图片顺序号',
  `size` varchar(50) NOT NULL DEFAULT '' COMMENT '图片大小',
  `ext` varchar(20) NOT NULL DEFAULT '' COMMENT '图片扩展名',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件原名',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品图片详情表';

DROP TABLE IF EXISTS `wu_product_category`;
CREATE TABLE `wu_product_category` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
   `pid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '0=>为最高分类 1=>为二级分类  数字越大,分类越后',
   `code` varchar(255) NOT NULL DEFAULT '' COMMENT '分类码',
   `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类别优先级',
   `bar_code` varchar(255) NOT NULL DEFAULT '' COMMENT '二维码地址',
   `img` varchar(255) NOT NULL DEFAULT '' COMMENT '首页展示图',
   `menu` varchar(255) NOT NULL DEFAULT '' COMMENT '按钮选项',
   `object` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '面向对象1=>商户，2=>普通用户',
   `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分类状态 0=>禁用，1=>启用',
   `create_time` datetime NOT NULL COMMENT '创建时间',
   `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品分类表';
INSERT INTO wu_product_category
VALUES(1,'丸物文具报价单',0,'GJBJD_01',1,'','','1,2',1,1,'2019-05-20 12:12:12','2019-05-20 12:12:12'),
(2,'印章笔定制',0,'YZBDZ_01',2,'','','1',2,1,'2019-05-20 12:12:12','2019-05-20 12:12:12'),
(3,'商品OEM',0,'SPOEM_01',3,'','','1',1,1,'2019-05-20 12:12:12','2019-05-20 12:12:12');



DROP TABLE IF EXISTS `wu_product_attribute`;
CREATE TABLE `wu_product_attribute` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品类别编号',
   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '属性名称',
   `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '属性优先级',
   `is_sale` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否销售属性 0=>否 1=>是',
   `has_src` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否拥有图片链接 0=>否 1=>是',
   `has_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除 0=>否 1=>是',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品属性表';

DROP TABLE IF EXISTS `wu_attribute_option`;
CREATE TABLE `wu_attribute_option` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `attr_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '属性码',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
    `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类别优先级',
    `has_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除 0=>否 1=>是',
    PRIMARY KEY (`id`),
    KEY `idx_attrid` (`attr_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品属性值表';

DROP TABLE IF EXISTS `wu_attribute_relate`;
CREATE TABLE `wu_attribute_relate` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联商品id',
    `attr_id` int(11) unsigned NOT NULL COMMENT '关联属性id',
    `option_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联属性选项id',
    `path` varchar(255)  NOT NULL DEFAULT '' COMMENT '文件路径',
    PRIMARY KEY (`id`),
    KEY `idx_pid` (`pid`) USING BTREE,
    KEY `idx_attrid` (`attr_id`) USING BTREE,
    KEY `idx_optionid` (`option_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品规格属性绑定表';

DROP TABLE IF EXISTS `wu_product_sku`;
CREATE TABLE `wu_product_sku` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品编码',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'sku名称',
    `img` varchar(255) NOT NULL DEFAULT '' COMMENT '主图',
    `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
    `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
    `code` varchar(255) DEFAULT NULL COMMENT '商品编码',
    `barcode` varchar(255) DEFAULT NULL COMMENT '商品条形码',
    `data` varchar(255) DEFAULT NULL COMMENT 'sku串',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `idx_name_productid` (`name`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品sku表';

DROP TABLE IF EXISTS `wu_order_cart`;
CREATE TABLE `wu_order_cart` (
     `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
     `product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
     `bid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商户id',
     `category_id` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
     `sku_ids` varchar(255)  NOT NULL DEFAULT '' COMMENT 'sku串',
     `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
     `sku` varchar(255) NOT NULL DEFAULT '' COMMENT 'sku组合名',
     `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否有效',
     `create_time` datetime NOT NULL COMMENT '创建时间',
     `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
     PRIMARY KEY (`id`),
     KEY `idx_productid` (`product_id`),
     KEY `idx_bid` (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='购物车表';

DROP TABLE IF EXISTS `wu_order_item`;
CREATE TABLE `wu_order_item` (
     `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
     `sub_no` varchar(255) NOT NULL DEFAULT '' COMMENT '主订单号',
     `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '子订单号',
     `product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
     `sku` varchar(255)  NOT NULL DEFAULT '' COMMENT '商品sku',
     `no` varchar(50)  NOT NULL DEFAULT '' COMMENT '商品编号',
     `product_name`varchar(255) NOT NULL DEFAULT '' COMMENT '商品可能删除,所以这里要记录，不能直接读商品表',
     `product_marque` varchar(255) NOT NULL DEFAULT '' COMMENT '商品型号',
     `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
     `real_price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '应付总价',
     `free_price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '减免价格',
     `desc` varchar(500)  NOT NULL DEFAULT '' COMMENT '客户额外描述',
     `custom` varchar(500)  NOT NULL DEFAULT '' COMMENT '额外私人定制-json',
     `unit_price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '记录商品下单时单价，防止以后商品价格变动',
     `create_time` datetime NOT NULL COMMENT '创建时间',
     `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
     PRIMARY KEY (`id`),
     unique `uni_tradeno` (`trade_no`),
     KEY `idx_subno` (`sub_no`),
     KEY `idx_tradeno_productid` (`trade_no`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='子订单表';

DROP TABLE IF EXISTS `wu_sub_order`;
CREATE TABLE `wu_sub_order` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `sub_no` varchar(255) NOT NULL DEFAULT '' COMMENT '主订单号',
    `bid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商户id',
    `category_id` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
    `total_price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '应付总价',
    `actual_price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '实付总价',
    `express_price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
    `trade_name` varchar(255) NOT NULL DEFAULT '' COMMENT '收货名',
    `trade_phone` varchar(11) NOT NULL DEFAULT '' COMMENT '收货号码',
    `address` varchar(255) NOT NULL DEFAULT '' COMMENT '收货地址',
    `mark` varchar(255) NOT NULL DEFAULT '' COMMENT '订单备注',
    `code` varchar(255) NOT NULL DEFAULT '' COMMENT '供应商编码',
    `shop_address` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺地址',
    `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
    `receive_time` datetime DEFAULT NULL COMMENT '收货时间',
    `return_time` datetime DEFAULT NULL COMMENT '退货时间',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`),
    unique `uni_subno` (`sub_no`),
    KEY `idx_subno_bid_categoryid` (`sub_no`,`bid`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单主表';

DROP TABLE IF EXISTS `wu_pay_info`;
CREATE TABLE `wu_pay_info` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `sub_no` varchar(255) NOT NULL DEFAULT '' COMMENT '主订单号',
    `order_sn` varchar(100) NOT NULL DEFAULT '' COMMENT '交易号',
    `member_id` varchar(20) NOT NULL DEFAULT '' COMMENT '交易的用户ID',
    `pay_way` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式，支付宝|微信|银联|见面|交易|其他',
    `source` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '支付来源 wx app web wap',
    `pay_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '支付状态 1=>取消 2=>未完成 3=>已完成 4=>异常',
    `serial_no` varchar(255) NOT NULL DEFAULT '' COMMENT '流水号',
    `pay_no` varchar(255) NOT NULL DEFAULT '' COMMENT '支付订单号',
    `note` varchar(255) NOT NULL DEFAULT '' COMMENT '交易备注',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `idx_subno_payno` (`sub_no`,`pay_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单交易信息表';


DROP TABLE IF EXISTS `wu_order_express`;
CREATE TABLE `wu_order_express` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `sub_no` varchar(255) NOT NULL DEFAULT '' COMMENT '主订单号',
    `express_id` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '物流方',
    `express_no` varchar(50) NOT NULL DEFAULT '' COMMENT '物流编号',
    `deliver_time` datetime NOT NULL DEFAULT '2019-06-06 00:00:00' COMMENT '发货时间',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `idx_subno` (`sub_no`),
    KEY `expressno` (`express_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单物流表';

DROP TABLE IF EXISTS `wu_express`;
CREATE TABLE `wu_express` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL DEFAULT '' COMMENT '物流名称',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='物流方表';

DROP TABLE IF EXISTS `wu_upload_log`;
CREATE TABLE `wu_upload_log` (
     `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
     `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件存储路径',
     `size` varchar(50) NOT NULL DEFAULT '' COMMENT '文件大小',
     `ext` varchar(20) NOT NULL DEFAULT '' COMMENT '文件扩展名',
     `old_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件原名',
     `hash` varchar(255) NOT NULL DEFAULT '' COMMENT '文件hash值',
     `date` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Ymd日期',
     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文件上传记录表';




#
#第二次导入
#
DROP TABLE IF EXISTS `wu_admin`;
CREATE table `wu_admin`(
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `account` varchar(255) NOT NULL DEFAULT '' COMMENT '登录账号',
   `password` varchar(200) NOT NULL DEFAULT '' COMMENT '密码',
   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
   `avatar` varchar(150) NOT NULL DEFAULT '' COMMENT '头像',
   `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '电话',
   `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
   `sex` tinyint(1) unsigned  NOT NULL DEFAULT '0' COMMENT '性别(0:未知,1:男,2:女)',
   `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态(1:正常,0:锁定)',
   `depart_name` varchar(255)  NOT NULL DEFAULT '' COMMENT '部门',
   `level`  mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级领导id',
   `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
   `entry_date` date  DEFAULT NULL COMMENT  '入职时间',
   `create_time` datetime NOT NULL COMMENT '创建时间',
   `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT ='后台管理表';
INSERT INTO wu_admin VALUES (1,'root','$2y$10$Zvtciymck5c8pEgapXNi0.DKcY9BhN5F/99HVibcpdHi2HZgYRhZG',
                             '丸物管理员','','','',2,1,'',0,'这是默认超管账号，请修改账号密码','2019-05-20','2019-05-20 12:12:12','2019-05-20 12:12:12');

DROP TABLE IF EXISTS `wu_auth_group`;
CREATE TABLE `wu_auth_group` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
    `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 : 1为正常,0为禁用',
    `rules` varchar(100) NOT NULL DEFAULT ''  COMMENT '规则ID （这里填写的是 wu_auth_rule里面的规则的ID)',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户组表';

INSERT INTO wu_auth_group VALUES (1, '超级管理员', 1, '*');

DROP TABLE IF EXISTS `wu_auth_rule`;
CREATE TABLE `wu_auth_rule` (
   `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id',
   `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称,格式 为【模块名/控制器名/方法名】或【自定义规则】,多个规则之间用,隔开即可',
   `title` varchar(225) NOT NULL DEFAULT '' COMMENT '规则中文名称',
   `no` varchar(100) NOT NULL DEFAULT  '' COMMENT '编号',
   `pid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为顶级菜单',
   `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '如果type为1,condition字段就可以定义规则表达式。如定义{score}>5 and {score}<100 表示用户的分数在5-100之间时这条规则才会通过。（默认为1）',
   `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为正常,0为禁用',
   `condition` varchar(100) NOT NULL DEFAULT ''  COMMENT  '规则表达式，不为空and type字段=1 会按照条件验证  ',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT ='用户组规则表';;
INSERT INTO wu_auth_rule
VALUES(1,'','订单管理','DDGL',0,1,1,''),
      (2,'','供应商管理','GYSGL',0,1,1,''),
      (3,'','商品管理','SPGL',0,1,1,''),
      (4,'','系统设置','XTSZ',0,1,1,''),
      (5,'','订单明细','DDGL_DDMX',1,1,1,''),
      (6,'','供应商编码','GYSBM',2,1,1,''),
      (7,'','供应商账号','GYSZH',2,1,1,''),
      (8,'','商品列表','SPLB',3,1,1,''),
      (9,'','账户管理','ZHGL',4,1,1,'');

DROP TABLE IF EXISTS `wu_auth_group_access`;
CREATE TABLE `wu_auth_group_access` (
   `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
   `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
   `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '组id',
   `rules` varchar(255)  NOT NULL DEFAULT '' COMMENT '规则id，逗号隔开',
   PRIMARY KEY (`id`),
   KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT ='用户组明细表';
INSERT INTO wu_auth_group_access VALUES (1, 1, 1,'*');

DROP TABLE IF EXISTS `wu_admin_depart`;
CREATE table `wu_admin_depart`(
     `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
     `name` varchar(255) NOT NULL DEFAULT '' COMMENT '后台用户id',
     `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为正常,0为禁用',
     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT ='部门表';

DROP TABLE IF EXISTS `wu_admin_token`;
CREATE table `wu_admin_token`(
   `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
   `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '后台用户id',
   `token` varchar(255) NOT NULL DEFAULT '' COMMENT 'token',
   `expire` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
   `count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
   `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT ='后台token记录表';

