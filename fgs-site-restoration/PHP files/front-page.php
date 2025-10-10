<?php
get_header(); ?>

<main id="site-content">

  <!-- Hero / Intro -->
  <section class="hero">
    <h1><?php bloginfo('name'); ?></h1>
    <p><?php bloginfo('description'); ?></p>
  </section>

  <!-- Current Events -->
  <?php echo do_shortcode('[current_events]'); ?>

  <!-- Upcoming Events -->
  <?php echo do_shortcode('[upcoming_events count="4"]'); ?>

  <!-- Latest News -->
  <section class="latest-news">
    <h2>Latest News</h2>
    <div class="news-grid">
      <?php
      $news = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => 3
      ));
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
        echo '<p>No news available.</p>';
      endif;
      ?>
    </div>
  </section>

  <!-- Video Info -->
  <section class="video-info">
    <h2>Latest Video</h2>
    <div class="video-embed">
      <!-- Replace with your YouTube/Vimeo embed -->
      <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
              title="Video" 
              frameborder="0" 
              allowfullscreen></iframe>
    </div>
  </section>

</main>

<?php get_footer(); ?>
