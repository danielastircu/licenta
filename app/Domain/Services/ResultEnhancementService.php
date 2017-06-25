<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 6/25/2017
 * Time: 9:09 PM
 */

namespace App\Domain\Services;


class ResultEnhancementService {

	public function getFinalData( $target, $text ) {

		if ( $target == 'nutrition' ) {
			return $this->getNutritionData( $text );
		}
		list( $language, $propriety ) = $this->getLanguageAndProperty( $target );

		$functionName = 'get' . ucfirst( $propriety ) . "For" . ucfirst( $language );

		$text = trim( preg_replace( "/\r|\n/", ' ', $text ) );


		$text = $this->$functionName( $text );

		return $text;

	}

	public function getNutritionData( $obj ) {

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