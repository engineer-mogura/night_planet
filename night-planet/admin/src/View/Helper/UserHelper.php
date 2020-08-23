<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * User helper
 */
class UserHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * ユーザ情報を返す.
     *
     * @var array
     */
    function get_u_info(String $str)
    {
		switch ($str) {
			// IDを返す
			case 'id';
				$rtn = $this->_View->viewVars['userInfo']['id'];
				return $rtn;
			break;
			// 名前を返す
			case 'name';
				$rtn = !empty($this->_View->viewVars['userInfo']['name']) ?
					$this->_View->viewVars['userInfo']['name'] : 'ゲストさん';
				return $rtn;
			break;
			// メールアドレスを返す
			case 'email';
				$rtn = $this->_View->viewVars['userInfo']['email'];
				return $rtn;
			break;
			// 登録日を返す
			case 'created';
				$rtn = $this->_View->viewVars['userInfo']['created'];
				return $rtn;
			break;
			// アイコン画像のパスを返す。画像がない場合は共通画像を返す
			case 'icon';
				$rtn = !empty($this->_View->viewVars['userInfo']['icon_path']) ?
					$this->_View->viewVars['userInfo']['icon_path'] : PATH_ROOT['NO_IMAGE02'];
				return $rtn;
			break;
			// 認証状況を返す
			case 'is_auth';
				!empty($this->_View->viewVars['userInfo']) ? $rtn = true : $rtn = false;
				return $rtn;
			break;
			default:
				return '';
			break;
      }
    }

    /**
     * ログイン状況によるお気に入りボタンのHTMLを返す.
     *
     * @var array
     */
    function get_favo_html(string $type, Object $entity)
    {
		switch ($type) {
			// メイントップ、エリアトップ画面時のお気に入りボタンを返す
			case preg_match("/new_info/", $type) == 1;
				return $this->get_favo_html_type_new_info($type, $entity);
			break;
			case 'view_info';
				return $this->get_favo_html_type_view_info($entity);
			break;
			// 店舗、スタッフのヘッダお気に入りボタンを返す
			case 'header';
				return $this->get_favo_html_type_header($entity);
			break;
			// スタッフリストのお気に入りボタンを返す
			case 'staff_list';
				return $this->get_favo_html_type_staff_list($entity);
			break;
			// 検索画面時のお気に入りボタンを返す
			case 'search';
				return $this->get_favo_html_type_search($entity);
			break;
			// モーダル画面時のお気に入りボタンを返す
			case 'modal';
				return $this->get_favo_html_type_modal($entity);
			break;
			default:
				return '';
			break;
		}
    }

    /**
     * ログイン状況によるコメントボタンのHTMLを返す.
     *
     * @var array
     */
    function get_comment_html(string $type, Object $entity)
    {
		switch ($type) {
			// 店舗ページ画面時のコメントボタンを返す
			case 'header';
				return $this->get_comment_html_type_header($entity);
			break;
		default:
			return '';
		break;
		}
    }

    /**
     * ログイン状況によるログインボタンのHTMLを返す
     *
     * @var array
     */
    function get_login_html()
    {
		if ($this->get_u_info('is_auth')) {
			$html = '<li class="nav-account-login unlock"><a href="/user/users/mypage">'
					. '<i class="material-icons">lock_open</i><span>マイページ</span></a></li>';
		} else {
			$html = '<li class="nav-account-login lock"><a data-target="modal-login" class="modal-trigger">'
					. '<i class="material-icons">lock</i><span>ログイン</span></a></li>';
		}
		return $html;
	}

    /**
     * メイントップ、エリアトップ画面時のお気に入りボタンを返す
     *
     * @var array
     */
    function get_favo_html_type_new_info(string $type, Object $entity)
    {
		$count    = 0;
		$alias = $entity->registry_alias;

		// お気に入り数
		// 店舗
		if ($alias == 'shop_infos') {
			if (count($entity->shop_info_likes) > 0) {
				$count    = $entity->shop_info_likes[0]->total;
			}
		// スタッフ
		} else if ($alias == 'diarys') {
			if (count($entity->diary_likes) > 0) {
				$count    = $entity->diary_likes[0]->total;
			}
		}

		$html = '<a class="li-linkbox__a-favorite btn-floating btn lighten-1">'
					. '<i class="material-icons">favorite</i>'
				. '</a>'
				. '<span class="tabs-new-info__ul_li__favorite__count count">'.$count.'</span>';
		return $html;
	}

    /**
     * 新着情報表示用いいねボタンを返す
     *
     * @var array
     */
    function get_favo_html_type_view_info(Object $entity)
    {
		$count    = 0;
		$alias = $entity->registry_alias;

		// お気に入り数
		// 店舗
		if ($alias == 'shop_infos') {
			if (count($entity->shop_info_likes) > 0) {
				$count    = $entity->shop_info_likes[0]->total;
			}
		// スタッフ
		} else if ($alias == 'diarys') {
			if (count($entity->diary_likes) > 0) {
				$count    = $entity->diary_likes[0]->total;
			}
		}

		$html = '<a class="li-linkbox__a-favorite btn-floating btn lighten-1">'
					. '<i class="material-icons">favorite</i>'
				. '</a>'
				. '<span class="modal-footer__a-favorite__count count">'.$count.'</span>';

		return $html;
    }

    /**
     * 店舗、スタッフのヘッダお気に入りボタンを返す
     *
     * @var array
     */
    //function get_favo_html_type_header(string $alias, int $unique_id = null, int $id, bool $is_favo)
    function get_favo_html_type_header(Object $entity)
    {
		$count    = 0;
		$alias = $entity->registry_alias;

		// お気に入り数
		// 店舗
		if ($alias == 'shops') {
			if (count($entity->shop_likes) > 0) {
				$count    = $entity->shop_likes[0]->total;
			}
		// スタッフ
		} else if ($alias == 'casts') {
			if (count($entity->cast_likes) > 0) {
				$count    = $entity->cast_likes[0]->total;
			}
		}

		$html = '<a class="btn-floating btn red">'
					. '<i class="material-icons">favorite</i>'
				. '</a>'
				. '<span class="cast-head-line1__ul_li__favorite__count count">'.$count.'</span>';

		return $html;
    }

    /**
     * 店舗ページ画面時のコメントボタンを返す
     *
     * @var array
     */
    function get_comment_html_type_header(Object $entity)
    {
		$count    = 0;
		// $alias = $entity->registry_alias;

		// お気に入り数
		// 店舗
		if ($alias == 'shops') {
			if (count($entity->shop_likes) > 0) {
				$count    = $entity->shop_likes[0]->total;
			}
		// スタッフ
		} else if ($alias == 'casts') {
			if (count($entity->cast_likes) > 0) {
				$count    = $entity->cast_likes[0]->total;
			}
		}
		$html = '<a class="btn-floating btn red lighten-1">'
					. '<i class="material-icons">comment</i>'
				. '</a>'
				. '<span class="cast-head-line1__ul_li__voice__count count">'.$count.'</span>';

      return $html;
	}

	/**
     * モーダル画面時のお気に入りボタンを返す
     *
     * @var array
     */
    function get_favo_html_type_modal(Object $entity)
    {
		$html = '<a class="modal-footer__a-favorite btn-floating btn grey lighten-1">'
					. '<i class="material-icons">favorite</i>'
				. '</a>'
				. '<span class="modal-footer__a-favorite__count count">0</span>';
		return $html;
    }
}
