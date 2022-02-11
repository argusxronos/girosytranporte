<?PHP
	// FUNCION PARA PODER ENCRIPTAR 
	class ENCRYPT
	{
		public function  __construct() {
			
		}
		public function encrypt_str($CCADENA, $CSEMILLA)
		{
			// private $x = 1;
			$L = 0;
			$CCHAR = '';
			$E_XOR = '';
			$XY = 0;
			$CASCII = '';
			$Z = 0;
			$L = strlen($CSEMILLA);
			for ($X = 1; $X <= strlen($CCADENA); $X++)
			{
				$XY = $X % $L;
				$Z = ($XY) - $L * ( $XY == 0 ? 1 : 0);
				$CCHAR  = ord(substr($CSEMILLA,$Z,1));
				$CASCII = ord(substr($CCADENA,$X,1));
				$E_XOR = $this->bitxor($CASCII , $CCHAR );
				$CCADENA = $this->f_stuff($CCADENA,$this->f_chr($E_XOR),$X,1);
			}
			$lcNewCadena='';
			for($X = 1; $X <= strlen($CCADENA); $X++)
			{
				$CASCII = $this->f_ord(substr($CCADENA, $x, 1));
				if ($CASCII <> 32 && $CASCII <> 0)
				{
					$lcNewCadena = $lcNewCadena + substr($CCADENA, $X, 1);
				}
			}
			return $lcNewCadena;
		}
		
		public function __destruct()
		{
			
		}
		
		public function f_ord($asc)
		{
			// FUNCION PARA COMVERTIR UN CARACTER A CODIGO ASSCII
			return ord($asc);
		}
		
		public function f_chr($number)
		{
			// FUNCION PARA COMVERTI UN NUMERO DEL CODIGO ASCII A CARACTER
			return chr($number);
		}
		
		public function f_stuff($str, $str_r, $pos, $ccremp)
		{
			// FUNCTION PARA REALIZAR LA FUNCION STUFF DE FOXPRO
			return substr_replace($str, $str_r, $pos, $ccremp);
		}
		
		public function bitxor($str, $key) {
			$xorWidth = PHP_INT_SIZE*8;
			// split
			$o1 = str_split($str, $xorWidth);
			$o2 = str_split(str_pad('', strlen($str), $key), $xorWidth);
			$res = '';
			$runs = count($o1);
			for($i=0;$i<$runs;$i++)
				$res .= decbin(bindec($o1[$i]) ^ bindec($o2[$i]));       
			return $res;
		}
	}
?>