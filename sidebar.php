<div class="primary-sidebar column col4">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('colabs_right') ) : ?>   

				<aside class="widget block-background block-inner">
					<h4 class="widget-title"><?php _e('Tag Cloud','colabsthemes');?></h4>
					<ul>
						<?php wp_tag_cloud(''); ?>	
					</ul>
				</aside>

				<aside class="widget block-background block-inner">
					<h4 class="widget-title"><?php _e('Archive','colabsthemes');?></h4>
					<ul>
						<?php wp_get_archives('type=monthly&limit=6'); ?>
					</ul>
				</aside>

				<aside class="widget block-background block-inner">
					<h4 class="widget-title"><?php _e('Blogroll','colabsthemes');?></h4>
					<ul>
						<li><a href="http://colorlabsproject.com/forum"><?php _e('Community Forum','colabsthemes');?></a></li>
						<li><a href="http://colorlabsproject.com/documentation"><?php _e('Documentation','colabsthemes');?></a></li>
						<li><a href="http://colorlabsproject.com/faq"><?php _e('FAQ','colabsthemes');?></a></li>
						<li><a href="http://colorlabsproject.com/member/member.php"><?php _e('Member Area','colabsthemes');?></a></li>
						<li><a href="http://colorlabsproject.com/resolve"><?php _e('Resolution Center','colabsthemes');?></a></li>
						<li><a href="http://colorlabsproject.com/tutorials"><?php _e('Tutorials','colabsthemes');?></a></li>
					</ul>
				</aside>
	
	<?php endif; ?>
</div><!-- .primary-sidebar -->