<form action="#" method="post">
	<label>Localisation</label>
	<button class="add_pin">+</button>
	<br/>
	<label>Nom du Pin</label><br/>
	<input type="text" name="name[0]" placeholder="nom" required=""><br/>
	<input type="text" name="lat[0]" placeholder="latitude" required=""> <br/>
	<input type="text" name="lon[0]" placeholder="longitude" required=""><br/>
	<div class="location_content"></div>
	<label>Itinéraire</label><br/>
	<input type="checkbox" name="itineraire"><br/>
	<label>Autoriser la création d'itinéraire</label><br/>
	<input type="checkbox" name="allow_it"><br/>
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
			html_block += '<br/><input type="text" name="name['+increment+']" placeholder="nom" required="">';
			html_block += '<br/><input type="text" name="lat['+increment+']" placeholder="latitude" required=""><br/>';
			html_block += '	<input type="text" name="lon['+increment+']" placeholder="longitude" required=""><br/>';

			$('.location_content').append(html_block);

			increment = increment + 1;
		});

	});
</script>