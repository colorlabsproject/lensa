          <?php while(have_posts()): the_post(); if(colabs_image('link=url&return=false')=='') continue;?>
		  <li class="gallery-item"><a href="<?php colabs_image('link=url'); ?>" rel="lightbox"><?php colabs_image('width=157&height=157&link=img'); ?></a></li>
		  <?php endwhile; ?>