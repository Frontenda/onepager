<?php namespace ThemeXpert\Providers\WordPress;

use ThemeXpert\Providers\Contracts\ContentInterface;

/**
 * Class Content
 * @package ThemeXpert\Providers\WordPress
 */
class Content implements ContentInterface {
	/**
	 * @return array
	 */
	public function getPages() {
		return $this->objAsArray( get_pages(), 'ID', 'post_title' )+[""=>"Select"];
	}

	/**
	 * @param $obj
	 * @param $oKey
	 * @param $oValue
	 *
	 * @return array
	 */
	protected function objAsArray( $obj, $oKey, $oValue ) {
		$arr = [ ];

		array_walk( $obj, function ( $v, $k ) use ( &$arr, $oKey, $oValue ) {
			$arr[ $v->{$oKey} ] = $v->{$oValue};
		} );

		return $arr;
	}

	/**
	 *
	 */
	public function getPosts() {
		// TODO: Implement getPosts() method.
	}

	/**
	 * @return array
	 */
	public function getMenus() {
		return $this->objAsArray( get_terms( 'nav_menu', array('hide_empty'=> 0) ), 'term_id', 'name' ) + [""=>"Select"];
	}

	/**
	 * @return array
	 */
	public function getCategories() {
		return [""=>"select"]+$this->objAsArray( get_terms( 'category', array('hide_empty'=> 0) ), 'term_id', 'name' );
	}

	/**
	 *
	 */
	public function getMenuLocations() {
		// TODO: Implement getMenuLocations() method.
	}

  public function isBuildMode() {
      return is_super_admin() && $this->isOnepage() && (array_key_exists('onepager', $_GET) ? (int) $_GET['onepager'] : 0);
  }

	public function isOnepagerByTemplate(){
		$template = get_post_meta( $this->getCurrentPageId(), '_wp_page_template', true );

		//template name is onepage.php or op-*.php
    return ( $template == "onepage.php" || substr($template, 0, 3) == "op-" ) ? true : false;
	}

	public function isOnepagerByMeta() {
		$onepager = get_post_meta( $this->getCurrentPageId(), '_onepager_updated', true );

		return $onepager ? true : false;
	}

	public function isOnepage() {
		return $this->isOnepagerByTemplate() || $this->isOnepagerByMeta();
	}

	public function getCurrentPageId() {
		global $post;

		return $post && $post->ID ? $post->ID : null;
	}
}
