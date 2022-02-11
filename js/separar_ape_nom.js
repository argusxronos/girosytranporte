function DividirApeNom(str_Ape_Nom, field_ape, field_nom)
{
	str_Ape_Nom = str_Ape_Nom.toUpperCase();
	coma = str_Ape_Nom.indexOf(',');
	var apellidos = '';
	var nombres = '';
	if (coma > 0)
	{
		apellidos = str_Ape_Nom.substring(0,coma)
		nombres = str_Ape_Nom.substring(coma + 2, str_Ape_Nom.lenght)
	}
	document.getElementById(field_ape).value = apellidos
	document.getElementById(field_nom).value = nombres
}