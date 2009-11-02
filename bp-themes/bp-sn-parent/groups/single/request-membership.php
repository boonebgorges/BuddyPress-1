<?php get_header() ?>

	<div class="content-header">

	</div>

	<div id="content">
		<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

		<?php do_action( 'bp_before_group_request_membership_content' ) ?>

		<div class="left-menu">
			<?php locate_template( array( 'groups/single/menu.php' ), true ) ?>
		</div>

		<div class="main-column">

			<?php do_action( 'bp_before_group_name' ) ?>

			<div id="group-name">
				<h1><a href="<?php bp_group_permalink() ?>"><?php bp_group_name() ?></a></h1>
				<p class="status"><?php bp_group_type() ?></p>
			</div>

			<?php do_action( 'bp_after_group_name' ) ?>

			<div class="bp-widget">
				<h4><?php _e( 'Request Membership', 'buddypress' ); ?></h4>

				<?php do_action( 'template_notices' ) // (error/success feedback) ?>

				<?php if ( !bp_group_has_requested_membership() ) : ?>
					<p><?php printf( __( "You are requesting to become a member of the group '%s'.", "buddypress" ), bp_group_name( false, false ) ); ?></p>

					<form action="<?php bp_group_form_action('request-membership') ?>" method="post" name="request-membership-form" id="request-membership-form" class="standard-form">
						<label for="group-request-membership-comments"><?php _e( 'Comments (optional)', 'buddypress' ); ?></label>
						<textarea name="group-request-membership-comments" id="group-request-membership-comments"></textarea>

						<?php do_action( 'bp_group_request_membership_content' ) ?>

						<p><input type="submit" name="group-request-send" id="group-request-send" value="<?php _e( 'Send Request', 'buddypress' ) ?> &raquo;" />

						<?php wp_nonce_field( 'groups_request_membership' ) ?>
					</form>
				<?php endif; ?>

			</div>

		</div>

		<?php do_action( 'bp_after_group_request_membership_content' ) ?>

		<?php endwhile; endif; ?>
	</div>

<?php get_footer() ?>
