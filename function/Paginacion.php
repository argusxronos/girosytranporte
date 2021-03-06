<?php
class Paginacion
{	
	private $page; //Pagina actual
	private $from; //Numero de registro desde donde iniciar la busqueda
	private $quantity; //Catidad de registros por p?gina
	private $totalRows; //Total de registros a paginar
	private $numPages; //Numero total de paginas
	private $puri; //Parte de la uri a agregar a la url inicial
	private $urlOriginal; //Url de la p?gina que se desea hacer la paginaci?n
	private $urlCalculada;
	private $classLink;
	
	
	public function __construct($quantity, $page) {
			
		//Validamos la entrada de datos
		settype($page, "integer");
		settype($quantity, "integer");
		$this->page = ($page > 0) ? $page : 0;
		$this->quantity = ($quantity > 0) ? $quantity : 0;
		$this->from = $page * $quantity;
		
	}
	
	public function getFrom()
	{
		return $this->from;
	}
	
	public function generaPaginacion($totalRows, $back, $next, $url, $class = "")
	{
		settype($totalRows, "integer");
		$this->totalRows = $totalRows;
		
		$this->classLink = $class;		
		$this->urlOriginal = $url;
		$this->numPages = ceil($totalRows/$this->quantity);
		
		if ($this->page > 0) {
			$this->puri = $this->page - 1;
			$this->urlCalculada = $this->urlOriginal."&page=$this->puri";
			$this->rectificaUrl($this->urlCalculada);
			echo "<a href=\"$this->urlCalculada\">$back</a>";
		}
		
		if ($this->numPages > 1) {
			for ($i = 0; $i < $this->numPages; $i++) {
				if ($i == $this->page) {
					echo "<span class=\"actualPage\">$i</span>";
				}
				elseif ($i == $this->page + 1 || $i == $this->page + 2 || $i == $this->page - 1
				|| $i == $this->page - 2 || $i == 0 || $i == ($this->numPages - 1)) {
										
					//$page + 1, $page +2 son los numeros que se desea ver por delante del actual
					//$page -1, $page -2 son los numeros (Links) a ver por detras del actual
					//Esto se puede modificar como se desee
					$this->puri = $i;
					$this->urlCalculada = $this->urlOriginal."&page=$this->puri";
					
					$this->rectificaUrl($this->urlCalculada);
					
					echo " <a class=\"$this->classLink\" href=\"$this->urlCalculada\">$i</a> ";
				}
				elseif ($i == $this->page - 3) {
					echo "<span>...</span>";
				}
				elseif ($i == $this->page + 3) {
					echo "<span>...</span>";
				}
			}
		}
		
		if ($this->page < $this->numPages - 1) {
			$this->puri = $this->page + 1;
			$this->urlCalculada = $this->urlOriginal."&page=$this->puri";
			
			$this->rectificaUrl($this->urlCalculada);
			
			echo "<a href=\"$this->urlCalculada\">$next</a>";
		}
	}
	
	private function rectificaUrl($url)
	{
		$this->urlCalculada = preg_replace('[\&*\&]', '&', $url);	//Quita las && = & repetidas
		$this->urlCalculada = preg_replace('[\?\&]', '?',$this->urlCalculada);	//Quita	?& = ?
	}
	
}
?>