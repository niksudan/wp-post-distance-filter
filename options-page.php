<div class="wrap">
	
	<h2><span class="dashicons dashicons-location-alt" style="font-size: 36px; width: 36px; height: 36px;"></span> Wordpress Post Distance Filter</h2>
	<p>Plugin by <a href="http://niksudan.com">Nik Sudan</a> | <a href="https://github.com/NikSudan/wp-post-distance-filter/">View on Github</a> | <a href="https://wordpress.org/plugins/wp-post-distance-filter/">View on WordPress</a> | Version 1.1.1</p>

	<?php if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ) : ?>
		<div id="message" class="updated below-h2"><p>Options updated</p></div>
	<?php endif; ?>

	<div class="updated" style="border-left: 0;">
		<form method="post" action="options.php">

			<?php settings_fields( 'wpdf' ); ?>
			<?php do_settings_sections( 'wpdf' ); ?>

			<table class="form-table">
				<tbody>

					<tr>
						<th colspan="2" style="padding-top: 0;">
							<h3><span class="dashicons dashicons-admin-generic"></span> Configuration</h3>
						</th>
					</tr>

					<tr>
						<th><label for="wpdf_enabled">Plugin Enabled</label></th>
						<td>
							<select name="wpdf_enabled" id="wpdf_enabled">
								<option value="TRUE" <?php echo get_option( 'wpdf_enabled', WPDF_DEFAULT_ENABLED ) == 'TRUE' ? 'selected' : '' ?>>Yes</option>
								<option value="FALSE" <?php echo get_option( 'wpdf_enabled', WPDF_DEFAULT_ENABLED ) == 'FALSE' ? 'selected' : '' ?>>No</option>
							</select>
							<p class="description">Whether the filter should actually function.</p>
						</td>
					</tr>

					<tr>
						<th><label for="wpdf_measurement">Unit of measurement</label></th>
						<td>
							<select name="wpdf_measurement" id="wpdf_measurement">
								<?php global $wpdf_measurements; foreach ( $wpdf_measurements as $measurement => $value ) : ?>
									<option value="<?php echo $measurement ?>" <?php echo get_option( 'wpdf_measurement', WPDF_DEFAULT_MEASUREMENT ) == $measurement ? 'selected' : '' ?>><?php echo $measurement ?></option>
								<?php endforeach; ?>
							</select>
							<p class="description">How the distance should be interpreted.</p>
						</td>
					</tr>

					<tr>
						<th colspan="2">
							<hr>
							<h3><span class="dashicons dashicons-admin-site"></span> URL Parameters</h3>
							<?php if ( Wpdf::is_enabled() ) : ?>
								<p>Filter will work when the URL for an archive has the following format:</p>
								<p><code>/?<?php echo Wpdf::get_loc() ?>=London&<?php echo Wpdf::get_rad() ?>=10</code></p>
							<?php else : ?>
								<p>Filter is not enabled, so URL parameters have no effect</p>
							<?php endif; ?>
						</th>
					</tr>

					<tr>
						<th><label for="wpdf_get_loc">Location Parameter</label></th>
						<td>
							<input type="text" name="wpdf_get_loc" id="wpdf_get_loc" value="<?php echo get_option( 'wpdf_get_loc', WPDF_DEFAULT_GET_LOC ) ?>" placeholder="Location Parameter">
							<p class="description">The <code>GET</code> parameter for the location string.<br/>Is required in the URL for the filter to work.</p>
						</td>
					</tr>

					<tr>
						<th><label for="wpdf_get_rad">Radius Parameter</label></th>
						<td>
							<input type="text" name="wpdf_get_rad" id="wpdf_get_rad" value="<?php echo get_option( 'wpdf_get_rad', WPDF_DEFAULT_GET_RAD ) ?>" placeholder="Radius Parameter">
							<p class="description">The <code>GET</code> parameter for the radius integer.<br/>Optional parameter, only gets results within radius.</p>
						</td>
					</tr>

					<tr>
						<th colspan="2">
							<hr>
							<h3><span class="dashicons dashicons-admin-post"></span> Postmetas</h3>
							<p>Be careful when editing these - any old entries will not be transferred over.</p>
						</th>
					</tr>

					<tr>
						<th><label for="wpdf_postmeta">Location Key</label></th>
						<td>
							<input type="text" name="wpdf_postmeta" id="wpdf_postmeta" value="<?php echo get_option( 'wpdf_postmeta', WPDF_DEFAULT_POSTMETA ) ?>"placeholder="meta_key">
							<p class="description">The post's <code>meta_key</code> that should contain a location string.<br/>Assign this to a post with a custom field.</p>
						</td>
					</tr>

					<tr>
						<th><label>Latitude &amp; Longitude Keys</label></th>
						<td>
							<table>
								<tr>
									<td style="padding-top: 0; padding-left: 0;">
										<label for="wpdf_lat_postmeta">Latitude:</label>
										<input type="text" name="wpdf_lat_postmeta" id="wpdf_lat_postmeta" value="<?php echo get_option( 'wpdf_lat_postmeta', WPDF_DEFAULT_LAT_POSTMETA ) ?>"placeholder="meta_key">
									</td>
									<td style="padding-top: 0; padding-right: 0;">
										<label for="wpdf_lng_postmeta">Longitude:</label>
										<input type="text" name="wpdf_lng_postmeta" id="wpdf_lng_postmeta" value="<?php echo get_option( 'wpdf_lng_postmeta', WPDF_DEFAULT_LNG_POSTMETA ) ?>"placeholder="meta_key">
									</td>
								</tr>
							</table>
							<p class="description">The post <code>meta_key</code>s that will contain the latitude and longitude value.<br/>View this on a post with a custom field.</p>
						</td>
					</tr>

				</tbody>
			</table>

			<?php submit_button(); ?>

		</form>

	</div>

</div>
