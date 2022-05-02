<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Components\Plugin;
use App\Repositories\SwearWordsRepository;


class SwearWordController extends Controller {

	public function swearWords (Request $req) {

        $swear_words = SwearWordsRepository::allSwearWords();

        return Plugin::view('ContentModerationPlugin/swear_words', [
            'swear_words' => $swear_words
        ]);
    }


    public function addSwearWord (Request $req) {
        $word = $req->word;
        $match_all_pattern = $req->match_all_pattern == 'true' ? '1': '0';

        if (SwearWordsRepository::findSwearWordByWord($word)) {
            return response()->json(['status' => "error","message" => trans('ContentModerationPlugin.word_exists')]);
        }

        $success = SwearWordsRepository::addNewSwearWord($word, $match_all_pattern);

        if ($success) {
            return response()->json(['status' => "success","message" => trans('ContentModerationPlugin.word_deleted_msg'), 'word_object' => $success]);
        } else {
            return response()->json(['status' => "error","message" => trans('ContentModerationPlugin.failed_word_deleted_msg')]);
        }
    }


    public function setMatchAllPattern (Request $req) {
        $word_id = $req->word_id;
        $match_all_pattern = $req->match_all_pattern == 'true' ? 1 : 0 ;
        $success = SwearWordsRepository::setMatchAllPattern($word_id, $match_all_pattern);
        if ($success) {
            return response()->json(['status' => "success","message" => trans('ContentModerationPlugin.match_pattern_changed_msg'), 'match_all_pattern' => $success->match_all_pattern]);
        } else {
            return response()->json(['status' => "error","message" => trans('ContentModerationPlugin.failed_match_pattern_changed_msg')]);
        }

    }



    public function swearWordDelete (Request $req) {

        $word_id = $req->word_id;

        $success = SwearWordsRepository::deleteSwearWordByID($word_id);
        if ($success) {
            return response()->json(['status' => "success","message" => trans('ContentModerationPlugin.word_deleted_msg')]);
        } else {
            return response()->json(['status' => "error","message" => trans('ContentModerationPlugin.failed_word_deleted_msg')]);
        }

    }


}
