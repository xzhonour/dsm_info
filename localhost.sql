-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-05-18 19:09:00
-- 服务器版本： 5.5.56-log
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dsm_info`
--
CREATE DATABASE IF NOT EXISTS `dsm_info` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dsm_info`;

-- --------------------------------------------------------

--
-- 表的结构 `dsm_docs`
--

CREATE TABLE `dsm_docs` (
  `did` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `doc_name` varchar(100) NOT NULL COMMENT '文档名',
  `update_time` int(11) NOT NULL COMMENT '最后更新时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `dsm_docs`
--

INSERT INTO `dsm_docs` (`did`, `uid`, `doc_name`, `update_time`, `create_time`) VALUES
(29, 1, '测试文档一', 1524006817, 1523894003),
(30, 1, 'test', 1523975396, 1523975344),
(31, 1, 'asdf', 1523975569, 1523975489),
(32, 13, 'test123', 1523979534, 1523979507),
(34, 1, 'qweasd', 1524013849, 1524013811),
(35, 1, 'yyy 211', 1524032680, 1524032680),
(38, 1, 'yyy', 1524032750, 1524032750),
(39, 1, 'ttt', 1524032900, 1524032821),
(40, 1, 'qweqwe', 1524033494, 1524033193),
(41, 1, '123123123', 1524034072, 1524033328),
(42, 1, '444', 1524033702, 1524033702),
(45, 1, 'xinjianwen111', 1524033776, 1524033776),
(46, 1, '奴及你比', 1524033997, 1524033961),
(47, 1, '爱上的发生短发', 1524034191, 1524034191),
(50, 1, 'tttt', 1524190598, 1524034256),
(51, 14, '特殊图', 1524187541, 1524187372),
(54, 1, 'vvv', 1524188611, 1524188404),
(55, 16, 'xssd', 1524189398, 1524189269),
(58, 1, 'ttta', 1524190565, 1524190558),
(60, 17, '123123h', 1524190737, 1524190729),
(64, 18, '房屋建筑信息合同', 1524229338, 1524228685);

-- --------------------------------------------------------

--
-- 表的结构 `dsm_docs_route`
--

CREATE TABLE `dsm_docs_route` (
  `rid` int(11) NOT NULL,
  `did` int(11) NOT NULL,
  `step` tinyint(1) NOT NULL DEFAULT '0',
  `tag_1` int(11) NOT NULL,
  `tag_2` int(11) NOT NULL,
  `tag_3` int(11) NOT NULL,
  `content` text NOT NULL COMMENT '内容',
  `four_tags` varchar(50) NOT NULL COMMENT '第四级多选标签'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `dsm_docs_route`
--

INSERT INTO `dsm_docs_route` (`rid`, `did`, `step`, `tag_1`, `tag_2`, `tag_3`, `content`, `four_tags`) VALUES
(1, 24, 3, 1, 2, 3, '测试测试测试测试测试测试', '14,15'),
(9, 29, 3, 1, 2, 3, '测试文档一', '14,15'),
(10, 29, 3, 1, 2, 16, 'FSFSEFSFSEF', ''),
(11, 30, 3, 1, 2, 3, '测试1', '14,15'),
(12, 30, 3, 1, 2, 16, '测试2', '17'),
(13, 30, 3, 5, 7, 18, '测试3', '19'),
(14, 30, 3, 6, 8, 20, '测试4', '21'),
(15, 31, 3, 5, 7, 18, '123123', '19'),
(16, 31, 3, 6, 8, 20, '123123', '21'),
(17, 31, 3, 1, 2, 16, '123123', '17'),
(18, 32, 3, 1, 2, 3, 'test123test123', '14,15'),
(19, 32, 3, 1, 2, 16, 'test123test123test123test123', '17'),
(20, 32, 3, 5, 7, 18, 'test123test123test123test123test123', '19'),
(21, 34, 3, 5, 7, 18, '123123', '19'),
(22, 34, 3, 6, 8, 20, '123123', '21'),
(23, 39, 3, 5, 7, 18, 'wewrewrwerewr', ''),
(25, 40, 3, 1, 2, 16, 'qweqwe', '17,22'),
(26, 46, 3, 1, 2, 3, '1656165', '14'),
(27, 46, 3, 1, 2, 16, '19841981', '17,22'),
(28, 41, 3, 1, 2, 3, '阿斯达', '14,15'),
(29, 50, 3, 1, 2, 3, 'skaljdasd', '14,15'),
(30, 50, 3, 5, 7, 18, 'dfgdfgfdsg', '19,23'),
(31, 50, 3, 6, 8, 20, 'sfsef', '21'),
(32, 50, 3, 5, 24, 25, '阿达瓦大无', ''),
(33, 50, 3, 5, 24, 26, '色粉色发', ''),
(34, 51, 3, 1, 2, 3, '撒大声地', '14,15'),
(35, 51, 3, 1, 2, 16, '撒大声地啥多', '17,22'),
(36, 51, 3, 5, 7, 18, '萨达所大所多', '23'),
(37, 51, 3, 5, 24, 26, '我去问请问', ''),
(38, 51, 3, 6, 8, 20, '伟强翁无群二群翁', ''),
(39, 54, 3, 1, 2, 16, '屋顶的无群无群二', '17,22'),
(40, 54, 3, 1, 2, 3, '其味无穷二群翁无群二', '14,15'),
(41, 54, 3, 5, 7, 18, '伟强翁群无', '19'),
(42, 54, 3, 5, 24, 25, '外网确二无群二群翁', ''),
(43, 54, 3, 5, 24, 26, '持续性在vcxvcxv', ''),
(44, 55, 3, 1, 2, 3, 'sasd', '14,15'),
(45, 55, 3, 1, 2, 16, 'qwdq\r\n在AXZ安装AZ安装AZ安装', '17,22'),
(46, 55, 3, 5, 7, 18, '萨达多啥多所', '19,23'),
(47, 55, 3, 5, 24, 25, '1我去大群无', ''),
(48, 55, 3, 5, 24, 26, '的非官方能发给发给你', ''),
(49, 55, 3, 12, 30, 31, '奥术大师大多啥多', '32'),
(51, 50, 3, 12, 33, 34, '', '37'),
(52, 50, 3, 12, 30, 38, 'sddsf\r\n', '39'),
(53, 58, 3, 1, 27, 28, '', ''),
(54, 60, 3, 1, 2, 16, '', '22'),
(55, 64, 3, 1, 2, 3, '1231', '14,15'),
(56, 64, 3, 1, 2, 16, '15165', '17,22'),
(57, 64, 3, 1, 27, 28, '151313', ''),
(58, 64, 3, 5, 7, 18, '1113123132', '19,23'),
(59, 64, 3, 5, 24, 25, '151651616', ''),
(60, 64, 3, 5, 24, 26, '2\r\n2\r\n32\r\n32\r\n', ''),
(61, 64, 3, 6, 8, 20, '15113213', '21'),
(62, 64, 3, 12, 30, 31, '1413132132', '32'),
(63, 64, 3, 12, 30, 38, '55113213312', '39'),
(64, 64, 3, 12, 33, 34, '465513312132132312312', '35,36,37');

-- --------------------------------------------------------

--
-- 表的结构 `dsm_tags`
--

CREATE TABLE `dsm_tags` (
  `tid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL COMMENT '标签名',
  `info` text,
  `color` varchar(15) NOT NULL COMMENT '颜色',
  `ico` varchar(50) DEFAULT NULL COMMENT '图标',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1ok,0',
  `sort` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `level` int(11) NOT NULL DEFAULT '1',
  `create_date` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `dsm_tags`
--

INSERT INTO `dsm_tags` (`tid`, `pid`, `name`, `info`, `color`, `ico`, `status`, `sort`, `level`, `create_date`) VALUES
(1, 0, '施工图设计', '', '#bf00b5', 'xxx', 1, 0, 1, 0),
(2, 1, '第二级菜单', '', '#b70cf5', 'xxx', 1, 0, 2, 0),
(3, 2, '第三级菜单', '', '#0c28f5', 'xxx', 1, 0, 3, 0),
(5, 0, '初步设计', '初步设计\r\n', '#f6d90e', NULL, 1, 0, 1, 1523547493),
(6, 0, '设计依据', '设计依据\r\n\r\n', '#f63b11', NULL, 1, 0, 1, 1523547522),
(7, 5, ' 住宅部分', ' 住宅部分\r\n', '#adedd7', NULL, 1, 0, 2, 1523547575),
(8, 6, '部分12', '部分12 我是被移动来的', '#1455c9', NULL, 1, 0, 2, 1523552247),
(11, 0, '测试2', '', '#000000', NULL, 1, 0, 1, 1523618672),
(12, 0, '建筑专业', '', '#6e1212', NULL, 1, 0, 1, 1523618678),
(14, 3, '第四级标签001', '', '#c71a1a', NULL, 1, 0, 4, 1523715183),
(15, 3, '第四级标签002', '', '#c32315', NULL, 1, 0, 4, 1523716612),
(16, 2, '第三级标签2', '', '#851313', NULL, 1, 0, 3, 1523719383),
(17, 16, '第四级标签003', '', '#a60505', NULL, 1, 0, 4, 1523975260),
(18, 7, '魅力无限', '', '#ff6161', NULL, 1, 0, 3, 1523975283),
(19, 18, '第四级标签006', '', '#090996', NULL, 1, 0, 4, 1523975299),
(20, 8, 'axxxx', '', '#41148f', NULL, 1, 0, 3, 1523975323),
(21, 20, '第四级标签008', '', '#a60000', NULL, 1, 0, 4, 1523975336),
(22, 16, '345435', '', '#ff6161', NULL, 1, 0, 4, 1524032727),
(23, 18, 'dsdsafadsf', '', '#ff6161', NULL, 1, 0, 4, 1524032810),
(24, 5, '测试部分', '', '#ff6161', NULL, 1, 0, 2, 1524068195),
(25, 24, '测试部分四级1', '', '#ff6161', NULL, 1, 0, 3, 1524068210),
(26, 24, '测试部分四级2', '', '#8f0e0e', NULL, 1, 0, 3, 1524068221),
(27, 1, '驱蚊器翁无群二', '', '#570909', NULL, 1, 0, 2, 1524188659),
(28, 27, 'sddddd', '', '#1ed12c', NULL, 1, 0, 3, 1524188681),
(29, 11, '萨达所啥大所多', '', '#a32a2a', NULL, 1, 0, 2, 1524188728),
(30, 12, '墙体选材', '', '#b85c5c', NULL, 1, 0, 2, 1524188813),
(31, 30, '内墙材料', '', '#8a2626', NULL, 1, 0, 3, 1524188825),
(32, 31, '100厚实心砖', '', '#470b0b', NULL, 1, 0, 4, 1524188842),
(33, 12, '设计依据1', '', '#d63a3a', NULL, 1, 0, 2, 1524189975),
(34, 33, '实测地形', '', '#ff6161', NULL, 1, 0, 3, 1524190041),
(35, 34, '有白图', '', '#ff6161', NULL, 1, 0, 4, 1524190071),
(36, 34, '有电子版', '', '#ff6161', NULL, 1, 0, 4, 1524190090),
(37, 34, '有角点坐标', '', '#ff6161', NULL, 1, 0, 4, 1524190118),
(38, 30, '外墙材料', '', '#ff6161', NULL, 1, 0, 3, 1524190147),
(39, 38, '200厚加气混凝土', '', '#ff6161', NULL, 1, 0, 4, 1524190198);

-- --------------------------------------------------------

--
-- 表的结构 `dsm_user`
--

CREATE TABLE `dsm_user` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户编号',
  `gid` smallint(6) NOT NULL DEFAULT '0' COMMENT '-1 冻结 0 普通用户 2 试用用户组 1 管理组 ',
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `realname` char(16) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `idnumber` char(19) NOT NULL DEFAULT '' COMMENT '真实身份证号码',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(16) NOT NULL DEFAULT '' COMMENT '密码混杂',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `qq` char(15) NOT NULL DEFAULT '' COMMENT 'QQ',
  `create_ip` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时IP',
  `create_date` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `login_ip` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录时IP',
  `login_date` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录时间',
  `logins` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录次数',
  `avatar` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户最后更新图像时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `dsm_user`
--

INSERT INTO `dsm_user` (`uid`, `gid`, `username`, `realname`, `idnumber`, `password`, `salt`, `mobile`, `qq`, `create_ip`, `create_date`, `login_ip`, `login_date`, `logins`, `avatar`) VALUES
(1, 1, 'admin', '沙某某', '', '0192023a7bbd73250516f069df18b500', '123', '13023057601', '', 0, 0, 120210, 1524228871, 82, 0),
(12, 0, 'test', '', '', 'd0571850ff15ca34bcd172dac1dde6c9', 'HhvSVb53Uup9ihD3', '', '', 0, 1523771759, 0, 1523771777, 1, 0),
(15, 2, 'test5', '', '', '340c66e795a3fb17165b0203083a4dfe', 'Fs2nncGFbYOaUA8B', '', '', 0, 1524188117, 0, 0, 0, 0),
(14, 0, '甲方2', '', '', '31bd8156a5dc647ca311f0b3364fa3f6', '3F8V54Ibwohh0fkV', '', '', 0, 1524187316, 14156, 1524187994, 3, 0),
(16, 2, 'test6', '', '', 'e38e0534d4fe57da9429af0f53145f1d', 'uLgpl3zxcGHWAAcR', '', '', 0, 1524189236, 14156, 1524189261, 1, 0),
(17, 0, '123123', '', '', 'ded3cc06c98c2cfc529338b7ccae6186', 'UQNzm5rSrntZcm6Q', '', '', 0, 1524190688, 106121, 1524190709, 1, 0),
(18, 0, '测试用户123', '', '', 'b73d83458cc0bc87672eb8b62f71d70b', 'l3ia7YIgGrierAI8', '', '', 0, 1524228623, 120210, 1524229243, 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dsm_docs`
--
ALTER TABLE `dsm_docs`
  ADD PRIMARY KEY (`did`),
  ADD UNIQUE KEY `doc_name` (`doc_name`);

--
-- Indexes for table `dsm_docs_route`
--
ALTER TABLE `dsm_docs_route`
  ADD PRIMARY KEY (`rid`),
  ADD UNIQUE KEY `did` (`did`,`tag_1`,`tag_2`,`tag_3`);

--
-- Indexes for table `dsm_tags`
--
ALTER TABLE `dsm_tags`
  ADD PRIMARY KEY (`tid`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `dsm_user`
--
ALTER TABLE `dsm_user`
  ADD PRIMARY KEY (`uid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `dsm_docs`
--
ALTER TABLE `dsm_docs`
  MODIFY `did` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- 使用表AUTO_INCREMENT `dsm_docs_route`
--
ALTER TABLE `dsm_docs_route`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- 使用表AUTO_INCREMENT `dsm_tags`
--
ALTER TABLE `dsm_tags`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- 使用表AUTO_INCREMENT `dsm_user`
--
ALTER TABLE `dsm_user`
  MODIFY `uid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户编号', AUTO_INCREMENT=19;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
