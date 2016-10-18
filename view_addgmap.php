<form action="" method="post">
	<label>Localisation</label>
	<button class="add_pin">+</button>
	<br/>
	<label>Nom du Pin</label><br/>
	<div class="location_content">
		<input type="text" name="name[0]" placeholder="nom" required="">
		<input type="text" name="lat[0]" placeholder="latitude" required="">
		<input type="text" name="lon[0]" placeholder="longitude" required="">
	</div>
	<label>Itinéraire</label>
	<input type="checkbox" name="route">
	<label>Autoriser la création d'itinéraire</label>
	<input type="checkbox" name="allow_it">
	<input type="text" name="map_width" placeholder="width" required="">
	<input type="text" name="map_height" placeholder="height" required="">
	<input type="number" name="zoom" placeholder="map zoom" required="">

	<!--Action to trigger the add_action()-->
	<input type="hidden" name="action" value="insert_gmap_datas">

	<input type="submit" value="Créer la GMap">
</form>

<a href="http://www.coordonnees-gps.fr/" target="_blank">
	<h3>Get gps datas</h3>
</a>

<script type="text/javascript">
	jQuery(document).ready(function() {

			var increment = 1;

		jQuery('.add_pin').on('click', function(e){
			e.preventDefault();
			var html_block = '<br/>Pin +'+increment;
			html_block += '<input type="text" name="name['+increment+']" placeholder="nom" required="">';
			html_block += '<input type="text" name="lat['+increment+']" placeholder="latitude" required="">';
			html_block += '<input type="text" name="lon['+increment+']" placeholder="longitude" required="">';
			html_block += '<button class="delete_pin">-</button>';

			jQuery('.location_content').append(html_block);

			increment = increment + 1;
		});

		jQuery('input[name=route]').on('click', function(){

			if( jQuery('input[name=route]').is(':checked') ){
		    	jQuery('.add_pin').hide();
		    	jQuery('.location_content').html('');
		    	var html_block = "";
		    	html_block += '<input type="text" name="name['+increment+']" placeholder="name starting destination" required="">';
				html_block += '<input type="text" name="lat['+increment+']" placeholder="latitude starting destination" required="">';
				html_block += '<input type="text" name="lon['+increment+']" placeholder="longitude starting destination" required="">';
				html_block += '<input type="text" name="name['+increment+']" placeholder="name ending destination" required="">';
				html_block += '<input type="text" name="lat['+increment+']" placeholder="latitude ending destination" required="">';
				html_block += '<input type="text" name="lon['+increment+']" placeholder="longitude ending destination" required="">';

				$('.location_content').append(html_block);

			} else {
			    jQuery('.add_pin').show();
				jQuery('.location_content').html('');
			    var html_block = "";
				html_block += '<input type="text" name="name[0]" placeholder="nom" required="">';
				html_block += '<input type="text" name="lat[0]" placeholder="latitude" required="">';
				html_block += '<input type="text" name="lon[0]" placeholder="longitude" required="">';
				html_block += '<button class="delete_pin">-</button>';

				jQuery('.location_content').append(html_block);
			    jQuery('input[name="name[1]"], input[name="lat[1]"], input[name="lon[1]"]').remove();
			}

		});

	});
</script>