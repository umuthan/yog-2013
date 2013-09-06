<?php get_header(); ?>
			
<div class="container overflowHid marginAuto">
	<div class="contentWrap left stabilSize overflowHid">
		
		<?php if(have_posts()) : ?>
		
			<?php while(have_posts()) : the_post(); ?>
			
				<div class="post">
					<div class="postTitle overflowHid">
						<div class="left">
							<h1>
								<a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a>
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
							<?php the_content( __( '<button class="btn btn-inverse right marginBot">Devamı..</button>' ) ); ?>
						<?php endif; ?>
						
					</div>
					<div class="postdata">
						<?php yog_posted_on(); ?> <?php the_tags( ' | Etiketler : ', ', ', ''); ?> <span><?php edit_post_link(); ?></span>
					</div>
				</div>

			<?php endwhile; ?>
			
		<?php endif; ?>
<?php wp_pagenavi(); ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>