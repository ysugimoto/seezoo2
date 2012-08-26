--
-- テーブルの構造 `areas`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}areas` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_name` varchar(255) NOT NULL DEFAULT '',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`area_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_auto_navigation`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_auto_navigation` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(2) DEFAULT '1',
  `based_page_id` int(11) DEFAULT '1',
  `subpage_level` int(2) DEFAULT '1',
  `manual_selected_pages` varchar(255) DEFAULT '0',
  `handle_class` varchar(255) DEFAULT NULL,
  `display_mode` int(2) NOT NULL DEFAULT '1' COMMENT '表示方法',
  `show_base_page` int(1) NOT NULL DEFAULT '0' COMMENT '基点ページを表示するか(0:非表示 1:表示)',
  `current_class` varchar(255) NOT NULL DEFAULT '' COMMENT '現在表示中のページへのナビに付けるHTMLクラス属性',
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `blocks`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}blocks` (
  `block_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ブロックID',
  `collection_name` varchar(255) NOT NULL DEFAULT '0' COMMENT '使用している機能名',
  `is_active` int(1) NOT NULL DEFAULT '1' COMMENT '使用中かどうか',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '作成日時',
  PRIMARY KEY (`block_id`),
  UNIQUE KEY `block_id` (`block_id`)
) DEFAULT CHARSET=utf8;


--
-- テーブルの構造 `block_permissions`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}block_permissions` (
  `block_permissions_id` int(11) NOT NULL auto_increment COMMENT 'ブロックパーミッションID',
  `block_id` int(11) NOT NULL default '0',
  `allow_view_id` varchar(255) default NULL,
  `allow_edit_id` varchar(255) default NULL,
  PRIMARY KEY  (`block_permissions_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `block_versions`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}block_versions` (
  `block_version_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ブロックバージョンID',
  `block_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ブロック連番ID',
  `collection_name` varchar(255) NOT NULL DEFAULT '0' COMMENT '使用している機能名',
  `area_id` int(11) NOT NULL DEFAULT '0' COMMENT 'エリア識別バージョンID',
  `display_order` int(11) NOT NULL DEFAULT '0' COMMENT 'エリア内ブロックの並び順',
  `is_active` int(1) NOT NULL DEFAULT '1' COMMENT '使用中かどうか',
  `version_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'バージョンが追加された日時',
  `version_number` int(11) NOT NULL DEFAULT '0' COMMENT 'ページバージョンに対応したバージョンID',
  `ct_handle` varchar(255) NOT NULL DEFAULT '' COMMENT 'カスタムテンプレートのハンドル名',
  `slave_block_id` int(11) NOT NULL DEFAULT '0' COMMENT '静的ブロック参照先ID',
  PRIMARY KEY (`block_version_id`),
  UNIQUE KEY `block_id` (`block_version_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `blog_info`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}blog_info` (
  `template_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ブログで使用するテンプレートID',
  `entry_limit` int(11) NOT NULL DEFAULT '5' COMMENT '最新の記事一覧表示件数',
  `comment_limit` int(11) NOT NULL DEFAULT '10' COMMENT 'コメント表示件数',
  `is_enable` int(1) NOT NULL DEFAULT '0' COMMENT 'ブログを使用可能とするかのフラグ',
  `page_title` varchar(255) NOT NULL COMMENT 'ブログタイトル',
  `is_need_captcha` int(1) NOT NULL DEFAULT '0' COMMENT 'コメントの投稿に画像認証を使用するかどうか',
  `is_auto_ping` int(1) NOT NULL DEFAULT '1' COMMENT '自動的にpingを送信するかどうか',
  `zenback_code` text NULL COMMENT 'zenback連携コード',
  `rss_format` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配信するRSSのフォーマット（0:配信しない,1:RSS1.0,2:RSS2.0,3:Atom）'
) DEFAULT CHARSET=utf8;

--
--  `blog_info`
--

INSERT INTO `{DBPREFIX}blog_info` (`template_id`, `entry_limit`, `comment_limit`, `is_enable`, `page_title`, `is_need_captcha`, `is_auto_ping`) VALUES
(1, 5, 10, 0, 'blog', 1, 0);



--
-- テーブルの構造 `collections`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}collections` (
  `collection_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '機能ID',
  `collection_name` varchar(255) NOT NULL DEFAULT '0' COMMENT '機能名',
  `interface_width` int(6) NOT NULL DEFAULT '500' COMMENT 'ウインドウの幅',
  `interface_height` int(6) NOT NULL DEFAULT '500' COMMENT 'ウインドウの高さ',
  `description` varchar(255) NOT NULL COMMENT 'ブロック概要ワード',
  `added_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '追加日時',
  `block_name` varchar(255) NOT NULL COMMENT '表示するブロック名',
  `db_table` varchar(255) NOT NULL DEFAULT '0' COMMENT 'データベーステーブル名（メインテーブルのみ）',
  `plugin_id` int(11) NOT NULL DEFAULT '0' COMMENT 'プラグインハンドルID',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'アクティブフラグ',
  `pc_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'PCで追加可能なフラグ',
  `sp_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'スマートフォンで追加可能なフラグ',
  `mb_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'ガラケーで追加可能なフラグ',
  PRIMARY KEY (`collection_id`)
) DEFAULT CHARSET=utf8 ;

--
--  `collections`
--

INSERT INTO `{DBPREFIX}collections` (`collection_id`, `collection_name`, `interface_width`, `interface_height`, `description`, `added_date`, `block_name`, `db_table`, `pc_enabled`, `sp_enabled`, `mb_enabled`) VALUES
(1, 'textcontent', 760, 500, 'リッチエディタでコンテンツを作成します。', NOW(), '記事ブロック', 'sz_bt_textcontent', 1, 1, 1),
(2, 'image', 500, 500, '画像を設置します。', NOW(), '画像ブロック', 'sz_bt_image_block', 1, 1, 1),
(3, 'file_download', 500, 500, 'ファイルをダウンロードさせるブロックを設置します。', NOW(), 'ファイルダウンロードブロック', 'sz_bt_file_download', 1, 1, 1),
(4, 'form', 700, 600, 'お問い合わせフォームを設置します。', NOW(), 'お問い合わせフォームブロック', 'sz_bt_forms', 1, 1, 1),
(5, 'head', 500, 500, '見出しを挿入します。', NOW(), '見出しブロック', 'sz_bt_head_block', 1, 1, 1),
(6, 'auto_navigation', 500, 500, 'サイト構造に合わせて自動リンクを生成します。', NOW(), 'オートナビゲーションブロック', 'sz_bt_auto_navigation', 1, 1, 1),
(7, 'googlemap', 500, 500, 'GoogleMapを表示します。', NOW(), 'GoogleMapブロック', 'sz_bt_googlemap_block', 1, 1, 1),
(8, 'html', 500, 500, 'HTMLコードを挿入します。', NOW(), 'HTMLブロック', 'sz_bt_htmlcontent', 1, 1, 1),
(9, 'tabs', 500, 400, 'タブ切り替え可能なブロックを生成します。', NOW(), 'タブコンテンツブロック', 'sz_bt_tab_contents', 1, 1, 0),
(10, 'twitter', 500, 500, 'twitterのつぶやきを表示します。', NOW(), 'twitterブロック', 'sz_bt_twitter_block', 1, 1, 1),
(12, 'slideshow', 600, 500, '画像をスライドショーで切り替えます。', NOW(), 'スライドショーブロック', 'sz_bt_slideshow', 1, 1, 0),
(13, 'image_text', 500, 500, 'テキスト＋画像を並べて表示します。', NOW(), 'テキスト＋画像ブロック', 'sz_bt_image_text', 1, 1, 1),
(14, 'area_splitter', 500, 500, '編集エリアを分割します。', NOW(), '編集エリア分割ブロック', 'sz_bt_area_splitter', 1, 1, 1),
(15, 'flash', 500, 500, 'Flashムービーを設置します。', NOW(), 'Flash設置ブロック', 'sz_bt_flash', 1, 0, 1),
(16, 'video', 500, 500, '外部ビデオを再生します。', NOW(), 'ビデオプレイヤーブロック', 'sz_bt_video', 1, 1, 0),
(17, 'table', 760, 500, '簡易的な表を作成します。', NOW(), '表ブロック', 'sz_bt_table', 1, 1, 1),
(18, 'blogupdates', 500, 500, 'ブログの更新情報を表示します。', NOW(), 'ブログ更新情報ブロック', 'sz_bt_bloginfo', 1, 1, 1);

