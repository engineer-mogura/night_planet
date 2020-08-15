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
			// 名前を返す
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
			case 'new_info';
				return $this->get_favo_html_type_new_info($alias, $id);
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
				return $this->get_favo_html_type_search($alias, $id);
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
    function get_comment_html(string $type, string $alias, int $id)
    {
		switch ($type) {
			// 店舗ページ画面時のコメントボタンを返す
			case 'header';
				return $this->get_comment_html_type_header($alias, $id);
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
    function get_favo_html_type_new_info(string $alias, int $id)
    {
		if ($this->get_u_info('is_auth')) {
			$html = '<a class="li-linkbox__a-favorite btn-floating btn waves-effect waves-light grey lighten-1 favo_click" data-id="'.$id.'" data-alias="'.$alias.'">'
						. '<i class="material-icons">favorite</i>'
					. '</a>';
		} else {
			$html = '<a class="li-linkbox__a-favorite btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
						. '<i class="material-icons">favorite</i>'
					. '</a>';
		}
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

		if ($this->get_u_info('is_auth')) {
			$favorite = 'grey';

			$alias = $entity->registry_alias;
			$id    = $entity['id'];
			// 店舗
			if ($alias == 'shops') {

				if (count($entity->shop_likes) > 0) {
					$favorite = 'red';
				}
				$unique_id = $entity->shop_likes['id'];

				$data =  'data-id="'.$unique_id.'" data-shop_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';

			// スタッフ
			} else if ($alias == 'casts') {

				if (count($entity->cast_likes) > 0) {
					$favorite = 'red';
				}
				$unique_id = $entity->cast_likes[0]['id'];

				$data =  'data-id="'.$unique_id.'" data-cast_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';

			}
			$html = '<a class="btn-floating btn waves-effect waves-light '.$favorite.' lighten-1 favo_click" ' . $data . '>'
						. '<i class="material-icons">favorite</i>'
					. '</a>';
		} else {
		$html = '<a class="btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
					. '<i class="material-icons">favorite</i>'
				. '</a>';
		}
		return $html;
    }

    /**
     * スタッフリストのお気に入りボタンを返す
     *
     * @var array
     */
    //function get_favo_html_type_staff_list(string $alias, int $id, bool $is_favo)
    function get_favo_html_type_staff_list(Object $entity)
    {
        if ($this->get_u_info('is_auth')) {
			$favorite = 'grey';

			$alias = $entity->registry_alias;
			$id    = $entity['id'];
			// スタッフ
			if ($alias == 'casts') {

				if (count($entity->cast_likes) > 0) {
					$favorite = 'red';
				}
				$unique_id = $entity->shop_likes['id'];

				$data =  'data-id="'.$unique_id.'" data-cast_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';

			}
			$html = '<a class="p-casts-section__list__favorite btn-floating btn waves-effect waves-light '.$favorite.' lighten-1 favo_click" ' . $data . '>'
					. '<i class="material-icons p-casts-section__list__favorite__icon">favorite</i>'
					. '</a>';

        } else {
			$html = '<a class="p-casts-section__list__favorite btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
					. '<i class="material-icons p-casts-section__list__favorite__icon">favorite</i>'
					. '</a>';
        }
      return $html;
    }

    /**
     * 検索画面時のお気に入りボタンを返す
     *
     * @var array
     */
    function get_favo_html_type_search(string $alias, int $id)
    {
		if ($this->get_u_info('is_auth')) {
			$html = '<a class="p-casts-section__list__favorite btn-floating btn waves-effect waves-light grey lighten-1 favo_click" data-id="'.$id.'" data-alias="'.$alias.'">'
						. '<i class="material-icons p-casts-section__list__favorite__icon">favorite</i>'
					. '</a>';
		} else {
			$html = '<a class="p-casts-section__list__favorite btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
						. '<i class="material-icons p-casts-section__list__favorite__icon">favorite</i>'
					. '</a>';
		}
		return $html;
    }

    /**
     * 店舗ページ画面時のコメントボタンを返す
     *
     * @var array
     */
    function get_comment_html_type_header(string $alias, int $id)
    {
		if ($this->get_u_info('is_auth')) {
			$html = '<a class="btn-floating btn waves-effect waves-light grey favo_click" data-id="'.$id.'" data-alias="'.$alias.'">'
						. '<i class="material-icons">comment</i>'
					. '</a>';
		} else {
			$html = '<a class="btn-floating btn waves-effect waves-light red modal-trigger" data-target="modal-login">'
						. '<i class="material-icons">comment</i>'
					. '</a>';
		}
      return $html;
    }
}
