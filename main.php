<?php
/*
Implementación en PHP del método de cifrado 'Solitaire' de Bruce Schneier.
Desarrollado por git/coriumalpha
Last update: 2016-02-25
*/

error_reporting(E_ERROR | E_WARNING | E_PARSE);


$deckUnparsed = ''; //Insertar baraja sin parsear a formato numérico
$inputCipherText = ''; //Ciphertext en mayúsculas y sin espacios


function inputToNumbers($input) //Parsea la entrada y devuelve un valor entero para cada carta si hay 54 elementos
{
	$arrayInput = explode(" ", $input); //Convierte la cadena de entrada en un array de elementos (1 elemento cada espacio)
	if(count($arrayInput) == 54) //¿Hay 54 cartas? Pues p'alante
	{
		foreach ($arrayInput as $cifrado)
		{
			if($cifrado == 'A') //Asigna valor 53 al elemento A
			{
				$numbers .= ' 53';
			}
			elseif($cifrado == 'B') //Asigna valor 54 al elemento B
			{
				$numbers .= ' 54';
			}
			else
			{
				//Se calcula actualNumber para elementos con valor indexado
				if($cifrado[0] == 'J') 
				{
					$actualNumber = 11;
				}
				elseif($cifrado[0] == 'Q')
				{
					$actualNumber = 12;				
				}
				elseif($cifrado[0] == 'K')
				{
					$actualNumber = 13;				
				}
				elseif($cifrado[0] == 'T')
				{
					$actualNumber = 10;				
				}
				elseif($cifrado[0] == 'A')
				{
					$actualNumber = 1;				
				}
				else
				{
					$actualNumber = $cifrado[0]; //Si el elemento actual no tiene valor indexado, su valor se mantiene.
				}

				//Toma el segundo índice del elemento para calcular el valor como entero de la carta
				if($cifrado[1] == 'c') //Tréboles (valor + 0)
				{
					$numbers .= ' '.$actualNumber;
				}
				elseif($cifrado[1] == 'd') //Diamantes (valor + 13)
				{
					$numbers .= ' '.($actualNumber + 13);
				}
				elseif($cifrado[1] == 'h') //Corazones (valor + 26)
				{
					$numbers .= ' '.($actualNumber + 26);
				}
				elseif($cifrado[1] == 's') //Picas (valor + 39)
				{
					$numbers .= ' '.($actualNumber + 39);
				}
			}
		}
	}
	else
	{
		//La baraja contiene menos de 54 elementos
	}
	$numbers = substr($numbers, 1);
	return $numbers;
}

function elementSwap($deck, $posIni, $posFin){
   $tmp = $deck[$posIni]; //Almacena en variable temporal el contenido del elemento número posIni
   $deck[$posIni] = $deck[$posFin]; //El elemento posición posIni es igual al elemento situado en posFin
   $deck[$posFin] = $tmp; //Restaura el valor de posFin al valor almacenado en temporal
   return $deck; //Devuelve la baraja tras el ajuste
}

function jokerSwapA($deck)
{
	$array = explode(" ", $deck);
	$positionA = array_search('53', $array); //Posición origen de JokerA (búsqueda en array)
	$positionB = ($positionA + 1) % 54; //Posición destino de JokerA (posición + 1 congruente módulo 54)

	$swaped = elementSwap($array, $positionA, $positionB); //Intercambia el JokerA por su carta posterior
	foreach($swaped as $carta) //Convierte el array ordenado en una cadena
	{
		$resultado .= ' '.$carta;
	}
	//echo '<pre> Tras jokerSwapA -> '.substr($resultado, 1).'</pre><br>';
	return substr($resultado, 1);
}

function jokerSwapB($deck)
{
	$array = explode(" ", $deck);
	$positionA = array_search('54', $array); //Posición origen de JokerB (búsqueda en array)
	$positionB = ($positionA + 3) % 54; //Posición destino de JokerB (posición + 3 congruente módulo 54)

	for($i = 0; $i < $positionA; $i++) //Primera parte del array, antes de la aparición de JokerB
	{
		$primerArray .= ' '.$array[($i % 54)];
	}
	//Encuentra el comodín B. Muévelo bajo la carta que está debajo de la que tiene debajo.
	$primerArray .= ' '.$array[(($positionA + 1) % 54)];
	$primerArray .= ' '.$array[(($positionA + 2) % 54)];
	$primerArray .= ' '.$array[($positionA % 54)];
	for($i = $positionB; $i < 54; $i++)
	{
		$primerArray .= ' '.$array[($i  % 54)];
	}

	//echo '<pre> Tras jokerSwapB -> '.substr($resultado, 1).'</pre><br>';
	return substr($primerArray, 1);
}

