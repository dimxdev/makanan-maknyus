-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 06, 2025 at 02:06 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tubesbasdatfix`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adm_id` int NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adm_id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `id_Delivery` int NOT NULL,
  `status` enum('on the way','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_order` int DEFAULT NULL,
  `id_jasaantar` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`id_Delivery`, `status`, `id_order`, `id_jasaantar`) VALUES
(3, 'delivered', 1, 1),
(5, 'on the way', 8, 3),
(6, 'on the way', 9, 1),
(7, 'on the way', 10, 3),
(8, 'on the way', 11, 5),
(9, 'on the way', 12, 4),
(11, 'delivered', 14, 2);

-- --------------------------------------------------------

--
-- Table structure for table `jasaantar`
--

CREATE TABLE `jasaantar` (
  `id_jasaantar` int NOT NULL,
  `nama_jasa` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jasaantar`
--

INSERT INTO `jasaantar` (`id_jasaantar`, `nama_jasa`) VALUES
(1, 'Gojek'),
(2, 'Grab'),
(3, 'Uber'),
(4, 'Shopee Antar'),
(5, 'TokoDelivery');

-- --------------------------------------------------------

--
-- Table structure for table `makanan`
--

CREATE TABLE `makanan` (
  `id_makanan` int NOT NULL,
  `nama_makanan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `harga` decimal(10,2) NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `makanan`
--

INSERT INTO `makanan` (`id_makanan`, `nama_makanan`, `deskripsi`, `harga`, `img`) VALUES
(1, 'Sate Ayam', 'Sate ayam adalah potongan daging ayam yang dibumbui dan ditusuk menggunakan tusukan bambu, kemudian dipanggang. Disajikan dengan bumbu kacang khas Indonesia.', '14000.00', 'SateAyam.jpeg'),
(2, 'Gulai Kambing', 'Gulai kambing adalah masakan khas Indonesia yang terbuat dari daging kambing yang dimasak dalam kuah santan kental dengan bumbu rempah yang kaya.', '36000.00', 'GulaiKambing.jpeg'),
(3, 'Ayam Betutu', 'Ayam Betutu adalah ayam yang dimasak dengan bumbu rempah khas Bali yang kaya, kemudian dibungkus dengan daun pisang dan dipanggang hingga empuk.', '23000.00', 'AyamBetutu.jpeg'),
(4, 'Kentang Balado', 'Kentang goreng yang dipotong kecil-kecil dan dibumbui dengan sambal balado pedas khas Indonesia.', '8000.00', 'KentangBalado.jpg'),
(5, 'Nasi Padang', 'Nasi Padang adalah hidangan khas Minangkabau yang terdiri dari nasi putih yang disajikan dengan berbagai lauk-pauk seperti rendang, ayam pop, dan sambal hijau.', '21000.00', 'NasiPadang.jpg'),
(6, 'Penyetan Tahu Tempe', 'Tahu dan tempe goreng yang disajikan dengan sambal terasi pedas, nasi putih, dan lalapan.', '5000.00', 'PTahuTempe.jpg'),
(7, 'Crispy Chicken Strip', 'Strip ayam goreng, disajikan dengan saus mustard madu khas.', '18000.00', 'CrispyAyam.jpg'),
(8, 'Ayam Bakar Taliwang', 'Ayam bakar dengan bumbu pedas khas Lombok yang kaya rempah, disajikan dengan nasi putih dan sambal.', '11000.00', 'AyamTaliwang.jpg'),
(9, 'Nasi Goreng', 'Nasi goreng khas Indonesia dengan bahan-bahan seperti ayam, telur, udang, dan bumbu kecap manis yang lezat.', '5000.00', 'Nasgor.jpeg'),
(10, 'Keripik Singkong', 'Keripik singkong renyah yang digoreng, disajikan dalam porsi 12 potong.', '7000.00', 'KripikSingkong.jpg'),
(11, 'Lumpia Semarang', 'Lumpia dengan isian rebung, udang, dan ayam, dibungkus dengan kulit tipis dan digoreng hingga renyah.', '6000.00', 'Lumpia.jpg'),
(12, 'Ayam Penyet', 'Ayam penyet yang digoreng kemudian dihancurkan dan disajikan dengan sambal penyet dan nasi.', '11000.00', 'AyamPenyet.jpg'),
(13, 'Ceker Pedas', 'Ceker ayam yang dimasak dengan bumbu pedas khas Indonesia, disajikan dengan nasi putih dan sambal.', '11000.00', 'Ceker.jpg'),
(14, 'Bakwan Jagung', 'Bakwan jagung goreng yang renyah, terbuat dari jagung manis, tepung terigu, dan bumbu rempah.', '9000.00', 'Bakwan.jpg'),
(15, 'Tahu Gejrot', 'Tahu goreng yang disiram dengan saus manis asam pedas, dilengkapi dengan irisan bawang merah dan cabai.', '6000.00', 'TahuGejrot.jpg'),
(16, 'Mie Goreng Jawa', 'Mie goreng dengan campuran ayam, telur, dan sayuran, dimasak dengan bumbu kecap manis khas Jawa.', '10000.00', 'MieGoreng.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int NOT NULL,
  `adm_id` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `MetodePembayaran` enum('tunai','transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `adm_id`, `id_user`, `total_harga`, `MetodePembayaran`, `date`) VALUES
(1, 1, 1, '70000.00', 'tunai', '2024-12-15 09:24:59'),
(8, 1, 1, '37000.00', 'tunai', '2024-12-30 16:00:03'),
(9, 1, 1, '37000.00', 'tunai', '2024-12-30 16:08:55'),
(10, 1, 1, '59000.00', 'transfer', '2024-12-30 16:19:15'),
(11, 1, 1, '16000.00', 'tunai', '2024-12-30 16:19:39'),
(12, 1, 1, '16000.00', 'transfer', '2024-12-30 16:39:09'),
(14, 1, 1, '59000.00', 'transfer', '2025-01-06 14:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id_makanan` int NOT NULL,
  `id_order` int NOT NULL,
  `jumlah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id_makanan`, `id_order`, `jumlah`) VALUES
(1, 1, 2),
(2, 1, 1),
(2, 14, 1),
(3, 1, 1),
(3, 14, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama_user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `f_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `l_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_tlp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pass_user` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_user`, `f_name`, `l_name`, `email`, `no_tlp`, `pass_user`, `alamat`) VALUES
(1, 'jarwo', 'jarwo', 'wicaksono', 'jarwo@gmail.com', '0811223344', '454cc75b7299b76e68cc9e9eba369ce3', 'Citeureup');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adm_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id_Delivery`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_jasaantar` (`id_jasaantar`);

--
-- Indexes for table `jasaantar`
--
ALTER TABLE `jasaantar`
  ADD PRIMARY KEY (`id_jasaantar`);

--
-- Indexes for table `makanan`
--
ALTER TABLE `makanan`
  ADD PRIMARY KEY (`id_makanan`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `adm_id` (`adm_id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id_makanan`,`id_order`),
  ADD KEY `id_order` (`id_order`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adm_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id_Delivery` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jasaantar`
--
ALTER TABLE `jasaantar`
  MODIFY `id_jasaantar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `makanan`
--
ALTER TABLE `makanan`
  MODIFY `id_makanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `delivery_ibfk_2` FOREIGN KEY (`id_jasaantar`) REFERENCES `jasaantar` (`id_jasaantar`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`adm_id`) REFERENCES `admin` (`adm_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`id_makanan`) REFERENCES `makanan` (`id_makanan`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
