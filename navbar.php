  	<?php
		if (isset($title))
		{
	?>
  	<nav class="navbar navbar-default ">
  	    <div class="container-fluid">
  	        <!-- Brand and toggle get grouped for better mobile display -->
  	        <div class="navbar-header">
  	            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
  	                <span class="sr-only">Toggle navigation</span>
  	                <span class="icon-bar"></span>
  	                <span class="icon-bar"></span>
  	                <span class="icon-bar"></span>
  	            </button>
  	            <a class="navbar-brand" href="#"><img src="http://factura.solucionesrp.com.mx/img/logo.png" width="200" height="25"></a>
  	        </div>

  	        <!-- Collect the nav links, forms, and other content for toggling -->
  	        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

  	            <ul class='nav navbar-nav'>
                <?php 
                if($_SESSION['tipo'] == 5){
    
  	               
  	              echo "  <li class='$active_clientes'><a href='clientes.php'><i class='glyphicon glyphicon-user'></i>     Clientes</a></li>  	                

  	                <li class='$active_cpc'><a href='cpc.php'><i class='glyphicon glyphicon-briefcase'></i> Cuentas por cobrar</a></li>
                    
                    <li class='$active_movimientos'><a href='movimientos.php'><i class='glyphicon glyphicon-retweet'></i> Movimientos</a></li>

  	                <li class='$active_usuarios'><a href='usuarios.php'><i class='glyphicon glyphicon-lock'></i> Usuarios</a></li>"; 
                } 
            
                else if($_SESSION['tipo'] == 1){
                    
                echo "<li class='$active_clientes'><a href='clientes.php'><i class='glyphicon glyphicon-user'></i> Clientes</a></li>
  	                "; 
                    
                }
            
                else if($_SESSION['tipo'] == 2){
                    
                echo "                    
                    <li class='$active_cpc'><a href='cpc.php'><i class='glyphicon glyphicon-briefcase'></i> Cuentas por cobrar</a></li>

  	                "; 
                    
                }
            
                else if($_SESSION['tipo'] == 3){
                    
                echo "
  	                <li class='$active_clientes'><a href='clientes.php'><i class='glyphicon glyphicon-user'></i> Clientes</a></li>

  	                <li class='$active_cpc'><a href='cpc.php'><i class='glyphicon glyphicon-briefcase'></i> Cuentas por cobrar</a></li>

  	                "; 
                    
                }
            
                else if($_SESSION['tipo'] == 4){
                    
                echo "
  	                <li class='$active_clientes'><a href='clientes.php'><i class='glyphicon glyphicon-user'></i> Clientes</a></li>

  	                <li class='$active_cpc'><a href='cpc.php'><i class='glyphicon glyphicon-briefcase'></i> Cuentas por cobrar</a></li>
                    
                    <li class='$active_movimientos'><a href='movimientos.php'><i class='glyphicon glyphicon-retweet'></i> Movimientos</a></li>

  	                "; 
                    
                }                                    
                    ?>    
  	            </ul>


  	            <ul class="nav navbar-nav navbar-right">

  	                <li><a href="login.php?logout"><i class='glyphicon glyphicon-off'></i> Salir</a></li>
  	            </ul>
  	        </div><!-- /.navbar-collapse -->
  	    </div><!-- /.container-fluid -->
  	</nav>
  	<?php
		}
	?>
