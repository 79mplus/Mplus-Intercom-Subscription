<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) :
	die;
endif;
?>
<div class="mplus-intercom-subscription-wrap">
	<div class="mplus-intercom-subscription-form">
	<?php
		$mplus_intercom_subscription_form = new Mplus_Intercom_Subscription_Form();
		echo $mplus_intercom_subscription_form->render_form();
	?>
	</div>
</div>
