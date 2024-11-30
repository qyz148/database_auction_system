-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 09:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acution_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `AddressID` varchar(10) NOT NULL,
  `AddressStreet` varchar(20) NOT NULL,
  `City` varchar(20) NOT NULL,
  `Postcode` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE `adminlogin` (
  `AdminID` varchar(10) NOT NULL,
  `AdminPassword` varchar(20) NOT NULL,
  `AdminUsername` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auction`
--

CREATE TABLE `auction` (
  `AuctionID` varchar(10) NOT NULL,
  `UserID` varchar(10) NOT NULL,
  `ItemID` varchar(10) NOT NULL,
  `DateOfPurchase` date DEFAULT NULL,
  `PurchasePrice` varchar(20) DEFAULT NULL,
  `AuctionStatus` varchar(20) DEFAULT NULL,
  `AuctionStartingTime` datetime DEFAULT NULL,
  `ReservePrice` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bid`
--

CREATE TABLE `bid` (
  `BidID` varchar(10) NOT NULL,
  `UserID` varchar(10) DEFAULT NULL,
  `ItemID` varchar(10) DEFAULT NULL,
  `BidAmount` varchar(20) DEFAULT NULL,
  `TimeOfBid` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` varchar(10) NOT NULL,
  `ItemCategoryName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE `commission` (
  `CommissionID` varchar(10) NOT NULL,
  `AuctionID` varchar(10) NOT NULL,
  `PurchasePrice` varchar(20) DEFAULT NULL,
  `CommissionFee` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE `inbox` (
  `InboxID` varchar(10) NOT NULL,
  `UserID` varchar(10) DEFAULT NULL,
  `MessageContent` text NOT NULL,
  `MessageType` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `ItemID` varchar(10) NOT NULL,
  `UserID` varchar(10) DEFAULT NULL,
  `CategoryID` varchar(10) DEFAULT NULL,
  `ItemName` varchar(20) NOT NULL,
  `ItemDescription` text DEFAULT NULL,
  `RemainingTime` time DEFAULT NULL,
  `StartingPrice` varchar(20) DEFAULT NULL,
  `ClosingDate` date DEFAULT NULL,
  `CurrentBid` varchar(20) DEFAULT NULL,
  `MinimumBid` varchar(20) DEFAULT NULL,
  `ItemPicture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` varchar(10) NOT NULL,
  `UserID` varchar(10) DEFAULT NULL,
  `AuctionID` varchar(10) DEFAULT NULL,
  `UserRating` varchar(20) DEFAULT NULL,
  `ReviewDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userpersonalinformation`
--

CREATE TABLE `userpersonalinformation` (
  `UserID` varchar(10) NOT NULL,
  `AdminID` varchar(10) DEFAULT NULL,
  `AddressID` varchar(10) DEFAULT NULL,
  `FirstName` varchar(20) NOT NULL,
  `LastName` varchar(20) NOT NULL,
  `UserEmail` varchar(20) NOT NULL,
  `UserPassword` varchar(20) NOT NULL,
  `UserRating` int(11) DEFAULT NULL,
  `TotalSalesAmount` varchar(20) DEFAULT NULL,
  `AccountType` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `userpersonalinformation`
--
DELIMITER $$
CREATE TRIGGER `before_insert_user` BEFORE INSERT ON `userpersonalinformation` FOR EACH ROW BEGIN
    DECLARE new_id VARCHAR(10); -- 用于存储生成的 ID
    DECLARE max_id INT;         -- 当前最大数字部分的 ID

    -- 获取当前最大 UserID 的数字部分
    SELECT COALESCE(MAX(CAST(SUBSTRING(UserID, 2) AS UNSIGNED)), 0) INTO max_id
    FROM UserPersonalInformation;

    -- 生成新的 UserID，例如 U0000001
    SET new_id = CONCAT('U', LPAD(max_id + 1, 7, '0'));

    -- 将生成的 UserID 赋值给新插入的记录
    SET NEW.UserID = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `watchlist`
--

CREATE TABLE `watchlist` (
  `UserID` varchar(10) NOT NULL,
  `ItemID` varchar(10) NOT NULL,
  `WatchListDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`AddressID`);

--
-- Indexes for table `adminlogin`
--
ALTER TABLE `adminlogin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `auction`
--
ALTER TABLE `auction`
  ADD PRIMARY KEY (`AuctionID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Indexes for table `bid`
--
ALTER TABLE `bid`
  ADD PRIMARY KEY (`BidID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`CommissionID`),
  ADD KEY `AuctionID` (`AuctionID`);

--
-- Indexes for table `inbox`
--
ALTER TABLE `inbox`
  ADD PRIMARY KEY (`InboxID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `AuctionID` (`AuctionID`);

--
-- Indexes for table `userpersonalinformation`
--
ALTER TABLE `userpersonalinformation`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `AdminID` (`AdminID`),
  ADD KEY `AddressID` (`AddressID`);

--
-- Indexes for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`UserID`,`ItemID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auction`
--
ALTER TABLE `auction`
  ADD CONSTRAINT `auction_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `userpersonalinformation` (`UserID`),
  ADD CONSTRAINT `auction_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`);

--
-- Constraints for table `bid`
--
ALTER TABLE `bid`
  ADD CONSTRAINT `bid_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `userpersonalinformation` (`UserID`),
  ADD CONSTRAINT `bid_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`);

--
-- Constraints for table `commission`
--
ALTER TABLE `commission`
  ADD CONSTRAINT `commission_ibfk_1` FOREIGN KEY (`AuctionID`) REFERENCES `auction` (`AuctionID`);

--
-- Constraints for table `inbox`
--
ALTER TABLE `inbox`
  ADD CONSTRAINT `inbox_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `userpersonalinformation` (`UserID`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `userpersonalinformation` (`UserID`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `userpersonalinformation` (`UserID`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`AuctionID`) REFERENCES `auction` (`AuctionID`);

--
-- Constraints for table `userpersonalinformation`
--
ALTER TABLE `userpersonalinformation`
  ADD CONSTRAINT `userpersonalinformation_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `adminlogin` (`AdminID`),
  ADD CONSTRAINT `userpersonalinformation_ibfk_2` FOREIGN KEY (`AddressID`) REFERENCES `address` (`AddressID`);

--
-- Constraints for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `userpersonalinformation` (`UserID`),
  ADD CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Add initialized datasets
-- category info
INSERT INTO `category`(`CategoryID`, `ItemCategoryName`) VALUES ('item_1','chair');
INSERT INTO `category`(`CategoryID`, `ItemCategoryName`) VALUES ('item_2','Display Card');
INSERT INTO `category`(`CategoryID`, `ItemCategoryName`) VALUES ('item_3','CPU');
INSERT INTO `category`(`CategoryID`, `ItemCategoryName`) VALUES ('item_4','Computer Monitor');

-- admin details


-- initial users (optional)


