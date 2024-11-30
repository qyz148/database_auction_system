-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 27, 2024 at 03:17 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auction_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `Address`
--

CREATE TABLE `Address` (
  `AddressID` int(11) NOT NULL,
  `AddressStreet` varchar(20) NOT NULL,
  `City` varchar(20) NOT NULL,
  `Postcode` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `AdminLogin`
--

CREATE TABLE `AdminLogin` (
  `AdminID` int(11) NOT NULL,
  `AdminPassword` varchar(20) NOT NULL,
  `AdminUsername` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Auction`
--

CREATE TABLE `Auction` (
  `AuctionID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `DateOfPurchase` date DEFAULT NULL,
  `PurchasePrice` varchar(20) DEFAULT NULL,
  `AuctionStatus` varchar(20) DEFAULT NULL,
  `AuctionStartingTime` datetime DEFAULT NULL,
  `ReservePrice` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Bid`
--

CREATE TABLE `Bid` (
  `BidID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ItemID` int(11) DEFAULT NULL,
  `BidAmount` varchar(20) DEFAULT NULL,
  `TimeOfBid` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
  `CategoryID` int(11) NOT NULL,
  `ItemCategoryName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Commission`
--

CREATE TABLE `Commission` (
  `CommissionID` int(11) NOT NULL,
  `AuctionID` int(11) NOT NULL,
  `PurchasePrice` varchar(20) DEFAULT NULL,
  `CommissionFee` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Inbox`
--

CREATE TABLE `Inbox` (
  `InboxID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `MessageContent` text NOT NULL,
  `MessageType` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Item`
--

CREATE TABLE `Item` (
  `ItemID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
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
-- Table structure for table `Review`
--

CREATE TABLE `Review` (
  `ReviewID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `AuctionID` int(11) DEFAULT NULL,
  `UserRating` varchar(20) DEFAULT NULL,
  `ReviewDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `UserPersonalInformation`
--

CREATE TABLE `UserPersonalInformation` (
  `UserID` int(11) NOT NULL,
  `AdminID` int(11) DEFAULT NULL,
  `AddressID` int(11) DEFAULT NULL,
  `FirstName` varchar(20) NOT NULL,
  `LastName` varchar(20) NOT NULL,
  `UserEmail` varchar(20) NOT NULL,
  `UserPassword` varchar(20) NOT NULL,
  `UserRating` int(11) DEFAULT NULL,
  `TotalSalesAmount` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `WatchList`
--

CREATE TABLE `WatchList` (
  `UserID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `WatchListDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Address`
--
ALTER TABLE `Address`
  ADD PRIMARY KEY (`AddressID`);

--
-- Indexes for table `AdminLogin`
--
ALTER TABLE `AdminLogin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `Auction`
--
ALTER TABLE `Auction`
  ADD PRIMARY KEY (`AuctionID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Indexes for table `Bid`
--
ALTER TABLE `Bid`
  ADD PRIMARY KEY (`BidID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `Commission`
--
ALTER TABLE `Commission`
  ADD PRIMARY KEY (`CommissionID`),
  ADD KEY `AuctionID` (`AuctionID`);

--
-- Indexes for table `Inbox`
--
ALTER TABLE `Inbox`
  ADD PRIMARY KEY (`InboxID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `Item`
--
ALTER TABLE `Item`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `Review`
--
ALTER TABLE `Review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `AuctionID` (`AuctionID`);

--
-- Indexes for table `UserPersonalInformation`
--
ALTER TABLE `UserPersonalInformation`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `AdminID` (`AdminID`),
  ADD KEY `AddressID` (`AddressID`);

--
-- Indexes for table `WatchList`
--
ALTER TABLE `WatchList`
  ADD PRIMARY KEY (`UserID`,`ItemID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Auction`
--
ALTER TABLE `Auction`
  ADD CONSTRAINT `auction_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `UserPersonalInformation` (`UserID`),
  ADD CONSTRAINT `auction_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `Item` (`ItemID`);

--
-- Constraints for table `Bid`
--
ALTER TABLE `Bid`
  ADD CONSTRAINT `bid_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `UserPersonalInformation` (`UserID`),
  ADD CONSTRAINT `bid_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `Item` (`ItemID`);

--
-- Constraints for table `Commission`
--
ALTER TABLE `Commission`
  ADD CONSTRAINT `commission_ibfk_1` FOREIGN KEY (`AuctionID`) REFERENCES `Auction` (`AuctionID`);

--
-- Constraints for table `Inbox`
--
ALTER TABLE `Inbox`
  ADD CONSTRAINT `inbox_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `UserPersonalInformation` (`UserID`);

--
-- Constraints for table `Item`
--
ALTER TABLE `Item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `UserPersonalInformation` (`UserID`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `Category` (`CategoryID`);

--
-- Constraints for table `Review`
--
ALTER TABLE `Review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `UserPersonalInformation` (`UserID`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`AuctionID`) REFERENCES `Auction` (`AuctionID`);

--
-- Constraints for table `UserPersonalInformation`
--
ALTER TABLE `UserPersonalInformation`
  ADD CONSTRAINT `userpersonalinformation_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `AdminLogin` (`AdminID`),
  ADD CONSTRAINT `userpersonalinformation_ibfk_2` FOREIGN KEY (`AddressID`) REFERENCES `Address` (`AddressID`);

--
-- Constraints for table `WatchList`
--
ALTER TABLE `WatchList`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `UserPersonalInformation` (`UserID`),
  ADD CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `Item` (`ItemID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
