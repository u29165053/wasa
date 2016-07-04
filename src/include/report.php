<?php 

require_once('dompdf/autoload.inc.php');
require_once('dompdf/src/Dompdf.php');
require_once('database.php');

use Dompdf\Dompdf;

ob_start();

function formatXmlString($xml){
    $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
    $token      = strtok($xml, "\n");
    $result     = '';
    $pad        = 0; 
    $matches    = array();
    while ($token !== false) : 
        if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) : 
          $indent=0;
        elseif (preg_match('/^<\/\w/', $token, $matches)) :
          $pad--;
          $indent = 0;
        elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
          $indent=1;
        else :
          $indent = 0; 
        endif;
        $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
        $result .= $line . "\n";
        $token   = strtok("\n");
        $pad    += $indent;
    endwhile; 
    return $result;
}


if (!isset($_GET['sha256'])){
	die();
}

$sha256 = $_GET['sha256'];

$db = new DAO();

$analisis = $db->getAnalysis($sha256);

$pdf = new Dompdf();
?>
<style type="text/css">
body {
    color: #000000;
    background-color: #fff;
	font-family: 'Open Sans',"Helvetica Neue",Helvetica,Arial,sans-serif;
    padding-left: 1em;
    padding-top: 2em;
    padding-bottom: 5px;
}
.page{
	height:100px;
}
#portada{
	padding:0;margin:0;
	width:100%;
	height: 95%
}
#portada .frame{
	margin-top:30%;
}
#portada h1,#portada h2,#portada h3,#portada h4,#portada h5,#portada h6{
	text-align: center;
}
table.table-bordered{
	border-collapse: collapse;
	border-spacing: 0;
	margin:10px auto;
}
table.table-bordered tr td,
table.table-bordered tr th{
	border:1px solid #ddd;
	padding:5px;
}
div.row{
	margin-top:15px;
	font-size:10pt;
}

#header{ position:fixed; top:-30px; font-size:12px;}
#header table {width:100%;color:#5C5C5C; margin-bottom:15px;}
#header table .fecha{ float:left; width:50%; vertical-align:bottom;}
#header table .minilogo{ width:25%;}

#footer{color:#5C5C5C; font-size:12px; position:fixed; vertical-align:bottom; left: 0px; bottom: 15px; right: 0px;}
#footer table {width:100%; vertical-align:bottom;}
#footer table .autor{float:left; width:75%; vertical-align:bottom;}
#footer table .tfg{width:20%; vertical-align:bottom;}
div.wrap-pre{
	/*white-space: pre-wrap;*/
}
pre {
    white-space:pre-wrap;
     word-wrap:break-word;
}

</style>
<body>
<div id="header">
	<table id="header" cellpadding="0" cellspacing="0">
	    <tr>
	      	<td class="fecha"><br /><?php echo "Fecha de informe ".date("d/m/Y") ?></td>
	      	<td rowspan="2" class="minilogo"><img src="../img/logounir.png" title="Logo Cabecera" /></td>
	    </tr>
	    <tr>
	    	<td>Windows and Android Static Analyzer</td>
	    </tr>
	    <tr>
	    	<td colspan="2"></td>
	    </tr>
	</table>
	<br /><br />
</div>

<div id="portada" class="page">
	<div class="frame">
		<h2>Informe de Análisis est&aacute;tico del fichero:</h2>
		<h3><?php echo $analisis->getFilename(); ?></h3>
	</div>
</div>
<div style="page-break-after: always;"></div>
<div class="row">
	<h1>1. Alcance y objetivo</h3>
	<p>
		El objetivo del presente documento es facilitar una versión imprimible del análisis estático
		del fichero <?php echo $analisis->getFilename(); ?>.
	</p>
	<p>A lo largo de este documento se presentarán las información del análisis en diferentes apartados.</p>
</div>
<div class="row">
	<h1>2. Informaci&oacute;n del fichero</h3>
	<table class="table table-bordered table-condensed">
		<tbody>
			<tr><td>Nombre:</td><td><?php echo $analisis->getFilename(); ?></td></tr>
			<tr><td>Tamaño:</td><td><?php echo $analisis->getSize(); ?></td></tr>
			<tr><td>Hash MD5:</td><td><?php echo $analisis->getMd5(); ?></td></tr>
			<tr><td>Hash SHA1:</td><td><?php echo $analisis->getSha1(); ?></td></tr>
			<tr><td>Hash SHA256:</td><td><?php echo $analisis->getSha256(); ?></td></tr>
		</tbody>
	</table>
</div>
<?php if ( $analisis->getType() == 0 ):	 ?>
<div style="page-break-after: always;"></div>
<div class="row">
	<h1>3. Secciones del Binario </h1>
	<?php 

	?>

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
<div style="page-break-after: always;"></div>
<div class="row">
	<h1>4. Librerias y funciones importadas</h1>
	<ul>
		<?php 
		foreach ($analisis->getDlls() as $dll => $funciones) {
			echo "<li class='library'>".$dll;
			$count = count($funciones);
			if($count > 0){
				echo "<ul>";
				for($i = 0; $i < $count; $i++) {
					echo '<li>'.$funciones[$i][0].': '.$funciones[$i][1].'</li>';
				}
				echo "</ul>";
			}
		}
		 ?>
	</ul>
</div>

<div style="page-break-after: always;"></div>
<div class="row">
	<h1>5. Código ASM</h1>
	<pre><?php echo $analisis->getCode(); ?></pre>
</div>
<?php endif; ?>


<?php if ( $analisis->getType() == 1 ):	 ?>


<div style="page-break-after: always;"></div>
<div class="row" style="font-size:8pt">
	<h1>3. Android Manifest</h1>

		<?php 
			$f = fopen ($analisis->getManifest(), "r");
			$texto = "";
		    while ($trozo = fgets($f, 1024)){
		      $texto .= $trozo;
			}
			$replaced = preg_replace('/(\ +)</', "\n$1<", $texto);
			//$out = preg_replace('/^(android:[^=]+="[^"]+")/', "\n    $1", $replaced);
			$dom = new DOMDocument();

			// Initial block (must before load xml string)
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			// End initial block

			$dom->loadXML($replaced);
			$outXml = $dom->saveXML();

			print '<div class="wrap-pre">';
			print '<pre style="">'.htmlentities($replaced)."</pre>";
			print '</div>';
		?>
</div>
<div style="page-break-after: always;"></div>
<div class="row">
	<h1>4. Permisos</h1>

	<?php 
	foreach ($analisis->getPermisos() as $key => $value) {
		if ( $key != "OTHER") {
			echo '<h4><strong>Android: </strong> '.$key.'</h4>';
			echo '<p style="font-style: italic">'.$value.'</p>';
		}else{
			$other = $value;
		}
	}
	foreach ($other as $key => $value) {
			echo '<h5><strong>Otros: </strong> '.$value.'</h5>';
	}
 ?>
</div>

<?php endif; ?>
<div id="footer">
	<table cellpadding="0" cellspacing="0">
	    <tr>
	      	<td class="autor">Diego Fernández Valero</td>
	      	<td class="tfg" style="text-align:right">Trabajo de Fin de Grado</td>
	    </tr>
	</table>
<?php
		// echo $pdf->get_canvas()->get_page_count();
?>
</div>

</body>
<?php 

header("Pragma: cache"); 
$buffer=ob_get_contents();
$buffer1 = htmlentities($buffer);
$html=ob_get_clean();
$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
$pdf->load_html(utf8_decode($html),$buffer1);
$pdf->render();
$pdf->stream("Informe_general.pdf", array("Attachment" => 0)) 

 ?>