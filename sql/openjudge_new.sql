-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-05-14 10:03:59
-- 服务器版本： 8.0.18
-- PHP 版本： 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `openjudge`
--

-- --------------------------------------------------------

--
-- 表的结构 `oj_constatus`
--

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
  `Judger` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `oj_contest`
--

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
  `RatingData` longtext COLLATE utf8_bin NOT NULL COMMENT '战斗力结算数据',
  `Problem` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '题目',
  `Show` int(11) NOT NULL,
  `Practice` int(11) NOT NULL COMMENT '是否为练习'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `oj_data`
--

CREATE TABLE `oj_data` (
  `oj_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '网站名称',
  `oj_html_title` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '标题',
  `oj_title` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '副名称',
  `maintain` int(11) NOT NULL COMMENT '是否维护网站'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `oj_judger`
--

CREATE TABLE `oj_judger` (
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `run_count` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `oj_judge_compile_log`
--

CREATE TABLE `oj_judge_compile_log` (
  `contestID` int(11) DEFAULT NULL,
  `runID` int(11) DEFAULT NULL,
  `compileLog` text COLLATE utf8_bin
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `oj_judge_task`
--

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
  `prohibit` int(11) NOT NULL COMMENT '禁止查看代码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `oj_problem`
--

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
  `Show` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `oj_problem_data`
--

CREATE TABLE `oj_problem_data` (
  `problemID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  `input` longtext COLLATE utf8_bin NOT NULL,
  `output` longtext COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `oj_status`
--

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
  `Judger` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `oj_user`
--

CREATE TABLE `oj_user` (
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户姓名',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户密码',
  `jurisdiction` int(11) NOT NULL COMMENT '权限',
  `signature` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '签名',
  `email` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '邮箱',
  `regtime` datetime NOT NULL COMMENT '注册时间',
  `logtime` datetime NOT NULL COMMENT '最后登陆的时间',
  `fight` int(11) NOT NULL COMMENT '战斗力',
  `skin` varchar(11) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'OJ皮肤',
  `sessionid` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `tails` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 转储表的索引
--

--
-- 表的索引 `oj_constatus`
--
ALTER TABLE `oj_constatus`
  ADD UNIQUE KEY `RunID_2` (`ConID`,`RunID`) USING BTREE,
  ADD KEY `SubTime` (`SubTime`),
  ADD KEY `Problem` (`Problem`),
  ADD KEY `Status` (`Status`),
  ADD KEY `Show` (`Show`),
  ADD KEY `User` (`User`),
  ADD KEY `runID` (`ConID`,`RunID`) USING BTREE;

--
-- 表的索引 `oj_contest`
--
ALTER TABLE `oj_contest`
  ADD UNIQUE KEY `ConID_2` (`ConID`),
  ADD KEY `conID` (`ConID`),
  ADD KEY `OverTime` (`OverTime`),
  ADD KEY `Show` (`Show`);

--
-- 表的索引 `oj_data`
--
ALTER TABLE `oj_data`
  ADD UNIQUE KEY `oj_name` (`oj_name`);

--
-- 表的索引 `oj_judger`
--
ALTER TABLE `oj_judger`
  ADD PRIMARY KEY (`ip`),
  ADD UNIQUE KEY `ip` (`ip`) USING BTREE;

--
-- 表的索引 `oj_judge_compile_log`
--
ALTER TABLE `oj_judge_compile_log`
  ADD UNIQUE KEY `contestID_2` (`contestID`,`runID`),
  ADD KEY `contestID` (`contestID`,`runID`);

--
-- 表的索引 `oj_judge_task`
--
ALTER TABLE `oj_judge_task`
  ADD UNIQUE KEY `runID_2` (`runID`,`contestID`),
  ADD KEY `finish` (`isRead`),
  ADD KEY `runID` (`runID`,`contestID`);

--
-- 表的索引 `oj_problem`
--
ALTER TABLE `oj_problem`
  ADD UNIQUE KEY `proNum_2` (`proNum`),
  ADD KEY `proNum` (`proNum`),
  ADD KEY `Show` (`Show`);

--
-- 表的索引 `oj_problem_data`
--
ALTER TABLE `oj_problem_data`
  ADD UNIQUE KEY `problemID` (`problemID`,`testID`);

--
-- 表的索引 `oj_status`
--
ALTER TABLE `oj_status`
  ADD UNIQUE KEY `RunID_2` (`RunID`),
  ADD KEY `runID` (`RunID`),
  ADD KEY `Show` (`Show`),
  ADD KEY `User` (`User`),
  ADD KEY `Problem` (`Problem`);

--
-- 表的索引 `oj_user`
--
ALTER TABLE `oj_user`
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `user` (`name`),
  ADD KEY `fight` (`fight`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `oj_constatus`
--
ALTER TABLE `oj_constatus`
  MODIFY `RunID` int(11) NOT NULL AUTO_INCREMENT COMMENT '运行ID';

--
-- 使用表AUTO_INCREMENT `oj_status`
--
ALTER TABLE `oj_status`
  MODIFY `RunID` int(11) NOT NULL AUTO_INCREMENT COMMENT '运行ID';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