--
-- テーブルの構造 `directories`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}directories` (
  `directories_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ディレクトリ連番ID',
  `path_name` varchar(255) NOT NULL DEFAULT '/' COMMENT 'ディレクトリパス名',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '親階層ディレクトリID',
  `dir_name` varchar(255) NOT NULL DEFAULT 'no_name' COMMENT 'ディレクトリ名',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '作成日時',
  `access_permission` varchar(255) DEFAULT ':1:' COMMENT 'オープンを許可するユーザーID群',
  PRIMARY KEY (`directories_id`)
) DEFAULT CHARSET=utf8;

--
--  `directories`
--

INSERT INTO `{DBPREFIX}directories` (`directories_id`, `path_name`, `parent_id`, `dir_name`, `created_date`, `access_permission`) VALUES
(1, '/', 0, 'no_name', '0000-00-00 00:00:00', ':1:');

--
-- テーブルの構造 `draft_blocks`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}draft_blocks` (
  `draft_blocks_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '下書き',
  `block_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ブロックID',
  `collection_name` varchar(255) NOT NULL DEFAULT '0' COMMENT '使用するブロック名',
  `drafted_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '下書きに保存したユーザーID',
  `ct_handle` varchar(255) DEFAULT '' COMMENT '下書き追加時点でのカスタムテンプレートハンドル',
  `alias_name` varchar(255) NOT NULL COMMENT '識別名',
  PRIMARY KEY (`draft_blocks_id`)
) DEFAULT CHARSET=utf8 ;

