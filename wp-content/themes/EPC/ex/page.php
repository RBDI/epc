<?php get_header(); ?>
<div class="white-block">
	<div class='row'>
		<div class="span8">
			<? if (have_posts()) : the_post(); ?>
			<h2><? the_title(); ?></h2>
			<? the_content(); ?>
			<? endif; ?>       
		</div>
	</div>
</div>
<?php get_footer(); ?>