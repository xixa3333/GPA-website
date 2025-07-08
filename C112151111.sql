-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-07-08 13:38:04
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `c112151111`
--

-- --------------------------------------------------------

--
-- 資料表結構 `account`
--

CREATE TABLE `account` (
  `user` varchar(50) NOT NULL,
  `password` varchar(61) NOT NULL,
  `Revise_Time` date DEFAULT NULL,
  `manage` int(2) DEFAULT NULL,
  `error_passwords` int(3) NOT NULL DEFAULT 0,
  `login_time` datetime DEFAULT current_timestamp(),
  `address` varchar(256) DEFAULT NULL,
  `token` varchar(33) DEFAULT NULL,
  `Revise_password` int(2) DEFAULT 0,
  `newaddress` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `account`
--

INSERT INTO `account` (`user`, `password`, `Revise_Time`, `manage`, `error_passwords`, `login_time`, `address`, `token`, `Revise_password`, `newaddress`) VALUES
('admin', '$2y$10$LFUnyFGQUBN0XnsijnMzJuJ3nHmez53IuNOvWhn4SpHwB8ph5Rx7q', '2024-06-15', 1, 0, '2025-07-08 13:36:45', 'c112151111@nkust.edu.tw', '72ff28be6dcd8436a9565ced18dcf097', 0, 'NULL'),
('c112118210', '$2y$10$jCTTiMpDw/Pyrd2L0k9rUeHnP4r/r0IrntfDI08DBpFGyw9yaRyUu', '2024-06-30', 0, 0, '2024-06-30 03:52:36', 'c112118210@nkust.edu.tw', 'f3ab467cf86fb1f275fe128f92a15e09', 0, NULL),
('C113135127', '$2y$10$IpX3s.yWJcFu8aPapS76puPCzE6HAsre.B81DHGLRvIfP3W1bGljq', '2024-11-28', 0, 0, '2024-12-04 16:10:14', 'c113135127@nkust.edu.tw', 'a8fbc0eeb5b1951f860742756293830c', 0, NULL),
('C113154115', '$2y$10$a4Y6bqNzn1dkKUkj6G3niOMZLICwPs1hy1Y/3zGpgFKgYzdvBRji6', '2024-12-10', 0, 0, '2024-12-11 15:48:02', 'C113154115@nkust.edu.tw', '26a03b1cdcc5cfffb257bd65c0552f2f', 0, NULL),
('c113176211', '$2y$10$YF0gZuT3JOJq0.HxKsYMN.JlMlNZ6Vza61g/iaqcalAAlbnGg4Toy', '2024-12-01', 0, 0, '2024-12-21 17:57:37', 'a0968593048@gmail.com', '039483f4fbdecdda6eb81e843fa42490', 0, NULL),
('jiawen', '1234', '2024-06-11', 0, 0, '2024-06-15 06:03:53', 'c112151105@nkust.edu.tw', '1ecc8efb3270899c7dc2bdf77576e5e5', 0, NULL),
('milkgreen', '$2y$10$rpkrlSIr8v.yHfVm.Y0S4eRPNyZG7ThWLI2nEiaY8pohzwCiZkmhm', '2024-07-09', 0, 0, '2024-07-09 15:04:38', 'C113176211@nkust.edu.tw', '4ce314b0308bb77a4b8814249b578752', 0, NULL),
('sdf0w0', '$2y$10$PmZ0T6SLeleEyyKMh7K2jeFvW23hPNjbr9PWXcbmOa/NHTblaDL/6', '2024-07-09', 0, 0, '2025-04-15 03:36:48', 'wuchiaen95@gmail.com', 'ca2f52f87e4c579b51ab51dfe5ced79f', 0, NULL),
('Ss', '$2y$10$C6dS/AET36NRriF8BBzNq.r9gNdzcI.b7lYwGqpPdSTYbdX/bXXh.', '2024-07-11', 1, 0, '2024-07-23 03:23:32', '1111xixa1111@gmail.com', 'ddfdf4eb1e34476558aff87295c1fd35', 0, NULL),
('xixa3333', '$2y$10$FC0DdtiiSO6MhqvFXD2jTOjh48izxnvM2Kv/iH12nKZlZ2eezQbWa', '2025-03-25', 0, 0, '2025-07-08 13:36:28', 'xixa3333@gmail.com', '8729bcba662ca95aed929de250915b84', 0, 'NULL');

-- --------------------------------------------------------

--
-- 資料表結構 `goods`
--

CREATE TABLE `goods` (
  `id` int(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(4) NOT NULL,
  `cost` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `goods`
--

INSERT INTO `goods` (`id`, `name`, `quantity`, `cost`) VALUES
(1, 'Transcend 創見 64G記憶卡', 3, 990),
(2, 'Transcend 創見 32G記憶卡', 5, 550),
(3, 'SanDisk 記憶卡 32G Micro SD ', 5, 550),
(4, '創見 記憶卡 Transcend 32G Micro SD  ', 6, 370),
(5, '原廠全新記憶卡 SanDisk microSD 32G  T-Flash', 4, 285),
(6, '原廠終保 KINGSTON 金士頓  16G microSD 記憶卡', 8, 165),
(7, '全新記憶卡 ADATA 威剛 SDHC SD 8G microSD', 4, 95),
(8, 'SanDisk Micro SDHC 64G 30MB/s 200X 超快記憶卡公司貨', 4, 900),
(9, 'Micro SD TF USB 2.0 讀卡機', 6, 30),
(10, '最新款共振音響KK5 藍牙 可插Micro SD聽歌', 4, 999),
(11, 'SanDisk 記憶卡 16G Micro SD 16GB UHS ', 8, 199),
(12, 'SanDisk記憶卡 128GB 128G 320X 48MB micro SDXC ', 2, 1500);

-- --------------------------------------------------------

--
-- 資料表結構 `student`
--

CREATE TABLE `student` (
  `id` tinyint(2) UNSIGNED ZEROFILL NOT NULL,
  `name` varchar(20) NOT NULL,
  `sex` enum('M','F') NOT NULL DEFAULT 'F',
  `birthday` date NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `student`
--

INSERT INTO `student` (`id`, `name`, `sex`, `birthday`, `email`, `phone`, `address`) VALUES
(01, '777', 'M', '1978-02-20', 'white@gmail.com', '0911225566', '高雄市建國路一段17號'),
(02, '許夢喆 ', 'M', '1985-07-27', 'orange@gmail.com', '0944555566', '台中市太原路三段517號'),
(14, 'Kai Hong Wang', 'M', '2024-04-01', '1111xixa1111@gmail.com', '0989191610', '206基隆市七堵區東新街22號');

-- --------------------------------------------------------

--
-- 資料表結構 `table_`
--

CREATE TABLE `table_` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` int(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_112down`
--

CREATE TABLE `table_112down` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_112downc112118210`
--

CREATE TABLE `table_112downc112118210` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112downc112118210`
--

INSERT INTO `table_112downc112118210` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '專業', '中文閱讀與表達', '62', 2, 2),
('選修', '專業', '實務專題-資訊技術', '99', 1, 4),
('必修', '專業', '實用英文', '62', 2, 2),
('選修', '專業', '智慧交通與聯網智駕', '92', 3, 4),
('必修', '通識', '智慧物聯網程式設計實作', '68', 2, 2),
('必修', '通識', '智慧財產權法', '70', 2, 3),
('必修', '通識', '物件導向程式設計', '18', 3, 0),
('必修', '專業', '經濟學', '55', 3, 1),
('必修', '通識', '資訊管理與導論', '60', 3, 2),
('必修', '專業', '電腦程式設計與實習', '73', 2, 3);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112downc113154115`
--

CREATE TABLE `table_112downc113154115` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112downc113154115`
--

INSERT INTO `table_112downc113154115` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '通識', '中文閱讀與表達(二)', NULL, 2, 0),
('必修', '通識', '博雅(社會)性別與法律', NULL, 2, 0),
('必修', '通識', '博雅(科技)現今科技議題', NULL, 2, 0),
('必修', '通識', '實用英文(二)', NULL, 2, 0),
('必修', '專業', '微積分(二)', NULL, 3, 0),
('必修', '通識', '數位通識(科技)-人可以貌相：臉孔處理與辨識', NULL, 1, 0),
('必修', '專業', '物理(二)', NULL, 3, 0),
('必修', '專業', '計算機程式設計', NULL, 3, 0),
('必修', '專業', '電機機械', NULL, 3, 0),
('必修', '專業', '電路學(二)', NULL, 3, 0),
('必修', '通識', '體育(二)', NULL, 0, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112downjiawen`
--

CREATE TABLE `table_112downjiawen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_112downmilk green`
--

CREATE TABLE `table_112downmilk green` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_112downmilkgreen`
--

CREATE TABLE `table_112downmilkgreen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` int(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_112downsdf0w0`
--

CREATE TABLE `table_112downsdf0w0` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112downsdf0w0`
--

INSERT INTO `table_112downsdf0w0` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('選修', '通識', 'BI達人養成', '98', 1, 4),
('必修', '專業', '中文閱讀與表達(二)', '85', 2, 4),
('選修', '通識', '人可以貌相', '98', 1, 4),
('選修', '專業', '人工智慧倫理', '95', 3, 4),
('選修', '通識', '創意學經濟', '75', 2, 3),
('必修', '專業', '實用英文(二)', '75', 2, 3),
('選修', '通識', '無所不在的經濟學', '95', 2, 4),
('必修', '專業', '物件導向程式設計', '100', 3, 4),
('必修', '專業', '經濟學(二)', '85', 3, 4),
('必修', '專業', '視窗程式設計實務', '100', 3, 4),
('必修', '專業', '資訊管理導論', '80', 3, 4),
('必修', '專業', '離散數學', '90', 3, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112downxixa3333`
--

CREATE TABLE `table_112downxixa3333` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112downxixa3333`
--

INSERT INTO `table_112downxixa3333` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '通識', '中文閱讀與表達(二)', '91', 2, 4),
('選修', '專業', '互動式網頁程式設計', '99', 3, 4),
('選修', '專業', '創客微學分(一) ', '合格', 1, 0),
('選修', '通識', '大數據:資料採集與視覺化', '99', 2, 4),
('必修', '通識', '實用英文(二)', '82', 2, 4),
('必修', '專業', '微積分(二)', '91', 3, 4),
('必修', '通識', '服務教育(二)', '合格', 0, 0),
('選修', '專業', '程式語言實習(二)', '99', 1, 4),
('選修', '專業', '組合語言程式設計', '97', 3, 4),
('必修', '專業', '網際網路暨應用', '92', 3, 4),
('必修', '專業', '計算機結構', '96', 3, 4),
('必修', '通識', '體育(二) ', '82', 0, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112up`
--

CREATE TABLE `table_112up` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` int(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upc112118210`
--

CREATE TABLE `table_112upc112118210` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upc113135127`
--

CREATE TABLE `table_112upc113135127` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112upc113135127`
--

INSERT INTO `table_112upc113135127` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '專業', '中文閱讀與表達(一) ', '98', 2, 4),
('必修', '通識', '博雅(社會)易經管理思維 ', '97', 2, 4),
('必修', '通識', '博雅(科技)智慧物聯網程式設計實作', '95', 2, 4),
('選修', '專業', '商事法', '86', 3, 4),
('必修', '專業', '基礎德語文法I ', '64', 4, 2),
('必修', '專業', '基礎德語文法應用I ', '70', 2, 3),
('必修', '專業', '基礎德語會話I ', '89', 2, 4),
('必修', '專業', '基礎德語聽力 I ', '97', 2, 4),
('必修', '專業', '基礎德語讀本I ', '73', 2, 3),
('必修', '通識', '數位通識(科技)-學會學:學習之道 ', '82', 2, 4),
('必修', '專業', '服務教育(一) ', NULL, 0, NULL),
('選修', '專業', '泰語(一) ', '90', 2, 4),
('必修', '專業', '體育(一) ', '90', 0, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upc113154115`
--

CREATE TABLE `table_112upc113154115` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112upc113154115`
--

INSERT INTO `table_112upc113154115` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '通識', '中文閱讀與表達(一)', '82', 2, 3.7),
('必修', '通識', '實用英文(一)', '78', 2, 3.3),
('必修', '專業', '影像處理微學分', '合格', 1, NULL),
('必修', '專業', '微積分(一)', '60', 3, 1.7),
('必修', '通識', '服務教育(一)', '合格', 0, NULL),
('選修', '通識', '校定(一)藝術美感探索', '74', 2, 3),
('必修', '專業', '物理(一)', '43', 3, 0),
('選修', '專業', '視窗程式設計', '91', 3, 4.3),
('必修', '專業', '計算機概論', '78', 3, 3.3),
('必修', '專業', '邏輯設計', '74', 3, 3),
('必修', '專業', '電路學(一)', '84', 3, 3.7),
('必修', '通識', '體育(一)', '55', 0, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upc113176211`
--

CREATE TABLE `table_112upc113176211` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112upc113176211`
--

INSERT INTO `table_112upc113176211` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '專業', '健康促進與生活實踐', '78', 2, 3),
('必修', '專業', '情感與親密', '79', 2, 3),
('必修', '專業', '水產概論', '74', 2, 3),
('必修', '專業', '水產食品科學概論', '83', 2, 4),
('必修', '專業', '生物學', '83', 2, 4),
('必修', '專業', '英文', '75', 2, 3),
('必修', '專業', '食品加工', '74', 2, 3),
('必修', '專業', '食品加工實習', '81', 1, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upjiawen`
--

CREATE TABLE `table_112upjiawen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112upjiawen`
--

INSERT INTO `table_112upjiawen` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '專業', '中文閱讀與表達', '73', 2, 3),
('必修', '通識', '國際人權法律與實務', '76', 2, 3),
('必修', '專業', '實用英文ㄧ', '82', 2, 4),
('必修', '專業', '微積分ㄧ', '60', 3, 2),
('必修', '通識', '性別與法律', '81', 2, 4),
('必修', '通識', '憲法與人權', '85', 2, 4),
('必修', '專業', '數位邏輯設計', '78', 3, 3),
('必修', '專業', '計算機概論', '76', 3, 3),
('必修', '專業', '計算機程式設計', '82', 3, 4),
('必修', '專業', '體育ㄧ', '81', 0, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upmilk green`
--

CREATE TABLE `table_112upmilk green` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112upmilk green`
--

INSERT INTO `table_112upmilk green` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '專業', '國文', '100', 2, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upmilkgreen`
--

CREATE TABLE `table_112upmilkgreen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` int(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upsdf0w0`
--

CREATE TABLE `table_112upsdf0w0` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112upsdf0w0`
--

INSERT INTO `table_112upsdf0w0` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '通識', '中文閱讀與表達', '77', 2, 3),
('必修', '通識', '實用英文(一)', '71', 2, 3),
('必修', '專業', '微積分', '78', 3, 3),
('必修', '專業', '會計學', '100', 3, 4),
('選修', '通識', '東南亞文化與社會', '95', 2, 4),
('選修', '通識', '民主與法治', '90', 2, 4),
('必修', '專業', '程式語言', '100', 3, 4),
('必修', '專業', '管理學', '78', 3, 3),
('必修', '專業', '經濟學(一)', '69', 3, 2),
('必修', '專業', '計算機概論', '72', 3, 3),
('必修', '通識', '體育(一)', '86', 0, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upss`
--

CREATE TABLE `table_112upss` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_112upxixa3333`
--

CREATE TABLE `table_112upxixa3333` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_112upxixa3333`
--

INSERT INTO `table_112upxixa3333` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '通識', '中文閱讀與表達(一)', '82', 2, 4),
('選修', '通識', '台灣古蹟與歷史', '83', 2, 4),
('必修', '通識', '實用英文(一)', '90', 2, 4),
('選修', '通識', '巨量資料分析與應用 ', '96', 2, 4),
('必修', '專業', '微積分(一)', '94', 3, 4),
('必修', '專業', '數位邏輯設計', '88', 3, 4),
('選修', '通識', '服務創新', '88', 2, 4),
('必修', '通識', '服務教育(一)', '合格', 0, 0),
('選修', '專業', '程式語言實習(一)', '99', 1, 4),
('必修', '專業', '計算機概論', '91', 3, 4),
('必修', '專業', '計算機程式設計', '97', 3, 4),
('選修', '通識', '音樂賞析', '90', 2, 4),
('必修', '通識', '體育(一)', '77', 0, 3);

-- --------------------------------------------------------

--
-- 資料表結構 `table_113downjiawen`
--

CREATE TABLE `table_113downjiawen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113downmilkgreen`
--

CREATE TABLE `table_113downmilkgreen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` int(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113downsdf0w0`
--

CREATE TABLE `table_113downsdf0w0` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113downxixa3333`
--

CREATE TABLE `table_113downxixa3333` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_113downxixa3333`
--

INSERT INTO `table_113downxixa3333` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('選修', '專業', '人工智慧倫理', NULL, 3, 0),
('選修', '通識', '創意與創新', NULL, 2, 0),
('必修', '通識', '實用英文（四）', NULL, 2, 0),
('必修', '專業', '微處理機', NULL, 3, 0),
('必修', '通識', '桌球', NULL, 0, 0),
('必修', '專業', '機率與統計', NULL, 3, 0),
('必修', '專業', '線性代數', NULL, 3, 0),
('必修', '專業', '計算機網路', NULL, 3, 0),
('選修', '專業', '資料結構實務', NULL, 3, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `table_113up`
--

CREATE TABLE `table_113up` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` int(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113upc112118210`
--

CREATE TABLE `table_113upc112118210` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113upc113154115`
--

CREATE TABLE `table_113upc113154115` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113upjiawen`
--

CREATE TABLE `table_113upjiawen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113upmilk green`
--

CREATE TABLE `table_113upmilk green` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113upmilkgreen`
--

CREATE TABLE `table_113upmilkgreen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` int(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113upsdf0w0`
--

CREATE TABLE `table_113upsdf0w0` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_113upxixa3333`
--

CREATE TABLE `table_113upxixa3333` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `table_113upxixa3333`
--

INSERT INTO `table_113upxixa3333` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES
('必修', '通識', '實用英文(三)', '75', 2, 3),
('選修', '專業', '影像處理微學分-深度學習實作模組', '合格', 1, NULL),
('選修', '通識', '心理學與教育', '83', 2, 4),
('必修', '專業', '物件導向程式設計', '99', 3, 4),
('選修', '專業', '物件導向程式設計實習', '99', 2, 4),
('選修', '通識', '生成式AI與ChatGPT應用', '93', 2, 4),
('選修', '專業', '系統程式', '96', 3, 4),
('必修', '專業', '資料結構', '97', 3, 4),
('必修', '專業', '離散數學', '97', 3, 4),
('選修', '通識', '電腦遊戲設計導論', '91', 2, 4),
('必修', '通識', '體育（三）', '76', 0, 3);

-- --------------------------------------------------------

--
-- 資料表結構 `table_114downjiawen`
--

CREATE TABLE `table_114downjiawen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_114downxixa3333`
--

CREATE TABLE `table_114downxixa3333` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_114upmilkgreen`
--

CREATE TABLE `table_114upmilkgreen` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` int(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_114upxixa3333`
--

CREATE TABLE `table_114upxixa3333` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_115downxixa3333`
--

CREATE TABLE `table_115downxixa3333` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `table_115upxixa3333`
--

CREATE TABLE `table_115upxixa3333` (
  `Required_elective` enum('必修','選修') NOT NULL DEFAULT '必修',
  `course` enum('專業','通識') NOT NULL DEFAULT '專業',
  `suject` varchar(30) NOT NULL,
  `score` varchar(4) DEFAULT NULL,
  `credit` int(2) DEFAULT NULL,
  `GPA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `totalc112118210`
--

CREATE TABLE `totalc112118210` (
  `GPA_total` float DEFAULT NULL,
  `score_total` float DEFAULT NULL,
  `credit_total` int(5) DEFAULT NULL,
  `Original_credits` int(5) DEFAULT NULL,
  `GPA_sort` varchar(12) DEFAULT NULL,
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `totalc112118210`
--

INSERT INTO `totalc112118210` (`GPA_total`, `score_total`, `credit_total`, `Original_credits`, `GPA_sort`, `table_name`) VALUES
(2, 62.78, 17, 23, 'NKUST', 'table_112downc112118210');

-- --------------------------------------------------------

--
-- 資料表結構 `totalc113135127`
--

CREATE TABLE `totalc113135127` (
  `GPA_total` float DEFAULT NULL,
  `score_total` float DEFAULT NULL,
  `credit_total` int(5) DEFAULT NULL,
  `Original_credits` int(5) DEFAULT NULL,
  `GPA_sort` varchar(12) DEFAULT NULL,
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `totalc113135127`
--

INSERT INTO `totalc113135127` (`GPA_total`, `score_total`, `credit_total`, `Original_credits`, `GPA_sort`, `table_name`) VALUES
(3.52, 83.84, 25, 25, 'NKUST', 'table_112upC113135127');

-- --------------------------------------------------------

--
-- 資料表結構 `totalc113154115`
--

CREATE TABLE `totalc113154115` (
  `GPA_total` float DEFAULT NULL,
  `score_total` float DEFAULT NULL,
  `credit_total` int(5) DEFAULT NULL,
  `Original_credits` int(5) DEFAULT NULL,
  `GPA_sort` varchar(12) DEFAULT NULL,
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `totalc113154115`
--

INSERT INTO `totalc113154115` (`GPA_total`, `score_total`, `credit_total`, `Original_credits`, `GPA_sort`, `table_name`) VALUES
(2.83, 73.25, 22, 24, 'TW3', 'table_112upC113154115');

-- --------------------------------------------------------

--
-- 資料表結構 `totalc113176211`
--

CREATE TABLE `totalc113176211` (
  `GPA_total` float DEFAULT NULL,
  `score_total` float DEFAULT NULL,
  `credit_total` int(5) DEFAULT NULL,
  `Original_credits` int(5) DEFAULT NULL,
  `GPA_sort` varchar(12) DEFAULT NULL,
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `totalc113176211`
--

INSERT INTO `totalc113176211` (`GPA_total`, `score_total`, `credit_total`, `Original_credits`, `GPA_sort`, `table_name`) VALUES
(3.33, 78.2, 15, 15, 'NKUST', 'table_112upc113176211');

-- --------------------------------------------------------

--
-- 資料表結構 `totaljiawen`
--

CREATE TABLE `totaljiawen` (
  `GPA_total` float DEFAULT NULL,
  `score_total` float DEFAULT NULL,
  `credit_total` int(5) DEFAULT NULL,
  `Original_credits` int(5) DEFAULT NULL,
  `GPA_sort` varchar(12) DEFAULT NULL,
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `totaljiawen`
--

INSERT INTO `totaljiawen` (`GPA_total`, `score_total`, `credit_total`, `Original_credits`, `GPA_sort`, `table_name`) VALUES
(3.27, 76.45, 22, 22, 'NKUST', 'table_112upjiawen');

-- --------------------------------------------------------

--
-- 資料表結構 `totalsdf0w0`
--

CREATE TABLE `totalsdf0w0` (
  `GPA_total` float DEFAULT NULL,
  `score_total` float DEFAULT NULL,
  `credit_total` int(5) DEFAULT NULL,
  `Original_credits` int(5) DEFAULT NULL,
  `GPA_sort` varchar(12) DEFAULT NULL,
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `totalsdf0w0`
--

INSERT INTO `totalsdf0w0` (`GPA_total`, `score_total`, `credit_total`, `Original_credits`, `GPA_sort`, `table_name`) VALUES
(3.86, 89.5, 28, 28, 'NKUST', 'table_112downsdf0w0'),
(3.27, 82.96, 26, 26, 'NKUST', 'table_112upsdf0w0');

-- --------------------------------------------------------

--
-- 資料表結構 `totalxixa3333`
--

CREATE TABLE `totalxixa3333` (
  `GPA_total` float DEFAULT NULL,
  `score_total` float DEFAULT NULL,
  `credit_total` int(5) DEFAULT NULL,
  `Original_credits` int(5) DEFAULT NULL,
  `GPA_sort` varchar(12) DEFAULT 'NKUST4.0',
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `totalxixa3333`
--

INSERT INTO `totalxixa3333` (`GPA_total`, `score_total`, `credit_total`, `Original_credits`, `GPA_sort`, `table_name`) VALUES
(4, 94, 23, 22, 'NKUST', 'table_112downxixa3333'),
(4, 90.68, 25, 25, 'NKUST', 'table_112upxixa3333'),
(3.91, 93.14, 23, 22, 'NKUST', 'table_113upxixa3333');

-- --------------------------------------------------------

--
-- 資料表結構 `zipcode`
--

CREATE TABLE `zipcode` (
  `city` varchar(255) NOT NULL COMMENT '直轄市',
  `district` varchar(255) NOT NULL COMMENT '行政區',
  `zipcode` int(3) NOT NULL COMMENT '郵遞區號'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `zipcode`
--

INSERT INTO `zipcode` (`city`, `district`, `zipcode`) VALUES
('新北市', '三峽區', 237),
('Kaohsiung', '三民區', 807),
('新北市', '中和區', 235),
('桃園市', '中壢區', 320),
('台南市', '安平區', 708),
('Kaohsiung', '小港區', 812),
('台南市', '新營區', 730),
('Kaohsiung', '新興區', 800),
('新北市', '新莊區', 242);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`user`);

--
-- 資料表索引 `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `table_`
--
ALTER TABLE `table_`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112down`
--
ALTER TABLE `table_112down`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112downc112118210`
--
ALTER TABLE `table_112downc112118210`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112downc113154115`
--
ALTER TABLE `table_112downc113154115`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112downjiawen`
--
ALTER TABLE `table_112downjiawen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112downmilk green`
--
ALTER TABLE `table_112downmilk green`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112downmilkgreen`
--
ALTER TABLE `table_112downmilkgreen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112downsdf0w0`
--
ALTER TABLE `table_112downsdf0w0`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112downxixa3333`
--
ALTER TABLE `table_112downxixa3333`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112up`
--
ALTER TABLE `table_112up`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upc112118210`
--
ALTER TABLE `table_112upc112118210`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upc113135127`
--
ALTER TABLE `table_112upc113135127`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upc113154115`
--
ALTER TABLE `table_112upc113154115`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upc113176211`
--
ALTER TABLE `table_112upc113176211`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upjiawen`
--
ALTER TABLE `table_112upjiawen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upmilk green`
--
ALTER TABLE `table_112upmilk green`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upmilkgreen`
--
ALTER TABLE `table_112upmilkgreen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upsdf0w0`
--
ALTER TABLE `table_112upsdf0w0`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upss`
--
ALTER TABLE `table_112upss`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_112upxixa3333`
--
ALTER TABLE `table_112upxixa3333`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113downjiawen`
--
ALTER TABLE `table_113downjiawen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113downmilkgreen`
--
ALTER TABLE `table_113downmilkgreen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113downsdf0w0`
--
ALTER TABLE `table_113downsdf0w0`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113downxixa3333`
--
ALTER TABLE `table_113downxixa3333`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113up`
--
ALTER TABLE `table_113up`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113upc112118210`
--
ALTER TABLE `table_113upc112118210`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113upc113154115`
--
ALTER TABLE `table_113upc113154115`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113upjiawen`
--
ALTER TABLE `table_113upjiawen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113upmilk green`
--
ALTER TABLE `table_113upmilk green`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113upmilkgreen`
--
ALTER TABLE `table_113upmilkgreen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113upsdf0w0`
--
ALTER TABLE `table_113upsdf0w0`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_113upxixa3333`
--
ALTER TABLE `table_113upxixa3333`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_114downjiawen`
--
ALTER TABLE `table_114downjiawen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_114downxixa3333`
--
ALTER TABLE `table_114downxixa3333`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_114upmilkgreen`
--
ALTER TABLE `table_114upmilkgreen`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_114upxixa3333`
--
ALTER TABLE `table_114upxixa3333`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_115downxixa3333`
--
ALTER TABLE `table_115downxixa3333`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `table_115upxixa3333`
--
ALTER TABLE `table_115upxixa3333`
  ADD PRIMARY KEY (`suject`);

--
-- 資料表索引 `totalc112118210`
--
ALTER TABLE `totalc112118210`
  ADD PRIMARY KEY (`table_name`);

--
-- 資料表索引 `totalc113135127`
--
ALTER TABLE `totalc113135127`
  ADD PRIMARY KEY (`table_name`);

--
-- 資料表索引 `totalc113154115`
--
ALTER TABLE `totalc113154115`
  ADD PRIMARY KEY (`table_name`);

--
-- 資料表索引 `totalc113176211`
--
ALTER TABLE `totalc113176211`
  ADD PRIMARY KEY (`table_name`);

--
-- 資料表索引 `totaljiawen`
--
ALTER TABLE `totaljiawen`
  ADD PRIMARY KEY (`table_name`);

--
-- 資料表索引 `totalsdf0w0`
--
ALTER TABLE `totalsdf0w0`
  ADD PRIMARY KEY (`table_name`);

--
-- 資料表索引 `totalxixa3333`
--
ALTER TABLE `totalxixa3333`
  ADD PRIMARY KEY (`table_name`);

--
-- 資料表索引 `zipcode`
--
ALTER TABLE `zipcode`
  ADD PRIMARY KEY (`district`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `student`
--
ALTER TABLE `student`
  MODIFY `id` tinyint(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