function tripleCut($deck)
{
	$array = explode(" ", $deck);
	$primerJoker = min(array_search('54', $array), array_search('53', $array)); //Calcula primer comodín en base al número de posición más bajo
	$segundoJoker = max(array_search('54', $array), array_search('53', $array)); //Calcula el segundo (número más alto)
	$orbitalSegundoJoker = 53 - $segundoJoker; //Tiempo de órbita para obtener SectorC
	$orbitalSectorB = $segundoJoker - $primerJoker; //Tiempo de órbita (tamaño sector intermedio)

	for($i = 0; $i < ($primerJoker); $i++) //Añade cartas desde el principio de la baraja, en orden, hasta alcanzar Joker1-1
	{
		$sectorA .= ' '.$array[$i];
	}
	$sectorA = substr($sectorA, 1);
	//echo '<pre> SectorA -> '.$sectorA.'</pre><br>';
	for($i = 0; $i <= $orbitalSectorB; $i++) //Añade cartas desde Joker1 a Joker2 (ambos incluídos)
	{
		$sectorB .= ' '.$array[$primerJoker + $i];
	}
	$sectorB = substr($sectorB, 1);
	//echo '<pre> SectorB -> '.$sectorB.'</pre><br>';
	for($i = 0; $i < $orbitalSegundoJoker; $i++) //Añade cartas a sectorC a partir de Joker2
	{
		$sectorC .= ' '.$array[($segundoJoker + $i + 1)];
	}
	$sectorC = substr($sectorC, 1);
	//echo '<pre> SectorC -> '.$sectorC.'</pre><br>';

	$concatenacion = $sectorC.' '.$sectorB.' '.$sectorA;
	//echo '<pre> Sectores Concatenados -> '.$concatenacion.'</pre><br>';
	return $concatenacion; //Devuelve la concatenación en nuevo orden de los tres sectores
}

function countCut($deck)
{
	$array = explode(" ", $deck);
	$ultimaCarta = $array[53];

	if($ultimaCarta == '53' || $ultimaCarta == '54')
	{
		$valorResultante = '53';
	}
	else
	{
		$valorResultante = $ultimaCarta;
	}
	//echo '<pre> Valor Resultante -> '.$valorResultante.'</pre><br>';

	for($i = 0; $i < $valorResultante; $i++)
	{
		$sectorA .= ' '.$array[$i];
	}
	$sectorA = substr($sectorA, 1);
	for($i = 0; $i <= (52 - $valorResultante); $i++)
	{
		$sectorB .= ' '.$array[$valorResultante + $i];
	}
	$sectorB = substr($sectorB, 1);
	//echo '<pre> SectorA -> '.$sectorA.'</pre><br>';
	//echo '<pre> SectorB -> '.$sectorB.'</pre><br>';	
	//echo '<pre> Última Carta -> '.$ultimaCarta.'</pre><br>';
	
	$concatenacion = $sectorB.' '.$sectorA.' '.$ultimaCarta;
	
	//echo '<pre> Concatenación -> '.$concatenacion.'</pre><br>';
	return $concatenacion;
}

function findOutputCard($deck)
{
	$array = explode(" ", $deck);
	$primeraCarta = $array[0];
	//echo '<pre> Primera Carta -> '.$primeraCarta.'</pre><br>';
	settype($primeraCarta, "integer");

	$conteo = $array[$primeraCarta];
	return $conteo;
}

function generateKey($deck)
{
	//echo 'Parsed Deck -> '.$deck.'<br>';
	$swapA = jokerSwapA($deck);
	//echo 'Swap A -> '.$swapA.'<br>';
	$swapB = jokerSwapB($swapA);
	//echo 'Swap B -> '.$swapB.'<br>';
	$tripleCut = tripleCut($swapB);
	//echo 'TripleCut -> '.$tripleCut.'<br>';
	$countCut = countCut($tripleCut);
	//echo 'CountCut -> '.$countCut.'<br>';
	$findOutputCard = findOutputCard($countCut);
	//echo 'findOutputCard -> '.$findOutputCard.'<br>';

	$key = $findOutputCard;
	$resultado[0] = $findOutputCard;
	$resultado[1] = $countCut;
	return $resultado;
}

function keyToLetter($key)
{
	$espacio = Array('Z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y');
	$result = $espacio[$key];
	//echo '<pre> Plaintext Key -> '.$result.'</pre><br>';
	return $result;
}

function letterToKey($letter)
{
	$espacio = Array('Z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y');
	$result = array_search($letter, $espacio);
	//echo '<pre> Plaintext Key -> '.$result.'</pre><br>';
	return $result;
}


function inputDecode($inputCipherText, $deckUnparsed)
{
	$temp = inputToNumbers($deckUnparsed);
	//echo $deckUnparsed.'temp<br>';

	for($i = 0; $i < strlen($inputCipherText); $i++)
	{
		$temporalStorage = generateKey($temp);
		$temp = $temporalStorage[1];

		if($temporalStorage[0] != 53 && $temporalStorage[0] != 54)
		{
			$keyedInputCipher = letterToKey($inputCipherText[$i]);
			if($keyedInputCipher < ($temporalStorage[0] % 26))
			{
				$keyedInputCipher = $keyedInputCipher + 26;
			}
			$iToPlain = $keyedInputCipher - ($temporalStorage[0] % 26);
			$iToPlain = abs($iToPlain);
			$result[] = keyToLetter(abs($iToPlain % 26));
		}
		else
		{
			$i = $i - 1;
		}
	}
	return $result;
}

$doDecode = inputDecode($inputCipherText, $deckUnparsed);

echo 'Input key: '.$deckUnparsed.'<br>Input Ciphertext: '.$inputCipherText.'<br>Decoded sequence:<br><br>';



for($i = 0; $i < strlen($inputCipherText); $i += 5)
{
	echo implode(" ", array_slice($doDecode, $i, 5)).'<br>';
}
?>
