<form method="get" class="searchform" action="<?php echo esc_url( home_url() ); ?>">
	<input type="text" title="<?php echo esc_attr__( 'Search', 'anchorage' ); ?>" value="<?php echo esc_attr__( 'Search', 'anchorage' ); ?>" name="s" class="s" />
	<button type="submit" title="<?php echo esc_attr__( 'Search', 'anchorage' ); ?>" class="screen-reader-text searchsubmit"><?php echo esc_html__( 'Search', 'anchorage' ); ?></button>		
</form>