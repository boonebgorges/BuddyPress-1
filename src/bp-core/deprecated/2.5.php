<?php
/**
 * Deprecated functions.
 *
 * @deprecated 2.5.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Set "From" name in outgoing email to the site name.
 *
 * @deprecated 2.5.0
 *
 * @return string The blog name for the root blog.
 */
function bp_core_email_from_name_filter() {
	_deprecated_function( __FUNCTION__, '2.5' );

	/**
	 * Filters the "From" name in outgoing email to the site name.
	 *
	 * @since 1.2.0
	 * @deprecated 2.5.0 Not used any more in BuddyPress core, but left intact for old plugins.
	 *                   This used to be hooked to WordPress' "wp_mail_from_name" action.
	 *
	 * @param string $value Value to set the "From" name to.
	 */
 	return apply_filters( 'bp_core_email_from_name_filter', bp_get_option( 'blogname', 'WordPress' ) );
}

/**
 * Add support for pre-2.5 email filters.
 *
 * @since 2.5.0
 *
 * @param mixed $value
 * @param string $property Name of property.
 * @param string $tranform Return value transformation. Unused.
 * @param BP_Email $email Email object reference.
 * @return mixed
 */
function bp_core_deprecated_email_filters( $value, $property, $transform, $email ) {
	$pre_2_5_emails = array(
		'activity-at-message',
		'activity-comment',
		'activity-comment-author',
		'core-user-registration',
		'core-user-registration-validation',
		'core-user-registration-with-blog',
		'friends-request',
		'friends-request-accepted',
		'groups-at-message',
		'groups-details-updated',
		'groups-invitation',
		'groups-member-promoted',
		'groups-membership-request',
		'groups-membership-request-accepted',
		'messages-unread',
		'settings-verify-email-change',
	);

	remove_filter( 'bp_email_get_property', 'bp_core_deprecated_email_filters', 4, 4 );
	$email_type = $email->get( 'type' );
	$tokens     = $email->get( 'tokens' );

	// Backpat for pre-2.5 emails only.
	if ( ! in_array( $email_type, $pre_2_5_emails, true ) ) {
		add_filter( 'bp_email_get_property', 'bp_core_deprecated_email_filters', 4, 4 );
		return $value;
	}

	if ( $email_type === 'activity-comment' ) {
		if ( $property === 'to' ) {
			/**
			 * Filters the user email that the new comment notification will be sent to.
			 *
			 * @since 1.2.0
			 * @since 2.5.0 Argument type changes from string to array.
			 * @deprecated 2.5.0 Use the filters in BP_Email.
			 *
			 * @param array $value User email the notification is being sent to.
			 *                     Array key is email address, value is the name.
			 */
			$value = apply_filters( 'bp_activity_new_comment_notification_to', $value );
			if ( ! is_array( $value ) ) {
				$value = array( $value => '' );
			}

		} elseif ( $property === 'subject' ) {
			/**
			 * Filters the new comment notification subject that will be sent to user.
			 *
			 * @since 1.2.0
			 * @deprecated 2.5.0 Use the filters in BP_Email.
			 *
			 * @param string $value       Email notification subject text.
			 * @param string $poster_name Name of the person who made the comment.
			 */
			$value = apply_filters( 'bp_activity_new_comment_notification_subject', $value, $tokens['{{poster_name}}'] );

		} elseif ( $property === 'body' ) {
			/**
			 * Filters the new comment notification message that will be sent to user.
			 *
			 * @since 1.2.0
			 * @deprecated 2.5.0 Use the filters in BP_Email.
			 *
			 * @param string $value         Email notification message text.
			 * @param string $poster_name   Name of the person who made the comment.
			 * @param string $content       Content of the comment.
			 * @param string $thread_link   URL permalink for the activity thread.
			 * @param string $deprecated    Optional. Removed in 2.5.0.
			 */
			$value = apply_filters( 'bp_activity_new_comment_notification_message', $value, $tokens['{{poster_name}}'], $tokens['{{content}}'], $tokens['{{thread_link}}'], '' );
		}

	} elseif ( $email_type === 'activity-comment-author' ) {
		if ( $property === 'to' ) {
			/**
			 * Filters the user email that the new comment reply notification will be sent to.
			 *
			 * @since 1.2.0
			 * @since 2.5.0 Argument type changes from string to array.
			 * @deprecated 2.5.0 Use the filters in BP_Email.
			 *
			 * @param array $value User email the notification is being sent to.
			 *                     Array key is email address, value is the name.
			 */
			$value = apply_filters( 'bp_activity_new_comment_notification_comment_author_to', $value );
			if ( ! is_array( $value ) ) {
				$value = array( $value => '' );
			}

		} elseif ( $property === 'subject' ) {
			/**
			 * Filters the new comment reply notification subject that will be sent to user.
			 *
			 * @since 1.2.0
			 * @deprecated 2.5.0 Use the filters in BP_Email.
			 *
			 * @param string $value       Email notification subject text.
			 * @param string $poster_name Name of the person who made the comment.
			 */
			$value = apply_filters( 'bp_activity_new_comment_notification_comment_author_subject', $value, $tokens['{{poster_name}}'] );

		} elseif ( $property === 'body' ) {
			/**
			 * Filters the new comment reply notification message that will be sent to user.
			 *
			 * @since 1.2.0
			 * @deprecated 2.5.0 Use the filters in BP_Email.
			 *
			 * @param string $value         Email notification message text.
			 * @param string $poster_name   Name of the person who made the comment.
			 * @param string $content       Content of the comment.
			 * @param string $deprecated    Optional. Removed in 2.5.0.
			 * @param string $thread_link   URL permalink for the activity thread.
			 */
			$value = apply_filters( 'bp_activity_new_comment_notification_comment_author_message', $value, $tokens['{{poster_name}}'], $tokens['{{content}}'], '', $tokens['{{thread_link}}'] );
		}

	} elseif ( $email_type === 'activity-at-message' || $email_type === 'groups-at-message' ) {
		if ( $property === 'to' ) {
			/**
			 * Filters the user email that the @mention notification will be sent to.
			 *
			 * @since 1.2.0
			 * @since 2.5.0 Argument type changes from string to array.
			 * @deprecated 2.5.0 Use the filters in BP_Email.
			 *
			 * @param array $value User email the notification is being sent to.
			 *                     Array key is email address, value is the name.
			 */
			$value = apply_filters( 'bp_activity_at_message_notification_to', $value );
			if ( ! is_array( $value ) ) {
				$value = array( $value => '' );
			}

		} elseif ( $property === 'subject' ) {
			/**
			 * Filters the @mention notification subject that will be sent to user.
			 *
			 * @since 1.2.0
			 * @deprecated 2.5.0 Use the filters in BP_Email.
			 *
			 * @param string $value       Email notification subject text.
			 * @param string $poster_name Name of the person who made the @mention.
			 */
			$value = apply_filters( 'bp_activity_at_message_notification_subject', $value, $tokens['{{poster_name}}'] );

		} elseif ( $property === 'body' ) {
			/**
			 * Filters the @mention notification message that will be sent to user.
			 *
			 * @since 1.2.0
			 * @deprecated 2.5.0 Use the filters in BP_Email.
			 *
			 * @param string $message       Email notification message text.
			 * @param string $poster_name   Name of the person who made the @mention.
			 * @param string $content       Content of the @mention.
			 * @param string $message_link  URL permalink for the activity message.
			 * @param string $deprecated    Optional. Removed in 2.5.0.
			 */
			$value = apply_filters( 'bp_activity_at_message_notification_message', $value, $tokens['{{poster_name}}'], $tokens['{{content}}'], $tokens['{{message_link}}'], '' );
		}
	}

	add_filter( 'bp_email_get_property', 'bp_core_deprecated_email_filters', 4, 4 );
	return $value;
}
add_filter( 'bp_email_get_property', 'bp_core_deprecated_email_filters', 4, 4 );

