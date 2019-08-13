<!-- Contenedor de la cabecera-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Sistema de control presupuestario" />
	<meta name="author" content="Víctor Mancilla Rosales" />
	<title><?php 
	$activePage = basename($_SERVER['PHP_SELF'], ".php"); 
	echo $titulo ?? 'Sistema de control presupuestario' ?></title>
	<!-- Bootstrap -->
	<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.3.1/css/buttons.bootstrap4.min.css"/>
	<link href="bootstrap/css/style.css" rel="stylesheet">
	<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
	<link href="css/select2-bootstrap4.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<link rel="stylesheet" href="css/jquery.range.css">
	<link rel="stylesheet" href="css/Chart.min.css">
	<link rel=icon href='./img/logo.png' sizes="32x32" type="image/png">
</head>
<!-- /Contenedor de la cabecera-->