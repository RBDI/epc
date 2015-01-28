<?php get_header(); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<? if (have_posts()) : the_post(); ?>
			<h1><? the_title(); ?></h1>
			<? the_content(); ?>
			<? endif; ?>       
		</div>
	</div>
</div>
<?php get_footer(); ?>