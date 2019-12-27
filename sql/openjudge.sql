/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1_3306
Source Server Version : 80018
Source Host           : 127.0.0.1:3306
Source Database       : oj_test

Target Server Type    : MYSQL
Target Server Version : 80018
File Encoding         : 65001

Date: 2019-12-27 14:27:59
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
  KEY `SubTime` (`SubTime`,`Show`),
  KEY `Status` (`Status`)
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
  `oj_EvaMacState_1` int(11) NOT NULL,
  `oj_EvaMacState_2` int(11) NOT NULL,
  `oj_allrun_1` int(11) NOT NULL,
  `oj_allrun_2` int(11) NOT NULL,
  `maintain` int(11) NOT NULL COMMENT '是否维护网站',
  UNIQUE KEY `oj_name` (`oj_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_data
-- ----------------------------
INSERT INTO `oj_data` VALUES ('SEOJ', 'OnlineJudge - 评测平台', 'OnlineJudge - 源程序判题系统', '692', '1', '0', '0', '0', '0');

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
-- Table structure for oj_problem_test
-- ----------------------------
DROP TABLE IF EXISTS `oj_problem_test`;
CREATE TABLE `oj_problem_test` (
  `problemID` int(11) DEFAULT NULL,
  `testNum` int(11) DEFAULT NULL,
  `test1_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test1_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test2_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test2_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test3_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test3_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test4_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test4_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test5_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test5_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test6_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test6_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test7_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test7_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test8_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test8_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test9_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test9_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test10_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test10_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test11_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test11_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test12_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test12_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test13_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test13_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test14_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test14_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test15_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test15_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test16_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test16_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test17_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test17_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test18_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test18_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test19_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test19_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test20_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test20_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test21_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test21_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test22_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test22_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test23_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test23_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test24_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test24_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test25_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test25_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test26_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test26_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test27_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test27_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test28_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test28_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test29_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test29_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test30_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test30_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test31_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test31_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test32_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test32_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test33_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test33_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test34_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test34_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test35_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test35_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test36_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test36_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test37_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test37_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test38_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test38_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test39_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test39_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test40_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test40_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test41_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test41_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test42_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test42_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test43_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test43_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test44_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test44_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test45_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test45_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test46_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test46_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test47_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test47_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test48_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test48_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test49_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test49_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test50_in` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `test50_out` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  UNIQUE KEY `problemID_2` (`problemID`),
  KEY `problemID` (`problemID`),
  KEY `problemID_3` (`problemID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oj_problem_test
-- ----------------------------
INSERT INTO `oj_problem_test` VALUES ('1000', '10', '0 0', '0', '1000 1', '1001', '-1 1', '0', '-10 100', '90', '-100 -200', '-300', '1000000000 1', '1000000001', '666 666', '1332', '10086 11', '10097', '11 11', '22', '1 2', '3', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

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
  KEY `Show` (`Show`)
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
INSERT INTO `oj_user` VALUES ('admin', '1', 'd8599493ae274d2416d2e50dd9397305', '2', '我不管，我最帅，我是你的小可爱', 'test@test.com', '2019-01-01 00:00:00', '2019-12-27 14:23:41', '3200', 'Slate', '', '管理员');
