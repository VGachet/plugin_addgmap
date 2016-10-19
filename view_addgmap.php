<div class="container-fluid">
<div class="row">
		<div class="col-md-8 text-center">
			<h2>AddGmap Options</h2>
		</div>
		<div class="col-md-4 text-center">
			<h2>Shortcode Gmap List</h2>
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
					<button class="add_pin pull-right" type="button"><?php _e('Add pin', 'addgmap_plugin'); ?></button>
					<button type="button" id="get-location" class="pull-right" data-toggle="modal" data-target="#location_modal">Get your adress location</button>
					<br/>
					<div class="location_content">
						<div class="col-md-4">
							<label>Pin 1</label><br/>
							<input type="text" name="name[0]" placeholder="<?php _e('Pin name', 'addgmap_plugin'); ?>" required="">
							<input type="text" name="lat[0]" placeholder="Latitude" required="">
							<input type="text" name="lon[0]" placeholder="Longitude" required="">
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<label>Advanced Options</label>
				</div>
					<div class="col-md-6">
						<input type="text" name="map_width" placeholder="Width" required="">
					</div>
					<div class="col-md-6">
						<input type="text" name="map_height" placeholder="Height" required="">
					</div>
					<div class="col-md-6">
						<input type="number" name="zoom" placeholder="Map zoom" required="">
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

<div class="modal fade" id="location_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="#" id="addgmap_location_form">
		    <input type="text" name="adress" placeholder="<?php _e('Insert your adress','addgmap_plugin'); ?>" required="">
		    <input type="submit" value="<?php _e('Get locations','addgmap_plugin'); ?>">
		</form>
		<div id="loading_location">
			<img src="<?php echo plugin_dir_url( 'gmap_plugin' ).'gmap_plugin/loader/loader.gif'; ?>" alt="">
		</div>
		<div id="adress_location"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {

			var increment = 1;

		jQuery('.add_pin').on('click', function(e){
			e.preventDefault();
			var html_block = '<div class="col-md-4"><label>Pin '+(increment+1)+'</label>';
			html_block += '<button class="delete_pin" type="button">-</button>';
			html_block += '<input type="text" name="name['+increment+']" placeholder="Pin name" required="">';
			html_block += '<input type="text" name="lat['+increment+']" placeholder="Latitude" required="">';
			html_block += '<input type="text" name="lon['+increment+']" placeholder="Longitude" required=""></div>';

			jQuery('.location_content').append(html_block);

			increment = increment + 1;
		});

		jQuery('input[name=route]').on('click', function()
		{

			if( jQuery('input[name=route]').is(':checked') ){
		    	jQuery('.add_pin').hide();
		    	jQuery('.location_content').html('');
		    	var html_block = "";
		    	html_block += '<input type="hidden" name="name[0]">';
				html_block += '<input type="text" name="lat[0]" placeholder="Starting destination latitude" required="">';
				html_block += '<input type="text" name="lon[0]" placeholder="Starting destination longitude" required="">';
				html_block += '<input type="hidden" name="name[1]">';
				html_block += '<input type="text" name="lat[1]" placeholder="Ending destination latitude" required="">';
				html_block += '<input type="text" name="lon[1]" placeholder="Ending destination longitude" required="">';

				$('.location_content').append(html_block);

			} else {
			    jQuery('.add_pin').show();
				jQuery('.location_content').html('');
			    var html_block = '<div class="col-md-4"><label>Pin 1</label>';
			html_block += '<div class="col-md-12"><input type="text" name="name[0]" placeholder="Pin name" required=""></div>';
			html_block += '<div class="col-md-12"><input type="text" name="lat[0]" placeholder="Latitude" required=""></div>';
			html_block += '<div class="col-md-12"><input type="text" name="lon[0]" placeholder="Longitude" required=""></div>';

				jQuery('.location_content').append(html_block);
			    jQuery('input[name="name[1]"], input[name="lat[1]"], input[name="lon[1]"]').remove();
			}

		});

		/*jQuery('.delete_pin').live( "click", function() {
			console.log('click');
		    jQuery(this).next().remove();
		});*/

		var $loading = $('#loading_location').hide();
		$(document)
		 .ajaxStart(function () {
		    $loading.show();
		  })
		  .ajaxStop(function () {
		    $loading.hide();
		  });

		jQuery('#addgmap_location_form').on('submit', function(e){
			e.preventDefault();

			var adress = jQuery('input[name=adress]').val();

			var data = {
				'action': 'addgmap_location',
				'adress': adress
			};

			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#adress_location').html(response);
			});

		});

	});
</script>