--
-- テーブルの構造 `files`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ファイル管理連番ID',
  `file_name` varchar(255) NOT NULL DEFAULT '0' COMMENT 'ファイル名',
  `crypt_name` varchar(255) NOT NULL DEFAULT '0' COMMENT '実体ファイル名（暗号化済み）',
  `extension` varchar(10) NOT NULL DEFAULT '0' COMMENT '拡張子',
  `width` int(11) NOT NULL DEFAULT '0' COMMENT '横幅',
  `height` int(11) NOT NULL DEFAULT '0' COMMENT '縦幅',
  `added_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '追加日時',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT 'ファイルサイズ',
  `file_group` varchar(255) NOT NULL COMMENT '属するグループID（複数：区切り）',
  `directories_id` int(1) NOT NULL DEFAULT '1' COMMENT 'ファイル配置ディレクトリID',
  `download_count` int(11) NOT NULL DEFAULT '0' COMMENT 'ファイルダウンロード回数',
  PRIMARY KEY (`file_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `file_groups`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}file_groups` (
  `file_groups_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '連番ID',
  `group_name` varchar(255) NOT NULL DEFAULT '0' COMMENT 'グループ名',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '追加日時',
  PRIMARY KEY (`file_groups_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_forms`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_forms` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `question_key` varchar(255) DEFAULT '0',
  `form_title` varchar(255) DEFAULT NULL,
  `use_captcha` int(1) DEFAULT '0',
  `is_remail` int(1) NOT NULL DEFAULT '0',
  `re_mail` varchar(255) DEFAULT '0',
  `thanks_msg` text,
  `form_class_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_googlemap_block`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_googlemap_block` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `api_key` varchar(255) DEFAULT NULL,
  `zoom` int(2) DEFAULT '13',
  `address` varchar(255) DEFAULT NULL,
  `lat` varchar(60) DEFAULT NULL,
  `lng` varchar(60) DEFAULT NULL,
  `width` int(4) DEFAULT NULL,
  `height` int(4) DEFAULT NULL,
  `version` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_head_block`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_head_block` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `head_level` varchar(2) DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `content_type` int(1) DEFAULT '0',
  `content_file_id` int(11) DEFAULT NULL,
  `alt_text` int(255) DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_htmlcontent`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_htmlcontent` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `body` text,
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_image_block`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_image_block` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `file_id` int(11) DEFAULT '0',
  `alt` varchar(255) DEFAULT NULL,
  `link_to` varchar(255) DEFAULT NULL,
  `action_method` varchar(255) DEFAULT NULL,
  `action_file_id` int(11) DEFAULT NULL,
  `hover_file_id` int(11) DEFAULT '0',
  `link_type` int(1) DEFAULT '1',
  `link_to_page_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `pages`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ページID',
  `version_number` int(11) NOT NULL DEFAULT '1' COMMENT '編集中のバージョンID',
  `is_editting` int(1) NOT NULL DEFAULT '0' COMMENT '編集中フラグ',
  `edit_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '編集中のユーザーID',
  `is_arranging` int(11) NOT NULL DEFAULT '0' COMMENT '移動モードフラグ',
  `edit_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`page_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `pages`
--

INSERT INTO `{DBPREFIX}pages` (`page_id`, `version_number`, `is_editting`, `edit_user_id`, `is_arranging`, `edit_start_time`) VALUES
(1, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(2, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(3, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(4, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(5, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(6, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(7, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(8, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(9, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(10, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(11, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(12, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(13, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(14, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(15, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(16, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(17, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(18, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(19, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(20, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(21, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(22, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(23, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(24, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(25, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(26, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(27, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(28, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(29, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(30, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(31, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(32, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(33, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(34, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(35, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(36, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(37, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(38, 1, 0, 0, 0, '0000-00-00 00:00:00'),

(39, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(40, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(41, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(42, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(43, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(44, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(45, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(46, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(47, 1, 0, 0, 0, '0000-00-00 00:00:00');

--
-- テーブルの構造 `page_paths`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}page_paths` (
  `page_path_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ページパスID',
  `page_path` varchar(255) NOT NULL COMMENT 'ページパス名',
  `page_id` int(11) NOT NULL DEFAULT '1' COMMENT 'ページID',
  `plugin_id` int(11) NOT NULL DEFAULT '0' COMMENT 'プラグインハンドルID',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'アクティブフラグ',
  PRIMARY KEY (`page_path_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `page_paths`
--

INSERT INTO `{DBPREFIX}page_paths` (`page_path_id`, `page_path`, `page_id`) VALUES
(1, 'home', 1),
(2, 'login', 2),
(3, 'logout', 3),
(4, 'dashboard/panel', 4),
(5, 'dashboard/pages', 5),
(6, 'dashboard/pages/page_list', 6),
(7, 'dashboard/pages/system_page', 7),
(8, 'dashboard/pages/static_pages', 8),
(9, 'dashboard/pages/pseudo_variables', 9),
(10, 'dashboard/files', 10),
(11, 'dashboard/files/directory_view', 11),
(12, 'dashboard/files/file_groups', 12),
(13, 'dashboard/blocks', 13),
(14, 'dashboard/templates', 14),
(15, 'dashboard/reports', 15),
(16, 'dashboard/users', 16),
(17, 'dashboard/users/user_list', 17),
(18, 'dashboard/users/edit_user', 18),
(19, 'dashboard/gadget', 19),
(20, 'dashboard/site_settings', 20),
(21, 'dashboard/blog', 21),
(22, 'dashboard/blog/entries', 22),
(23, 'dashboard/blog/edit', 23),
(24, 'dashboard/blog/categories', 24),
(25, 'dashboard/blog/comment', 25),
(26, 'dashboard/blog/ping', 26),
(27, 'dashboard/blog/settings', 27),
(28, 'dashboard/image', 28),
(29, 'dashboard/draft_blocks', 29),
(30, 'blog', 30),
(31, 'dashboard/backend_process', 31),
(32, 'dashboard/blog/menu_settings', 32),
(33, 'dashboard/site_settings/base', 33),
(34, 'dashboard/site_settings/upgrade', 34),
(35, 'dashboard/blog/drafts', 35),
(36, 'dashboard/blog/trackbacks', 36),
(37, 'dashboard/site_settings/ssl', 37),
(38, 'dashboard/site_settings/log_history', 38),
(39, 'registration', 39),
(40, 'member_login', 40),
(41, 'dashboard/members', 41),
(42, 'dashboard/members/member_list', 42),
(43, 'dashboard/members/edit_member', 43),
(44, 'dashboard/members/attributes', 44),
(45, 'profile', 45),
(46, 'dashboard/plugins', 46),
(47, 'dashboard/plugins/plugin_list', 47);

--
-- テーブルの構造 `page_versions`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}page_versions` (
  `page_version_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ページバージョンID',
  `version_number` int(11) NOT NULL DEFAULT '1' COMMENT 'バージョン番号',
  `page_id` int(11) NOT NULL COMMENT 'ページID',
  `page_title` varchar(255) NOT NULL DEFAULT '0' COMMENT 'ページタイトル',
  `template_id` int(11) NOT NULL DEFAULT '0' COMMENT '使用しているテンプレートID',
  `meta_title` varchar(255) NOT NULL DEFAULT '0' COMMENT 'ページタイトル<meta>',
  `meta_keyword` varchar(255) NOT NULL COMMENT 'メタキーワード',
  `meta_description` text NOT NULL COMMENT 'メタデスクリプション',
  `navigation_show` int(1) NOT NULL DEFAULT '1' COMMENT 'ナビゲーションに表示させるかどうか',
  `parent` int(11) NOT NULL DEFAULT '1' COMMENT '親階層のページ',
  `display_order` int(11) NOT NULL DEFAULT '0' COMMENT 'サイトマップ内表示順',
  `display_page_level` int(11) NOT NULL DEFAULT '0' COMMENT 'ページの階層レベル',
  `version_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'バージョンが追加された日時',
  `created_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ページ作成者のユーザーID',
  `is_public` int(1) NOT NULL DEFAULT '0' COMMENT '公開されているかどうかのフラグ',
  `public_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '公開日時',
  `approved_user_id` int(11) NOT NULL COMMENT '承認したユーザーのID',
  `version_comment` varchar(255) DEFAULT NULL COMMENT 'バージョンのコメント',
  `is_system_page` int(1) NOT NULL DEFAULT '0' COMMENT 'システムページのデータかどうか',
  `is_mobile_only` int(1) NOT NULL DEFAULT '0' COMMENT 'モバイルからのみアクセスさせるかどうか',
  `is_ssl_page` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'SSL通信させるページかどうか',
  `alias_to` int(11) NOT NULL DEFAULT '0' COMMENT 'エイリアスページかどうか',
  `page_description` varchar(255) DEFAULT NULL,
  `external_link` varchar(255) DEFAULT NULL COMMENT '外部リンクURL',
  `target_blank` tinyint(1) NOT NULL DEFAULT '0' COMMENT '新規タブで開くかどうか',
  PRIMARY KEY (`page_version_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `page_versions`
--

INSERT INTO `{DBPREFIX}page_versions` (`page_version_id`, `version_number`, `page_id`, `page_title`, `template_id`, `meta_title`, `meta_keyword`, `meta_description`, `navigation_show`, `parent`, `display_order`, `display_page_level`, `version_date`, `created_user_id`, `is_public`, `public_datetime`, `approved_user_id`, `version_comment`, `is_system_page`, `is_mobile_only`, `alias_to`, `page_description`) VALUES
(1, 1, 1, 'Seezooトップ', 1, '', '', '', 1, 0, 1, 0, NOW(), 1, 1, NOW(), 1, '初稿', 0, 0, 0, NULL),
(2, 1, 2, 'ログイン', 0, '0', '', '', 1, 0, 1, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(3, 1, 3, 'ログアウト', 0, '0', '', '', 1, 0, 1, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(4, 1, 4, '管理トップ', 0, '', '', '', 1, 0, 1, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(5, 1, 5, 'ページ管理', 0, '0', '', '', 1, 0, 2, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(6, 1, 6, '一般ページ管理', 0, '0', '', '', 1, 5, 1, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, 'システムから生成されたページリストを表示します。'),
(7, 1, 7, 'システムページ管理', 0, '0', '', '', 1, 5, 2, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, 'システムページを追加したり削除したりします。'),
(8, 1, 8, '静的ページ管理', 0, '0', '', '', 1, 5, 3, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(9, 1, 9, '静的変数一覧', 0, '0', '', '', 1, 5, 4, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(10, 1, 10, 'ファイル管理', 0, '0', '', '', 1, 0, 3, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(11, 1, 11, 'ファイル管理', 0, '0', '', '', 1, 10, 1, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(12, 1, 12, 'グループ管理', 0, '0', '', '', 1, 10, 2, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(13, 1, 13, 'ブロック管理', 0, '0', '', '', 1, 0, 4, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(14, 1, 14, 'テンプレート管理', 0, '0', '', '', 1, 0, 5, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(15, 1, 15, 'レポート', 0, '', '', '', 0, 0, 6, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(16, 1, 16, '管理ユーザー設定', 0, '0', '', '', 1, 0, 7, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(17, 1, 17, 'ユーザー一覧/検索', 0, '0', '', '', 1, 16, 1, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, '管理者として登録されているユーザーが確認できます。'),
(18, 1, 18, 'ユーザー追加/編集', 0, '0', '', '', 1, 16, 2, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(19, 1, 19, 'ユーザーツール設定', 0, '0', '', '', 1, 0, 8, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(20, 1, 20, 'サイト全体の設定', 0, '0', '', '', 1, 0, 9, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(21, 1, 21, 'ブログ管理', 0, '0', '', '', 1, 0, 10, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(22, 1, 22, 'エントリー一覧', 0, '', '', '', 1, 21, 1, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(23, 1, 23, '新規投稿', 0, '0', '', '', 1, 21, 3, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, '新規エントリーを投稿します。'),
(24, 1, 24, 'カテゴリ管理', 0, '0', '', '', 1, 21, 4, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, 'ブログカテゴリを管理します。'),
(25, 1, 25, 'コメント管理', 0, '0', '', '', 1, 21, 5, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, '投稿につけられたコメントを管理します。'),
(26, 1, 26, 'ping送信先管理', 0, '0', '', '', 1, 21, 6, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, '新規記事投稿時のping送信先を管理します。'),
(27, 1, 27, 'ブログ設定情報管理', 0, '0', '', '', 1, 21, 7, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, 'ブログ設定情報を管理します。'),
(28, 1, 28, '画像の編集', 0, '0', '', '', 1, 0, 11, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(29, 1, 29, '下書きブロック管理', 0, '0', '', '', 1, 0, 12, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(30, 1, 30, 'ブログ', 1, '0', '', '', 0, 1, 2, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(31, 1, 31, 'バックエンド処理', 0, '0', '', '', 1, 0, 13, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(32, 1, 32, 'ブログメニュー管理', 0, '0', '', '', 1, 21, 7, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(33, 1, 33, 'サイト運用設定', 0, '0', '', '', 1, 20, 1, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(34, 1, 34, 'アップグレード', 0, '0', '', '', 1, 20, 2, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(35, 1, 35, '下書き一覧', 0, '0', '', '', 1, 21, 2, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(36, 1, 36, 'トラックバック管理', 0, '0', '', '', 1, 21, 8, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(37, 1, 37, 'SSL設定', 0, '0', '', '', 1, 20, 3, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(38, 1, 38, 'システムログ', 0, '0', '', '', 1, 20, 4, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),

(39, 1, 39, 'ユーザー登録', 0, '0', '', '', 0, 1, 3, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(40, 1, 40, 'ユーザーログイン', 0, '0', '', '', 0, 1, 4, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(41, 1, 41, 'メンバー管理', 0, '0', '', '', 1, 0, 14, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(42, 1, 42, 'メンバー一覧/検索', 0, '0', '', '', 1, 41, 1, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(43, 1, 43, 'メンバー追加/編集', 0, '0', '', '', 1, 41, 2, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(44, 1, 44, 'メンバー項目設定', 0, '0', '', '', 1, 41, 3, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(45, 1, 45, 'ユーザープロフィール', 0, '0', '', '', 0, 1, 5, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(46, 1, 46, 'プラグイン管理', 0, '0', '', '', 1, 0, 15, 0, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, ''),
(47, 1, 47, 'プラグイン一覧', 0, '0', '', '', 1, 46, 1, 1, NOW(), 1, 1, NOW(), 1, NULL, 1, 0, 0, '');


--
-- テーブルの構造 `pending_blocks`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}pending_blocks` (
  `pending_block_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ブロックID',
  `block_version_id` int(11) NOT NULL DEFAULT '0',
  `block_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ブロックID',
  `collection_name` varchar(255) NOT NULL DEFAULT '0' COMMENT '使用している機能名',
  `area_id` int(11) NOT NULL DEFAULT '0' COMMENT 'エリア識別ID',
  `display_order` int(11) NOT NULL DEFAULT '0' COMMENT 'エリア内ブロックの並び順',
  `is_active` int(1) NOT NULL DEFAULT '1' COMMENT '使用中かどうか',
  `version_number` int(11) NOT NULL DEFAULT '0',
  `version_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ct_handle` varchar(255) NOT NULL DEFAULT '' COMMENT 'カスタムテンプレートハンドル名',
  `slave_block_id` int(11) NOT NULL DEFAULT '0' COMMENT '静的ブロック参照先ID',
  PRIMARY KEY (`pending_block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `pending_pages`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}pending_pages` (
  `pending_page_version_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ページバージョンID',
  `page_version_id` int(11) NOT NULL,
  `version_number` int(11) NOT NULL DEFAULT '1' COMMENT 'バージョン番号',
  `page_id` int(11) NOT NULL COMMENT 'ページID',
  `page_title` varchar(255) NOT NULL DEFAULT '0' COMMENT 'ページタイトル',
  `template_id` int(11) NOT NULL DEFAULT '0' COMMENT '使用しているテンプレートID',
  `meta_title` varchar(255) NOT NULL DEFAULT '0' COMMENT 'ページタイトル<meta>',
  `meta_keyword` varchar(255) NOT NULL COMMENT 'メタキーワード',
  `meta_description` text NOT NULL COMMENT 'メタデスクリプション',
  `navigation_show` int(1) NOT NULL DEFAULT '1' COMMENT 'ナビゲーションに表示させるかどうか',
  `parent` int(11) NOT NULL DEFAULT '1' COMMENT '親階層のページ',
  `display_order` int(11) NOT NULL DEFAULT '0' COMMENT 'サイトマップ内表示順',
  `display_page_level` int(11) NOT NULL DEFAULT '0' COMMENT 'ページの階層レベル',
  `version_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'バージョンが追加された日時',
  `created_user_id` int(11) NOT NULL DEFAULT '0',
  `is_public` int(1) NOT NULL DEFAULT '0' COMMENT '公開されているかどうかのフラグ',
  `public_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '公開日時',
  `approved_user_id` int(11) NOT NULL COMMENT '承認したユーザーのID',
  `is_system_page` int(1) NOT NULL DEFAULT '0',
  `is_mobile_only` int(1) NOT NULL DEFAULT '0',
  `is_ssl_page` tinyint(1) NOT NULL DEFAULT '0',
  `alias_to` int(11) NOT NULL DEFAULT '0',
  `page_description` varchar(255) DEFAULT NULL,
  `external_link` varchar(255) DEFAULT NULL COMMENT '外部リンクURL',
  `target_blank` tinyint(1) NOT NULL DEFAULT '0' COMMENT '新規タブで開くかどうか',
  PRIMARY KEY (`pending_page_version_id`)
) DEFAULT CHARSET=utf8;


-- 
-- テーブルの構造 `sz_backend`
-- 

CREATE TABLE `sz_backend` (
  `sz_backend_id` int(11) NOT NULL auto_increment,
  `backend_handle` varchar(255) NOT NULL,
  `backend_name` varchar(255) NOT NULL,
  `description` varchar(255) default NULL,
  `last_run` datetime NOT NULL default '0000-00-00 00:00:00',
  `result` text,
  `is_process` int(1) NOT NULL default '0',
  PRIMARY KEY  (`sz_backend_id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルの構造 `sz_bt_questions`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_questions` (
  `question_id` int(1) NOT NULL AUTO_INCREMENT,
  `question_key` varchar(255) DEFAULT '0',
  `question_name` varchar(255) DEFAULT '0',
  `question_type` varchar(255) DEFAULT '0',
  `validate_rules` varchar(255) DEFAULT NULL,
  `rows` int(5) DEFAULT NULL,
  `cols` int(5) DEFAULT NULL,
  `options` varchar(255) DEFAULT '0',
  `accept_ext` varchar(255) DEFAULT NULL,
  `max_file_size` int(5) DEFAULT '100',
  `display_order` int(3) DEFAULT '1',
  `is_active` int(1) NOT NULL DEFAULT '1' COMMENT 'この質問を使用するかどうか',
  `class_name` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`question_id`),
  KEY `question_key` (`question_key`, `question_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_question_answers`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_question_answers` (
  `question_key` varchar(255) DEFAULT '0',
  `question_id` int(11) DEFAULT '0',
  `answer` varchar(255) DEFAULT NULL,
  `answer_text` text,
  `post_date` datetime DEFAULT '0000-00-00 00:00:00',
  KEY `question_key` (`question_key`, `question_id`),
  KEY `question_id` (`question_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `site_info`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}site_info` (
  `site_title` varchar(255) NOT NULL COMMENT 'サイト名',
  `google_analytics` text NOT NULL COMMENT 'アクセス解析タグ',
  `is_maintenance` int(1) NOT NULL DEFAULT '0' COMMENT 'サイトメンテナンスフラグ',
  `default_template_id` int(11) NOT NULL DEFAULT '1' COMMENT 'デフォルトで使用するテンプレートID',
  `gmap_api_key` varchar(255) NOT NULL COMMENT 'Google maps APIキー',
  `system_mail_from` varchar(255) NOT NULL DEFAULT '' COMMENT 'システムメールのメールアドレス',
  `enable_mod_rewrite` int(1) NOT NULL DEFAULT '0' COMMENT 'mod_rewriteによるURI書き換えが有効かどうか',
  `enable_cache` int(1) NOT NULL DEFAULT '0' COMMENT 'キャッシュ有効フラグ',
  `ssl_base_url` varchar(255) NOT NULL DEFAULT '' COMMENT 'SSL時のbase_url',
  `log_level` tinyint(1) NOT NULL DEFAULT '2' COMMENT 'システムのロギングレベル',
  `is_accept_member_registration` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'メンバー登録を受け付けるかどうか（0:受け付けない1:受け付ける）',
  `debug_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'デバッグレベル',
  `enable_mobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'ガラケー有効フラグ',
  `enable_smartphone` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'スマートフォン有効フラグ'
)  DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `static_vars`
--

INSERT INTO `{DBPREFIX}site_info` (`site_title`, `google_analytics`, `is_maintenance`, `default_template_id`, `gmap_api_key`, `system_mail_from`, `enable_mod_rewrite`, `enable_cache`) VALUES
('', '', 0, 1, '', '', 0, 0);

--
-- テーブルの構造 `sz_bt_image_text`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_image_text` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `file_id` int(11) DEFAULT NULL,
  `text` text,
  `float_mode` varchar(10) DEFAULT 'left',
  PRIMARY KEY (`block_id`)
)  DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_flash`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_flash` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `file_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_video`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_video` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `file_id` int(11) DEFAULT NULL,
  `display_width` int(5) DEFAULT NULL,
  `display_height` int(5) DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_blog`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_blog` (
  `sz_blog_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ブログエントリーID',
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ブログエントリーの投稿者ID',
  `sz_blog_category_id` int(11) NOT NULL COMMENT 'カテゴリID',
  `title` varchar(255) NOT NULL COMMENT 'タイトル',
  `body` text NOT NULL COMMENT '内容',
  `entry_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '投稿日時',
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新日時',
  `is_accept_comment` int(1) NOT NULL DEFAULT '1' COMMENT 'コメントを受け付けるかどうかのチェック',
  `is_accept_trackback` int(1) NOT NULL DEFAULT '1' COMMENT 'トラックバックを受け付けるかどうかのチェック',
  `is_public` tinyint(1) NOT NULL DEFAULT 1 COMMENT '下書きフラグ（0:下書き1:公開）',
  `drafted_by` int(11) NOT NULL DEFAULT 0 COMMENT '下書き元のエントリーID',
  PRIMARY KEY (`sz_blog_id`)
) DEFAULT CHARSET=utf8;


--
-- テーブルの構造 `sz_blog_category`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_blog_category` (
  `sz_blog_category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'カテゴリID',
  `category_name` varchar(255) NOT NULL COMMENT 'カテゴリ名',
  `is_use` int(1) NOT NULL DEFAULT '1' COMMENT '使用しているかどうか',
  PRIMARY KEY (`sz_blog_category_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_blog_comment`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_blog_comment` (
  `sz_blog_comment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'コメントID',
  `sz_blog_id` int(11) NOT NULL COMMENT 'コメント対象の記事ID',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '投稿日時',
  `name` varchar(255) NOT NULL DEFAULT 'no name' COMMENT '投稿者名',
  `comment_body` text NOT NULL COMMENT 'コメント内容',
  PRIMARY KEY (`sz_blog_comment_id`),
  KEY `sz_blog_id` (`sz_blog_id`, `sz_blog_comment_id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- テーブルの構造 `sz_blog_ping_list`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_blog_ping_list` (
  `sz_blog_ping_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `ping_server` varchar(255) NOT NULL DEFAULT '0' COMMENT 'ping送信先URL',
  `ping_name` varchar(255) NOT NULL DEFAULT '0' COMMENT 'ping名',
  PRIMARY KEY (`sz_blog_ping_list_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `sz_blog_ping_list`
--

INSERT INTO `{DBPREFIX}sz_blog_ping_list` (`sz_blog_ping_list_id`, `ping_server`, `ping_name`) VALUES
(1, 'http://blogsearch.google.com/ping/RPC2', 'Google Blog Search');


--
-- テーブルの構造 `sz_bt_area_splitter`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_area_splitter` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `as_relation_key` varchar(255) DEFAULT '0',
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_area_splitter_relation`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_area_splitter_relation` (
  `as_relation_key` varchar(255) DEFAULT '0',
  `contents_name` varchar(255) DEFAULT '0',
  `contents_per` int(3) DEFAULT '0',
  KEY `as_relation_key` (`as_relation_key`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_file_download`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_file_download` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `file_id` int(11) DEFAULT NULL,
  `dl_text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_slideshow`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_slideshow` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `slide_type` varchar(60) DEFAULT NULL,
  `delay_time` int(11) DEFAULT '3000',
  `play_type` int(11) DEFAULT NULL,
  `file_ids` varchar(255) DEFAULT NULL,
  `page_ids` varchar(255) DEFAULT NULL,
  `is_caption` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`block_id`)
)  DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_gadgets`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadgets` (
  `gadget_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ガジェットID',
  `gadget_name` varchar(255) NOT NULL DEFAULT '0',
  `db_table` varchar(255) NOT NULL DEFAULT '0',
  `display_gadget_name` varchar(255) NOT NULL DEFAULT '0' COMMENT '表示用ガジェット名',
  `gadget_description` varchar(255) NOT NULL DEFAULT '0' COMMENT 'ガジェット概要',
  PRIMARY KEY (`gadget_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `sz_gadgets`
--

INSERT INTO `{DBPREFIX}sz_gadgets` (`gadget_id`, `gadget_name`, `db_table`, `display_gadget_name`, `gadget_description`) VALUES
(1, 'memo', 'sz_gadget_memo', 'メモ', '簡単なメモ書きを保存しておけます。'),
(2, 'weather', 'sz_gadget_weather', '天気', '地域別の天気を確認できます。'),
(3, 'twitter', 'sz_gadget_twitter', 'Twitter', 'Twitterのつぶやきを表示します。'),
(4, 'rss', 'sz_gadget_rss', 'RSS', 'RSSを購読します(RSS1.0,RSS2.0形式のみ対応)'),
(5, 'bbs', 'sz_gadget_bbs', '簡易BBS', 'システムのユーザー間でチャットが行えます。'),
-- (6, 'google_translate', 'sz_gadget_translate', 'Google翻訳', 'Google AJAX Language APIを利用して翻訳が行えます。'),
(7, 'wikipedia', 'sz_gadget_wikipedia', 'Wikipedia', 'WIkipediaで意味を検索できます。');

--
-- テーブルの構造 `sz_gadget_bbs`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadget_bbs` (
  `sz_gadget_bbs_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sz_gadget_bbs_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_gadget_bbs_data`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadget_bbs_data` (
  `sz_gadget_bbs_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `posted_user_id` int(11) NOT NULL,
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `body` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sz_gadget_bbs_data_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_gadget_master`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadget_master` (
  `gadget_master_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `token` varchar(255) NOT NULL DEFAULT '0',
  `gadget_id` int(11) NOT NULL DEFAULT '0',
  `display_order` int(11) NOT NULL DEFAULT '1' COMMENT '表示順',
  PRIMARY KEY (`gadget_master_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_gadget_memo`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadget_memo` (
  `gadget_memo_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'メモガジェットID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '使用しているユーザーID',
  `token` varchar(255) NOT NULL DEFAULT '0' COMMENT 'ハッシュキー',
  `data` text NULL COMMENT 'メモ内容',
  `update_time` int(11) NOT NULL DEFAULT '60000' COMMENT '更新間隔（ms）',
  PRIMARY KEY (`gadget_memo_id`)
)  DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_gadget_rss`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadget_rss` (
  `sz_gadgt_rss_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL DEFAULT '0',
  `rss_url` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sz_gadgt_rss_id`)
)  DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_gadget_translate`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadget_translate` (
  `sz_gadget_translate_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sz_gadget_translate_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_gadget_twitter`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadget_twitter` (
  `sz_gadget_twitter_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL DEFAULT '0',
  `account_name` varchar(255) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '5' COMMENT '更新間隔（分）',
  `show_count` int(3) NOT NULL DEFAULT '10',
  PRIMARY KEY (`sz_gadget_twitter_id`)
)  DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_gadget_weather`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadget_weather` (
  `sz_gadget_weather_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL DEFAULT '0',
  `city_id` int(4) NOT NULL DEFAULT '63' COMMENT '地域ID(デフォルト63:東京)',
  PRIMARY KEY (`sz_gadget_weather_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_gadget_wikipedia`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_gadget_wikipedia` (
  `sz_gadget_wikipedia_id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sz_gadget_wikipedia_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_sessions`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `session_mobile_id` varchar(255) DEFAULT NULL,
  `ip_address` varchar(39) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
)  DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_tab_contents`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_tab_contents` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `tab_relation_key` varchar(255) DEFAULT '0',
  `single_contents` int(1) DEFAULT '1',
  `link_inner` int(1) DEFAULT '0',
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_tab_relations`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_tab_relations` (
  `tab_relation_id` int(1) NOT NULL AUTO_INCREMENT,
  `tab_relation_key` varchar(255) DEFAULT '0',
  `contents_name` varchar(255) DEFAULT '0',
  PRIMARY KEY (`tab_relation_id`),
  KEY `tab_relation_key` (`tab_relation_key`) 
) DEFAULT CHARSET=utf8;


--
-- テーブルの構造 `templates`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}templates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'テンプレートID',
  `template_name` varchar(255) NOT NULL COMMENT 'テンプレート名',
  `template_handle` varchar(255) NOT NULL DEFAULT '0' COMMENT 'テンプレートハンドル',
  `description` text NOT NULL COMMENT '概要',
  `advance_css` text COMMENT '追加CSS',
  PRIMARY KEY (`template_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `templates`
--

INSERT INTO `{DBPREFIX}templates` (`template_id`, `template_name`, `template_handle`, `description`, `advance_css`) VALUES
(1, 'seezooデフォルトテンプレート', 'default', 'デフォルトのテンプレートです。', NULL);

--
-- テーブルの構造 `sz_bt_textcontent`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_textcontent` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `body` text,
  PRIMARY KEY (`block_id`)
)  DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_twitter_block`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_twitter_block` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `view_type` int(1) DEFAULT '1',
  `view_limit` int(2) DEFAULT '10',
  PRIMARY KEY (`block_id`)
)  DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_users`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_users` (
  `user_id` int(11) NOT NULL auto_increment COMMENT 'ユーザーID',
  `user_name` varchar(255) NOT NULL default '0' COMMENT 'ユーザー名',
  `password` varchar(255) NOT NULL default '0' COMMENT 'パスワード',
  `hash` varchar(255) NOT NULL default '0' COMMENT 'ハッシュキー',
  `email` varchar(255) default NULL COMMENT 'ユーザーのメールアドレス',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '最終ログイン日時',
  `login_times` int(11) NOT NULL default '1' COMMENT '総ログイン回数',
  `admin_flag` int(1) NOT NULL default '0' COMMENT '管理者権限フラグ（０：なし１：あり）',
  `regist_time` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '登録日時',
  `remember_token` varchar(255) NOT NULL default '0' COMMENT '継続的なログイントークン（クッキー照合用）',
  `image_data` varchar(255) default NULL,
  `login_miss_count` tinyint(1) default '0',
  `is_admin_user` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
)   DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `page_permissions`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}page_permissions` (
  `page_permissions_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '連番ID',
  `page_id` int(11) NOT NULL DEFAULT '0' COMMENT '権限を発行するページのID',
  `allow_access_user` varchar(255) DEFAULT '' COMMENT 'アクセスを許可するユーザーID群',
  `allow_edit_user` varchar(255) DEFAULT '' COMMENT '編集を許可するユーザーID群',
  `allow_approve_user` varchar(255) DEFAULT '' COMMENT '公開権限を与えるユーザーID群',
  PRIMARY KEY (`page_permissions_id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO `{DBPREFIX}page_permissions` (`page_id`, `allow_access_user`, `allow_edit_user`, `allow_approve_user`) VALUES (1, ':0:m:', '', ''), (39, ':0:m:', '', ''), (40, ':0:m:', '', ''), (45, ':0:m:', '', '');

--
-- テーブルの構造 `page_approve_orders`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}page_approve_orders` (
  `page_approve_orders_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ページ承認申請ID',
  `page_id` int(11) NOT NULL DEFAULT '0' COMMENT '申請対象ページID',
  `version_number` int(11) NOT NULL DEFAULT '1' COMMENT '申請対象バージョンID',
  `ordered_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '申請を行ったユーザーID',
  `ordered_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '申請日時',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '申請状況ステータス',
  `comment` text COMMENT '申請コメント',
  `approved_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '承認、または差し戻しを行ったユーザーID',
  `is_recieve_mail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '申請結果をメールで受け取るかどうか',
  PRIMARY KEY (`page_approve_orders_id`),
  KEY `page_id` (`page_id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- テーブルの構造 `block_set_master`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}block_set_master` (
  `block_set_master_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ブロックセットマスターID',
  `master_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'ブロックセット名',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '作成日時',
  PRIMARY KEY (`block_set_master_id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルの構造 `block_set_data`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}block_set_data` (
  `block_set_data_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ブロックセット管理連番ID',
  `block_set_master_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ブロックセットマスターID',
  `display_order` int(3) NOT NULL DEFAULT '1' COMMENT 'セット内での表示順',
  `block_id` int(11) NOT NULL DEFAULT '0' COMMENT '対象のブロックID',
  PRIMARY KEY (`block_set_data_id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- ---------------------------------------------------------------------------------------------

--
-- テーブルの構造 `sz_search_index`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_search_index` (
  `page_id` int(11) NOT NULL,
  `page_path` varchar(255) DEFAULT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `indexed_words` text,
  UNIQUE KEY `page_id` (`page_id`)
)  DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_blog_menu`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_blog_menu` (
  `sz_blog_menu_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'メニュー連番ID',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '表示するかどうかのフラグ',
  `display_order` int(3) NOT NULL DEFAULT '1' COMMENT '表示順',
  `menu_type` varchar(255) NOT NULL DEFAULT '' COMMENT 'メニュータイプ',
  `menu_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'メニュー名',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'メニュー概要',
  PRIMARY KEY (`sz_blog_menu_id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルの構造 `block_relations`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}block_relations` (
  `block_relations_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ブロックリレーションID',
  `block_id` int(11) NOT NULL DEFAULT '0' COMMENT 'マスターブロックID',
  `slave_block_id` int(11) NOT NULL DEFAULT '0' COMMENT 'スレーブブロックID',
  PRIMARY KEY (`block_relations_id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルの構造 `static_blocks`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}static_blocks` (
  `static_block_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '静的ブロック連番ID',
  `block_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ブロックID',
  `collection_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'コレクション名',
  `add_user_id` int(11) NOT NULL DEFAULT '1' COMMENT '追加したユーザーのID',
  `tmp_static_from` int(11) NOT NULL DEFAULT '0' COMMENT '一時保存先静的ブロックID',
  `alias_name` varchar(255) NOT NULL COMMENT '識別名',
  PRIMARY KEY (`static_block_id`),
  KEY `block_id` (`block_id`,`collection_name`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- テーブルの構造 `sz_blog_trackbacks`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_blog_trackbacks` (
  `sz_blog_trackbacks_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '連番ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '記事タイトル',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '参照元URI',
  `blog_name` varchar(255) NOT NULL DEFAULT '' COMMENT '参照元ブログ名',
  `excerpt` varchar(255) NOT NULL DEFAULT '' COMMENT '記事概要',
  `ip_address` varchar(60) NOT NULL DEFAULT '0.0.0.0' COMMENT '送信元IPアドレス',
  `received_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '受付日時',
  `is_allowed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '表示を許可したかどうか',
  `sz_blog_id` int(11) NOT NULL DEFAULT '0' COMMENT 'トラックバック対象の記事ID',
  PRIMARY KEY (`sz_blog_trackbacks_id`),
  KEY `sz_blog_id` (`sz_blog_id`)
)   DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- テーブルの構造 `sz_system_logs`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_system_logs` (
  `sz_system_logs_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ログID',
  `log_type` varchar(60) NOT NULL DEFAULT 'debug' COMMENT 'ロギングタイプ',
  `severity` varchar(60) NOT NULL DEFAULT '' COMMENT 'エラーレベル',
  `log_text` text NOT NULL COMMENT 'ログの内容',
  `logged_date` datetime NOT NULL COMMENT 'ログ書き込み日時',
  PRIMARY KEY (`sz_system_logs_id`),
  KEY `log_type` (`log_type`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- テーブルの構造 `sz_bt_table`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_table` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `table_data` text,
  PRIMARY KEY (`block_id`)
)  DEFAULT CHARSET=utf8;


--
-- テーブルの構造 `sz_members`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_members` (
  `sz_member_id` int(11) NOT NULL AUTO_INCREMENT,
  `nick_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `activation_code` varchar(255) NOT NULL,
  `is_activate` tinyint(1) NOT NULL DEFAULT '0',
  `relation_site_user` int(11) NOT NULL DEFAULT '0',
  `login_miss_count` int(2) NOT NULL DEFAULT '0',
  `login_times` int(11) NOT NULL DEFAULT '0',
  `image_data` varchar(255) DEFAULT NULL,
  `joined_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation_limit_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `twitter_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sz_member_id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `sz_member_attributes`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_member_attributes` (
  `sz_member_attributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_name` varchar(255) NOT NULL DEFAULT '',
  `attribute_type` varchar(255) NOT NULL DEFAULT 'text',
  `rows` int(5) NOT NULL DEFAULT '0',
  `cols` int(5) NOT NULL DEFAULT '0',
  `options` varchar(255) DEFAULT NULL,
  `validate_rule` varchar(255) DEFAULT NULL DEFAULT '',
  `is_use` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` int(3) NOT NULL DEFAULT '0',
  `is_inputable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sz_member_attributes_id`),
  KEY `is_use` (`is_use`),
  KEY `is_inputable` (`is_inputable`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `sz_member_attributes_value`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_member_attributes_value` (
  `sz_member_id` int(3) NOT NULL DEFAULT '0',
  `sz_member_attributes_id` int(11) NOT NULL,
  `sz_member_attributes_value` varchar(255) DEFAULT NULL,
  `sz_member_attributes_value_text` text,
  KEY `sz_member_id` (`sz_member_id`,`sz_member_attributes_id`),
  KEY `sz_member_attributes_id` (`sz_member_attributes_id`)
) DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `sz_bt_bloginfo`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_bt_bloginfo` (
  `block_id` int(11) NOT NULL DEFAULT '0',
  `category` int(11) DEFAULT '0',
  `view_count` int(3) DEFAULT '10',
  `accept_comment_only` tinyint(1) DEFAULT '0',
  `accept_trackback_only` tinyint(1) DEFAULT '0',
  `posted_user` int(11) DEFAULT '0',
  PRIMARY KEY (`block_id`)
) DEFAULT CHARSET=utf8;


--
-- テーブルの構造 `sz_activation_data`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_activation_data` (
  `activation_code` varchar(255) NOT NULL,
  `sz_member_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) DEFAULT NULL,
  `data` text,
  `activation_limit_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `activation_code` (`activation_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- テーブルの構造 `sz_options`
--
CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '汎用オプションID',
  `name` varchar(255) NOT NULL COMMENT 'オプション名',
  `value` varchar(255) DEFAULT NULL COMMENT 'オプション設定値',
  `handle_key` varchar(60) NOT NULL DEFAULT 'common' COMMENT 'プラグイン識別キー',
  PRIMARY KEY (`option_id`)
) DEFAULT CHARSET=utf8;


--
-- テーブルの構造 `sz_ogp_data`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_ogp_data` (
  `is_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'OGP出力有効/無効フラグ',
  `site_type` varchar(64) NOT NULL DEFAULT 'website' COMMENT 'サイトタイプ',
  `file_id` int(11) NOT NULL DEFAULT '0' COMMENT 'imageプロパティに使用するファイルID',
  `extra` text NOT NULL COMMENT '追加プロトコルテキスト'
) DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- テーブルの構造 `sz_plugins`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}sz_plugins` (
  `plugin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'プラグイン連番ID',
  `plugin_name` varchar(255) NOT NULL COMMENT 'プラグイン名',
  `plugin_handle` varchar(255) NOT NULL COMMENT 'プラグインディレクトリハンドル',
  `description` varchar(255) DEFAULT NULL,
  `added_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`plugin_id`)
) DEFAULT CHARSET=utf8;



-- -------------------------------------------------------------------------------------------------

-- --------------------------------------------------------------------------------------------------
-- デフォルトインストールデータ
-- --------------------------------------------------------------------------------------------------

-- page_versions
-- 先に公開バージョンフラグを下げておく
UPDATE `{DBPREFIX}page_versions` SET `is_public` = 0 WHERE `page_id` = 1;
-- 続いて、初期ブロックをセットしたものを公開状態でINSERT
INSERT INTO `{DBPREFIX}page_versions` (`version_number`, `page_id`, `page_title`, `template_id`, `meta_title`, `meta_keyword`, `meta_description`, `navigation_show`, `parent`, `display_order`, `display_page_level`, `version_date`, `created_user_id`, `is_public`, `public_datetime`, `approved_user_id`, `version_comment`, `is_system_page`, `is_mobile_only`, `alias_to`, `page_description`) VALUES (2, 1, 'Seezooトップ', 1, '', '', '', 1, 0, 1, 0, NOW(), 1, 1, NOW(), 1, 'バージョン2', 0, 0, 0, NULL);

-- areas
INSERT INTO `{DBPREFIX}areas` (`area_id`, `area_name`, `page_id`, `created_date`) VALUES (1, 'primary_navigation', 1, NOW()),
(2, 'global_navigation', 1, NOW()),
(3, 'main_image', 1, NOW()),
(4, 'main', 1, NOW()),
(5, 'submenu', 1, NOW()),
(6, 'footer_navigation', 1, NOW());

-- blocks
INSERT INTO `{DBPREFIX}blocks` (`block_id`, `collection_name`, `is_active`, `created_time`) VALUES (1, 'image', 1, NOW()),
(2, 'auto_navigation', 1, NOW()),
(3, 'head', 1, NOW()),
(4, 'textcontent', 1, NOW()),
(5, 'head', 1, NOW()),
(6, 'textcontent', 1, NOW());

-- block_versions
INSERT INTO `{DBPREFIX}block_versions` (`block_version_id`, `block_id`, `collection_name`, `area_id`, `display_order`, `is_active`, `version_date`, `version_number`, `ct_handle`) VALUES (1, 1, 'image', 3, 1, 1, NOW(), 2, ''),
(2, 2, 'auto_navigation', 2, 1, 1, NOW(), 2, ''),
(3, 3, 'head', 4, 1, 1, NOW(), 2, ''),
(4, 4, 'textcontent', 4, 2, 1, NOW(), 2, ''),
(5, 5, 'head', 5, 1, 1, NOW(), 2, ''),
(6, 6, 'textcontent', 5, 2, 1, NOW(), 2, '');

-- auto_navigation
INSERT INTO `{DBPREFIX}sz_bt_auto_navigation` (`block_id`, `sort_order`, `based_page_id`, `subpage_level`, `manual_selected_pages`, `handle_class`, `display_mode`, `show_base_page`, `current_class`) VALUES (2, 1, 1, 1, '0', NULL, 2, 1, 'current');

-- head
INSERT INTO `{DBPREFIX}sz_bt_head_block` (`block_id`, `head_level`, `class_name`, `text`) VALUES (3, '3', 'green', 'Welcome to Seezoo!'),
(5, '2', NULL, 'サブメニュー');

-- textcontent
INSERT INTO `{DBPREFIX}sz_bt_textcontent` (`block_id`, `body`) VALUES (4, '<br>Seezooは見たままを直感的に編集するCMSです。<br><br>編集エリアに色々な機能を持ったブロックを追加することで、ページをしていきます。<br><br>編集履歴をバージョン管理しているので、前の状態に戻すことも簡単にできます。<br><br>まずはブロックを追加して、ページを編集してみてください！<br>'),
(6, '<br>ここは右カラムのエリアですが、追加したブロックは他のエリアに自由に移動させることができます。<br><br>ツールバーのメニューから『移動モード』を選択し、マウスを合わせると現れるレイヤーをドラッグ＆ドロップし、ブロックを移動してみてください。<br>');

-- image_block
INSERT INTO `{DBPREFIX}sz_bt_image_block` (`block_id`, `file_id`, `alt`, `link_to`, `action_method`, `action_file_id`) VALUES (1, 1, NULL, NULL, NULL, NULL);

-- files
INSERT INTO `{DBPREFIX}files` (`file_id`, `file_name`, `crypt_name`, `extension`, `width`, `height`, `added_date`, `size`, `file_group`, `directories_id`) VALUES (1, 'img_01', '366bbb57fbcaedbe769a3357c23b9b6e', 'jpg', 920, 262, '2010-09-28 17:57:54', 58, '', 1),
(2, 'img_02', '00be4ac5615a580db88443112e80a381', 'jpg', 920, 261, '2010-09-28 17:57:55', 93, '', 1),
(3, 'img_03', '511fc42ed68a53999d2325d97fdb8c40', 'jpg', 920, 260, '2010-09-28 17:57:55', 136, '', 1),
(4, 'img_04', '6f629b4e27a0d5892aa284c6aa698486', 'jpg', 920, 259, '2010-09-28 17:57:56', 133, '', 1);

-- blog menu
INSERT INTO `{DBPREFIX}sz_blog_menu` (`is_hidden`, `display_order`, `menu_type`, `menu_title`, `description`) VALUES
(0, 4, 'calendar', 'カレンダー', 'カレンダーを表示します。'),
(0, 5, 'category', 'カテゴリ表示', '記事カテゴリーを表示します。'),
(0, 3, 'comment', 'コメントを表示', '最近付けられたコメントを表示します。'),
(0, 2, 'articles', '最新記事表示', '新しい記事を更新日順に表示します。'),
(0, 1, 'search', 'ブログ検索', 'ブログ検索フォームを設置します。');


-- Table Indexes
-- thanks @tktools!
ALTER TABLE {DBPREFIX}areas ADD INDEX page_id ( `page_id` , `area_id` );
ALTER TABLE {DBPREFIX}areas ADD INDEX area_name ( `area_name` , `page_id` , `area_id` ) ;
ALTER TABLE {DBPREFIX}blocks ADD INDEX ( `collection_name` ) ;
ALTER TABLE {DBPREFIX}block_permissions ADD INDEX ( `block_id` ) ;
ALTER TABLE {DBPREFIX}block_relations ADD INDEX ( `block_id` ) ;
ALTER TABLE {DBPREFIX}block_set_data ADD INDEX ( `block_id` ) ;
ALTER TABLE {DBPREFIX}collections ADD INDEX ( `collection_name` ) ;
ALTER TABLE {DBPREFIX}pages ADD INDEX page_id ( `page_id` , `version_number` );
ALTER TABLE {DBPREFIX}page_paths ADD INDEX page_path ( `page_path` , `page_id` ) ;
ALTER TABLE {DBPREFIX}page_paths ADD INDEX page_id ( `page_id` , `page_path` ) ;
ALTER TABLE {DBPREFIX}page_permissions ADD INDEX ( `page_id` ) ;
ALTER TABLE {DBPREFIX}page_versions ADD INDEX ( `page_id` ) ;
ALTER TABLE {DBPREFIX}page_versions ADD INDEX ( `parent` ) ;
ALTER TABLE {DBPREFIX}block_versions ADD INDEX area_id ( `area_id` , `is_active` , `version_number` , `display_order` );



