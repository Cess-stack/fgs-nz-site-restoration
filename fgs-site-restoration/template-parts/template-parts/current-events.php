<?php
function render_current_events_shortcode($atts) {
  // Default attributes
  $atts = shortcode_atts(
    array(
      'city' => 'auckland', // default if none passed
    ),
    $atts,
    'current_events'
  );

  // Query args
  $args = array(
    'post_type'      => 'activities',
    'posts_per_page' => 2,
    'tax_query'      => array(
      array(
        'taxonomy' => 'location',
        'field'    => 'slug',
        'terms'    => sanitize_title($atts['city']),
      ),
    ),
    'meta_key'   => 'event_date',
    'orderby'    => 'meta_value',
    'order'      => 'ASC',
    'meta_query' => array(
      array(
        'key'     => 'event_date',
        'value'   => date('Ymd'),
        'compare' => '>=',
        'type'    => 'NUMERIC'
      )
    )
  );

  $events = new WP_Query($args);

  ob_start();

  echo '<section class="current-events city-' . esc_attr($atts['city']) . '">';
  echo '<h2>Current Events – ' . ucfirst(esc_html($atts['city'])) . '</h2>';

  if ($events->have_posts()) :
    while ($events->have_posts()) : $events->the_post(); ?>
      <article class="event-card">
        <div class="event-image">
          <?php the_post_thumbnail('large'); ?>
        </div>
        <div class="event-details">
          <h3><?php the_title(); ?></h3>
          <p><?php echo esc_html(get_field('event_date')); ?></p>
          <a href="<?php the_permalink(); ?>" class="btn">View Details</a>
        </div>
      </article>
    <?php endwhile;
    wp_reset_postdata();
  else :
    echo '<p>No upcoming events found.</p>';
  endif;

  echo '</section>';

  return ob_get_clean();
}
add_shortcode('current_events', 'render_current_events_shortcode');
