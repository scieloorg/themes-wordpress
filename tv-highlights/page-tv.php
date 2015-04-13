<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

$events = array();

query_posts("post_type=calp_event&posts_per_page=-1"); ?>
<?php if(have_posts()): while(have_posts()): the_post(); ?>	

	<?php
		// pega os atributos do evento
		$post = get_post(get_the_ID());
		$event = Calp_Events_Helper::get_event(get_the_ID());

		echo "<!-- INICIO EVENTO: " . $post->ID . "-->\n";

		// Range de init padrão (3 dias atrás)
		$range_init = strtotime(date("d-m-Y",time())) - (60*60*24*3);
		$range_end = strtotime(date("d-m-Y",time())) - (60*60*24);

		// caso tenha sido preenchido manualmente a data de início, da prioridade pra essa data
		$timestamp_day_start_priority = get_post_meta($post->ID, 'tv_inicio', true);
		if(!empty($timestamp_day_start_priority)) {
			
			$timestamp_day_start = strtotime($timestamp_day_start_priority);

			// Range de init é hoje, pois a data é especificada manualmente
			$range_init = strtotime(date("d-m-Y",time()));
		} else {
			
			$timestamp_day_start = strtotime(date("d-m-Y", $event->start));
		}

		echo "<!-- range_init: " . date("d/m/Y", $range_init) . "-->\n";
		echo "<!-- day_start : " . date("d/m/Y", $timestamp_day_start) . "-->\n";

		// caso tenha sido preenchido manualmente a data final, da prioridade pra essa data
		$timestamp_day_end_priority = get_post_meta($post->ID, 'tv_final', true);
		if(!empty($timestamp_day_end_priority)) {

			$timestamp_day_end = strtotime($timestamp_day_end_priority);
			$range_end = strtotime(date("d-m-Y",time()));
		} else {

			$timestamp_day_end = strtotime(date("d-m-Y", $event->end));
		}

		echo "<!-- range_end: " . date("d/m/Y", $range_end) . "-->\n";
		echo "<!-- day_end : " . date("d/m/Y", $timestamp_day_end) . "-->\n";
		
		echo "<!-- FIM EVENTO: " . $post->ID . "-->\n\n";

		// se a data do evento estiver dentro do range, adiciona ao event
		if(!($range_init <= $timestamp_day_start and $range_end <= $timestamp_day_end)) {
			continue;
		}

		// caso existam dois eventos no mesmo horario, soma 1 segundo para diferenciar
		if(array_key_exists($event->start, $events)) {
			$events[$event->start+1] = $event;
		} else {
			$events[$event->start] = $event;
		}

		
	?>
<?php endwhile; endif; 

// webservice para ser consumido pelo crontab que envia email com relatorio
if(isset($_GET['type']) and $_GET['type'] == "json") {

	$output = array();
	foreach($events as $event) {

		if(date('d-m-Y') == date("d-m-Y", $event->start)) {
			$output[] = $event;
		}
	}

	print json_encode($output);
	die;
}

get_header('tv'); ?>

<!-- reload da página baseado no numero de notícias -->
<script>
$(function(){
	setTimeout(function(){
	    location.reload();
	}, <?php echo count($events) * 5000 + 1000; ?>);
});
</script>

<section id="primary" class="site-content">
	<div id="content" role="main">
		
		<?php // dynamic_sidebar( 'tv' ); ?>

			<div id="calp_agenda_widget-3" class="widget widget_calp_agenda_widget">
				<div class="calp-agenda-widget-view">
					<div class="calp-widget-loading"></div>

					<div class="slideshow">
		
						<?php ksort($events); foreach($events as $event): the_post($event->post->post_id); ?>
							<div class="event-item">
								
								<article class="post-208 page type-page status-publish hentry" style="background: url(<?php $image_id = get_post_thumbnail_id();
$image_url = wp_get_attachment_image_src($image_id,'full', true); echo $image_url[0];?>) no-repeat;">
								<!--article class="calp-date "-->
									<div class="gradient">
										<div class="post-info">
											
											<header>
												<div class="event-date"><?php echo date("d/m/Y, \à\s H:i", $event->start); ?></div>
												<div class="time-range">
													<!-- <span class="calp-widget-allday">(all-day)</span> -->
												</div>
												<h4 class="entry-title"><span class="calp-event-title">
													<?php the_title(); ?>
												</span></h4>
											</header>

											<div class="entry-summary"><?php the_content(); ?>
												<!--div class="img"><?php the_post_thumbnail(); ?></div-->
											</div>
										</div>
									</div>
								</article>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

	</div><!-- #content -->
</section><!-- #primary -->

<?php get_footer(); ?>
