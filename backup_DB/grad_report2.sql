/*
 Navicat MySQL Data Transfer

 Source Server         : databaseTest
 Source Server Type    : MySQL
 Source Server Version : 80042 (8.0.42-0ubuntu0.20.04.1)
 Source Host           : 10.104.177.79:3306
 Source Schema         : eoffice

 Target Server Type    : MySQL
 Target Server Version : 80042 (8.0.42-0ubuntu0.20.04.1)
 File Encoding         : 65001

 Date: 05/08/2026 16:31:07
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbluser_ex
-- ----------------------------
DROP TABLE IF EXISTS `tbluser_ex`;
CREATE TABLE `tbluser_ex`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `extID` int NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `position` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbluser_ex
-- ----------------------------
INSERT INTO `tbluser_ex` VALUES (13, 7, 'test3', 'ตำแหน่งผู้บรหารทดสอบ');
INSERT INTO `tbluser_ex` VALUES (14, 2, '110057', 'คณบดีคณะวิทยาศาสตร์');
INSERT INTO `tbluser_ex` VALUES (15, 6, '107374', 'รองคณบดีฝ่ายบริหาร');
INSERT INTO `tbluser_ex` VALUES (17, 6, '114788', 'รองคณบดีฝ่ายวิจัย');
INSERT INTO `tbluser_ex` VALUES (18, 6, '116444', 'รองคณบดีฝ่ายพัฒนานักศึกษาและศิษย์เก่าสัมพันธ์');
INSERT INTO `tbluser_ex` VALUES (19, 6, '110735', 'รองคณบดีฝ่ายวิชาการ');
INSERT INTO `tbluser_ex` VALUES (20, 7, '125625', 'ผู้ช่วยคณบดีฝ่ายการต่างประเทศ');
INSERT INTO `tbluser_ex` VALUES (21, 7, '122275', 'ผู้ช่วยคณบดีฝ่ายนวัตกรรมและวิสาหกิจ');
INSERT INTO `tbluser_ex` VALUES (23, 7, '122635', 'ผู้ช่วยคณบดีฝ่ายบริหาร');
INSERT INTO `tbluser_ex` VALUES (25, 8, '121285', 'ผู้ดูแลระบบร้องทุกข์');
INSERT INTO `tbluser_ex` VALUES (26, 7, '114650', 'ตำแหน่งผู้บริหารทดสอบ');
INSERT INTO `tbluser_ex` VALUES (27, 7, '115416', 'ผู้ช่วยคณบดีฝ่ายการศึกษา และบริการวิชาการ');
INSERT INTO `tbluser_ex` VALUES (30, 7, '127071', 'ผู้ช่วยคณบดีฝ่ายสื่อสารองค์กร');

SET FOREIGN_KEY_CHECKS = 1;
