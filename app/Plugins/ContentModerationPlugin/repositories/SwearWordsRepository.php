<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Components\Plugin;
use App\Models\SwearWord;

class SwearWordsRepository {


    public static function writeArrayToFile($arry, $file) {
        try {
            $string = var_export($arry, true);
            $string = "<?php return \n".$string . ";"; 
            file_put_contents($file, $string, LOCK_EX);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public static function addNewSwearWord($word, $match_all_pattern) {
        $new_word = new SwearWord;
        $new_word->word = $word;
        $new_word->match_all_pattern = $match_all_pattern;
        $new_word->save();

        if (self::writeFileFromDB()) return $new_word;
        else false;
    }

    public static function findSwearWordByWord ($word) {
        return SwearWord::where('word', $word)->first();
    }


    public static function allSwearWords() {
        return SwearWord::all();
    }

    public static function saveSwearWord($word) {

    }

    public static function deleteSwearWordByID($word_id) {
        $swear_word = SwearWord::find($word_id);
        if ($swear_word) $swear_word->forceDelete();

        self::writeFileFromDB();
        
        return true;
    }

    public static function allSwearWordRawDB () {
        return DB::select('SELECT word, match_all_pattern FROM swear_words');
    }

    
    public static function writeFileFromDB () {
        
        $swear_words_config = include Plugin::path('ContentModerationPlugin/repositories/bad_word_filter_config.php');
        $swear_words_file  = $swear_words_config['source_file'];

        $new_array = [
            "all" => []
        ];

        $all_words = self::allSwearWordRawDB();
        foreach ($all_words as $word) {
            if ($word->match_all_pattern == 1)
                array_push($new_array['all'] , [$word->word, 'all_pattern']);
            else 
                array_push($new_array['all'] , $word->word);
        }

        self::writeArrayToFile($new_array, $swear_words_file);
        return true;
    }

    public static function setMatchAllPattern($word_id, $match_all_pattern = 0){
        $swear_word = SwearWord::find($word_id);
        if ($swear_word) {
            $swear_word->match_all_pattern = $match_all_pattern;
            $swear_word->save();
            self::writeFileFromDB();
            return $swear_word;
        }
        return false;
    }

}