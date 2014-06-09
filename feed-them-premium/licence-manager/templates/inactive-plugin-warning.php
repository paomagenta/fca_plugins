<?php
/**
 * Template displays error message when licence for current plugin is not valid
 */
?>

<div class="error">
	<p>
		<?php echo sprintf(	__( 'License key for <i>%s</i> is not valid. <a href="index.php?page=licence-validator"> Please click here to activate it</a> - until then plugin will be disabled. Your licence key(s) may be found on <a href="http://www.slickremix.com/my-account/" target="_blank">www.slickremix.com/my-account/</a>. If you are an existing customer and do not have a licence key(s) please contact <a href="mailto:support@slickremix.com">Support@slickremix.com</a> (Proof of purchase or Email Address REQUIRED) ', $this->validator_token ),
							$this->plugin_title ); ?>
	</p>
</div>
