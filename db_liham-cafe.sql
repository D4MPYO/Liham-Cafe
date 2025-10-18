-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2024 at 01:47 PM
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
-- Database: `db_liham-cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_ons`
--

CREATE TABLE `add_ons` (
  `id` int(11) NOT NULL,
  `menu_item_id` int(11) DEFAULT NULL,
  `addon_name` varchar(255) NOT NULL,
  `addon_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_ons`
--

INSERT INTO `add_ons` (`id`, `menu_item_id`, `addon_name`, `addon_price`) VALUES
(6, 1, '', 0.00),
(7, 1, '', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `upload_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `image_name`, `description`, `image_path`, `upload_time`) VALUES
(57, '1', NULL, '../uploads/67643318cc49b4.75144007.jpg', '2024-12-19 22:52:08'),
(58, '2', NULL, '../uploads/676433293d68c9.12571717.jpg', '2024-12-19 22:52:25'),
(59, '3', NULL, '../uploads/67643334dae881.59036648.jpg', '2024-12-19 22:52:36'),
(60, '4', NULL, '../uploads/6764333d308a99.00099081.jpg', '2024-12-19 22:52:45'),
(61, '5', NULL, '../uploads/67643346355f46.76234507.jpg', '2024-12-19 22:52:54'),
(62, '6', NULL, '../uploads/6764335418d5f4.76530809.jpg', '2024-12-19 22:53:08'),
(63, '7', NULL, '../uploads/676433606c3b95.74167757.jpg', '2024-12-19 22:53:20'),
(64, '8', NULL, '../uploads/6764336fb012b8.20845317.jpg', '2024-12-19 22:53:35'),
(65, '9', NULL, '../uploads/6764337ce911d5.95542876.jpg', '2024-12-19 22:53:48'),
(66, '10', NULL, '../uploads/6764338a6fdc98.66383380.jpg', '2024-12-19 22:54:02'),
(67, '11', NULL, '../uploads/676433970c6b56.96834251.jpg', '2024-12-19 22:54:15'),
(68, '15', NULL, '../uploads/676433b184fcd4.93244136.jpg', '2024-12-19 22:54:41'),
(69, '7-\'TEEN', NULL, '../uploads/676433f30197d1.56491949.jpg', '2024-12-19 22:55:47'),
(70, '18', NULL, '../uploads/6764340558ed01.38759306.jpg', '2024-12-19 22:56:05'),
(71, '19', NULL, '../uploads/676434113f8912.06024929.jpg', '2024-12-19 22:56:17'),
(72, 'LAST3', NULL, '../uploads/6764347e165fd1.56303114.jpg', '2024-12-19 22:58:06'),
(73, 'last\'2', NULL, '../uploads/6764349a8e8992.56916706.jpg', '2024-12-19 22:58:34'),
(74, 'last1', NULL, '../uploads/676434a56d7a68.41653897.jpg', '2024-12-19 22:58:45');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `best_selling` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `category`, `image`, `created_at`, `best_selling`) VALUES
(1, 'Lumpiasilog', 'Served with rice and egg', 70.00, 'Rice Meals', '/user/uploads/67643538140055.04720425.png', '2024-12-01 13:57:35', 0),
(2, 'Hotsilog', 'Served with rice and egg', 70.00, 'Rice Meals', '/user/uploads/6757259abc2f38.97759595.png', '2024-12-01 13:57:35', 0),
(3, 'LiempoSilog', 'Served with rice and egg', 100.00, 'Rice Meals', '/user/uploads/675725bda03ba3.57277042.png', '2024-12-01 13:57:35', 0),
(4, 'Longsilog', 'Served with rice and egg', 100.00, 'Rice Meals', '/user/uploads/675725d0800604.77190478.png', '2024-12-01 13:57:35', 0),
(5, 'Chicksilog', 'Served with rice and egg', 100.00, 'Rice Meals', '/user/uploads/675725e2083173.12941828.png', '2024-12-01 13:57:35', 0),
(6, 'Chocolate Cake Slice', 'Delicious and moist chocolate cake', 120.00, 'Dessert/Snack', '/user/uploads/6757dea7c7e565.10986693.jpg', '2024-12-01 13:57:35', 0),
(7, 'Pink Gelatin', 'Sweet and creamy pink gelatin', 25.00, 'Dessert/Snack', '/user/uploads/6757bed5a36e15.90548989.png', '2024-12-01 13:57:35', 0),
(8, 'French Fries (Regular)', 'French fries with sour cream or BBQ', 60.00, 'Dessert/Snack', '/user/uploads/6757bf604d9131.58726417.png', '2024-12-01 13:57:35', 0),
(10, 'Cheese Sticks (6 pcs)', 'Crispy cheese sticks', 60.00, 'Dessert/Snack', '/user/uploads/6757e36b814896.97159559.jpg', '2024-12-01 13:57:35', 0),
(40, 'Passion Fruit (22oz)', 'Larger size passion fruit tea', 90.00, 'Fruit Tea', '/user/uploads/67579fe41bf552.22308837.jpg', '2024-12-01 14:04:56', 0),
(42, 'Lychee (22oz)', 'Larger size lychee fruit tea', 90.00, 'Fruit Tea', '/user/uploads/6757a0049e4412.00445830.jpg', '2024-12-01 14:04:56', 0),
(44, 'Green Apple (22oz)', 'Larger size green apple fruit tea', 90.00, 'Fruit Tea', '/user/uploads/6757a01b397c49.72226194.jpg', '2024-12-01 14:04:56', 0),
(46, 'Strawberry (22oz)', 'Larger size strawberry fruit tea', 90.00, 'Fruit Tea', '/user/uploads/6757a03ef186a6.38853421.jpg', '2024-12-01 14:04:56', 0),
(48, 'Wintermelon (22oz)', 'Larger size wintermelon fruit tea', 90.00, 'Fruit Tea', '/user/uploads/6757d68a292024.98325223.jpg', '2024-12-01 14:04:56', 0),
(49, 'Strawberry Milk (Hot)', 'Hot strawberry milk', 90.00, 'Non-Coffee', '/user/uploads/6757ffd9ac8c63.21888199.jpg', '2024-12-01 14:06:34', 0),
(50, 'Strawberry Milk (Iced)', 'Iced strawberry milk', 100.00, 'Non-Coffee', '/user/uploads/67579f236d2a27.78514519.jpg', '2024-12-01 14:06:34', 0),
(51, 'Chocolate Milk (Hot)', 'Hot chocolate milk', 90.00, 'Non-Coffee', '/user/uploads/6758000b0b21f4.60629534.jpg', '2024-12-01 14:06:34', 0),
(52, 'Chocolate Milk (Iced)', 'Iced chocolate milk', 100.00, 'Non-Coffee', '/user/uploads/67579f3b2def14.58338716.jpg', '2024-12-01 14:06:34', 0),
(53, 'Matcha Milk (Hot)', 'Hot matcha milk', 120.00, 'Non-Coffee', '/user/uploads/6757fffa22aec8.18661364.jpg', '2024-12-01 14:06:34', 0),
(54, 'Matcha Milk (Iced)', 'Iced matcha milk', 130.00, 'Non-Coffee', '/user/uploads/67579f5d36d5d7.59054555.jpg', '2024-12-01 14:06:34', 0),
(56, 'Thai Milk Tea (Iced)', 'Large Thai milk tea', 90.00, 'Non-Coffee', '/user/uploads/6757e425d09b19.09000463.png', '2024-12-01 14:06:34', 0),
(57, 'Rose Cup', 'Rose-flavored tea', 120.00, 'Non-Coffee', '/user/uploads/6757a6d1ad9b59.28829077.png', '2024-12-01 14:06:34', 0),
(58, 'Passion Fruit Yakult (16oz)', 'Passion fruit-flavored Yakult drink', 80.00, 'Yakult Series', '/user/uploads/675801192f3fb2.42908016.jpg', '2024-12-01 14:07:01', 0),
(59, 'Lychee Yakult (16oz)', 'Lychee-flavored Yakult drink', 80.00, 'Yakult Series', '/user/uploads/67580131bae574.15161895.jpg', '2024-12-01 14:07:01', 0),
(60, 'Green Apple Yakult (16oz)', 'Green apple-flavored Yakult drink', 80.00, 'Yakult Series', '/user/uploads/6758014c163286.60820300.jpg', '2024-12-01 14:07:01', 0),
(61, 'Strawberry Yakult (16oz)', 'Strawberry-flavored Yakult drink', 80.00, 'Yakult Series', '/user/uploads/6758016748a794.41273815.jpg', '2024-12-01 14:07:01', 0),
(62, 'Liham Set (Pick Up)', 'Liham set without drink', 100.00, 'Liham Set', '/user/uploads/675715578e61a0.16370749.jpg', '2024-12-01 14:07:14', 0),
(63, 'Liham Set (Delivery)', 'Liham set with drink', 299.00, 'Liham Set', '/user/uploads/675715339470b2.44662249.jpg', '2024-12-01 14:07:14', 1),
(64, 'Americano (Hot)', 'Classic hot Americano coffee', 80.00, 'Espresso', '/user/uploads/675823b82614a5.83989049.png', '2024-12-01 14:08:26', 0),
(65, 'Americano (Iced)', 'Refreshing iced Americano coffee', 90.00, 'Espresso', '/user/uploads/6758181d61c358.66511045.jpg', '2024-12-01 14:08:26', 0),
(66, 'Latte (Hot)', 'Smooth and creamy hot latte', 90.00, 'Espresso', '/user/uploads/675825c09a1de9.09310981.png', '2024-12-01 14:08:26', 0),
(67, 'Latte (Iced)', 'Chilled and creamy iced latte', 100.00, 'Espresso', '/user/uploads/67581887e05b83.39845197.jpg', '2024-12-01 14:08:26', 0),
(68, 'Cappuccino (Hot)', 'Rich and foamy hot cappuccino', 90.00, 'Espresso', '/user/uploads/675826400bdf06.94941556.png', '2024-12-01 14:08:26', 0),
(69, 'Cappuccino (Iced)', 'Refreshing iced cappuccino', 100.00, 'Espresso', '/user/uploads/67580f25bbabf3.12668115.jpg', '2024-12-01 14:08:26', 0),
(70, 'Caramel Macchiato (Hot)', 'Sweet caramel macchiato served hot', 120.00, 'Espresso', '/user/uploads/6758240779deb1.26193318.png', '2024-12-01 14:08:26', 0),
(71, 'Caramel Macchiato (Iced)', 'Chilled caramel macchiato', 130.00, 'Espresso', '/user/uploads/675818662d60e0.02197543.jpg', '2024-12-01 14:08:26', 0),
(72, 'Spanish Latte (Hot)', 'Hot Spanish latte with sweetened milk', 120.00, 'Espresso', '/user/uploads/67582426d56600.77059926.png', '2024-12-01 14:08:26', 0),
(73, 'Spanish Latte (Iced)', 'Iced Spanish latte with sweetened milk', 130.00, 'Espresso', '/user/uploads/67581389a3a743.30607364.jpg', '2024-12-01 14:08:26', 0),
(74, 'Vanilla Latte (Hot)', 'Serve hot delicious vanilla-flavored latte  ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ', 120.00, 'Espresso', '/user/uploads/67582442e5b486.53515581.png', '2024-12-01 14:08:26', 0),
(75, 'Vanilla Latte (Iced)', 'Serve Iced delicious vanilla-flavored latte', 130.00, 'Espresso', '/user/uploads/67581a771ced24.79854590.jpg', '2024-12-01 14:08:26', 0),
(76, 'Hazelnut Latte (Hot)', 'Hot hazelnut-flavored latte', 120.00, 'Espresso', '/user/uploads/6758246200c7c7.19950487.png', '2024-12-01 14:08:26', 0),
(77, 'Hazelnut Latte (Iced)', 'Iced hazelnut-flavored latte', 130.00, 'Espresso', '/user/uploads/67581b3acf8c62.30174616.jpg', '2024-12-01 14:08:26', 0),
(78, 'Mocha (Hot)', 'Rich hot mocha coffee', 120.00, 'Espresso', '/user/uploads/67582484996898.32553286.png', '2024-12-01 14:08:26', 0),
(79, 'Mocha (Iced)', 'Iced mocha coffee', 130.00, 'Espresso', '/user/uploads/6758142e822860.60601504.jpg', '2024-12-01 14:08:26', 0),
(80, 'White Choco Mocha (Hot)', 'White chocolate-flavored mocha served hot', 120.00, 'Espresso', '/user/uploads/675824a8300db4.29944793.png', '2024-12-01 14:08:26', 0),
(81, 'White Choco Mocha (Iced)', 'Delicious White chocolate mocha served iced', 130.00, 'Espresso', '/user/uploads/67581b7d3da4b5.98064949.jpg', '2024-12-01 14:08:26', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `order_option` varchar(255) NOT NULL,
  `status` enum('Pending','Processing','Completed','Cancelled') DEFAULT NULL,
  `tip_amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `full_name`, `email`, `password`) VALUES
(18, 'Juan Dela Cruz', 'testing@gmail.com', 'qwerty12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_ons`
--
ALTER TABLE `add_ons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_id` (`order_id`),
  ADD KEY `fk_item_id` (`item_id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu_items_id` (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`) USING BTREE,
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_ons`
--
ALTER TABLE `add_ons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `add_ons`
--
ALTER TABLE `add_ons`
  ADD CONSTRAINT `add_ons_ibfk_1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_item_id` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
