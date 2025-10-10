<?php

function twentytwentyfive_child_enqueue_styles() {
  wp_enqueue_style(
    'parent-style',
    get_template_directory_uri() . '/style.css'
  );

  wp_enqueue_style(
    'child-style',
    get_stylesheet_directory_uri() . '/style.css',
    array('parent-style')
  );

  wp_enqueue_style(
    'components-style',
    get_stylesheet_directory_uri() . '/css/components.css',
    array('child-style'),
    filemtime(get_stylesheet_directory() . '/css/components.css')
  );

  if (is_page('contact-us')) {
    wp_enqueue_style(
      'contact-style',
      get_stylesheet_directory_uri() . '/contact-us.css',
      array('components-style'),
      filemtime(get_stylesheet_directory() . '/contact-us.css')
    );
  }

  if (is_page('about-us')) {
    wp_enqueue_style(
      'about-style',
      get_stylesheet_directory_uri() . '/about.css',
      array('components-style'),
      filemtime(get_stylesheet_directory() . '/about.css')
    );
  }

  if ( is_page( 'art-gallery' ) ) {
  wp_enqueue_style(
    'art-gallery-style',
    get_stylesheet_directory_uri() . '/art-gallery.css',
    array('components-style'),
    filemtime(get_stylesheet_directory() . '/art-gallery.css')
  );
}
}
add_action('wp_enqueue_scripts', 'twentytwentyfive_child_enqueue_styles');


function render_current_events_shortcode() {
    $cities = array('auckland', 'christchurch');
    $today = date('Ymd');

    ob_start();
    echo '<section class="current-events">';
    echo '<h2>Current Events</h2>';
    echo '<div class="events-grid">';

    foreach ($cities as $city) {
        $args = array(
            'post_type' => 'activities',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'location',
                    'field' => 'slug',
                    'terms' => $city,
                ),
            ),
        );

        $events = new WP_Query($args);
        $found = false;

        if ($events->have_posts()) :
            while ($events->have_posts()) : $events->the_post();
              $raw_date = get_field('event_date');
echo '<pre>';
var_dump($raw_date);
echo '</pre>';

                $formatted_date = DateTime::createFromFormat('d/m/Y', $raw_date)->format('Ymd');
                if ($formatted_date === $today && !$found) {
                    $found = true;
                    ?>
                    <article class="event-card city-<?php echo esc_attr($city); ?>">
                        <div class="event-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                        <div class="event-details">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo esc_html($raw_date); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn">View Details</a>
                        </div>
                    </article>
                    <?php
                }
            endwhile;
            wp_reset_postdata();
        endif;

        if (!$found) {
            echo '<p>No current events in ' . ucfirst(esc_html($city)) . '.</p>';
        }
    }

    echo '</div></section>';
    return ob_get_clean();
}
add_shortcode('current_events', 'render_current_events_shortcode');



function render_upcoming_events_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 4,
    ), $atts, 'upcoming_events');

    $today = date('Ymd');
    $args = array(
        'post_type' => 'activities',
        'posts_per_page' => -1,
        'meta_key' => 'event_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
    );

    $events = new WP_Query($args);
    $shown = 0;

    ob_start();
    echo '<section class="upcoming-events">';
    echo '<h2>Upcoming Events</h2>';
    echo '<div class="events-carousel">';

    if ($events->have_posts()) :
        while ($events->have_posts()) : $events->the_post();
            $raw_date = get_field('event_date');
echo '<pre>';
var_dump($raw_date);
echo '</pre>';

            if (!$raw_date) continue;
            
            $formatted_date = DateTime::createFromFormat('d/m/Y', $raw_date)->format('Ymd');
            if ($formatted_date > $today && $shown < $atts['count']) {
                $shown++;
                ?>
                
                <article class="event-card">
                    <div class="event-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                    <div class="event-details">
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo esc_html($raw_date); ?></p>
                        <a href="<?php the_permalink(); ?>" class="btn">View Details</a>
                    </div>
                </article>
                <?php
            }
        endwhile;
        wp_reset_postdata();
    endif;

    if ($shown === 0) {
        echo '<p>No upcoming events found.</p>';
    }

    echo '</div></section>';
    return ob_get_clean();
}
add_shortcode('upcoming_events', 'render_upcoming_events_shortcode');



function render_latest_news_shortcode($atts) {
  $atts = shortcode_atts(array(
    'count' => 3
  ), $atts, 'latest_news');

  $args = array(
    'post_type'      => 'post',
    'posts_per_page' => intval($atts['count']),
  );

  $news = new WP_Query($args);

  ob_start();
  echo '<section class="latest-news"><h2>Latest News</h2><div class="news-grid">';
  if ($news->have_posts()) :
    while ($news->have_posts()) : $news->the_post(); ?>
      <article class="news-card">
        <h3><?php the_title(); ?></h3>
        <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
        <a href="<?php the_permalink(); ?>" class="btn">Read More</a>
      </article>
    <?php endwhile;
    wp_reset_postdata();
  else :
    echo '<p>No news found.</p>';
  endif;
  echo '</div></section>';
  return ob_get_clean();
}
add_shortcode('latest_news', 'render_latest_news_shortcode');

function enqueue_swiper_assets() {
  // Swiper CSS
  wp_enqueue_style(
    'swiper-css',
    'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css'
  );

  // Swiper JS
  wp_enqueue_script(
    'swiper-js',
    'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js',
    array(),
    null,
    true
  );
}
add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');


function render_video_info_shortcode() {
  ob_start();
  echo '<section class="video-info">';
  echo '<h2>Watch Our Videos</h2>';
  echo '<div class="video-embed">';
  echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>';
  echo '</div></section>';
  return ob_get_clean();
}
add_shortcode('video_info', 'render_video_info_shortcode');

