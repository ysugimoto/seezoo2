<?php  if ( ! defined('SZ_EXEC')) exit('No direct script access allowed');

/**
 * setup JavaScript config
 */
$config['init_config'] = array(

		'version' => '0.7.0',

		// siteUrl - ドキュメントルートの定義（末尾に「/」必須）
		'siteUrl' => '/',

		// ssl_siteURL - SSL通信時のプロトコル定義（末尾に「/」必須）
		'ssl_siteUrl' => '',

		// scriptPath - このファイルまでのパスを定義（末尾に「/」必須）
		'scriptPath' => 'js/',

		// debugMode - デバッグモードの使用。エレメントの取得ができなかった場合等にalertが出る
		// true => 有効
		// false => 無効
		'debugMode' => false,

		// useProfiler - プロファイラの使用。DOMツリーやシステムのロード時間の計測を行う。
		// true =>有効
		// false => 無効
		'useProfiler' => false,

		// language - 使用言語のロード。使用しない場合は空欄にする（速度アップ、多言語展開）。
		'language' => 'auto',

		// zIndexer - 各エレメントのz-indexの値を管理
		// true => 有効
		// false => 無効
		'zIndexer' => false,

		// disableEval - window.evalをシステム上使用禁止にする(不用意な即時コンパイルを防ぐ為)
		// true => 有効
		// false => 無効
		'disableEval' => false,

		// useMobileAgent - ゲーム機等主要ブラウザ意外のuserAgentのチェックを行う
		// true => 有効
		// false => 無効
		'useMobileAgent' => false,

		// usePlugins - 機能拡張用のプラグインを指定
		// routingMode = segment以外の場合、読み込むプラグインの数だけhttpリクエストが発生し、スタートアップがやや遅れる
		'usePlugins' => array('animation'),

		// autoLoadLibrary - コントローラの生成時に自動的にロードするライブラリを指定
		'autoLoadLibrary' => array(),

		// autoLoadHelper - コントローラの生成時に自動的にロードするヘルパを指定
		'autoLoadHelper' => array('popwindow'),

		// autoLoadModel - コントローラの生成時に自動的にロードするモデルファイルを指定
		'autoLoadModel' => array(),

		// autoLoadModule - コントローラの生成時に自動的にロードするモジュールを指定
		'autoLoadModule' => array(),

		// useExtendClass - 各種組み込みオブジェクトのprototype拡張を行う
		// true => 有効
		// false => 無効
		'useBuiltinClassExtend' => false,

		// IEboost - IEのCSSサポートをエミュレート
		// それぞれサポートさせたい機能のみをtrueにしてください。
		// 追加される機能:
		//   canvas::VMLサポート
		//   position:fixedサポート
		//   mix[min]-width[height]サポート
		//   ##但し、はみ出た部分は表示されない[overflow:hidden]。処理負荷軽減のため、動的な追加はFIXまでタイムラグが発生##
		//   image-PNG/background-PNGサポート
		//   border-radiusサポート(初期レンダリングのみ)
		//   ##コンテンツ追加には追従しません。高さが動的に変わる場合は別途FIXメソッドを実行してください(IEFIX.fixBorderRadius())##
		// ## background-PNGとborder-radiusはCanvas-VMLが必要。サポートさせる場合はcanvasVMLをtrueに設定##
		// ## IEboostをONにすると、IE6ではposition:fixedサポートのため、DOMツリーの改変が行われます。セレクタ検索の場合は注意が必要##
		'IEboost' => array(
			'canvasVML'    => false,
			'positionFix'  => true,
			'MinMax'       => false,
			'PNG'          => false,
			'borderRadius' => false
		),

		// routindMode - コントローラとメソッドを決定する
		// none - ルーティング無し。グローバルに設定に基づいたオブジェクトが生成される
		// default - このファイルの<script>タグのid属性からコントローラを生成
		// segment - URIからコントローラを決定する。siteUrlを除く第一セグメントをコントローラクラス、第二セグメントを初期実行メソッドとする。
		// config - 設定オブジェクトから指定したコントローラをロードさせる。指定コントローラはloadController、指定メソッドはexecMethod[デフォルトはindex]で指定
		// @note segmentモードの場合、第三セグメント以下は配列として初期実行メソッドの引数として渡される。
		'routingMode' => 'config',

		// default controller
		// セグメント無しのURIの場合にインスタンス化するコントローラを定義
		// この設定はroutingModeがdefault、またはsegmentの時に有効
		'defaultController' => 'page',

		// direcotry List
		// この配列で指定した名前がコントローラとマッチすると、パスをつないでディレクトリ内からコントローラをロードする
		// 文字列「auto」を指定すると、PHP側で自動インデックスする（アクセスの度に再帰処理が行われるので生成が遅くなります）
		//'directoryList' => array('dashboard'),
		'directoryList' => 'auto',

		// グローバルラインで使用するオブジェクト名の定義
		'globalNames' => array(
			'Controller' => 'FL',  // routingModeがnoneの場合に使用
			'DOM'        => 'DOM',  // HTML Element操作系オブジェクト
			'Animation'  => 'Animation',  // エフェクト系オブジェクト
			'Helper'     => 'Helper', // ロードされたヘルパー関数格納先（空ならwindowとなる）
			'Module'     => 'Module'  // ロードされたモジュール格納先
		),

		// cookie config - クッキーに関する設定
		'cookieName'      => 'flCookie',  // クッキーにつけるprefix
		'cookieDomain'    => 'document.domain',  // クッキードメイン
		'cookiePath'      => '/',  // クッキーを利用可能とするパス
		'cookieMaxAge'    => 7,  // デフォルトのクッキー有効期限（日）
		'cookieDelimiter' => '&',  //クッキーを分割するデリミタ
		'cookieSeparator' => ':',  // クッキーを分割するセパレータ

		// sessionName - セッションに関する設定
		'sessionName' => 'flSession',  // セッション名

				// AjaxMaxConnection - Ajax同時通信数上限
		// デフォルト4,上限なしの場合は0をセット
		// 上限なしの場合、最大同時接続数はクライアントに依存します。
		// 参考：IE7-(XMLHttpRequest、ActiceX含む）の場合、HTTP1.0で最大2、HTTP1.1で最大4
		//           IE8はブロードバンド接続の場合、HTTP1.0/HTTP1.1共に6
		//          @see http://msdn.microsoft.com/ja-jp/library/cc304129%28VS.85%29.aspx
		//          Firefox3.5は初期値6（ただしチューニングした場合はこの限りではない）、2系は初期値4
		//          @see about:config → network.http.max-persistent-connections-per-server
		//          Safari3.x+ は4
		//          Opera9.x系は4
		//          Opera10.x系は8?
		//          Google Chromeは多分4
		// 推奨値4、上限を超えた場合は待機状態となり、それまでのリクエストが完了した後、順に実行される
		'AjaxMaxConnection' => 4

);