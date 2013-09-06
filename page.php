<?php get_header(); ?>

<div class="container overflowHid marginAuto">
	<div class="contentWrap left stabilSize overflowHid">
				
			<?php while(have_posts()) : the_post(); ?>
			
				<div class="post">
					<div class="postTitle overflowHid">
						<div class="left">
							<h1>
								<?php the_title();?>
							</h1>
						</div> 
						
						<div class="commentNum right">
							<a href="#"><span><?php comments_popup_link(__('yorum yok'), __('1 yorum'), __('% yorum'), '', __('Yorumlara kapalı'));?></span></a>
						</div>
					</div>
					
					<div class="postContent overflowHid">
					
						<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" title="<?php  the_title; ?>" class="overflowHid postThumb">
							<?php  echo the_post_thumbnail();?>
						</a>
						
						<?php the_excerpt(''); ?>
						
						<?php else : ?>
							<?php the_content( __( 'Devamı..' ) ); ?>
						<?php endif; ?>
						
					</div>
					<div class="postdata">
						<?php yog_posted_on(); ?> <span><?php edit_post_link(); ?></span>
					</div>
				</div>
				<?php comments_template(); ?>
			<?php endwhile; ?>
		
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>