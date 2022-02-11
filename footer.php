<!-- START C. FOOTER AREA -->
<div class="CurrentUser">
      <hr style=""/>
	  <p>Usuario Actual : <span><?php 
	  	if (isset($_SESSION['USUARIO']))
			echo $_SESSION['USUARIO']; 
		else
			echo 'INVITADO'; 
	  ?></span> | Agencia Actual : <span><?php 
	  	if (isset($_SESSION['OFICINA']))
			echo $_SESSION['OFICINA'];
		else
			echo 'TURISMO CENTRAL S.A.';
		?></span></p>
</div>
<div class="footer">
  <p>Copyright &copy; 2011 Turismo Central S.A. | Todos los Derechos Reservados </p>
  <p class="credits">Oficina Principal Jr Ayacucho 274 | Telf. (+51) 064 223128 | RPM #223504 <a href="http://rivera.xronos.herobo.com/" title="Desarrollado por: Jonatan Rivera C." style="text-decoration:none;" target="_blank" >v.1.0</a> <BR /> E-mail. <a href="http://rivera.xronos.herobo.com/" target="_blank" >sugerencias@turismocentral.com.pe </a></p>
</div>
<!-- END C. FOOTER AREA -->