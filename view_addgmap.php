<div class="container-fluid">
<div class="row">
		<div class="col-md-8 text-center">
			<h2><?php _e('Addmap Options', 'addmap') ?></h2>
		</div>
		<div class="col-md-4 text-center">
			<h2><?php _e('Shortcode Addmap List', 'addmap') ?></h2>
			<button id="help-button" data-toggle="modal" data-target="#help_modal"><?php _e('Help','addmap') ?></button>
			<form id="export-form" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
				<input type="hidden" name="action" value="export_csv">
				<input type="submit" id="export_csv" value="<?php _e('Export pin datas', 'addmap'); ?>">
			</form>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8">

			<form action="" method="post">
				<div class="col-md-12">
				<label><?php _e('Your Google Map api Key','addmap') ?> - <a target="_blank" href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend%2Cgeocoding_backend%2Cdirections_backend%2Cdistance_matrix_backend%2Celevation_backend%2Cplaces_backend&reusekey=true&hl=fr"><?php _e('Get a key', 'addmap'); ?></a></label>
				<input type="text" name="api_key" placeholder="Api key" value="<?php if(isset($api_key)){ echo $api_key; } ?>" required="">
				<div class="checkbox">
					<label><input type="checkbox" name="route"><?php _e('Route', 'addmap'); ?></label>
				</div>
				<!--<div class="checkbox">
					<label><input type="checkbox" name="allow_it"><?php _e('Display Google Map search bar', 'addmap'); ?></label>
				</div>-->
				<div id="select_mode">
				<label><?php _e('Travel Modes','addmap') ?></label>
				<select name="travel_mode">
					<option value="DRIVING"><?php _e('Driving','addmap') ?></option>
					<option value="BICYCLING"><?php _e('Bicycling','addmap') ?></option>
					<option value="" ="TRANSIT"><?php _e('Transit','addmap') ?></option>
					<option value="" ="WALKING"><?php _e('Walking','addmap') ?></option>
				</select><br/>
				</div>
					<label><?php _e('Location(s)', 'addmap'); ?></label>
					<button class="add_pin pull-right" type="button"><?php _e('Add pin', 'addmap'); ?></button>
					<button type="button" id="get-location" class="pull-right" data-toggle="modal" data-target="#location_modal"><?php _e('Get your adress location','addmap') ?></button>
					<br/>
					<div class="location_content">
						<div class="col-md-4">
							<label>Pin 1</label><br/>
							<input type="text" name="name[0]" placeholder="<?php _e('Pin name', 'addmap'); ?>" required="">
							<input type="text" name="lat[0]" placeholder="<?php _e('Latitude','addmap') ?>" required="">
							<input type="text" name="lon[0]" placeholder="<?php _e('Longitude','addmap') ?>" required="">
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<label><?php _e('Advanced Options', 'addmap'); ?></label>
				</div>
					<div class="col-md-6">
						<input type="text" name="map_width" placeholder="<?php _e('Width (empty: 500px)', 'addmap') ?>">
					</div>
					<div class="col-md-6">
						<input type="text" name="map_height" placeholder="<?php _e('Height (empty: 500px)', 'addmap') ?>">
					</div>
					<div class="col-md-6">
						<input type="number" name="zoom" placeholder="<?php _e('Map zoom (empty: 8)', 'addmap') ?>">
					</div>
					<div class="col-md-12 text-center">
						<input type="submit" value="<?php _e('Generate addmap shortcode', 'addmap'); ?>">
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
		    <input type="text" name="adress" placeholder="<?php _e('Insert your adress','addmap'); ?>" required="">
		    <input type="submit" value="<?php _e('Get locations','addmap'); ?>">
		</form>
		<div id="loading_location">
			<img src="<?php echo plugin_dir_url( 'gmap_plugin' ).'addmap/loader/loader.gif'; ?>" alt="">
		</div>
		<div id="adress_location"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="help_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        
      <h2><?php _e('Shortcode Help','addmap') ?></h2>
      <p><?php _e('Add the complete shortcode to your content of pages or posts.','addmap') ?></p>
      <p><?php _e('Modify possibilities : ','addmap') ?></p>
      <ul>
	      <li><?php _e('map_width','addmap') ?></li>
	      <li><?php _e('map_height', 'addmap') ?></li>
	      <li><?php _e('zoom (0 - 19)', 'addmap') ?></li>
	      <li><?php _e('allow_it (display the search bar : 0 / 1)','addmap') ?></li>
	      <li><?php _e('Example : ','addmap') ?>[addmap map_id="1" allow_it="0" route="1" map_width="100%" map_height="200px" zoom="7"]</li>
      </ul>

      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {

			var increment = 1;

		jQuery('#select_mode').hide();

		jQuery('.add_pin').on('click', function(e){
			e.preventDefault();
			var html_block = '<div class="col-md-4"><label>Pin '+(increment+1)+'</label>';
			html_block += '<button class="delete_pin" type="button">Delete</button>';
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
		    	jQuery('input[name=zoom]').hide();
		    	jQuery('#select_mode').show();
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
				jQuery('#select_mode').hide();
			    jQuery('.add_pin').show();
			    jQuery('input[name=zoom]').show();
				jQuery('.location_content').html('');
			    var html_block = '<div class="col-md-4"><label>Pin 1</label>';
			html_block += '<div class="col-md-12"><input type="text" name="name[0]" placeholder="Pin name" required=""></div>';
			html_block += '<div class="col-md-12"><input type="text" name="lat[0]" placeholder="Latitude" required=""></div>';
			html_block += '<div class="col-md-12"><input type="text" name="lon[0]" placeholder="Longitude" required=""></div>';

				jQuery('.location_content').append(html_block);
			    jQuery('input[name="name[1]"], input[name="lat[1]"], input[name="lon[1]"]').remove();
			}

		});

		jQuery('.location_content').on( "click",".delete_pin", function() {
			console.log('click');
		    jQuery(this).nextAll('input:lt(3)').remove();
		    jQuery(this).prev().remove();
		    jQuery(this).closest('.col-md-4').remove();
		    jQuery(this).remove();

		    var pin_counter = (jQuery('.location_content input').length)/3;
		    var eq = 0;

		    for(i = 0; i < pin_counter; i++)
		    {
		    	increment = i;
		    	console.log(increment);
		    	for(j = eq; j < eq+3; j++)
		    	{
		    		if(j == eq){
		    			jQuery('.location_content input:eq('+j+')').attr('name', 'name['+increment+']');
		    		}
		    		else if(j == (eq + 1))
		    		{
		    			jQuery('.location_content input:eq('+j+')').attr('name', 'lat['+increment+']');
		    		}
		    		else
		    		{
		    			jQuery('.location_content input:eq('+j+')').attr('name', 'lon['+increment+']');
		    		}
		    	}
		    	eq = eq + 3;
		    }

		    increment++;

		});

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

		jQuery('#export_csv').on('click', function(e){

			var data = {
				'action': 'export_csv'
			};

			jQuery.post(ajaxurl, data);

		});

	});
</script>