/**
 * Add support for pre-2.5 email actions.
 *
 * @since 2.5.0
 *
 * @param BP_Email $email Email object reference.
 * @param bool|WP_Error $delivery_status Bool if the email was sent or not.
 *                                       If a WP_Error, there was a failure.
 * @return mixed
 */
function bp_core_deprecated_email_actions( $email, $delivery_status ) {
	$pre_2_5_emails = array(
		'activity-at-message',
		'activity-comment',
		'activity-comment-author',
		'core-user-registration',
		'core-user-registration-validation',
		'core-user-registration-with-blog',
		'friends-request',
		'friends-request-accepted',
		'groups-at-message',
		'groups-details-updated',
		'groups-invitation',
		'groups-member-promoted',
		'groups-membership-request',
		'groups-membership-request-accepted',
		'messages-unread',
		'settings-verify-email-change',
	);

	remove_action( 'bp_sent_email', 'bp_core_deprecated_email_actions', 4, 2 );
	$email_body    = $email->get( 'body' );
	$email_subject = $email->get( 'subject' );
	$email_type    = $email->get( 'type' );
	$tokens        = $email->get( 'tokens' );

	// Backpat for pre-2.5 emails only.
	if ( ! in_array( $email_type, $pre_2_5_emails, true ) ) {
		add_action( 'bp_sent_email', 'bp_core_deprecated_email_actions', 4, 2 );
		return $value;
	}

	if ( $email_type === 'activity-comment' ) {
		/**
		 * Fires after the sending of a reply to an update email notification.
		 *
		 * @since 1.5.0
		 * @deprecated 2.5.0 Use the filters in BP_Email. $params argument unset and deprecated.
		 *
		 * @param int    $user_id       ID of the original activity item author.
		 * @param string $email_subject Email notification subject text.
		 * @param string $email_body    Email notification message text.
		 * @param int    $comment_id    ID for the newly received comment.
		 * @param int    $commenter_id  ID of the user who made the comment.
		 * @param array  $params        Deprecated in 2.5; now an empty array.
		 */
		do_action( 'bp_activity_sent_reply_to_update_email', $tokens['{{original_activity.user_id}}'], $email_subject, $email_body, $tokens['{{comment_id}}'], $tokens['{{commenter_id}}'], array() );

	} elseif ( $email_type === 'activity-comment-author' ) {
		/**
		 * Fires after the sending of a reply to a reply email notification.
		 *
		 * @since 1.5.0
		 * @deprecated 2.5.0 Use the filters in BP_Email. $params argument unset and deprecated.
		 *
		 * @param int    $user_id       ID of the parent activity item author.
		 * @param string $email_subject Email notification subject text.
		 * @param string $email_body    Email notification message text.
		 * @param int    $comment_id    ID for the newly received comment.
		 * @param int    $commenter_id  ID of the user who made the comment.
		 * @param array  $params        Deprecated in 2.5; now an empty array.
		 */
		do_action( 'bp_activity_sent_reply_to_reply_email', $tokens['{{parent_comment.user_id}}'], $email_subject, $email_body, $tokens['{{comment_id}}'], $tokens['{{commenter_id}}'], array() );

	} elseif ( $email_type === 'activity-at-message' || $email_type === 'groups-at-message' ) {
		/**
		 * Fires after the sending of an @mention email notification.
		 *
		 * @since 1.5.0
		 * @deprecated 2.5.0 Use the filters in BP_Email.
		 *
		 * @param BP_Activity_Activity $activity         Activity Item object.
		 * @param string               $email_subject          Email notification subject text.
		 * @param string               $email_body          Email notification message text.
		 * @param string               $content          Content of the @mention.
		 * @param int                  $receiver_user_id The ID of the user who is receiving the update.
		 */
		do_action( 'bp_activity_sent_mention_email', $tokens['activity'], $email_subject, $email_body, $tokens['{{content}}'], $tokens['{{receiver_user_id}}'] );
	}

	add_action( 'bp_sent_email', 'bp_core_deprecated_email_actions', 4, 2 );
}
add_action( 'bp_sent_email', 'bp_core_deprecated_email_actions', 4, 2 );
