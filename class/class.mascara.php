<?php
	class Mascara extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new Mascara();
	        return self::$instance;
	    }

		public function OutMascaraCPF($val, $mask = '###.###.###-##'){

			$maskared = '';
			$k = 0;
			for($i = 0; $i<=strlen($mask)-1; $i++){
				if($mask[$i] == '#'){
					if(isset($val[$k]))
						$maskared .= $val[$k++];
				}
				else{
					if(isset($mask[$i]))
						$maskared .= $mask[$i];
				}
			}
			return $maskared;
		}

		public function OutMascaraCNPJ($val, $mask = '##.###.###/####-##'){

			$maskared = '';
			$k = 0;
			for($i = 0; $i<=strlen($mask)-1; $i++){
				if($mask[$i] == '#'){
					if(isset($val[$k]))
						$maskared .= $val[$k++];
				}
				else{
					if(isset($mask[$i]))
						$maskared .= $mask[$i];
				}
			}
			return $maskared;
		}

		public function OutMascaraData($data){

			$maskared = '';
			$maskared = implode('/', array_reverse(explode('-', $data)));
			return $maskared;
		}

		public function InMascaraData($data){
			$maskared = '';
			$maskared = implode('-', array_reverse(explode('/', $data)));
			return $maskared;
		}

		public function InMascaraCPF($data){
			$maskared = '';
			$maskared = str_replace('.', '', $data);
    		$maskared = str_replace('-', '', $maskared);
			return $maskared;
		}

		public function InMascaraCelular($data){
			$maskared = '';
			$maskared = str_replace('(', '', $data);
			$maskared = str_replace(')', '', $maskared);
			$maskared = str_replace('-', '', $maskared);
			$maskared = str_replace(' ', '', $maskared);
			return $maskared;
		}

		public function InMascaraCEP($data){
			$maskared = '';
			$maskared = str_replace('.', '', $data);
    		$maskared = str_replace('-', '', $maskared);
			return $maskared;
		}



	}