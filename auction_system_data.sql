INSERT INTO `auction` (`AuctionID`, `UserID`, `ItemID`, `DateOfPurchase`, `PurchasePrice`, `AuctionStatus`, `AuctionStartingTime`, `ReservePrice`) VALUES
('263f1fde73', 'U0000004', '766c5834f0', NULL, '11', 'Active', '2024-12-01 23:54:55', '15'),
('3f42dfc82b', 'U0000005', '8000be9288', NULL, '222', 'Completed', '2024-12-01 23:56:50', '111'),
('e9fc437610', 'U0000004', '731afa857b', NULL, '10', 'Active', '2024-12-01 23:55:48', '20');

INSERT INTO `bid` (`BidID`, `UserID`, `ItemID`, `BidAmount`, `TimeOfBid`) VALUES
('57e4627645', 'U0000001', '766c5834f0', '50', '2024-12-01 23:57:34'),
('f923314241', 'U0000002', '8000be9288', '222', '2024-12-01 23:58:16'),
('fcbeec254d', 'U0000002', '766c5834f0', '20000', '2024-12-01 23:58:38');

INSERT INTO `inbox` (`InboxID`, `UserID`, `MessageContent`, `MessageType`) VALUES
('3df49669a1', 'U0000002', 'Congratulations! You won the auction for item 8000be9288 at £222.', 'Auction Win'),
('920fd2f8e6', 'U0000005', 'Your auction for item 8000be9288 has ended with a final price of £222.', 'Auction End');

INSERT INTO `item` (`ItemID`, `UserID`, `CategoryID`, `ItemName`, `ItemDescription`, `RemainingTime`, `StartingPrice`, `ClosingDate`, `CurrentBid`, `MinimumBid`, `ItemPicture`) VALUES
('731afa857b', 'U0000004', 'item_2', 'lse', 'dfjkaljfklajf\r\nadfdfadf\r\nasdfadfadsf', NULL, '10', '2024-12-02 00:10:00', '10', NULL, 'images/img_674cf784b2ae15.95962453.jpg'),
('766c5834f0', 'U0000004', 'item_1', 'ucl', 'adjfkajflakdjfladjkflajkdlsjafklaj\r\nkjaldfjklajfkla;skj\r\nadskfj;lkj', NULL, '11', '2024-12-02 00:54:00', '20000', NULL, 'images/img_674cf74fee8631.58216233.jpg'),
('8000be9288', 'U0000005', 'item_3', 'kcl', 'aaa\r\naaa\r\naaa', NULL, '111', '2024-12-02 00:01:00', '222', NULL, NULL);

INSERT INTO `userpersonalinformation` (`UserID`, `AdminID`, `AddressID`, `FirstName`, `LastName`, `UserEmail`, `UserPassword`, `UserRating`, `TotalSalesAmount`, `AccountType`) VALUES
('U0000001', NULL, NULL, 'buyer1', 'buyer1', 'buyer1@buyer.com', '1', NULL, NULL, 'buyer'),
('U0000002', NULL, NULL, 'buyer2', 'buyer2', 'buyer2@buyer.com', '1', NULL, NULL, 'buyer'),
('U0000003', NULL, NULL, 'buyer3', 'buyer3', 'buyer3@buyer.com', '1', NULL, NULL, 'buyer'),
('U0000004', NULL, NULL, 'seller1', 'seller1', 'seller1@seller.com', '1', NULL, NULL, 'seller'),
('U0000005', NULL, NULL, 'seller2', 'seller2', 'seller2@seller.com', '1', NULL, NULL, 'seller');
