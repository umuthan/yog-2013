</div>

<div class="footer overflowHid marginAuto stabilSize">

	<?php
		if (function_exists('dynamic_sidebar')) {
			dynamic_sidebar("footer-content");
		}
	?>

</div>


<?php wp_footer(); ?>

</body>
</html>