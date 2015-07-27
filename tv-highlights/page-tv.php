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

// webservice para ser consumido pelo crontab que envia email com relatorio
if(isset($_GET['type']) and $_GET['type'] == "json") {
	
	$today = strtotime(date("d M Y"));
	$tomorrow = $today + (24 * 60 * 60);
	
	if(isset($_GET['period']) and $_GET['period'] == 'nextweek') {
	
		$tomorrow = $today + (7 * 24 * 60 * 60);
	
	} elseif(isset($_GET['period']) and $_GET['period'] == 'lastweek') {

		$tomorrow = $today;
		$today = $today - (7 * 24 * 60 * 60);

	}

	$posts = get_posts("post_type=calp_event&posts_per_page=-1&orderby=start&order=ASC");

	$output = array();
	foreach($posts as $post) {

		$event = Calp_Events_Helper::get_event($post->ID);
		$now = time();

		// pega o time do dia
		$start_day = strtotime(date("d M Y", $event->start));

		if($start_day >= $today and $start_day <= $tomorrow) {
			$output[] = $event;
		}
	}

	header('content-type: application/json');
	print json_encode($output);
	die;
}

$events = array();

// Range de init padrão (3 dias antes)
$range_init = strtotime(date("d-m-Y",time())) - (60*60*24);
$range_end = strtotime(date("d-m-Y",time())) + (3*60*60*24);

foreach(get_posts("post_type=calp_event&posts_per_page=-1") as $post): ?>	

	<?php
		// pega os atributos do evento
		$event = Calp_Events_Helper::get_event(get_the_ID());

		
		// caso tenha sido preenchido manualmente a data de início, da prioridade pra essa data
		$timestamp_day_start_priority = get_post_meta($post->ID, 'tv_inicio', true);
		if(!empty($timestamp_day_start_priority)) {

			$timestamp_day_start_priority = str_replace("/", " ", $timestamp_day_start_priority);
			$timestamp_day_start = strtotime($timestamp_day_start_priority);

		} else {
			
			$timestamp_day_start = strtotime(date("d-m-Y", $event->start));
		}

		// day end
		$timestamp_day_end = $timestamp_day_start;

		// se a data do evento estiver dentro do range, adiciona ao event
		if(!($range_init <= $timestamp_day_start and $range_end >= $timestamp_day_end)) {
			continue;
		}

		// caso existam dois eventos no mesmo horario, soma 1 segundo para diferenciar
		if(array_key_exists($event->start, $events)) {
			$events[$event->start+1] = $event;
		} else {
			$events[$event->start] = $event;
		}

		echo "<!-- INICIO EVENTO: " . $post->post_title . "-->\n";
		echo "<!-- range_init: " . date("d/m/Y", $range_init) . "-->\n";
		echo "<!-- day_start : " . date("d/m/Y", $timestamp_day_start) . "-->\n";
		echo "<!-- day_end   : " . date("d/m/Y", $timestamp_day_end) . "-->\n";
		echo "<!-- range_end : " . date("d/m/Y", $range_end) . "-->\n";
		echo "<!-- FIM EVENTO: " . $post->ID . "-->\n\n";
		
	?>
<?php endforeach;

// var_dump($events);

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
		
						<?php ksort($events); foreach($events as $event): the_post($event->post->post_id); $post = $event->post; ?>
							<div class="event-item">
								
								<article class="post-208 page type-page status-publish hentry" style="background: url(<?php $image_id = get_post_thumbnail_id(); $image_url = wp_get_attachment_image_src($image_id,'full', true); echo $image_url[0];?>) no-repeat;">
								<!--article class="calp-date "-->
									<div class="gradient">
										<div class="post-info">
											
											<header>
												<div class="event-date"><?php echo date("d/m/Y, \à\s H:i", $event->start); ?></div>
												<div class="time-range">
													<!-- <span class="calp-widget-allday">(all-day)</span> -->
												</div>
												<h4 class="entry-title"><span class="calp-event-title">
													<?php echo $post->post_title; ?>
												</span></h4>
											</header>

											<div class="entry-summary">
												<?php echo $event->post->post_content; ?>
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
