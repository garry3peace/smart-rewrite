<?php

namespace app\components;

/**
 * List of all the rules to rewrite sentence
 * The parameter accept either array rule or
 * a Rule class
 */
class SentenceRewriterRule{

	const SENTENCE_OPENING = '%\b';//symbols for opening sentence
	const SENTENCE_CLOSING = '\b%i'; //symbols for closing sentence
	
	public static function rules()
	{
		$ifWord = "(seandainya|andaikan|jika|kalau|jikalau|asal|asalkan|manakala)";

		$timeWord = '(sejak|semenjak|sedari|sewaktu|tatkala|ketika|sementara|seraya|
			selagi|selama|sambil|demi|setelah|sesudah|sebelum|sehabis|selesai|seusai|
			hingga)';

		return [
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s`-]*) '.$ifWord .' ([\w\s`-]*) maka ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match1|ucfirst,:match4 :match2 :match3|trim|lcfirst',
			],
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s`-]*) '.$ifWord .' ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2|trim|ucfirst :match3, :match1|lcfirst|trim',
			],
			[
				'rule'=>self::SENTENCE_OPENING.$ifWord.' ([\w\s`-]*), ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match3|trim|ucfirst :match1|lcfirst :match2',
			],
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s-`,]+) (dikarenakan|karena|sebab|supaya|agar) ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2|trim|ucfirst :match3|trim, :match1|lcfirst',
			],
			[
				'rule'=>self::SENTENCE_OPENING.'([\w\s-`]+) '.$timeWord.' ([\w\s-`]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2|trim|ucfirst :match3|trim, :match1|trim|lcfirst',
			],
			[
				'rule'=>\app\components\rules\PassiveRule::rule(),
				'process'=>'func:\app\components\rules\PassiveRule::rewrite',
			],
		];
	}
}