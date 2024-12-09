-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 10:49 PM
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
-- Database: `team35`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `CartID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `CartID` bigint(20) UNSIGNED NOT NULL,
  `ProductID` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL,
  `orderDate` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `OrderID` bigint(20) UNSIGNED NOT NULL,
  `ProductID` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` bigint(20) UNSIGNED NOT NULL,
  `OrderID` bigint(20) UNSIGNED NOT NULL,
  `cardDetails` varchar(255) NOT NULL,
  `paymentStatus` varchar(255) NOT NULL,
  `paymentDate` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` bigint(20) UNSIGNED NOT NULL,
  `productName` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `productName`, `description`, `stock`, `category`, `created_at`, `updated_at`, `price`) VALUES
(1, 'Cardio Routine', '10-30 min routines to boost endurance safely.', 100, 'Workout', NULL, NULL, 19.99),
(2, 'Strength Routine', 'Build muscle and strength without injury.', 100, 'Workout', NULL, NULL, 24.99),
(3, 'Flexibility Routine', 'Improve flexibility and mobility with safe techniques.', 100, 'Workout', NULL, NULL, 14.99),
(4, 'Creatine', 'Enhances strength and power. Ideal for muscle growth.', 100, 'Supplement', NULL, NULL, 29.99),
(5, 'Whey Protein', 'Rapid absorption for post-workout recovery.', 100, 'Supplement', NULL, NULL, 34.99),
(6, 'Plant-Based Protein', 'Vegan-friendly protein for muscle growth and repair.', 100, 'Supplement', NULL, NULL, 26.99),
(7, 'Protein Bar', 'Best bar on the market', 100, 'Supplement', NULL, NULL, 27.99),
(8, 'BCAA', 'BCAA is the best supplement when it comes to recovery', 100, 'Supplement', NULL, NULL, 23.99),
(9, 'Body Building Routine', 'Gain Muscle ', 100, 'Workout ', NULL, NULL, 129.99),
(10, 'MultiVitamins', 'We’ve packed seven essential vitamins including vitamins A, C, D3, E, as well as thiamine, riboflavin, and niacin into one super-convenient tablet', 100, 'Vitamins', NULL, NULL, 12.99),
(11, 'Amino Acid Tablets', 'Glutamine is an amino acid that the body is only able to produce in small amounts, so the majority of it must come through your diet — which can be expensive and time consuming.\r\n\r\nPerfect for on-the-go, our L-Glutamine tablets offer you an easy way to get the balance of amino acids you need, whatever your fitness goals.', 100, 'Vitamins', NULL, NULL, 42.99),
(12, 'HMB Tablets', 'Beta-Hydroxy Beta-Methylbutyrate, or more commonly known by its abbreviation HMB, is a metabolite of the branched-chain amino acid leucine. Growing in popularity since the 1990s, HMB is now widely used in many ‘all-in-one’ products — helping to support your fitness goals.', 100, 'Vitamins', NULL, NULL, 32.99),
(13, 'L-Carnitine Tablets', 'L-Carnitine is an amino acid that is found naturally in the body, and is formed from the amino acids lysine and methionine. Our high-strength l-carnitine tablets deliver 1000mg per serving, for a super-convenient and powerful boost to your amino acid intake.\r\n\r\nPlus, they’re suitable for vegetarians and vegans too — making them the perfect amino acid supplement for anyone following a plant-based diet.\r\n\r\nWhatever your fitness goal, these l-carnitine tablets have been formulated to help even your toughest workouts, as well as support a balanced diet.', 100, 'Vitamins', NULL, NULL, 14.99),
(14, 'Liquid L-Carnitine Capsules', 'L-carnitine is created in the body from the amino acid lysine and methionine, and our capsules are perfect for boosting your daily intake – through a unique liquid delivery system.\r\n\r\nWhat is l-carnitine?\r\nL-carnitine is an amino acid which is naturally produced in the body. It can also be found in food sources – red meat has the highest levels, but chicken, milk and dairy products also contain l-carnitine.', 100, 'Vitamins', NULL, NULL, 23.99),
(15, 'Origin Pre-Workout Stim-Free (Alpha)', 'Experience the power of Origin Stim-Free Pre-Workout. A hard-hitting no-nonsense formula, packed with optimal doses of research-backed ingredients, and zero caffeine.\r\n\r\nWe’ve refined our powerful pre-workout blend by removing caffeine to create a fully charged, stimulant-free solution, designed for you to make the most out of every session. Cut the caffeine, ignite your workout. ', 100, 'PreWorkout', NULL, NULL, 18.99),
(16, 'Pure Caffeine Tablets', 'Caffeine is the most widely used natural stimulant in the world — commonly found in tea, coffee, soft drinks, and cold & flu remedies.\r\n\r\nWhat is Pure Caffeine?\r\nA popular pre-workout ingredient, our Pure Caffeine tablets deliver a boost of caffeine — to power you through even the toughest of workouts.1\r\n\r\nCaffeine helps to improve endurance performance and capacity,1,2 so you can get the most from every session and push past your limits.', 100, 'PreWorkout', NULL, NULL, 12.99),
(17, 'Citrulline Malate Powder', 'Citrulline Malate is a unique combination of the amino acid citrulline and the organic salt malate. It\'s ideal for people involved in high-intensity exercise such as weightlifting and sprinting.', 100, 'PreWorkout', NULL, NULL, 14.99),
(18, 'THE Pre-Thermo', 'THE Pre-Thermo is our powerful pre-workout thermogenic blend, delivering a boost1 exactly when you need it. Designed for the dedicated, this will ignite your workouts to new heights.\r\n\r\nCreated using an explosive blend of caffeine, l-citrulline, and beta-alanine, alongside the thermogenic ingredients CaloriBurn, l-carnitine and choline bitartrate2 – to deliver the ultimate boost1 when you need it most.', 100, 'PreWorkout', NULL, NULL, 24.99),
(19, 'THE Pre-Workout Gel', 'THE Pre-Workout Gel. Designed for the dedicated and backed by experts, it’s time to prepare for the challenge.\r\n\r\nBoasting 230mg of caffeine per gel, with 150mg of that coming from natural guarana extract, this pre-emptive formulation sharpens your focus and delivers an endurance boost right when you need it1,2.\r\n\r\nWe’ve packed in expert backed ingredients including 1.5g citrulline malate, 1.5g beta-alanine and 1g taurine, as well as additional essential B vitamins which will help reduce tiredness and fatigue ahead of your workout3.', 100, 'PreWorkout', NULL, NULL, 6.99),
(20, 'Protein Granola', 'Great for breakfast or as a post-workout snack, Protein Granola is full to the brim with soy and milk protein, which helps to grow and maintain important muscle.1 Whatever your fitness ambitions, it’s a tasty protein boost whenever you need it most.', 100, 'ProteinFoods', NULL, NULL, 12.99),
(21, 'Protein Brownie', 'Get ready to experience a brownie like no other. Baked with a mighty 23g of protein, and sweet chocolate chunks. It\'s a protein-packed delight that will leave you craving more.\r\n\r\nSeriously satisfying, this pocket-sized protein snack helps build and support muscle1 on the go with ease.', 100, 'ProteinFoods', NULL, NULL, 15.99),
(22, 'Oat Bakes', 'he soft and chewy protein treat, our Oatbakes are perfect for satisfying sweet-tooth cravings — with protein supporting your workout goals1 and carbs to help you recover session after session.2\r\n\r\nGrab yours in delicious Chocolate Chip or White Chocolate and Berry flavours.', 100, 'ProteinFoods', NULL, NULL, 7.99),
(23, 'Breakfast Smoothie', 'Looking for a sweet and simple breakfast to set you up well for the day? Well, our high-protein Breakfast Smoothie will instantly have you on track to hitting your daily macros.\r\n\r\nOur Breakfast Smooth is a delicious blend of protein, carbs and fats, made with added real fruits – delivering everything you need, to get your day off to a strong start.\r\n\r\nDesigned to support weight management, we’ve added LeanBiome® a scientifically supported blend of dietary fibres.', 100, 'ProteinFoods', NULL, NULL, 14.99),
(24, 'Vegan Protein Pancake Mix', 'Make everyday Pancake Day with a quick and easy Protein Pancake Mix. Made in a matter of minutes, skip the hassle of measuring ingredients and perfecting your batter consistency, with light and fluffy American style pancakes every time. \r\n\r\nEach freshly flipped stack is packed with 10g of protein and low in sugar, helping your body stay topped up on protein all day.', 100, 'ProteinFoods', NULL, NULL, 23.99),
(25, 'Calisthenics Routine', 'A calisthenics workout involves a series of bodyweight exercises that use your own body weight as resistance', 100, 'Workout', NULL, NULL, 32.99);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `usertype` varchar(255) NOT NULL DEFAULT 'user',
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `email`, `usertype`, `name`, `created_at`, `updated_at`) VALUES
(1, 'keval', '$2y$10$xqRzZ/XlnwuF49GLjT.Bk.7YKasXnuq7Y1N.uelVf9vMWObidyalG', 'keval@gmail.com', 'user', 'Keval Thanki', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `carts_userid_foreign` (`userID`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`CartID`,`ProductID`),
  ADD KEY `cart_items_productid_foreign` (`ProductID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `orders_userid_foreign` (`userID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`OrderID`,`ProductID`),
  ADD KEY `order_items_productid_foreign` (`ProductID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `payments_orderid_foreign` (`OrderID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `CartID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cartid_foreign` FOREIGN KEY (`CartID`) REFERENCES `carts` (`CartID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_productid_foreign` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_orderid_foreign` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_productid_foreign` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_orderid_foreign` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
