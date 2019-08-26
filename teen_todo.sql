/*
 Navicat Premium Data Transfer

 Source Server         : MYSQL
 Source Server Type    : MySQL
 Source Server Version : 50722
 Source Host           : localhost:3306
 Source Schema         : teen_todo

 Target Server Type    : MySQL
 Target Server Version : 50722
 File Encoding         : 65001

 Date: 26/08/2019 11:32:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for list
-- ----------------------------
DROP TABLE IF EXISTS `list`;
CREATE TABLE `list`  (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `lname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'default',
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  PRIMARY KEY (`lid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of list
-- ----------------------------
INSERT INTO `list` VALUES (47, 'TESTList', 8, 42);
INSERT INTO `list` VALUES (48, 'TESTList', 8, 3);
INSERT INTO `list` VALUES (49, 'TESTList', 8, 31);

-- ----------------------------
-- Table structure for task
-- ----------------------------
DROP TABLE IF EXISTS `task`;
CREATE TABLE `task`  (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `timportant` int(255) NULL DEFAULT 0,
  `tdone` int(255) NULL DEFAULT 0,
  `tdeadline` datetime(6) NULL DEFAULT NULL,
  PRIMARY KEY (`tid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of task
-- ----------------------------
INSERT INTO `task` VALUES (3, 8, '收购肯德基', 1, 0, '2019-06-20 19:05:16.000000');
INSERT INTO `task` VALUES (31, 8, 'english~', 0, 1, '2019-06-06 16:58:27.000000');
INSERT INTO `task` VALUES (33, 9, '开发游戏', 0, 0, '2019-06-06 00:00:00.000000');
INSERT INTO `task` VALUES (34, 9, '写PHP作业', 1, 1, '2019-06-05 21:20:57.000000');
INSERT INTO `task` VALUES (42, 8, 'PHP 功能拓展', 1, 1, '2019-06-06 14:34:34.000000');
INSERT INTO `task` VALUES (47, 8, '修复打包问题', 0, 0, '2019-06-10 08:19:35.000000');
INSERT INTO `task` VALUES (48, 8, '123', 1, 1, '2019-06-09 15:53:33.000000');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `uemail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `upasswd` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `uhead` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (6, '马化腾', '1234@qq.com', '1234', 'http://localhost:3000/upfile/20160707163006_EnLeG.thumb.700_0.jpeg');
INSERT INTO `user` VALUES (8, '马云', '123@qq.com', '123', 'http://localhost:3000/upfile/cute.jpeg');
INSERT INTO `user` VALUES (9, 'BEATREE', '12345@qq.com', '12345', 'http://localhost:3000/upfile/lovelydog.jpeg');

SET FOREIGN_KEY_CHECKS = 1;
