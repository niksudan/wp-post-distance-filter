<div class="wrap">
	
	<h2>Wordpress Post Distance Filter</h2>
	<p>Plugin by <a href="http://niksudan.com">Nik Sudan</a> | <a href="https://github.com/NikSudan/wp-post-distance-filter/">View on Github</a></p>
	<?php if (Wpdf::isEnabled()) : ?>
		<div class="updated" style="border-left: 0;">
			<p>Filter will work when the URL for an archive has the following format:</p>
			<p><code>/?<?= Wpdf::getLoc() ?>=London&<?= Wpdf::getRad() ?>=10</code></p>
		</div>
	<?php else : ?>
		<p>Filter is not enabled</p>
	<?php endif; ?>

	<hr>
	<h3>Plugin Options</h3>

	<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') : ?>
		<div id="message" class="updated below-h2"><p>Options updated</p></div>
	<?php endif; ?>

	<form method="post" action="options.php">

		<?php settings_fields('wpdf'); ?>
		<?php do_settings_sections('wpdf'); ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th><label for="wpdf_enabled">Plugin Enabled</label></th>
					<td>
						<select name="wpdf_enabled" id="wpdf_enabled">
							<option value="TRUE" <?= get_option('wpdf_enabled', WPDF_DEFAULT_ENABLED) == 'TRUE' ? 'selected' : '' ?>>Yes</option>
							<option value="FALSE" <?= get_option('wpdf_enabled', WPDF_DEFAULT_ENABLED) == 'FALSE' ? 'selected' : '' ?>>No</option>
						</select>
						<p class="description">Whether the filter should actually function.</p>
					</td>
				</tr>

				<tr>
					<th><label for="wpdf_postmeta">Location Key</label></th>
					<td>
						<input type="text" name="wpdf_postmeta" id="wpdf_postmeta" value="<?= get_option('wpdf_postmeta', WPDF_DEFAULT_POSTMETA) ?>"placeholder="meta_key">
						<p class="description">The post's <code>meta_key</code> that should contain a location string.<br/>Assign this to a post with a custom field.</p>
					</td>
				</tr>

				<tr>
					<th><label for="wpdf_measurement">Unit</label></th>
					<td>
						<select name="wpdf_measurement" id="wpdf_measurement">
							<?php global $wpdf_measurements; foreach ($wpdf_measurements as $measurement => $value) : ?>
								<option value="<?= $measurement ?>" <?= get_option('wpdf_measurement', WPDF_DEFAULT_MEASUREMENT) == $measurement ? 'selected' : '' ?>><?= $measurement ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description">How the distance should be interpreted.</p>
					</td>
				</tr>

				<tr>
					<th><label for="wpdf_get_loc">Location Parameter</label></th>
					<td>
						<input type="text" name="wpdf_get_loc" id="wpdf_get_loc" value="<?= get_option('wpdf_get_loc', WPDF_DEFAULT_GET_LOC) ?>" placeholder="Location Parameter">
						<p class="description">The <code>GET</code> parameter for the location string.<br/>Is required in the URL for the filter to work.</p>
					</td>
				</tr>

				<tr>
					<th><label for="wpdf_get_rad">Radius Parameter</label></th>
					<td>
						<input type="text" name="wpdf_get_rad" id="wpdf_get_rad" value="<?= get_option('wpdf_get_rad', WPDF_DEFAULT_GET_RAD) ?>" placeholder="Radius Parameter">
						<p class="description">The <code>GET</code> parameter for the radius integer.<br/>Optional parameter, only gets results within radius.</p>
					</td>
				</tr>

			</tbody>
		</table>

		<?php submit_button(); ?>

	</form>

</div>
