<?php
/**
 * History Order.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$status = $order->get_status();
$pending = $processing = $shipped = $completed = 'disabled';
switch ($status) {
    case 'pending':
        $pending = 'active';
        break;
    case 'processing':
        $pending = 'complete';
        $processing = 'active';
        break;
    case 'shipped':
        $pending = $processing = 'complete';
        $shipped = 'active';
        break;
    case 'completed':
        $pending = $processing = $shipped = 'complete';
        $completed = 'active';
        break;
}
?>

<div class="container">
	<div class="bs-wizard">
		<div class="bs-wizard-step <?php echo $pending ?>">
			<div class="bs-wizard-stepnum"><?php _e('Pending Pay.', 'stj_woocommerceshippedstatus') ?></div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
		</div>

		<div class="bs-wizard-step <?php echo $processing ?>">
			<div class="bs-wizard-stepnum"><?php _e('Processing', 'stj_woocommerceshippedstatus') ?></div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
		</div>

		<div class="bs-wizard-step <?php echo $shipped ?>">
			<div class="bs-wizard-stepnum"><?php _e('Shipped', 'stj_woocommerceshippedstatus') ?></div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
		</div>

		<div class="bs-wizard-step <?php echo $completed ?>">
			<div class="bs-wizard-stepnum"><?php _e('Completed', 'stj_woocommerceshippedstatus') ?></div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
		</div>
	</div>        
</div>
</div>
