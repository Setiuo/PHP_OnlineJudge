/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1_3306
Source Server Version : 80018
Source Host           : 127.0.0.1:3306
Source Database       : new_oj_db

Target Server Type    : MYSQL
Target Server Version : 80018
File Encoding         : 65001

Date: 2020-02-09 20:49:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for oj_constatus
-- ----------------------------
DROP TABLE IF EXISTS `oj_constatus`;
CREATE TABLE `oj_constatus` (
  `RunID` int(11) NOT NULL COMMENT '运行ID',
  `ConID` int(11) NOT NULL COMMENT '比赛ID',
  `User` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户',
  `Problem` int(11) NOT NULL COMMENT '问题编号',
  `Status` int(11) NOT NULL COMMENT '状态',
  `UseTime` int(11) NOT NULL COMMENT '耗时',
  `UseMemory` int(11) NOT NULL COMMENT '耗用内存',
  `Language` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '语言',
  `CodeLen` int(11) NOT NULL COMMENT '代码长度',
  `SubTime` datetime NOT NULL COMMENT '提交时间',
  `AllStatus` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '测试点状态',
  `Show` int(11) NOT NULL COMMENT '是否显示',
  `Judger` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  UNIQUE KEY `RunID_2` (`RunID`),
  KEY `runID` (`RunID`,`ConID`),
  KEY `SubTime` (`SubTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_constatus
-- ----------------------------

-- ----------------------------
-- Table structure for oj_contest
-- ----------------------------
DROP TABLE IF EXISTS `oj_contest`;
CREATE TABLE `oj_contest` (
  `ConID` int(11) NOT NULL COMMENT '比赛ID',
  `Title` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '比赛标题',
  `Synopsis` text CHARACTER SET utf8 COLLATE utf8_bin,
  `Organizer` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '举办人',
  `Rule` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '规则',
  `Type` int(11) NOT NULL,
  `PassWord` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `StartTime` datetime NOT NULL COMMENT '开始时间',
  `OverTime` datetime NOT NULL COMMENT '结束时间',
  `FreezeTime` datetime DEFAULT NULL COMMENT '封榜开始时间',
  `UnfreezeTime` datetime DEFAULT NULL COMMENT '封榜结束时间',
  `EnrollStartTime` datetime NOT NULL COMMENT '报名开始时间',
  `EnrollOverTime` datetime NOT NULL COMMENT '报名结束时间',
  `EnrollPeople` text CHARACTER SET utf8 COLLATE utf8_bin COMMENT '参赛人员',
  `RiskRatio` float DEFAULT NULL COMMENT '风险系数',
  `RatingStatus` int(11) DEFAULT NULL COMMENT '战斗力结算状态',
  `RatingData` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '战斗力结算数据',
  `Problem` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '题目',
  `Show` int(11) NOT NULL,
  `Practice` int(11) NOT NULL COMMENT '是否为练习',
  UNIQUE KEY `ConID_2` (`ConID`),
  KEY `conID` (`ConID`),
  KEY `OverTime` (`OverTime`),
  KEY `Show` (`Show`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_contest
-- ----------------------------

-- ----------------------------
-- Table structure for oj_data
-- ----------------------------
DROP TABLE IF EXISTS `oj_data`;
CREATE TABLE `oj_data` (
  `oj_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '网站名称',
  `oj_html_title` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '标题',
  `oj_title` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '副名称',
  `oj_runid` int(11) NOT NULL,
  `maintain` int(11) NOT NULL COMMENT '是否维护网站',
  UNIQUE KEY `oj_name` (`oj_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_data
-- ----------------------------
INSERT INTO `oj_data` VALUES ('SEOJ', 'OnlineJudge - 评测平台', 'OnlineJudge - 源程序判题系统', '1', '0');

-- ----------------------------
-- Table structure for oj_judger
-- ----------------------------
DROP TABLE IF EXISTS `oj_judger`;
CREATE TABLE `oj_judger` (
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `run_count` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`ip`),
  UNIQUE KEY `ip` (`ip`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_judger
-- ----------------------------

-- ----------------------------
-- Table structure for oj_judge_compile_log
-- ----------------------------
DROP TABLE IF EXISTS `oj_judge_compile_log`;
CREATE TABLE `oj_judge_compile_log` (
  `contestID` int(11) DEFAULT NULL,
  `runID` int(11) DEFAULT NULL,
  `compileLog` text CHARACTER SET utf8 COLLATE utf8_bin,
  UNIQUE KEY `contestID` (`contestID`,`runID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_judge_compile_log
-- ----------------------------

-- ----------------------------
-- Table structure for oj_judge_task
-- ----------------------------
DROP TABLE IF EXISTS `oj_judge_task`;
CREATE TABLE `oj_judge_task` (
  `runID` int(11) DEFAULT NULL,
  `contestID` int(11) DEFAULT NULL,
  `user` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `problemID` int(11) DEFAULT NULL,
  `language` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `judgeType` int(11) DEFAULT NULL,
  `limitTime` int(11) DEFAULT NULL,
  `limitMemory` int(11) DEFAULT NULL,
  `test` text CHARACTER SET utf8 COLLATE utf8_bin,
  `code` longtext CHARACTER SET utf8 COLLATE utf8_bin,
  `isRead` int(11) DEFAULT NULL,
  `prohibit` int(11) DEFAULT NULL,
  UNIQUE KEY `runID_2` (`runID`),
  KEY `finish` (`isRead`),
  KEY `runID` (`runID`,`contestID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_judge_task
-- ----------------------------

-- ----------------------------
-- Table structure for oj_problem
-- ----------------------------
DROP TABLE IF EXISTS `oj_problem`;
CREATE TABLE `oj_problem` (
  `Name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `proNum` int(11) NOT NULL,
  `LimitTime` int(11) NOT NULL,
  `LimitMemory` int(11) NOT NULL,
  `Description` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `InputFormat` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `OutputFormat` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `EmpInput` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `EmpOutput` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Hint` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Source` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `CreateTime` date NOT NULL,
  `Test` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Show` int(11) NOT NULL,
  UNIQUE KEY `proNum_2` (`proNum`),
  KEY `proNum` (`proNum`),
  KEY `Show` (`Show`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_problem
-- ----------------------------
INSERT INTO `oj_problem` VALUES ('A+B Problem', '1000', '1000', '65536', 0x43616C63756C61746520612B62, 0x54776F20696E7465676572206120616E642062, 0x76616C7565206F6620612B62, 0x312032, 0x33, 0x433C6272202F3E0D0A3C7072653E3C636F646520636C6173733D2243223E23696E636C75646520266C743B737464696F2E682667743B0D0A696E74206D61696E28766F6964290D0A7B0D0A20202020696E7420612C623B0D0A202020207363616E66282225642564222C26616D703B612C26616D703B62293B0D0A202020207072696E746628222564222C612B62293B0D0A2020202072657475726E20303B0D0A7D0D0A3C2F636F64653E3C2F7072653E3C6272202F3E0D0A432B2B3C6272202F3E0D0A3C7072653E3C636F646520636C6173733D22432B2B223E23696E636C75646520266C743B696F73747265616D2667743B0D0A7573696E67206E616D657370616365207374643B0D0A696E74206D61696E28290D0A7B0D0A20202020696E7420612C623B0D0A2020202063696E2667743B2667743B612667743B2667743B623B0D0A20202020636F7574266C743B266C743B612B62266C743B266C743B656E646C3B0D0A2020202072657475726E20303B0D0A7D0D0A3C2F636F64653E3C2F7072653E3C6272202F3E0D0A4A6176613C6272202F3E0D0A3C7072653E3C636F646520636C6173733D224A617661223E696D706F7274206A6176612E7574696C2E2A3B0D0A696D706F7274206A6176612E696F2E2A3B0D0A7075626C696320636C617373204D61696E0D0A7B0D0A202020207075626C69632073746174696320766F6964206D61696E28537472696E675B5D2061726773290D0A202020207B0D0A20202020202020205363616E6E6572207265616465723D6E6577205363616E6E65722853797374656D2E696E293B0D0A2020202020202020696E7420612C623B0D0A2020202020202020613D7265616465722E6E657874496E7428293B0D0A2020202020202020623D7265616465722E6E657874496E7428293B0D0A202020202020202053797374656D2E6F75742E7072696E746C6E28612B62293B0D0A202020207D0D0A7D0D0A3C2F636F64653E3C2F7072653E3C6272202F3E0D0A507974686F6E332E373C6272202F3E0D0A3C7072653E3C636F646520636C6173733D22507974686F6E223E696D706F7274207379730D0A666F72206C696E6520696E207379732E737464696E3A0D0A2061203D206C696E652E73706C69742829200D0A7072696E742028696E7428615B305D29202B20696E742028615B315D29290D0A3C2F636F64653E3C2F7072653E, '', '2019-02-01', '1&2&3&4&5&6&7&8&9&10', '1');

-- ----------------------------
-- Table structure for oj_problem_data
-- ----------------------------
DROP TABLE IF EXISTS `oj_problem_data`;
CREATE TABLE `oj_problem_data` (
  `problemID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  `input` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `output` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `problemID` (`problemID`,`testID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_problem_data
-- ----------------------------
INSERT INTO `oj_problem_data` VALUES ('1000', '1', 0x302030, 0x30);
INSERT INTO `oj_problem_data` VALUES ('1000', '2', 0x313030302031, 0x31303031);
INSERT INTO `oj_problem_data` VALUES ('1000', '3', 0x2D312031, 0x30);
INSERT INTO `oj_problem_data` VALUES ('1000', '4', 0x2D313020313030, 0x3930);
INSERT INTO `oj_problem_data` VALUES ('1000', '5', 0x2D313030202D323030, 0x2D333030);
INSERT INTO `oj_problem_data` VALUES ('1000', '6', 0x313030303030303030302031, 0x31303030303030303031);
INSERT INTO `oj_problem_data` VALUES ('1000', '7', 0x36363620363636, 0x31333332);
INSERT INTO `oj_problem_data` VALUES ('1000', '8', 0x3130303836203131, 0x3130303937);
INSERT INTO `oj_problem_data` VALUES ('1000', '9', 0x3131203131, 0x3232);
INSERT INTO `oj_problem_data` VALUES ('1000', '10', 0x312032, 0x33);

-- ----------------------------
-- Table structure for oj_status
-- ----------------------------
DROP TABLE IF EXISTS `oj_status`;
CREATE TABLE `oj_status` (
  `RunID` int(11) NOT NULL COMMENT '运行ID',
  `User` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户',
  `Problem` int(11) NOT NULL COMMENT '问题编号',
  `Status` int(11) NOT NULL COMMENT '状态',
  `UseTime` int(11) NOT NULL COMMENT '耗时',
  `UseMemory` int(11) NOT NULL,
  `Language` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '语言',
  `CodeLen` int(11) NOT NULL COMMENT '代码长度',
  `SubTime` datetime NOT NULL COMMENT '提交时间',
  `AllStatus` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '测试点状态',
  `Show` int(11) NOT NULL,
  `Judger` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `RunID_2` (`RunID`),
  KEY `runID` (`RunID`),
  KEY `Show` (`Show`),
  KEY `User` (`User`),
  KEY `Problem` (`Problem`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_status
-- ----------------------------

-- ----------------------------
-- Table structure for oj_user
-- ----------------------------
DROP TABLE IF EXISTS `oj_user`;
CREATE TABLE `oj_user` (
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户姓名',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户密码',
  `jurisdicton` int(11) NOT NULL COMMENT '权限',
  `signature` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '签名',
  `email` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '邮箱',
  `regtime` datetime NOT NULL COMMENT '注册时间',
  `logtime` datetime NOT NULL COMMENT '最后登陆的时间',
  `fight` int(11) NOT NULL COMMENT '战斗力',
  `skin` varchar(11) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'OJ皮肤',
  `sessionid` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `tails` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `name` (`name`),
  KEY `user` (`name`),
  KEY `fight` (`fight`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_user
-- ----------------------------
INSERT INTO `oj_user` VALUES ('admin', '1', 'd8599493ae274d2416d2e50dd9397305', '64', 'ADMIN', 'admin@oj.com', '2019-01-01 00:00:00', '2020-02-05 23:30:22', '5000', 'Slate', '', '');
