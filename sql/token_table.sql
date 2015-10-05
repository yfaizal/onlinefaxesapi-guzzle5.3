--
-- Table structure for table `onlinefaxes_token`
--

CREATE TABLE IF NOT EXISTS `onlinefaxes_token` (
  `id` int(1) NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'token string',
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'notes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `onlinefaxes_token`
--

INSERT INTO `onlinefaxes_token` (`id`, `token`, `note`) VALUES
(1, 'Lzo2MDY2OTI3MDc5MWI0NjBkYTFmN2FmZGVhNWQ5NTE4YzpyQjBlRTFZMW1ZNHZwbEpJRGFsWm0xczQ1MUV2VjhzN3VTWUM0ZDVZQ0JZPTpjYzAzM2E3ODAzYzM0M2Q5YTZhNmE3MWVhNWU2MjcyZToxNDQ0MDEwNjgw', 'Active token , will update when request a new one'),
(2, 'Lzo2MDY2OTI3MDc5MWI0NjBkYTFmN2FmZGVhNWQ5NTE4YzpBUnplcXRUK1dSTW9vRGZBbUpMVjlHMVhPVUQyTTVyenZmMGREY1hTdEpjPTo5YTkyNjFkYjcxMjE0MjNkYjI3ODIwOGU2ZjMyOTdhNzoxNDQyMzkyNjE3', 'This is an expired token. Use for testing purpose');
