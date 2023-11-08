<?php
include("../plantilla1.php");
$permisos = getPermissionsUsuarioContract($_SESSION["username"]);

?>
<script>
    $( '#adminPlantilla1' ).addClass( "active" );
    $( '.activePlantilla1' ).html( " <a href='index.php'Dashboard</a>");

</script>

<?php if($permisos[0]['permission_aprobar']=="1"){ ?>
	<iframe src="dashboard_legal.php" height="800px" width="100%" onLoad="manage_location(this.contentWindow.location);"></iframe>
<?php }else{?>
	<iframe src="dashboard_user.php?id=<?=$_SESSION['id']?>" height="800px" width="100%" onLoad="manage_location(this.contentWindow.location);"></iframe>
<?php }?>
<script>
	function manage_location(url){
		
		if(String(url).includes("reportes")){
			location.href = url;
		}
	}	
</script>