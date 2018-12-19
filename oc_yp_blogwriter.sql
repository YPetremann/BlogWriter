-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 19 déc. 2018 à 14:54
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `oc_yp_blogwriter`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `post_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reported` tinyint(1) NOT NULL DEFAULT '0',
  `visibility` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `author_id`, `post_date`          , `reported`, `visibility`, `content`                                                 )
VALUES                 (1   , 1        , 1          , '2018-11-30 13:03:37', 0         , 1           , 'Voici le tout premier chapitre de mon prochain livre ...'),
                       (33  , 1        , 1          , '2018-12-17 17:28:52', 0         , 1           , 'J\'ai un nouveau en reserve'                             ),
                       (32  , 1        , 2          , '2018-12-17 17:21:31', 1         , 1           , 'Bonjour'                                                 ),
                       (34  , 25       , 0          , '2018-12-17 18:20:35', 0         , 1           , '&lt;b&gt;aaa&lt;/b&gt;'                                  );

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `post_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visibility` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `author_id`, `post_date`          , `visibility`, `title`                                  , `content`)
VALUES              (1   , 1          , '2018-11-30 12:23:23', 1           , 'Chapitre 1 : Une lettre venue de loin'  , '<p>Aujourd\'hui j\'ai re&ccedil;u une lettre des plus &eacute;trange, elle provenait d\'un ami d\'enfance me demandant de tout quitter pour venir en Alaska... Je suis pas fou, mon travail m\'ennuyait, j\'avait besoin d\'un peu de vent frais mais je ne comptais pas y rester... un mois la-bas serait bien.</p>'),
                    (2   , 1          , '2018-11-30 12:23:23', 0           , 'Chapitre 2 : Des missions ?'            , '<p>Pour mon voyage, j\'avait des \"mission\" &agrave; accomplir, la premi&egrave;re &eacute;tait de poser ma d&eacute;mission, venait ensuite l\'achat du ticket aller-retour pour l\'Alaska.</p>'),
                    (23  , 1          , '2018-12-12 16:12:21', 0           , 'Chapitre 3 : Lorem ipsum dolor sit amet', '<p><strong>Lorem ipsum dolor sit amet</strong>, consectetur adipiscing elit. Nunc eget nibh lorem. Nullam at dui ac eros faucibus consectetur et eget arcu. Pellentesque eu erat non eros sagittis facilisis eu in magna. Duis pulvinar, velit id tempor vestibulum, elit nibh consectetur leo, sit amet efficitur est massa a ante. Etiam interdum urna nec tempor molestie. Aenean condimentum, massa vitae suscipit vestibulum, diam diam iaculis sapien, non dignissim quam magna a sem. Sed quis lectus leo. Curabitur laoreet rhoncus turpis eu rutrum. Nam volutpat eros in urna rhoncus, nec tincidunt magna feugiat. In a malesuada libero. Nullam et sem fringilla, interdum eros quis, vehicula metus. Morbi dictum pellentesque arcu, aliquam rutrum orci condimentum at. Praesent vulputate sit amet massa et tempor. Pellentesque lorem massa, tempus at tincidunt ut, venenatis nec ligula.</p>\r\n<p>Donec quis porta sapien, non efficitur libero. Suspendisse at molestie ante. Maecenas volutpat eros vitae metus tempus ultricies. In sapien nisl, tincidunt eu purus vel, porta aliquam metus. Morbi molestie lacus sit amet dignissim interdum. Sed dapibus eleifend elit, vel venenatis mi rutrum at. Nunc semper neque massa, et pretium sem facilisis et. Nunc sit amet tellus est. Aenean euismod egestas velit, in rhoncus nunc maximus ut. Nam facilisis malesuada odio ut semper. Donec in semper mauris. Etiam vel justo consequat, auctor metus eu, accumsan ligula.</p>\r\n<p>Donec et nisl ipsum. Phasellus at tortor non mauris pharetra dapibus vel efficitur nibh. Nulla mattis placerat ex, a tempus diam vehicula at. Etiam et vestibulum tellus. Suspendisse at viverra leo, quis tristique ipsum. Morbi non libero eu quam auctor mattis. Aliquam erat volutpat. Curabitur id volutpat ipsum. In hac habitasse platea dictumst. Sed convallis suscipit tellus, eu porta metus rhoncus et. Quisque libero quam, eleifend vitae sem sit amet, tincidunt tristique nibh. Maecenas pulvinar iaculis elit eget porta. Nulla eu convallis felis. Donec consequat auctor metus. Quisque semper purus sed cursus vulputate. Aliquam et orci varius, finibus nunc in, ultrices mauris.</p>\r\n<p>Cras dapibus augue quam, sed volutpat lacus gravida vel. Vivamus eget lorem orci. Aenean diam ex, suscipit ac felis ac, semper euismod ipsum. Proin bibendum dui arcu. Ut malesuada urna ante, eget mollis tortor viverra sit amet. Sed vehicula sem eu augue pharetra ornare. Ut porttitor nisl a dapibus vehicula. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam semper posuere scelerisque. Sed feugiat augue nec nisl consequat volutpat. Ut pellentesque egestas nunc. Morbi porta sollicitudin faucibus.</p>\r\n<p>In sed blandit metus, eget convallis eros. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean efficitur erat eget venenatis commodo. Proin pharetra maximus purus a porttitor. Mauris molestie, ligula laoreet scelerisque consequat, massa diam gravida odio, ac varius risus dolor eget purus. Morbi aliquam viverra augue in tincidunt. Duis a sollicitudin magna. Quisque magna ipsum, gravida sed urna non, pretium consectetur felis. Suspendisse ultricies lobortis elementum. Morbi tempus sed nulla ut feugiat. Donec fermentum leo lectus, eu malesuada lacus gravida eget. Donec ornare arcu ut lacus lobortis varius. Fusce ac metus tincidunt, vestibulum velit sed, pharetra mauris. Donec maximus, orci sed bibendum iaculis, metus nisl suscipit neque, eget consectetur est quam non tortor. Praesent pellentesque sodales nulla, vitae pretium enim feugiat eleifend.</p>'),
                    (24  , 1          , '2018-12-17 17:22:59', 0           , 'Lorem'                                  , '<p>Ipsum</p>'),
                    (25  , 1          , '2018-12-17 17:23:15', 1           , 'Lorem'                                  , '<p>Lorem Ipsum&nbsp;</p>');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `type` enum('Guest','Member','Admin') NOT NULL DEFAULT 'Member',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `emailhash` varchar(32) NOT NULL,
  `passwordhash` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`type`  , `id`, `emailhash`           , `passwordhash`        , `name`               )
VALUES              ('Admin' , 1   , '_J9..rasmGsW5py/NBcE', '_J9..rasmNKJbgL8qrjI', 'Jean Forteroche'    ),
                    ('Guest' , 0   , ''                    , ''                    , 'Utilisateur Anonyme'),
                    ('Member', 2   , '_J9..rasm68VvzGXfKt6', '_J9..rasmEyXTLslfKgo', 'Membre Actif'       ),
                    ('Member', 3   , '_J9..rasmkz0u8DKNNF2', '_J9..rasmQTVvZuvN5fk', 'Utilisateur Factice'),
                    ('Member', 4   , '_J9..rasmgNVd1TRagfg', '_J9..rasmHDk4uBx5cfE', 'Blablacar'          );
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
