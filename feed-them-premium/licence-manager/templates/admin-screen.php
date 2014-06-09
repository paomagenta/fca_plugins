<?php
/**
 * Templage renders Menage licence page
 * @param licence_validator $this|self
 * @param string $user_email
 * @param array $messages
 * @param attay $keys
 */
?>

<div class="wrap">

	<?php screen_icon( 'plugins' ); ?>
	<h2><?php _e( 'Manage digital licences', $this->validator_token ); ?></h2>
	<?php foreach( $messages as $message ) : ?>
		<div class="<?php echo ( $message['success'] ? 'updated below-h2' : 'error' ); ?>">
			<p><?php echo $message['message']; ?></p>
		</div>
	<?php endforeach; ?>
	<p>
		<?php _e( 'Enter your license key to activate your plugins. Also, please verify whether suggested activation email is correct for particular plugins.', $this->validator_token ); ?>
		<?php _e( 'To deactivate the licence, remove the licence key from plugin field, but keep the correct email address.', $this->validator_token ); ?>
	</p>

	<form name="<?php echo $this->validator_token; ?>-login" id="<?php echo $this->validator_token; ?>-login"
		  action="<?php echo admin_url( 'index.php?page=' . $this->validator_token ); ?>" method="post">
		<?php wp_nonce_field( $this->validator_token . '-nonce', $this->validator_prefix . 'nonce' ); ?>
		<fieldset>
			<table class="form-table">
				<tbody>
					<?php foreach ( self::$plugins as $plugin_identifier => $info ) :

						$value = !empty( $keys[$plugin_identifier]['license_key'] ) ? $keys[$plugin_identifier]['license_key'] : '';
						$email = !empty( $keys[$plugin_identifier]['email'] ) ? $keys[$plugin_identifier]['email'] : '';
						if ( !empty( $_POST['license_keys'][$plugin_identifier] ) ) {
							$value = $_POST['license_keys'][$plugin_identifier];
							$email = $_POST['licence_emails'][$plugin_identifier];
						} ?>
						<tr>
							<th scope="row"><label for="license_key-<?php echo $plugin_identifier;?>"><?php echo $info['title'] ?></label></th>
							<td>
								<input type="text" class="input-text input-license regular-text" name="license_keys[<?php echo $plugin_identifier;?>]"
									   id="license_key-<?php echo $plugin_identifier;?>" value="<?php echo $value; ?>" />
							</td>
							<th scope="row">
								<label for="license_key_email-<?php echo $plugin_identifier;?>"><?php _e( 'Activation email', $this->validator_token ) ?></label>
							</th>
							<td>
								<input type="email" class="input-text input-license regular-text" placeholder="<?php echo $user_email; ?>" value="<?php echo $email; ?>"
									   name="licence_emails[<?php echo $plugin_identifier;?>]" id="license_key_email-<?php echo $plugin_identifier;?>"/>
							</td>
							<th>
								<?php if ( !isset( $keys[$plugin_identifier]['status'] ) || !$keys[$plugin_identifier]['status'] ) : ?>
									<b class="inactive-licence"><?php _e( 'Licence is inactive!', $this->validator_token ); ?></b>
								<?php endif; ?>
							</th>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</fieldset>

		<fieldset>
			<p class="submit">
				<button type="submit" name="<?php echo $this->validator_token; ?>-login" id="<?php echo $this->validator_token; ?>-login" class="button-primary">
					<?php _e( 'Save', $this->validator_token ); ?>
				</button>
			</p>
		</fieldset>
	</form>
</div>