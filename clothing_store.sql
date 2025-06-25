-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 07:29 PM
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
-- Database: `clothing_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `session_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Men'),
(2, 'Women'),
(3, 'Kids'),
(4, 'Accessories');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `username`, `address`, `phone`, `total`, `order_date`) VALUES
(16, 0, 'سير', 'سير', 'يرس', 200.00, '2025-06-07 21:41:47'),
(17, 0, 'سٍ]}ئ', 'ئءؤ', 'ؤءئ', 400.00, '2025-06-07 21:42:18'),
(18, 0, 'قلي', 'بلي', 'بل', 100.00, '2025-06-08 17:11:46'),
(19, 0, 'wfe', 'efef', 'fef', 100.00, '2025-06-08 17:14:55'),
(20, 0, 'wef', 'ef', 'ef', 100.00, '2025-06-08 18:40:39'),
(21, 0, 'sd', 'sdc', 'sd', 100.00, '2025-06-08 19:18:07'),
(22, 0, 'wf', 'wf', 'wf', 200.00, '2025-06-08 19:19:35'),
(23, 0, 'بيل', 'بيل', 'بيل', 200.00, '2025-06-08 19:28:10'),
(24, 0, 'يبلا', 'يبلاي', 'يبلا', 249.95, '2025-06-08 20:13:21'),
(25, 0, 'رايه', 'فرنسا', '0595665691', 100.00, '2025-06-09 00:11:03'),
(26, 0, 'sofyan', 'istanbul', '05316382373', 100.00, '2025-06-09 17:09:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `size`, `quantity`, `price`) VALUES
(1, 23, 4, 'XS', 2, 100.00),
(2, 24, 1, 'S', 5, 49.99),
(3, 25, 4, 'S', 1, 100.00),
(4, 26, 19, 'XXL', 1, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `stock_xs` int(11) NOT NULL DEFAULT 0,
  `stock_s` int(11) NOT NULL DEFAULT 0,
  `stock_m` int(11) NOT NULL DEFAULT 0,
  `stock_l` int(11) NOT NULL DEFAULT 0,
  `stock_xl` int(11) NOT NULL DEFAULT 0,
  `stock_xxl` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category_id`, `stock_xs`, `stock_s`, `stock_m`, `stock_l`, `stock_xl`, `stock_xxl`) VALUES
(4, 'hoodie', 100, 'hoodie good', 'hoodie.JPG.jpg', 1, 0, 0, 0, 0, 0, 0),
(5, 'Layered Black Top with White Shirt Underneath', 55, 'A stylish black blouse layered over a crisp white shirt, creating a trendy, preppy contrast – two pieces combined into one modern look.', 'W1.jpg', 2, 0, 0, 0, 0, 0, 0),
(6, 'Wide Brown Pants', 30, 'Loose-fit brown trousers with a high waist and flowy leg design, offering both comfort and style for everyday outfits.', 'W2.jpg', 2, 0, 0, 0, 0, 0, 0),
(7, 'Fitted Black Dress ', 70, 'A figure-hugging black dress with a defined waist, crafted from stretchy fabric to flatter the body shape, suitable for both formal and casual settings.', 'W3.jpg', 2, 0, 0, 0, 0, 0, 0),
(8, 'Black & White Bodysuits', 25, 'Two form-fitting bodysuits – one in black and one in white – made of soft stretch material, perfect for layering or wearing as sleek standalone tops.', 'W4.jpg', 2, 0, 0, 0, 0, 0, 0),
(9, 'Black One-Piece Tracksuit', 85, 'A full-body black tracksuit made of stretchy, smooth fabric, featuring a front zipper and a fitted cut for an athletic-chic look.', 'W5.jpg', 2, 0, 0, 0, 0, 0, 0),
(10, 'Light Yellow Button-Up Shirt ', 24, 'A cool-toned light yellow button-up shirt designed with a loose, airy silhouette. Made from soft, breathable fabric, it features a classic collar, front buttons, and dropped shoulders—perfect for a relaxed, feminine everyday look.', 'W6.jpg', 2, 0, 0, 0, 0, 0, 0),
(11, 'Fitted Blue Shirt with White Stripes', 35, 'A tailored blue shirt featuring thin white vertical stripes, designed with a cinched waist to highlight the silhouette. Made from a light, slightly stretchy fabric, it balances structure and comfort—ideal for smart-casual outfits.', 'W7.jpg', 2, 0, 0, 0, 0, 0, 0),
(12, 'Soft White Fitted Dress', 60, 'A delicate white dress with a fitted waist, crafted from lightweight, flowy fabric that drapes beautifully over the body. Its minimalist design and soft texture make it perfect for warm days or elegant casual outings.', 'W8.jpg', 2, 0, 0, 0, 0, 0, 0),
(13, 'White High-Low Evening Skirt', 40, 'A stylish white evening skirt featuring a high-low hem—short in the front and elegantly long in the back. Made from light, flowing fabric with a subtle sheen, it’s perfect for formal events or special occasions with a modern twist.', 'W9.jpg', 2, 0, 0, 0, 0, 0, 0),
(14, 'Beige Formal Set – Skirt and Blouse', 115, 'A sophisticated two-piece beige outfit featuring a tailored knee-length skirt and a matching formal blouse. The blouse has a structured collar and subtle pleats, while the skirt is sleek and fitted for an elegant, polished look—perfect for the office or formal occasions.', 'W10.jpg', 2, 0, 0, 0, 0, 0, 0),
(15, 'Men’s Black Top with Beige Collar', 15, 'A sleek black men’s top featuring a contrasting beige collar for a subtle yet stylish touch. Designed with a slim fit and made from soft, breathable fabric, it blends casual comfort with a refined edge—perfect for smart-casual looks.', 'M1.jpg', 1, 0, 0, 0, 0, 0, 0),
(16, 'Men’s Light Green Collared Top', 15, 'A light green men’s top featuring a classic collar design, offering a fresh and clean look. Made from soft, breathable fabric, it has a relaxed fit that works well for spring and summer outfits—both casual and semi-formal.', 'M2.jpg', 1, 0, 0, 0, 0, 0, 0),
(17, 'Men’s Light Wash Boyfriend Jeans', 30, 'A pair of light-wash denim jeans designed with a relaxed boyfriend fit. Featuring a slightly loose silhouette, mid-rise waist, and classic five-pocket styling, these jeans offer both comfort and a laid-back, trendy vibe.', 'M3.jpg', 1, 0, 0, 0, 0, 0, 0),
(18, 'White Top and Wide Beige Pants Set', 80, 'A stylish set featuring a crisp white top paired with wide-leg beige pants. The top is made from breathable, lightweight fabric with a clean, tailored look, while the beige pants offer a loose, flowing fit for maximum comfort and a modern, relaxed vibe.', 'M4.jpg', 1, 0, 0, 0, 0, 0, 0),
(19, 'Formal Set – White Pants and Dark Green Shirt', 100, 'A sharp formal outfit featuring crisp white trousers paired with a dark green shirt. The pants are tailored with a slim fit and clean lines, while the shirt has a structured collar and button-down front, creating a sophisticated and polished look perfect for formal events or office wear.', 'M5.jpg', 1, 0, 0, 0, 0, 0, 0),
(20, 'Black and White Sporty Tops (Loose Fit)', 50, 'Two sporty tops, one black and one white, made from breathable, moisture-wicking fabric. Both feature a crew neckline and a loose, relaxed fit that allows free movement—perfect for workouts or casual athleisure styles.', 'M6.jpg', 1, 0, 0, 0, 0, 0, 0),
(21, 'Black Shorts with White Drawstring', 15, 'A comfortable pair of black shorts featuring a contrasting white drawstring at the waist. Made from soft, breathable fabric with a relaxed fit, perfect for lounging, workouts', 'M7.jpg', 1, 0, 0, 0, 0, 0, 0),
(22, 'Black Jacket with Beige Faux Fur Lining and Buttons', 70, 'A stylish black jacket lined with soft beige faux fur for extra warmth and comfort. It features a front button closure, side pockets, and a high collar. The beige fur adds a cozy contrast, making it perfect for chilly days with a touch of elegance.', 'M8.jpg', 1, 0, 0, 0, 0, 0, 0),
(23, 'Black Leather Jacket', 60, 'A classic black leather jacket with a sleek finish and structured cut. Features a front zip closure, side pockets, and a sharp collar design. Made from smooth faux leather, it adds a bold, timeless edge to any outfit—perfect for casual or edgy looks.', 'M9.jpg', 1, 0, 0, 0, 0, 0, 0),
(24, 'White Formal Trousers', 35, 'A pair of tailored white formal trousers made from smooth, structured fabric. Designed with a flat front, straight legs, and a clean waistband for a polished and sophisticated look—ideal for office wear, events, or sharp semi-formal outfits.', 'M10.jpg', 1, 0, 0, 0, 0, 0, 0),
(25, 'two-piece girls’ outfit', 20, 'A charming two-piece girls’ outfit featuring a black skirt paired with a crisp white blouse decorated with small black bows.', 'K1.jpg', 3, 0, 0, 0, 0, 0, 0),
(26, 'white pants & brown top', 25, 'A stylish girls’ outfit featuring crisp white pants paired with a fitted brown sleeveless top', 'K2.jpg', 3, 0, 0, 0, 0, 0, 0),
(27, 'blue girls’ set ', 35, 'A trendy all-blue girls’ set featuring flowy wide-leg pants and a matching sleeveless top.', 'K3.jpg', 3, 0, 0, 0, 0, 0, 0),
(28, 'white pajama set for girls', 15, 'A sweet white pajama set for girls, made from soft, breathable fabric and covered in playful cherry prints.', 'K4.jpg', 3, 0, 0, 0, 0, 0, 0),
(29, 'A trendy girls’ set', 40, 'A trendy girls’ set featuring a short denim skirt with a clean, simple cut, paired with a lightweight open-front white top.', 'K5.jpg', 3, 0, 0, 0, 0, 0, 0),
(30, ' boys’ outfit ', 33, 'A playful boys’ outfit featuring olive green overalls with adjustable shoulder straps, paired with a soft white short-sleeve top underneath.', 'K6.jpg', 3, 0, 0, 0, 0, 0, 0),
(31, ' boys’ white shirt ', 10, 'A classic boys’ white shirt made from soft, breathable cotton fabric. Designed with a structured collar and short sleeves', 'K7.jpg', 3, 0, 0, 0, 0, 0, 0),
(32, 'three boys’ shirts', 30, 'A smart set of three boys’ shirts in shades of blue and white, all featuring classic shirt collars. Made from soft, breathable fabric for all-day comfort.', 'K8.jpg', 3, 0, 0, 0, 0, 0, 0),
(33, 'A stylish two-piece kids’ outfit', 45, 'A stylish two-piece kids’ outfit featuring a short-sleeve top and matching shorts in beautiful shades of green.', 'K9.jpg', 3, 0, 0, 0, 0, 0, 0),
(34, 'overall for girls', 30, 'A chic beige overall for girls, designed with a tailored fit and elegant structure. Made from smooth, high-quality fabric.', 'K10.jpg', 3, 0, 0, 0, 0, 0, 0),
(35, 'single-piece bracelet ', 5, 'A sleek and minimalistic single-piece bracelet in a shiny gold tone. Designed with a clean finish and timeless appeal', 'A1.jpg', 4, 0, 0, 0, 0, 0, 0),
(36, 'Silver Necklace', 10, 'A delicate silver necklace with a clean, timeless design. Crafted from high-quality sterling silver', 'A2.jpg', 4, 0, 0, 0, 0, 0, 0),
(37, 'leather belt', 15, 'A sophisticated leather belt featuring a sleek black strap and a shiny gold-tone buckle. Made from high-quality genuine leather.', 'A3.jpg', 4, 0, 0, 0, 0, 0, 0),
(38, ' black sun hat', 7, 'A stylish black sun hat designed to provide shade and comfort on sunny days. Made from lightweight, breathable fabric.', 'A4.jpg', 4, 0, 0, 0, 0, 0, 0),
(39, 'sunglasses', 15, 'A sleek duo of sunglasses featuring modern black and gold frames. Designed with UV-protective lenses and a stylish blend of matte black and shiny gold accents', 'A5.jpg', 4, 0, 0, 0, 0, 0, 0),
(40, 'charming bracelet set', 5, 'A charming bracelet set inspired by coastal vibes, featuring natural shells, rope textures, and ocean-themed details.', 'A6.jpg', 4, 0, 0, 0, 0, 0, 0),
(41, 'modern and stylish watch ', 20, 'A modern and stylish watch designed for a youthful look, featuring a smooth black leather strap .', 'A7.jpg', 4, 0, 0, 0, 0, 0, 0),
(42, 'hair clips', 2, 'A delicate set of hair clips featuring rose-shaped floral designs. Made with soft fabric petals and secure metal clips.', 'A8.jpg', 4, 0, 0, 0, 0, 0, 0),
(43, 'silver waist belt ', 9, 'A delicate and lightweight silver waist belt designed for accessorizing. With its slim profile and subtle shine.', 'A9.jpg', 4, 0, 0, 0, 0, 0, 0),
(44, 'collection of silver rings', 25, 'A stylish collection of silver rings featuring various designs, from minimalist bands to delicate patterns.', 'A10.jpg', 4, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(5) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_id`, `size`, `quantity`) VALUES
(12, 4, 'XS', 0),
(13, 4, 'S', 0),
(14, 4, 'M', 1),
(15, 4, 'L', 2),
(16, 4, 'XL', 0),
(17, 4, 'XXL', 2),
(18, 5, 'XS', 10),
(19, 5, 'S', 10),
(20, 5, 'M', 15),
(21, 5, 'L', 5),
(22, 5, 'XL', 5),
(23, 5, 'XXL', 2),
(24, 6, 'XS', 10),
(25, 6, 'S', 10),
(26, 6, 'M', 10),
(27, 6, 'L', 5),
(28, 6, 'XL', 7),
(29, 6, 'XXL', 5),
(30, 7, 'XS', 10),
(31, 7, 'S', 10),
(32, 7, 'M', 10),
(33, 7, 'L', 10),
(34, 7, 'XL', 5),
(35, 7, 'XXL', 5),
(36, 8, 'XS', 10),
(37, 8, 'S', 10),
(38, 8, 'M', 10),
(39, 8, 'L', 10),
(40, 8, 'XL', 5),
(41, 8, 'XXL', 5),
(42, 9, 'XS', 10),
(43, 9, 'S', 10),
(44, 9, 'M', 10),
(45, 9, 'L', 10),
(46, 9, 'XL', 5),
(47, 9, 'XXL', 5),
(48, 10, 'XS', 10),
(49, 10, 'S', 10),
(50, 10, 'M', 10),
(51, 10, 'L', 5),
(52, 10, 'XL', 5),
(53, 10, 'XXL', 5),
(54, 11, 'XS', 12),
(55, 11, 'S', 12),
(56, 11, 'M', 10),
(57, 11, 'L', 10),
(58, 11, 'XL', 4),
(59, 11, 'XXL', 5),
(60, 12, 'XS', 10),
(61, 12, 'S', 10),
(62, 12, 'M', 10),
(63, 12, 'L', 10),
(64, 12, 'XL', 5),
(65, 12, 'XXL', 5),
(66, 13, 'XS', 10),
(67, 13, 'S', 10),
(68, 13, 'M', 10),
(69, 13, 'L', 10),
(70, 13, 'XL', 5),
(71, 13, 'XXL', 5),
(72, 14, 'XS', 15),
(73, 14, 'S', 10),
(74, 14, 'M', 10),
(75, 14, 'L', 10),
(76, 14, 'XL', 10),
(77, 14, 'XXL', 5),
(78, 15, 'XS', 10),
(79, 15, 'S', 10),
(80, 15, 'M', 30),
(81, 15, 'L', 20),
(82, 15, 'XL', 20),
(83, 15, 'XXL', 15),
(84, 16, 'XS', 10),
(85, 16, 'S', 10),
(86, 16, 'M', 10),
(87, 16, 'L', 10),
(88, 16, 'XL', 10),
(89, 16, 'XXL', 10),
(90, 17, 'XS', 10),
(91, 17, 'S', 10),
(92, 17, 'M', 20),
(93, 17, 'L', 30),
(94, 17, 'XL', 15),
(95, 17, 'XXL', 10),
(96, 18, 'XS', 10),
(97, 18, 'S', 10),
(98, 18, 'M', 10),
(99, 18, 'L', 10),
(100, 18, 'XL', 10),
(101, 18, 'XXL', 10),
(102, 19, 'XS', 10),
(103, 19, 'S', 10),
(104, 19, 'M', 10),
(105, 19, 'L', 10),
(106, 19, 'XL', 10),
(107, 19, 'XXL', 9),
(108, 20, 'XS', 10),
(109, 20, 'S', 10),
(110, 20, 'M', 20),
(111, 20, 'L', 15),
(112, 20, 'XL', 10),
(113, 20, 'XXL', 10),
(114, 21, 'XS', 10),
(115, 21, 'S', 10),
(116, 21, 'M', 20),
(117, 21, 'L', 20),
(118, 21, 'XL', 20),
(119, 21, 'XXL', 20),
(120, 22, 'XS', 10),
(121, 22, 'S', 10),
(122, 22, 'M', 10),
(123, 22, 'L', 10),
(124, 22, 'XL', 10),
(125, 22, 'XXL', 10),
(126, 23, 'XS', 10),
(127, 23, 'S', 10),
(128, 23, 'M', 20),
(129, 23, 'L', 20),
(130, 23, 'XL', 10),
(131, 23, 'XXL', 10),
(132, 24, 'XS', 10),
(133, 24, 'S', 10),
(134, 24, 'M', 10),
(135, 24, 'L', 10),
(136, 24, 'XL', 10),
(137, 24, 'XXL', 10),
(138, 25, 'XS', 5),
(139, 25, 'S', 5),
(140, 25, 'M', 5),
(141, 25, 'L', 5),
(142, 25, 'XL', 5),
(143, 25, 'XXL', 5),
(144, 26, 'XS', 10),
(145, 26, 'S', 10),
(146, 26, 'M', 10),
(147, 26, 'L', 10),
(148, 26, 'XL', 2),
(149, 26, 'XXL', 5),
(150, 27, 'XS', 5),
(151, 27, 'S', 5),
(152, 27, 'M', 5),
(153, 27, 'L', 5),
(154, 27, 'XL', 5),
(155, 27, 'XXL', 5),
(156, 28, 'XS', 5),
(157, 28, 'S', 5),
(158, 28, 'M', 5),
(159, 28, 'L', 5),
(160, 28, 'XL', 5),
(161, 28, 'XXL', 5),
(162, 29, 'XS', 5),
(163, 29, 'S', 5),
(164, 29, 'M', 5),
(165, 29, 'L', 5),
(166, 29, 'XL', 5),
(167, 29, 'XXL', 5),
(168, 30, 'XS', 5),
(169, 30, 'S', 5),
(170, 30, 'M', 5),
(171, 30, 'L', 5),
(172, 30, 'XL', 5),
(173, 30, 'XXL', 5),
(174, 31, 'XS', 5),
(175, 31, 'S', 5),
(176, 31, 'M', 5),
(177, 31, 'L', 5),
(178, 31, 'XL', 5),
(179, 31, 'XXL', 5),
(180, 32, 'XS', 5),
(181, 32, 'S', 5),
(182, 32, 'M', 5),
(183, 32, 'L', 5),
(184, 32, 'XL', 5),
(185, 32, 'XXL', 5),
(186, 33, 'XS', 5),
(187, 33, 'S', 5),
(188, 33, 'M', 5),
(189, 33, 'L', 5),
(190, 33, 'XL', 5),
(191, 33, 'XXL', 5),
(192, 34, 'XS', 5),
(193, 34, 'S', 5),
(194, 34, 'M', 5),
(195, 34, 'L', 5),
(196, 34, 'XL', 5),
(197, 34, 'XXL', 5),
(198, 35, 'XS', 10),
(199, 35, 'S', 10),
(200, 35, 'M', 10),
(201, 35, 'L', 10),
(202, 35, 'XL', 10),
(203, 35, 'XXL', 10),
(204, 36, 'XS', 5),
(205, 36, 'S', 10),
(206, 36, 'M', 5),
(207, 36, 'L', 5),
(208, 36, 'XL', 5),
(209, 36, 'XXL', 5),
(210, 37, 'XS', 10),
(211, 37, 'S', 10),
(212, 37, 'M', 10),
(213, 37, 'L', 10),
(214, 37, 'XL', 10),
(215, 37, 'XXL', 10),
(216, 38, 'XS', 10),
(217, 38, 'S', 10),
(218, 38, 'M', 10),
(219, 38, 'L', 10),
(220, 38, 'XL', 10),
(221, 38, 'XXL', 10),
(222, 39, 'XS', 10),
(223, 39, 'S', 10),
(224, 39, 'M', 10),
(225, 39, 'L', 10),
(226, 39, 'XL', 0),
(227, 39, 'XXL', 0),
(228, 40, 'XS', 10),
(229, 40, 'S', 10),
(230, 40, 'M', 10),
(231, 40, 'L', 10),
(232, 40, 'XL', 1),
(233, 40, 'XXL', 1),
(234, 41, 'XS', 10),
(235, 41, 'S', 10),
(236, 41, 'M', 10),
(237, 41, 'L', 10),
(238, 41, 'XL', 10),
(239, 41, 'XXL', 0),
(240, 42, 'XS', 20),
(241, 42, 'S', 0),
(242, 42, 'M', 0),
(243, 42, 'L', 0),
(244, 42, 'XL', 0),
(245, 42, 'XXL', 0),
(246, 43, 'XS', 10),
(247, 43, 'S', 10),
(248, 43, 'M', 10),
(249, 43, 'L', 10),
(250, 43, 'XL', 10),
(251, 43, 'XXL', 10),
(252, 44, 'XS', 10),
(253, 44, 'S', 10),
(254, 44, 'M', 10),
(255, 44, 'L', 10),
(256, 44, 'XL', 10),
(257, 44, 'XXL', 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','visitor') DEFAULT 'visitor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$abcdefghijklmnopqrstuuEZ4tpXm4W5SeJkDbuNdfJ.CF0.Wc2V2', 'admin'),
(2, 'abd', '$2y$10$SG5MfN1aqa8RSIGWOXHDl.WOBs.lnZuC.aNpBRlRZxoh/.AlCto5m', 'visitor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
