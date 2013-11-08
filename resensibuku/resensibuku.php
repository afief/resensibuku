<?php
/**
 * @package resensibuku
 */
/*
Plugin Name: Resensi Buku
Plugin URI: http://afief.net
Description: Menyediakan Kolom dan Postingan khusus untuk resensi buku, dengan thumbnail gambar untuk mempercantik kolom
Version: 1
Author: Afief
Author URI: http://afief.net
License: GPLv2 or later
*/

function resensi_init() {
  $labels = array(
    'name'               => 'Resensi Buku',
    'singular_name'      => 'Resensi Buku',
    'add_new'            => 'Tambah Resensi',
    'add_new_item'       => 'Tambah Resensi Baru',
    'edit_item'          => 'Edit Resensi',
    'new_item'           => 'Resensi Baru',
    'all_items'          => 'Semua Resensi Buku',
    'view_item'          => 'Lihat Resensi',
    'search_items'       => 'Cari Resensi',
    'not_found'          => 'Resensi Tidak Ditemukan',
    'not_found_in_trash' => 'Tidak Ada Resensi Di Kotak Sampah',
    'parent_item_colon'  => '',
    'menu_name'          => 'Resensi Buku'
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'resensibuku' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
	'taxonomies'		 => array("post_tag"),
    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  );

  register_post_type( 'resensibuku', $args );
  
  $labels = array(
		'name'                       => "Kategori Buku",
		'singular_name'              => "Kategori Buku",
		'search_items'               => "Cari Kategori Buku",
		'popular_items'              => "Kategori Terlaris",
		'all_items'                  => "Semua Kategori",
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => "Ubah Kategori",
		'update_item'                => "Update Kategori",
		'add_new_item'               => __( 'Tambah Kategori Baru' ),
		'new_item_name'              => __( 'Nama Kategori Baru' ),
		'separate_items_with_commas' => __( 'Pisahkan Dengan Koma' ),
		'add_or_remove_items'        => __( 'Tambah / Hapus Kategori' ),
		'choose_from_most_used'      => __( 'Pilih Dari Kategori Populer' ),
		'not_found'                  => __( 'Kategori Tidak Ditemukan' ),
		'menu_name'                  => __( 'Kategori Resensi Buku' ),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'katbuku' ),
	);

	register_taxonomy( 'katbuku', 'resensibuku', $args );
}
add_action( 'init', 'resensi_init' );


class Resensi_Buku extends WP_Widget {

	function __construct() {
		parent::__construct( 'resensi_buku', 'Resensi Buku', // Name
			array( 'description' => "Daftar Postingan Dari Resensi Buku", ) // Args
		);
	}
	
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo $args['after_widget'];
		
		$args = array(
			'post_type'=> 'resensibuku',
			'order'    => 'ASC'
		);
		query_posts( $args );
		?>
        	<style type="text/css">
				.rthumbnail {
					display:block;
					width: 100%;
					height:150px;
					overflow:hidden;
					float:left;
					margin-bottom:10px;
					position:relative;
				}
				.rthumbnail h4 {
					position: absolute;
					top: 0px;
					padding: 10px 20px;
					background-color: rgba(23, 119, 136, 0.88);
					color: white;
				}
				.rthumbnail:hover h4 {
					background-color: rgba(33, 165, 189, 0.88);
				}
			</style>
		<?php
		while (have_posts()) { the_post();
			if (has_post_thumbnail()) {
				echo '<a class="rthumbnail" href="' . get_permalink() . '">';
					echo get_the_post_thumbnail(get_the_ID(), 'medium');
					echo '<h4>' . get_the_title() . '</h4>';
				echo '</a>';
			}
		}
		
		wp_reset_query();
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title ="Resensi Buku";
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}
function register_resensi_widget() {
    register_widget( 'Resensi_Buku' );
}
add_action( 'widgets_init', 'register_resensi_widget' );


?>