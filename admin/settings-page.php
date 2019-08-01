
<?php if ( current_user_can( 'manage_options' ) ) : ?>
	<h3>Webhook Basic Auth</h3>
	<p>If you have webhooks configured that require HTTP Basic Auth, configure them here!</p>
	<?php

	$form_id = isset( $_GET['id'] ) ? $_GET['id'] : null;
	$form = GFFormsModel::get_form_meta( $form_id );

	if ( isset( $_POST['gfwhba_save_settings'] ) ) {
		check_admin_referer( 'gfwhba-save-custom-setting', 'gfwhba_custom_setting_nonce' );

		$form['gfwhba'] = [
			'enable'  => esc_textarea( $_POST['gfwhba_custom_setting_enable'] ?? '' ),
			'userid'  => esc_textarea( $_POST['gfwhba_custom_setting_userid'] ?? '' ),
			'userkey' => esc_textarea( $_POST['gfwhba_custom_setting_userkey'] ?? '' ),
		];

		$result = GFFormsModel::update_form_meta( $form_id, $form );
		if ( false === $result ) {
			echo '<div id="message" class="notice notice-error fade"><p><strong>Data integration settings could not be updated.</strong></p></div>';
			GFCommon::log_error( "BC GF Webhook HTTP Basic Auth plugin: can't update_form_meta,  $result" );
		} else {
			//otherwise assume it all went according to plan
			echo '<div id="message" class="updated fade"><p><strong>HTTP Basic Auth settings updated!</strong></p></div>';
		}
	}
	?>

	<form method="post">
		<table class="gforms_form_settings" cellspacing="0" cellpadding="0">
			<tr>
				<th>
					<label for="gfwhba_custom_setting_enable">Enable for all feeds</label>
				</th>
				<td>
					<input type="radio" name="gfwhba_custom_setting_enable" id="gfwhba_custom_setting_enable" value='enabled' <?php echo esc_attr( 'enabled' === $form['gfwhba']['enable'] ? 'checked' : '' ); ?> > True
					<input type="radio" name="gfwhba_custom_setting_enable" value='disabled' <?php echo esc_attr( 'enabled' !== $form['gfwhba']['enable'] ? 'checked' : '' ); ?>> False
				</td>
			</tr>
			<tr>
				<th>
					<label for="gfwhba_custom_setting_userid">User ID</label>
				</th>
				<td>
					<input type="text" id="gfwhba_custom_setting_userid" name='gfwhba_custom_setting_userid' value="<?php echo esc_attr( $form['gfwhba']['userid'] ?? '' ); ?>">
				</td>
			</tr>
			<tr>
				<th>
					<label for="gfwhba_custom_setting_userkey">User Key</label>
				</th>
				<td>
					<input type="text" id="gfwhba_custom_setting_userkey" name='gfwhba_custom_setting_userkey' value="<?php echo esc_attr( $form['gfwhba']['userkey'] ?? '' ); ?>">
				</td>
			</tr>
		</table>
		<?php wp_nonce_field( 'gfwhba-save-custom-setting', 'gfwhba_custom_setting_nonce' ); ?>
		<input type="submit" id="gfwhba_save_settings" name="gfwhba_save_settings" value="Update Settings" class="button-primary gfbutton" />
	</form>
<?php endif; ?>
