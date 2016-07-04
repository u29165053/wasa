<?php 

session_start();

if (isset($GLOBALS["ABSPATH"])){
	require_once($GLOBALS["ABSPATH"]."/include/database.php");
	require_once($GLOBALS["ABSPATH"]."/include/utils.php");
	require_once($GLOBALS["ABSPATH"]."/include/settings.php");
}else{
	require_once("include/database.php");
	require_once("include/utils.php");
	require_once("include/settings.php");
}

function getPost($name){
	return isset($_POST[$name]) && !is_null($_POST[$name]) ? addslashes($_POST[$name]) : NULL;
}

$sha256 = getPost("analysis");

$db = new DAO();

$analisis = $db->getAnalysis($sha256);

 ?>
<div class="row" id="analysisContent">
	<div class="col-md-2 sidebar" >
		<div data-spy="affix" class="affix" data-offset-top="60" data-offset-bottom="200">
		<div class="list-group affix">
		  <a href="#titleGeneral" class="list-group-item active">
		    Información del fichero
		  </a>
		  <?php if( $analisis->getType() == 0 ): ?>
		  <a href="#titleSections" class="list-group-item">Secciones</a>
		  <a href="#titleDll" class="list-group-item">Librerias y funciones</a>
		  <a href="#titleCode" class="list-group-item">Código ensamblador</a>
		<?php elseif( $analisis->getType() == 1 ): ?>
		  <a href="#titleManifest" class="list-group-item">AndroidManifest.xml</a>
		  <a href="#titlePermisos" class="list-group-item">Permisos</a>

		<?php endif; ?>
		</div>
		</div>
	</div>
	<div class="col-md-10" style="border-left: 1px solid #ccc;">
		<div class="row">
			<div class="col-md-8">
				<p><a href="http://<?php echo $analisis->getMuestra(); ?>" >Descargar muestra del fichero.</a> 
					La contraseña es <span class="label label-info"><?php echo $analisis->getPwdMuestra(); ?></span></p>
			</div>
				
			<div class="col-md-2">
				<?php if ( $analisis->getType() == 1 ): ?>
				<form method="GET" action="include/smali.php">
					<input type="hidden" name="sha256" value="<?php echo $sha256; ?>" />
					<button class="btn btn-success" type="submit">Generar código smali</button>
				</form>
			<?php endif; ?>
			</div>
			<div class="col-md-2">
				<form method="GET" action="include/report.php">
					<input type="hidden" name="sha256" value="<?php echo $sha256; ?>" />
					<button class="btn btn-primary" type="submit">Generar informe</button>
				</form>
			</div>
		</div>
		<h3 id="titleGeneral">Información del fichero   
			<a class="btn btn-default" role="button" data-toggle="collapse" 
			href="#dataGeneral" aria-expanded="false" aria-controls="dataGeneral">
			<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></a>
		</h3>

		<div id="dataGeneral" class="">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>Propiedad</th>
					<th>Valor</th>
				</tr>	
			</thead>
			<tbody>
				<tr><td>Nombre:</td><td><?php echo $analisis->getFilename(); ?></td></tr>
				<tr><td>Tamaño:</td><td><?php echo $analisis->getSize(); ?></td></tr>
				<tr><td>Hash MD5:</td><td><?php echo $analisis->getMd5(); ?></td></tr>
				<tr><td>Hash SHA1:</td><td><?php echo $analisis->getSha1(); ?></td></tr>
				<tr><td>Hash SHA256:</td><td><?php echo $analisis->getSha256(); ?></td></tr>
			</tbody>
		</table>
		</div>

		<?php 
			if ( $analisis->getType() == 0 ):
		 ?>
		<h3 id="titleSections">Secciones del binario
			<a class="btn btn-default" role="button" data-toggle="collapse" 
			href="#dataSections" aria-expanded="false" aria-controls="dataSections">
			<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></a></h3>
		<div id="dataSections" class="">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>Sección</th>
					<th>Dirección Virtual</th>
					<th>Tamaño en Memoria</th>
					<th>Tamaño en Disco</th>
				</tr>	
			</thead>
			<tbody>
				<?php 
					foreach ($analisis->getSections() as $key => $value) {
						echo '<tr><td>'.$key.'</td><td>'.$value[0].'</td><td>'.$value[1].'</td><td>'.$value[2].'</td></tr>';
					}
				 ?>
			</tbody>
		</table>
		</div>


		<h3 id="titleDll">Dll's y funciones	
			<a class="btn btn-default" role="button" data-toggle="collapse" 
			href="#dataDll" aria-expanded="false" aria-controls="dataDll">
			<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></a></h3>

		<div id="dataDll" class="collapse">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>Dll</th>
					<th>Dirección de memoria</th>
					<th>Función</th>
				</tr>	
			</thead>
			<tbody>
				<?php 
					foreach ($analisis->getDlls() as $dll => $funciones) {
						$count = count($funciones);
						echo '<tr>';
						echo '<td rowspan="'.$count.'">'.$dll.'</td>';
						for($i = 0; $i < $count; $i++) {
							if($i>0){
								echo '<tr>';
							}
							echo '<td>'.$funciones[$i][0].'</td><td>'.$funciones[$i][1].'</td>';
							echo '</tr>';
						}
					}
				 ?>
			</tbody>
		</table>
		</div>


		<h3 id="titleCode">Código ASM	<a class="btn btn-default" role="button" data-toggle="collapse" 
			href="#dataCode" aria-expanded="false" aria-controls="dataCode">
			<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></a></h3>

		<div id="dataCode" class="collapse">

		<pre>
		<?php echo $analisis->getCode(); ?>
		</pre>
		</div>
		</div>
	<?php elseif( $analisis->getType() == 1 ): ?>
		<h3 id="titleManifest">Android Manifest
			<a class="btn btn-default" role="button" data-toggle="collapse" 
			href="#dataManifest" aria-expanded="false" aria-controls="dataManifest">
			<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></a></h3>

		<div id="dataManifest" class="">
				<?php 
					$f = fopen ($analisis->getManifest(), "r");
					$texto = "";
  				    while ($trozo = fgets($f, 1024)){
					      $texto .= $trozo;
					}
					echo "<pre>".htmlentities($texto)."</pre>";
				 ?>
		</div>

		<h3 id="titlePermisos">Permisos
			<a class="btn btn-default" role="button" data-toggle="collapse" 
			href="#dataPermisos" aria-expanded="false" aria-controls="dataPermisos">
			<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></a></h3>

		<div id="dataPermisos" class="">
			<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>Grupo</th>
					<th>Permiso</th>
					<th>Descripción</th>
				</tr>	
			</thead>
			<tbody>
				<?php 
					foreach ($analisis->getPermisos() as $key => $value) {
						if ( $key != "OTHER") {
							echo '<tr><td>Android</td><td>'.$key.'</td><td>'.$value.'</td></tr>';

						}else{
							$other = $value;
						}
					}
					foreach ($other as $key => $value) {
							echo '<tr><td>Otros</td><td colspan="2">'.$value.'</td></tr>';
					}
							
				 ?>
			</tbody>
		</table>
		</div>
	<?php endif; ?>
</div>