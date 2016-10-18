<div class="container-fluid">
<div class="row">
		<div class="col-md-8 text-center">
			<h2>AddGmap Options</h2>
		</div>
		<div class="col-md-4 text-center">
			<h2>Shortcode GMap List</h2>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8">

			<form action="" method="post">
				<div class="col-md-12">
				<div class="checkbox">
					<label><input type="checkbox" name="route"><?php _e('Route', 'addgmap_plugin'); ?></label>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="allow_it"><?php _e('Display Google Map search bar', 'addgmap_plugin'); ?></label>
				</div>

					<label><?php _e('Location(s)', 'addgmap_plugin'); ?></label>
					<button class="add_pin pull-right"><?php _e('Add pin', 'addgmap_plugin'); ?></button>
					<br/>
					<div class="location_content">
						<div class="col-md-4">
							<label>Pin 1</label><br/>
							<input type="text" name="name[0]" placeholder="<?php _e('pin name', 'addgmap_plugin'); ?>" required="">
							<input type="text" name="lat[0]" placeholder="latitude" required="">
							<input type="text" name="lon[0]" placeholder="longitude" required="">
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<label>Advanced Options</label>
				</div>
					<div class="col-md-6">
						<input type="text" name="map_width" placeholder="width" required="">
					</div>
					<div class="col-md-6">
						<input type="text" name="map_height" placeholder="height" required="">
					</div>
					<div class="col-md-6">
						<input type="number" name="zoom" placeholder="map zoom" required="">
					</div>
					<div class="col-md-12 text-center">
						<input type="submit" value="<?php _e('Create map', 'addgmap_plugin'); ?>">
					</div>
			</form>

		</div>

		<div class="col-md-4 display-shortcode">
			<?php
	 		foreach ($latest_map_shortcodes as $latest_map_shortcode) {
	 			echo "<p>";
	 			echo $latest_map_shortcode->map_shortcode;
	 			echo "</p>";
	 		}
	 		?>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {

			var increment = 1;

		jQuery('.add_pin').on('click', function(e){
			e.preventDefault();
			var html_block = '<div class="col-md-4"><label>Pin '+(increment+1)+'</label>';
			html_block += '<button class="delete_pin">-</button>';
			html_block += '<input type="text" name="name['+increment+']" placeholder="pin name" required="">';
			html_block += '<input type="text" name="lat['+increment+']" placeholder="latitude" required="">';
			html_block += '<input type="text" name="lon['+increment+']" placeholder="longitude" required=""></div>';

			jQuery('.location_content').append(html_block);

			increment = increment + 1;
		});

		jQuery('input[name=route]').on('click', function(){

			if( jQuery('input[name=route]').is(':checked') ){
		    	jQuery('.add_pin').hide();
		    	jQuery('.location_content').html('');
		    	var html_block = "";
		    	html_block += '<input type="hidden" name="name[0]" placeholder="name starting destination" required="">';
				html_block += '<input type="text" name="lat[0]" placeholder="latitude starting destination" required="">';
				html_block += '<input type="text" name="lon[0]" placeholder="longitude starting destination" required="">';
				html_block += '<input type="hidden" name="name[1]" placeholder="name ending destination" required="">';
				html_block += '<input type="text" name="lat[1]" placeholder="latitude ending destination" required="">';
				html_block += '<input type="text" name="lon[1]" placeholder="longitude ending destination" required="">';

				$('.location_content').append(html_block);

			} else {
			    jQuery('.add_pin').show();
				jQuery('.location_content').html('');
			    var html_block = '<div class="col-md-4"><label>Pin 1</label>';
			html_block += '<div class="col-md-12"><input type="text" name="name[0]" placeholder="pin name" required=""></div>';
			html_block += '<div class="col-md-12"><input type="text" name="lat[0]" placeholder="latitude" required=""></div>';
			html_block += '<div class="col-md-12"><input type="text" name="lon[0]" placeholder="longitude" required=""></div>';
			html_block += '<button class="delete_pin">-</button></div>';

				jQuery('.location_content').append(html_block);
			    jQuery('input[name="name[1]"], input[name="lat[1]"], input[name="lon[1]"]').remove();
			}

		});

	});
</script>