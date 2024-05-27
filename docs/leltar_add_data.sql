-- Insert data into Buildings
INSERT INTO `Buildings` (`BuildingID`, `BuildingName`, `Location`) VALUES
(1, 'Main Warehouse', '123 Warehouse Lane'),
(2, 'Secondary Storage', '456 Storage Drive');

-- Insert data into Aisles
INSERT INTO `Aisles` (`AisleID`, `AisleName`, `BuildingID`) VALUES
(1, 'Aisle 1', 1),
(2, 'Aisle 2', 1),
(3, 'Aisle 3', 2);

-- Insert data into Shelves
INSERT INTO `Shelves` (`ShelfID`, `ShelfName`, `AisleID`) VALUES
(1, 'Shelf 1', 1),
(2, 'Shelf 2', 1),
(3, 'Shelf 3', 2),
(4, 'Shelf 4', 3);

-- Insert data into MeasurementTypes
INSERT INTO `MeasurementTypes` (`MeasurementTypeID`, `MeasurementType`) VALUES
(1, 'Kilograms'),
(2, 'Liters'),
(3, 'Pieces');

-- Insert data into Items
INSERT INTO `Items` (`ItemID`, `ItemName`, `Description`, `Quantity`, `RealQuantity`, `MeasurementTypeID`, `ShelfID`) VALUES
(1, 'Item A', 'Description for Item A', 100.00, 98.50, 1, 1),
(2, 'Item B', 'Description for Item B', 50.00, 49.00, 2, 2),
(3, 'Item C', 'Description for Item C', 200.00, 198.00, 3, 3),
(4, 'Item D', 'Description for Item D', 75.00, 74.00, 1, 4);

-- Insert data into Users
INSERT INTO `Users` (`UserID`, `Username`, `Password`, `UserType`) VALUES
(1, 'admin', 'password_hash', 1),
(2, 'user1', 'password_hash', 2);