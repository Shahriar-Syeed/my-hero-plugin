<?php

/**
 * Plugin Name: WC Product Hero Manager
 * Description: Manage product hero section from admin dashboard.
 */

if (!defined('ABSPATH')) exit;

/*------------------------------------
 ENQUEUE FILES
------------------------------------*/
add_action('wp_enqueue_scripts', function () {

  if (!is_product()) return;

  wp_enqueue_style('swiper-css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.css');
  wp_enqueue_script('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.js', [], null, true);

  wp_enqueue_script(
    'swiper-init',
    plugin_dir_url(__FILE__) . 'assets/js/swiper-init.js',
    ['swiper-js'],
    null,
    true
  );

  wp_enqueue_style(
    'hero-style',
    plugin_dir_url(__FILE__) . 'assets/css/style.css'
  );
});


/*------------------------------------
 ADMIN MENU
------------------------------------*/
add_action('admin_menu', function () {
  add_menu_page(
    'Hero Settings',
    'Hero Settings',
    'manage_options',
    'hero-settings',
    'hero_settings_page'
  );
});


/*------------------------------------
 REGISTER SETTINGS
------------------------------------*/
add_action('admin_init', function () {

  register_setting('hero_settings_group', 'hero_video_url');
  register_setting('hero_settings_group', 'hero_heading');
  register_setting('hero_settings_group', 'hero_button_text');
  register_setting('hero_settings_group', 'testimonial_heading');
  register_setting('hero_settings_group', 'hero_slider_images');
});


/*------------------------------------
 ADMIN PAGE HTML
------------------------------------*/
function hero_settings_page()
{
?>
  <div class="wrap">
    <h1>Hero Section Settings</h1>

    <form method="post" action="options.php">
      <?php settings_fields('hero_settings_group'); ?>
      <?php do_settings_sections('hero_settings_group'); ?>

      <table class="form-table">

        <tr>
          <th>Video URL</th>
          <td>
            <input type="text" name="hero_video_url"
              value="<?php echo esc_attr(get_option('hero_video_url')); ?>"
              class="regular-text">
          </td>
        </tr>

        <tr>
          <th>Main Heading</th>
          <td>
            <textarea name="hero_heading" rows="4"
              class="large-text"><?php echo esc_textarea(get_option('hero_heading')); ?></textarea>
          </td>
        </tr>

        <tr>
          <th>Button Text</th>
          <td>
            <input type="text" name="hero_button_text"
              value="<?php echo esc_attr(get_option('hero_button_text')); ?>"
              class="regular-text">
          </td>
        </tr>

        <tr>
          <th>Testimonial Heading</th>
          <td>
            <textarea name="testimonial_heading" rows="4"
              class="large-text"><?php echo esc_textarea(get_option('testimonial_heading')); ?></textarea>
          </td>
        </tr>

        <tr>
          <th>Slider Image URLs (One per line)</th>
          <td>
            <textarea name="hero_slider_images" rows="6"
              class="large-text"><?php echo esc_textarea(get_option('hero_slider_images')); ?></textarea>
          </td>
        </tr>

      </table>

      <?php submit_button(); ?>

    </form>
  </div>
<?php
}


/*------------------------------------
 FRONTEND OUTPUT
------------------------------------*/
add_action('woocommerce_before_single_product', function () {

  if (!is_product()) return;

  $video_url    = esc_url(get_option('hero_video_url'));
  $main_heading = wp_kses_post(get_option('hero_heading'));
  $button_text  = esc_html(get_option('hero_button_text'));
  $testimonial_heading = wp_kses_post(get_option('testimonial_heading'));
  $images       = explode("\n", get_option('hero_slider_images'));

?>

  <div class="hero-container">

    <h2 class="main-heading"><?php echo $main_heading; ?></h2>

    <?php if ($video_url): ?>
      <div class="video-wrapper">
        <iframe src="<?php echo $video_url; ?>" frameborder="0" allowfullscreen></iframe>
      </div>
    <?php endif; ?>

    <a href="#checkout-section" class="hero-btn">
      <?php echo $button_text; ?>
    </a>

    <?php if (!empty($images)) : ?>
      <h2 class="heading"><?php echo $testimonial_heading; ?></h2>
      <div class="swiper mySwiper">
        <div class="swiper-wrapper">

          <?php foreach ($images as $img) :
            $img = trim($img);
            if (!$img) continue;
          ?>
            <div class="swiper-slide">
              <img src="<?php echo esc_url($img); ?>" alt="">
            </div>
          <?php endforeach; ?>

        </div>
      </div>
    <?php endif; ?>

  </div>

<?php
}, 5);
