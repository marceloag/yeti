<?php

define('THEME_DIR', get_template_directory_uri());
define('THEME_IMGS', get_template_directory_uri() .'/images');

/**
 * Add support for different features
 */
if ( function_exists('add_theme_support') ) {
  
  // Add Menu Support
  add_theme_support('menus');
  
  // Add Thumbnail Support
  add_theme_support('post-thumbnails');
  add_image_size( 'yeti-featured', 220, 140, true );
  add_image_size( 'blog-img', 597, 300, true ); 
  
  // Add Multi-Post Format Support
  add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );
  
}

/**
 * Load custom scripts
 */
function yeti_scripts() {
  wp_register_script( 'modernizer', THEME_DIR .'/js/foundation/modernizr.foundation.js', array( 'jquery' ), '1', false );
  wp_enqueue_script( 'modernizer' );
  
  wp_register_script( 'html5-shim', THEME_DIR .'/js/html5.js', array( 'jquery' ), '1', false );
  wp_enqueue_script( 'html5-shim' );
}
add_action( 'wp_enqueue_scripts', 'yeti_scripts' );

/**
 * Load custom styles
 */
function yeti_styles() {  
    //wp_register_style( 'screen-style', THEME_DIR . '/css_path/screen.css', array(), '1', 'all' );  
    //wp_enqueue_style( 'screen-style' );  
}  
add_action( 'wp_enqueue_scripts', 'yeti_styles' ); 

/**
 * Enable threaded comments
 */
function enable_threaded_comments() {
  if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
    wp_enqueue_script('comment-reply');
  }
}
add_action( 'get_header', 'enable_threaded_comments' );

/**
 * Define custom excerpt length
 */
function yeti_excerpt_length( $length ) {
  if ( is_front_page() ) {
  	return 17;
  } 
  
  return 55;
}
add_filter( 'excerpt_length', 'yeti_excerpt_length', 999 );

/**
 * Define custom excerpt style
 */
function yeti_excerpt_more( $more ) {
  return '...';
}
add_filter( 'excerpt_more', 'yeti_excerpt_more' );
  
/**
 * Remove generator meta tag
 */
remove_action('wp_head', 'wp_generator');

/**
 * Setup a dynamic sidebar
 */
function yeti_widgets_init() {
  register_sidebar( array(
    'name' => 'Main Sidebar',
    'id' => 'main-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => "</aside>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>'
  ));
}
add_action('widgets_init', 'yeti_widgets_init');

/**
 * Create a pagination for posts
 */
function yeti_pagination() {
  global $wp_query;
  $big = 999999999;
  echo paginate_links( array(
    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    'format' => '?paged=%#%',
    'current' => max( 1, get_query_var('paged') ),
    'total' => $wp_query->max_num_pages )
  );
}
add_action( 'init', 'yeti_pagination' );

/**
 * Add Menu support in the header
 */
function register_my_menus() {
  register_nav_menus(
    array( 'header-menu' => __( 'Header Menu' ) )
  );
}
add_action( 'init', 'register_my_menus' );


// Close the main PHP statement
?>

<?php
/**
 * Custom comments
 */ 
function yeti_comments($comment, $args, $depth) {

  $GLOBALS['comment'] = $comment; ?>
  <li <?php comment_class(); ?>>
    <article id="comment-<?php comment_ID(); ?>" class="panel">
      <div class="comment-author vcard row">
        <div class="twelve columns">
          <!--<div class="
              <?php
              $authID = get_the_author_meta('ID');
                                          
              if($authID == $comment->user_id)
                  echo "panel callout";
              else
                  echo "panel";
              ?>
          ">-->
                       <!-- <div class="row">
                    <div class="avatar two columns">-->
                      <?php echo get_avatar($comment, 60 ); ?>
                    <!--</div>
                    <div class="ten columns">-->
                      <div class="comment-meta">
                        <span class="the-author"><?php echo get_comment_author_link() ?></span>
                        <span class="the-date"><?php comment_time('F jS, Y'); ?></span>
                      </div>
                      
                                
                                <?php if ($comment->comment_approved == '0') : ?>
                                      <div class="alert-box success">
                                          <?php _e('Your comment is awaiting moderation.') ?>
                                        </div>
                                <?php endif; ?>
                                
                                <?php comment_text() ?>
                                
                                <!-- removing reply link on each comment since we're not nesting them -->
                      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                           <!-- </div>
                        </div>
                    </div>-->
                </div>
      </div>
    </article>
    <!-- </li> is added by wordpress automatically -->
<?php
} // don't remove this bracket!
?>
