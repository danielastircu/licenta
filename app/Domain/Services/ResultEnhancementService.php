<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 6/25/2017
 * Time: 9:09 PM
 */

namespace App\Domain\Services;


class ResultEnhancementService {

	private $dictionary;

	public function getFinalData( $target, VisionService $visionService, $obj, $originalPictureObj ) {

		if ( $target == 'nutrition' ) {
			return $this->getNutritionData( $visionService, $obj, $originalPictureObj );
		}

		$text = $visionService->getFullText( $obj );
		list( $language, $propriety ) = $this->getLanguageAndProperty( $target );

		$functionName = 'get' . ucfirst( $propriety ) . "For" . ucfirst( $language );

		$text = trim( preg_replace( "/\r|\n/", ' ', $text ) );


		$text = $this->$functionName( $text, $propriety, $language );

		$text = $this->validateData( $text, $propriety, $language );

		return $text;

	}

	public function validateData( $text, $propriety, $language ) {


		//open dictionary
		$dictionaryPath   = storage_path( 'dictionaries/' . $propriety . strtoupper( $language ) );
		$fileData         = file_get_contents( $dictionaryPath, 'w' );
		$this->dictionary = $fileData ? unserialize( $fileData ) : [ ];

		$data      = explode( ' ', $text );
		$finalText = " ";
		foreach ( $data as $key => $word ) {
			if ( 1 === preg_match( '~[0-9]~', $word ) ) {
				$finalText .= $word . " ";
				continue;
			}

			$partialWord     = $word;
			$hasComma        = false;
			$hasBracketLeft  = false;
			$hasBracketRight = false;
			if ( strpos( $word, ',' ) !== false ) {
				$hasComma    = true;
				$partialWord = str_replace( ",", "", $partialWord );
			}

			if ( strpos( $word, '(' ) !== false ) {
				$hasBracketLeft = true;
				$partialWord    = str_replace( "(", "", $partialWord );
			}

			if ( strpos( $word, ')' ) !== false ) {
				$hasBracketRight = true;
				$partialWord     = str_replace( ")", "", $partialWord );
			}

			//check word
			$candidateWord = $this->compareWithExistingData( $partialWord );

			if ( $hasBracketRight ) {
				$candidateWord .= ')';
			}
			if ( $hasComma ) {
				$candidateWord .= ',';
			}

			if ( $hasBracketLeft ) {
				$candidateWord = '(' . $candidateWord;
			}

			$finalText .= $candidateWord . " ";

		}


		file_put_contents( $dictionaryPath, serialize( $this->dictionary ) );

		return $finalText;
		//save dictionary
	}

	public function compareWithExistingData( $word ) {

		if ( isset( $this->dictionary[ $word ] ) ) {
			return $word;
		}

		$bestMatch = false;

		foreach ( $this->dictionary as $key => $row ) {
			$distance = $this->levenshteinDistance( $key, strtolower( $word ) );
			if ( $distance == 1 ) {
				return $key;
			} else if ( $distance == 2 && $bestMatch == false ) {
				$bestMatch = $key;
			}
		}

		if ( ! $bestMatch ) {

			$this->dictionary[ strtolower( $word ) ] = 1;

			return $word;
		}

		return $bestMatch;
	}

	public function levenshteinDistance( $str1, $str2 ) {
		$length1 = mb_strlen( $str1, 'UTF-8' );
		$length2 = mb_strlen( $str2, 'UTF-8' );
		if ( $length1 < $length2 ) {
			return $this->levenshteinDistance( $str2, $str1 );
		}
		if ( $length1 == 0 ) {
			return $length2;
		}
		if ( $str1 === $str2 ) {
			return 0;
		}
		$prevRow = range( 0, $length2 );
		for ( $i = 0; $i < $length1; $i ++ ) {
			$currentRow    = [ ];
			$currentRow[0] = $i + 1;
			$c1            = mb_substr( $str1, $i, 1, 'UTF-8' );
			for ( $j = 0; $j < $length2; $j ++ ) {
				$c2            = mb_substr( $str2, $j, 1, 'UTF-8' );
				$insertions    = $prevRow[ $j + 1 ] + 1;
				$deletions     = $currentRow[ $j ] + 1;
				$substitutions = $prevRow[ $j ] + ( ( $c1 != $c2 ) ? 1 : 0 );
				$currentRow[]  = min( $insertions, $deletions, $substitutions );
			}
			$prevRow = $currentRow;
		}

		return $prevRow[ $length2 ];
	}

	public function getNutritionData( VisionService $visionService, $obj, $originalPictureObj ) {
		$text         = $visionService->getFullText( $obj );
		$originalText = $visionService->getFullText( $originalPictureObj );

		$data['kJ']            = $this->getKJ( $text, $originalText );
		$data['kCal']          = $this->getKCal( $text, $originalText );
		$data['fat']           = $this->getFat( $text, $originalText );
		$data['saturated']     = $this->getSaturatedAcids( $text, $originalText );
		$data['carbohydrates'] = $this->getCarbohydrates( $text, $originalText );
		$data['sugar']         = $this->getSugar( $text, $originalText );
		$data['protein']       = $this->getProteins( $text, $originalText );
		$data['salt']          = $this->getSalt( $text, $originalText );

//		var_dump( $data );
//		die;
		return $data;

	}


