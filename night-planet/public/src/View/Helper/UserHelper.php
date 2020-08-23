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
		// いいねボタン無効化するか
		if (preg_match("/_favo_disable/", $type)) {
			$is_click = '';
		} else {
			$is_click = 'waves-effect waves-light favo_click ';
		}
		if ($this->get_u_info('is_auth')) {
			$favorite = 'grey';
			$id    = $entity['id'];

			// 店舗ニュース
			if ($alias == 'shop_infos') {

				if (count($entity->shop_info_likes) > 0) {
					// ユーザーがお気に入りしているか
					if ($entity->shop_info_likes[0]->is_like) {
						$favorite = 'red';
					}
				}
				$unique_id = $entity->shop_info_likes['id'];

				$data =  'data-id="'.$unique_id.'" data-shop_info_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';
			// ブログ
			} else if ($alias == 'diarys') {

				if (count($entity->diary_likes) > 0) {
					// ユーザーがお気に入りしているか
					if ($entity->diary_likes[0]->is_like) {
						$favorite = 'red';
					}
				}
				$unique_id = $entity->diary_likes[0]['id'];

				$data =  'data-id='.$unique_id.' data-diary_id='.$id.' data-user_id='.$this->get_u_info('id').' data-alias='.$alias.'"';

			}
				$html = '<a class="li-linkbox__a-favorite btn-floating btn '. $is_click .$favorite.' lighten-1 ' . $data . '>'
							. '<i class="material-icons">favorite</i>'
						. '</a>'
						. '<span class="tabs-new-info__ul_li__favorite__count count">'.$count.'</span>';
		} else {
			$html = '<a class="li-linkbox__a-favorite btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
						. '<i class="material-icons">favorite</i>'
					. '</a>'
					. '<span class="tabs-new-info__ul_li__favorite__count count">'.$count.'</span>';
		}
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
		if ($this->get_u_info('is_auth')) {
			$favorite = 'grey';
			$id    = $entity['id'];

			// 店舗ニュース
			if ($alias == 'shop_infos') {

				if (count($entity->shop_info_likes) > 0) {
					// ユーザーがお気に入りしているか
					if ($entity->shop_info_likes[0]->is_like) {
						$favorite = 'red';
					}
				}
				$unique_id = $entity->shop_info_likes['id'];

				$data =  'data-id="'.$unique_id.'" data-shop_info_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';
			// ブログ
			} else if ($alias == 'diarys') {

				if (count($entity->diary_likes) > 0) {
					// ユーザーがお気に入りしているか
					if ($entity->diary_likes[0]->is_like) {
						$favorite = 'red';
					}
				}
				$unique_id = $entity->diary_likes[0]['id'];

				$data =  'data-id='.$unique_id.' data-diary_id='.$id.' data-user_id='.$this->get_u_info('id').' data-alias='.$alias.'"';

			}
				$html = '<a class="li-linkbox__a-favorite btn-floating btn '. $is_click .$favorite.' lighten-1 favo_click ' . $data . '>'
							. '<i class="material-icons">favorite</i>'
						. '</a>'
						. '<span class="modal-footer__a-favorite__count count">'.$count.'</span>';
		} else {
			$html = '<a class="li-linkbox__a-favorite btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
						. '<i class="material-icons">favorite</i>'
					. '</a>'
					. '<span class="modal-footer__a-favorite__count count">'.$count.'</span>';
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

		if ($this->get_u_info('is_auth')) {
			$favorite = 'grey';
			$id    = $entity['id'];
			// 店舗
			if ($alias == 'shops') {

				if (count($entity->shop_likes) > 0) {
					// ユーザーがお気に入りしているか
					if ($entity->shop_likes[0]->is_like) {
						$favorite = 'red';
					}
				}
				$unique_id = $entity->shop_likes['id'];

				$data =  'data-id="'.$unique_id.'" data-shop_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';

			// スタッフ
			} else if ($alias == 'casts') {

				if (count($entity->cast_likes) > 0) {
					// ユーザーがお気に入りしているか
					if ($entity->cast_likes[0]->is_like) {
						$favorite = 'red';
					}
				}
				$unique_id = $entity->cast_likes[0]['id'];

				$data =  'data-id="'.$unique_id.'" data-cast_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';

			}
			$html =   '<a class="btn-floating btn waves-effect waves-light '.$favorite.' lighten-1 favo_click" ' . $data . '>'
						. '<i class="material-icons">favorite</i>'
					. '</a>'
					. '<i class="favorite-add material-icons">add</i>'
					. '<span class="cast-head-line1__ul_li__favorite__count count">'.$count.'</span>';
		} else {
			$html = '<a class="btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
						. '<i class="material-icons">favorite</i>'
					. '</a>'
					. '<i class="favorite-add material-icons">add</i>'
					. '<span class="cast-head-line1__ul_li__favorite__count count">'.$count.'</span>';
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
		$count    = 0;
		$alias = $entity->registry_alias;

		// お気に入り数
		// スタッフ
		if ($alias == 'casts') {
			if (count($entity->cast_likes) > 0) {
				$count    = $entity->cast_likes[0]->total;
			}
		}

        if ($this->get_u_info('is_auth')) {
			$favorite = 'grey';
			$id    = $entity['id'];
			// スタッフ
			if ($alias == 'casts') {

				if (count($entity->cast_likes) > 0) {
					// ユーザーがお気に入りしているか
					if ($entity->cast_likes[0]->is_like) {
						$favorite = 'red';
					}
				}
				$unique_id = $entity->shop_likes['id'];

				$data =  'data-id="'.$unique_id.'" data-cast_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';

			}
			$html = '<a class="p-casts-section__list__favorite btn-floating btn waves-effect waves-light '.$favorite.' lighten-1 favo_click" ' . $data . '>'
					. '<i class="material-icons p-casts-section__list__favorite__icon">favorite</i>'
					. '</a>'
					. '<span class="casts-section__list__favorite__count count">'.$count.'</span>';

        } else {
			$html = '<a class="p-casts-section__list__favorite btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
					. '<i class="material-icons p-casts-section__list__favorite__icon">favorite</i>'
					. '</a>'
					. '<span class="casts-section__list__favorite__count count">'.$count.'</span>';
        }
      return $html;
    }

    /**
     * 検索画面時のお気に入りボタンを返す
     *
     * @var array
     */
    function get_favo_html_type_search(Object $entity)
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

        if ($this->get_u_info('is_auth')) {
			$favorite = 'grey';
			$id    = $entity['id'];
			// スタッフ
			if ($alias == 'casts') {

				if (count($entity->cast_likes) > 0) {
					// ユーザーがお気に入りしているか
					if ($entity->cast_likes[0]->is_like) {
						$favorite = 'red';
					}
				}
				$unique_id = $entity->cast_likes['id'];

				$data =  'data-id="'.$unique_id.'" data-cast_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';

			// スタッフ
			} else if ($alias == 'shops') {

				if (count($entity->shop_likes) > 0) {
					// ユーザーがお気に入りしているか
					if ($entity->shop_likes[0]->is_like) {
						$favorite = 'red';
					}
				}
				$unique_id = $entity->shop_likes[0]['id'];

				$data =  'data-id="'.$unique_id.'" data-shop_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';

			}
			$html = '<a class="p-casts-section__list__favorite btn-floating btn waves-effect waves-light '.$favorite.' lighten-1 favo_click" ' . $data . '>'
					. '<i class="material-icons p-casts-section__list__favorite__icon">favorite</i>'
					. '</a>'
					. '<span class="search-result__like-count count">'.$count.'</span>';

        } else {
			$html = '<a class="p-casts-section__list__favorite btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
					. '<i class="material-icons p-casts-section__list__favorite__icon">favorite</i>'
					. '</a>'
					. '<span class="search-result__like-count count">'.$count.'</span>';
        }
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

		// // お気に入り数
		// // 店舗
		// if ($alias == 'shops') {
		// 	if (count($entity->shop_likes) > 0) {
		// 		$count    = $entity->shop_likes[0]->total;
		// 	}
		// // スタッフ
		// } else if ($alias == 'casts') {
		// 	if (count($entity->cast_likes) > 0) {
		// 		$count    = $entity->cast_likes[0]->total;
		// 	}
		// }

        if ($this->get_u_info('is_auth')) {
			$favorite = 'grey';
			// $id    = $entity['id'];
			// // 店舗
            // if ($alias == 'shops') {
            //     if (count($entity->shop_likes) > 0) {
            //         $favorite = 'red';
            //     }
            //     $unique_id = $entity->shop_likes['id'];

            //     $data =  'data-id="'.$unique_id.'" data-shop_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';

            // // スタッフ
            // } else if ($alias == 'casts') {

			// 	if (count($entity->cast_likes) > 0) {
			// 		// ユーザーがお気に入りしているか
			// 		if ($entity->cast_likes[0]->is_like) {
			// 			$favorite = 'red';
			// 		}
			// 	}
			// 	$unique_id = $entity->shop_likes['id'];

			// 	$data =  'data-id="'.$unique_id.'" data-cast_id="'.$id.'" data-user_id="'.$this->get_u_info('id').'" data-alias="'.$alias.'"';
			// }

			$html = '<a class="btn-floating btn waves-effect waves-light '.$favorite.' lighten-1" ' . $data . '>'
						. '<i class="material-icons">comment</i>'
					. '</a>'
					. '<i class="favorite-add material-icons">add</i>'
					. '<span class="cast-head-line1__ul_li__voice__count count">'.$count.'</span>';

		} else {
			$html = '<a class="btn-floating btn waves-effect waves-light red modal-trigger" data-target="modal-login">'
						. '<i class="material-icons">comment</i>'
					. '</a>'
					. '<i class="favorite-add material-icons">add</i>'
					. '<span class="cast-head-line1__ul_li__voice__count count">'.$count.'</span>';
		}
      return $html;
	}

	/**
     * モーダル画面時のお気に入りボタンを返す
     *
     * @var array
     */
    function get_favo_html_type_modal(Object $entity)
    {
		if ($this->get_u_info('is_auth')) {

			$alias = $entity->registry_alias;

			// 店舗ニュース
			if ($alias == 'shop_infos') {
				$data =  'data-id="" data-shop_info_id="" data-user_id='.$this->get_u_info('id').' data-alias='.$alias.'';
			// ブログ
			} else if ($alias == 'diarys') {
				$data =  'data-id="" data-diary_id="" data-user_id='.$this->get_u_info('id').' data-alias='.$alias.'';
			}
				$html = '<a class="modal-footer__a-favorite btn-floating btn grey lighten-1 favo_click" ' . $data . '>'
							. '<i class="material-icons">favorite</i>'
						. '</a>'
						. '<span class="modal-footer__a-favorite__count count">0</span>';
		} else {
			$html = '<a class="modal-footer__a-favorite btn-floating btn waves-effect waves-light grey lighten-1 modal-trigger" data-target="modal-login">'
						. '<i class="material-icons">favorite</i>'
					. '</a>'
					. '<span class="modal-footer__a-favorite__count count">0</span>';
		}
		return $html;
    }
}
