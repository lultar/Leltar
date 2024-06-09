-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: localhost
-- Létrehozás ideje: 2024. Máj 13. 11:56
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `leltar_db`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Aisles`
--

CREATE TABLE `Aisles` (
  `AisleID` int(11) NOT NULL,
  `AisleName` varchar(100) NOT NULL,
  `BuildingID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Buildings`
--

CREATE TABLE `Buildings` (
  `BuildingID` int(11) NOT NULL,
  `BuildingName` varchar(100) NOT NULL,
  `Location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Items`
--

CREATE TABLE `Items` (
  `ItemID` int(11) NOT NULL,
  `ItemName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Quantity` decimal(10,2) DEFAULT NULL,
  `RealQuantity` decimal(10,2) DEFAULT NULL,
  `MeasurementTypeID` int(11) DEFAULT NULL,
  `ShelfID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `MeasurementTypes`
--

CREATE TABLE `MeasurementTypes` (
  `MeasurementTypeID` int(11) NOT NULL,
  `MeasurementType` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Shelves`
--

CREATE TABLE `Shelves` (
  `ShelfID` int(11) NOT NULL,
  `ShelfName` varchar(100) NOT NULL,
  `AisleID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `Users`
--

CREATE TABLE `Users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `UserType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `Aisles`
--
ALTER TABLE `Aisles`
  ADD PRIMARY KEY (`AisleID`),
  ADD KEY `BuildingID` (`BuildingID`);

--
-- A tábla indexei `Buildings`
--
ALTER TABLE `Buildings`
  ADD PRIMARY KEY (`BuildingID`);

--
-- A tábla indexei `Items`
--
ALTER TABLE `Items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `MeasurementTypeID` (`MeasurementTypeID`),
  ADD KEY `ShelfID` (`ShelfID`);

--
-- A tábla indexei `MeasurementTypes`
--
ALTER TABLE `MeasurementTypes`
  ADD PRIMARY KEY (`MeasurementTypeID`);

--
-- A tábla indexei `Shelves`
--
ALTER TABLE `Shelves`
  ADD PRIMARY KEY (`ShelfID`),
  ADD KEY `AisleID` (`AisleID`);

--
-- A tábla indexei `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `Aisles`
--
ALTER TABLE `Aisles`
  ADD CONSTRAINT `Aisles_ibfk_1` FOREIGN KEY (`BuildingID`) REFERENCES `Buildings` (`BuildingID`);

--
-- Megkötések a táblához `Items`
--
ALTER TABLE `Items`
  ADD CONSTRAINT `Items_ibfk_1` FOREIGN KEY (`MeasurementTypeID`) REFERENCES `MeasurementTypes` (`MeasurementTypeID`),
  ADD CONSTRAINT `Items_ibfk_2` FOREIGN KEY (`ShelfID`) REFERENCES `Shelves` (`ShelfID`);

--
-- Megkötések a táblához `Shelves`
--
ALTER TABLE `Shelves`
  ADD CONSTRAINT `Shelves_ibfk_1` FOREIGN KEY (`AisleID`) REFERENCES `Aisles` (`AisleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
