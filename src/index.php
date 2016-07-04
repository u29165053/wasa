<?php 
session_start();



require_once("/include/database.php");
require_once("/include/utils.php");


$db = new DAO();
$db->setOption("abspath", dirname(__file__));
$protocol = !empty($_SERVER["HTTPS"]) ? "https://" : "http://";
$db->setOption("home", $protocol.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
unset($protocol);
$homeUrl = $db->getOption("home");
$db->setOption("url_uploads", $homeUrl."/uploads");
$db->setOption("url_muestras", $homeUrl."/muestras");


 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="Diego Fernández Valero">
    <link rel="icon" href="favicon.ico">

    <title>Windows and Android Static Analyzer</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron.css" rel="stylesheet">
    <link href="css/fileinput.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $homeUrl; ?>">WASA</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

        <?php 
        	if(isset($_SESSION["iu"]) && $_SESSION["iu"] > 0 && isset($_SESSION["ius"])):
        		// Usuario logueado
        		$user = unserialize(Utils::rc4_decrypt($_SESSION["ius"]));

        		
         ?>
	         <div class="navbar-form navbar-right dropdown">
     		 	<a class="navbar-brand dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
     		 		<?php echo $user->getUsername(); ?>
     		 		<span class="caret"></span>
     		 	</a>
     		 	<ul class="dropdown-menu" aria-labelledby="userDropdown">
				    <li><a href="actions/logout.php">Cerrar Sesion</a></li>
			 	</ul>
	         </div>

     	<?php 
     		else:
     			// Usuario anónimo
     	 ?>
          <form class="navbar-form navbar-right" method="post" action="actions/login.php">
            <div class="form-group">
              <input type="text" placeholder="Username" name="username" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Acceder</button>
            <a data-href="signup.php" href="#" class="ajax-load btn btn-default">Registrarse</a>
          </form>

          <?php 
            endif; //ENF IF
           ?>

        </div><!--/.navbar-collapse -->
      </div>
    </nav>
<div id="mainContent"> <!-- mainContent -->
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron" id="jumbotron">
        <h2 style="text-align:center; margin:0 auto;">Windows and Android Static Analyzer</h2>
        <p>
          Sube tus ficheros (.exe y .apk) a través del siguiente formulario para realizar un análisis estático del mismo.
        </p>
        <p>
          También puedes registrarte si no quieres que tus análisis sean visibles al público.
        </p>
    </div>
      <div class="container">
        <form>
            <div id="error"></div>
            <div id="successMsg" class="alert alert-info" role="alert" style="display:none;"></div>
            <input id="uploadMalware" name="mlwr_muestra" type="file" class="file-loading">
        </form> 
  


      </div>

<?php if(!isset($_SESSION['iu'])): ?>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row" id="promo">
        <div class="col-md-4 bulletDiv">
          <img class="imgBullet" src="<?php echo $homeUrl."/img/exe.png"; ?>" title="Analisis de ficheros EXE" />
          <h2>Ficheros EXE</h2>
          <p>
            Con WASA puede analizar ficheros ejecutables de Windows, en formato PE (Portable Executable).
            <p>
        </div>
        <div class="col-md-4 bulletDiv">
          <img class="imgBullet" src="<?php echo $homeUrl."/img/android.png"; ?>" title="Analisis de ficheros EXE" />
          <h2>Ficheros APK</h2>
          <p>
            Decodifique aplicaciones Android, consulte su Manifest y analice los permisos que solicita. 
          </p>
       </div>
         <div class="col-md-4 bulletDiv">
          <img class="imgBullet" src="<?php echo $homeUrl."/img/report.png"; ?>" title="Analisis de ficheros EXE" />
          <h2>Informes</h2>
          <p>
            Obtenga informes de los analisis realizados, para poder consultarlos en cualquier momento. 
          </p>
       </div>
      </div>

      <div class="row" id="publicAnalysis">
        <div class="col-md-1"></div>
        <div class="col-md-10 bulletDiv">
          <h2>Análisis Públicos</h2>
          <table class="table table-condensed table-bordered">
            <thead>
              <tr>
                <th>Hash SHA256</th>
                <th>Nombre del fichero</th>
                <th>Tamaño</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $analysis = $db->getPublicAnalysis();
                foreach ($analysis as $item): ?>
                  <tr>
                    <td class="tl"><a class="ajax-load" rel-method="post" rel-data="analysis=<?php echo $item["sha256"];?>" data-href="report.php" href="#"><?php echo $item["sha256"];?></a></td>
                    <td><?php echo $item["filename"];?></td>
                    <td><?php echo $item["size"];?></td>
                  </tr>
              <?php endforeach; ?>

            </tbody>
          </table>
        </div>
        <div class="col-md-1"></div>


      </div>
      <hr>
<?php else: ?>
  <div class="container"></div>

      <div class="row" id="publicAnalysis">
        <div class="col-md-1"></div>
        <div class="col-md-10 bulletDiv">
          <h2>Tus Análisis</h2>
          <table class="table table-condensed table-bordered">
            <thead>
              <tr>
                <th>Hash SHA256</th>
                <th>Nombre del fichero</th>
                <th>Tamaño</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $analysis = $db->getPrivateAnalysis($_SESSION['iu']);
                foreach ($analysis as $item): ?>
                  <tr>
                    <td class="tl"><a class="ajax-load" rel-method="post" rel-data="analysis=<?php echo $item["sha256"];?>" data-href="report.php" href="#"><?php echo $item["sha256"];?></a></td>
                    <td><?php echo $item["filename"];?></td>
                    <td><?php echo $item["size"];?></td>
                  </tr>
              <?php endforeach; ?>

            </tbody>
          </table>
        </div>
        <div class="col-md-1"></div>


      </div>
      <hr>


<?php endif; ?>
      <footer>
        <p>&copy; 2016 Windows and Android Static Analyzer.</p>
      </footer>
    </div> <!-- /container -->
</div> <!-- /mainContent -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/fileinput.min.js"></script>
    <script src="js/custom.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