	public function getKJ( $text, $originalText ) {
		preg_match_all( '/\d+[\s]?kj/', strtolower( $originalText ), $matches );

		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/\d+[\s]?kj/', strtolower( $text ), $matches );

		return $this->getNumber( reset( $matches[0] ) );

	}

	public function getKCal( $text, $originalText ) {
		preg_match_all( '/\d+[\s]?kcal/', strtolower( $originalText ), $matches );

		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}
		preg_match_all( '/\d+[\s]?kcal/', strtolower( $text ), $matches );

		return $this->getNumber( reset( $matches[0] ) );
	}

	public function getFat( $text, $originalText ) {

		//GERMAN
		preg_match_all( '/fett[\s]?\d+(,\d+)?/', strtolower( $originalText ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/fett[\s]?\d+(,\d+)?/', strtolower( $text ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		//ITALIAN
		preg_match_all( '/grassi[\s]?\d+(,\d+)?/', strtolower( $originalText ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/grassi[\s]?\d+(,\d+)?/', strtolower( $text ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		return false;
	}

	public function getCarbohydrates( $text, $originalText ) {
		//GERMAN
		preg_match_all( '/kohlenhydrate[\s]?\d+(,\d+)?/', strtolower( $originalText ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/kohlenhydrate[\s]?\d+(,\d+)?/', strtolower( $text ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		//ITALIAN
		preg_match_all( '/carboidrati[\s]?\d+(,\d+)?/', strtolower( $originalText ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/carboidrati[\s]?\d+(,\d+)?/', strtolower( $text ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		return true;
	}

	public function getSugar( $text, $originalText ) {
		//ITALIAN
		preg_match_all( '/zuccheri[\s]?\d+(,\d+)?/', strtolower( $originalText ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/zuccheri[\s]?\d+(,\d+)?/', strtolower( $text ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		return false;
	}

	public function getSaturatedAcids( $text, $originalText ) {
		//ITALIAN
		preg_match_all( '/grassi saturi?[\s]?\d+(,\d+)?/', strtolower( $originalText ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/grassi saturi?[\s]?\d+(,\d+)?/', strtolower( $text ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		return true;
	}

	public function getProteins( $text, $originalText ) {

		//GERMAN
		preg_match_all( '/eiweiss[\s]?\d+(,\d+)?/', strtolower( $originalText ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/eiweiss[\s]?\d+(,\d+)?/', strtolower( $text ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/proteine[\s]?\d+(,\d+)?/', strtolower( $originalText ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/proteine[\s]?\d+(,\d+)?/', strtolower( $text ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}


		return false;
	}

	public function getSalt( $text, $originalText ) {
		//ITALIAN
		preg_match_all( '/sale[\s]?\d+(,\d+)?/', strtolower( $originalText ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		preg_match_all( '/sale[\s]?\d+(,\d+)?/', strtolower( $text ), $matches );
		if ( $this->getNumber( reset( $matches[0] ) ) ) {
			return $this->getNumber( reset( $matches[0] ) );
		}

		return true;
	}

	public function getNumber( $text ) {
		preg_match_all( '/\d+(,\d+)?/', strtolower( $text ), $matches );

		return reset( $matches[0] );
	}

	public function getLanguageAndProperty( $target ) {
		$startsAt = strpos( $target, "[" ) + 1;
		$endsAt   = strpos( $target, "]", $startsAt );
		$language = substr( $target, $startsAt, $endsAt - $startsAt );


		$startsAt  = strpos( $target, "[", $endsAt ) + 1;
		$endsAt    = strpos( $target, "]", $startsAt );
		$propriety = substr( $target, $startsAt, $endsAt - $startsAt );

		return array( $language, $propriety );
	}


	public function getIngredientsForDe( $text ) {

		$text = preg_replace( '/.*Zutaten:? ?/', "", $text );

		return $text;
	}

	public function getIngredientsForFr( $text ) {
		$text = preg_replace( '/.*Ingrédients:? ?/', "", $text );

		return $text;
	}

	public function getIngredientsForIt( $text ) {
		$text = preg_replace( '/.*Ingredienti:? ?/', "", $text );

		return $text;
	}

	public function getIngredientsForEn( $text ) {
		$text = preg_replace( '/.*Ingredients:? ?/', "", $text );

		return $text;
	}

	public function getDescriptionForDe( $text ) {

		return $text;
	}

	public function getDescriptionForFr( $text ) {

		return $text;
	}

	public function getDescriptionForIt( $text ) {

		return $text;
	}

	public function getDescriptionForEn( $text ) {

		return $text;
	}

	public function getAllergensForDe( $text ) {

		return $text;
	}

	public function getAllergensForFr( $text ) {

		return $text;
	}

	public function getAllergensForIt( $text ) {

		return $text;
	}

	public function getAllergensForEn( $text ) {

		return $text;
	}


}