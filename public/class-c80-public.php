<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    c80
 * @subpackage c80/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    c80
 * @subpackage c80/public
 * @author     Your Name <email@example.com>
 */
class c80_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $c80    The ID of this plugin.
	 */
	private $c80;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $c80       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $c80, $version ) {

		$this->c80 = $c80;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in c80_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The c80_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->c80, plugin_dir_url( __FILE__ ) . 'css/c80-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in c80_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The c80_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->c80, plugin_dir_url( __FILE__ ) . 'js/c80-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Replaces classic content with special custom fields by paragraph
	 *
	 * @since    1.0.0
	 */
	public function c80_content( $content, $limit = NULL, $afterp = true ) {
		
		global $post;
		
		$post_type = get_post_type( $post );
		
		if($post_type == 'c80_cpt' && class_exists('RW_Meta_Box')) {
		
			if(rwmb_meta( 'c80_parrafo', $post->ID )) {
		
				//Reemplazo la variable de contenido
		
				$content = '';
				
		
				$parrafos = rwmb_meta('c80_parrafo', 'multiple=true', $post->ID );
		
				//var_dump($parrafos);
				
				foreach(  $parrafos as $key=>$parrafo ) {
					$extraclasses = '';
					
					$relids = $this->c80_relp( $this->c80_pid( $key, $post->ID ) );
					
					if($relids != 0) {
						$relids = implode($relids, ', ');
						$relids = ' data-relids="'. $relids . '"';
						$extraclasses = 'con-rel';
					} else {
						$relids = '';
					}
					//El ID de cada párrafo es una suma del ID del post más el orden en los campos personalizados
					//Con eso podemos buscar contenidos relacionados en base al ID del párrafo

					$content .= '<a class="c80_p ' . $extraclasses . '" href="#" id="'. $this->c80_name($post->ID, $key) .'" name="'. $this->c80_name($post->ID, $key) .'" data-pid="'. $this->c80_pid($key, $post->ID) . '" data-order="' . $key . '" ' . $relids . '" data-link="' . $this->c80_permalink($post->ID, $key) . '"><p>' . $parrafo . '</p></a>';
					if($afterp == true) {
						$content .= $this->c80_afterp( $post->ID, $key );
					}
				}
		
			}
		
		}
		
		return $content;
	}

	/**
	 * Generates paragraph permalink in article
	 *
	 * @since    1.0.0
	 */	
	public function c80_permalink( $postid, $key ) {
		return get_permalink($postid) . '#parrafo-' . $this->c80_pid($key, $postid);
	}

	/**
	 * Generates paragraph permalink in capitulo
	 *
	 * @since    1.0.0
	 */	
	public function c80_permalink_chapter( $postid, $key ) {
		$parents = get_post_ancestors( $postid );
		if($parents) {
			$parents = array_reverse($parents);
			$tops = $parents[0];
			return get_permalink($tops) . '#parrafo-' . $this->c80_pid($key, $postid);
		}
	}



	/**
	 * Generates paragraph name
	 *
	 * @since    1.0.0
	 */	
	public function c80_name( $postid, $key ) {
		return 'parrafo-' . $this->c80_pid($key, $postid);
	}

	/**
	 * Generates utilites after paragraph 
	 *
	 * @since    1.0.0
	 */
	public function c80_afterp( $postid, $key ) {
		
		$html = '<p class="afterp">';
		
		$html .= '<a href="#comment">Comentar</a> | <a href="#compartir">Compartir</a> | <a href="#citar">Citar</a> | <a href="' . $this->c80_permalink($postid, $key) . '">Permalink</a>';
		
		$html .= '</p>';

		$nothing = '';
		return $nothing;//$html;
	}

	/**
	 * Generates paragraph ID
	 *
	 * @since    1.0.0
	 */
	public function c80_pid( $key, $postid ) {
		return $key . '-' . $postid;
	}

	/**
	 * Devuelve un post ID de un Párrafo ID
	 */
	public function c80_invpid( $parrafoid ) {
		$parrafoid = explode('-', $parrafoid);
		//La segunda clave del array es la del ID del artículo
		return $parrafoid[1];
	}

	/**
	 * Fetches paragraph-related content
	 * Returns an array of related IDs or 0 in empty cases
	 *
	 * @since    1.0.0
	 */
	public function c80_relp( $parid ) {
		$args = array(
			'post_type' => 'any',
			'numberposts' => 100,
			'meta_query' => array(
				array(
					'key' => 'c80_parraforel',
					'value' => $parid
					)
				)
			);
		$prels = get_posts($args);
		if($prels) {
			$prelids = array();
			foreach($prels as $prel) {
				$prelids[] = $prel->ID;
			}
			return $prelids;	
		} else {
			return 0;
		}
	}

	/**
	 * Fetches article-related content
	 * Returns an array of related IDs or 0 in empty cases
	 *
	 * @since    1.0.0
	 */
	public function c80_relart( $artid ) {
		$args = array(
			'post_type' => 'any',
			'numberposts' => 100,
			'meta_query' => array(
				array(
					'key' => 'c80_artrel',
					'value' => $artid
					)
				)
			);
		$artrels = get_posts($args);
		if($artrels) {
			$artids = array();
			foreach($artrels as $artrel) {
				$artids[] = $artrel->ID;
			}
			return $artids;
		} else {
			return 0;
		}
	}
}