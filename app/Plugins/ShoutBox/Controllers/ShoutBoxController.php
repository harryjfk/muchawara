<?php

namespace App\Plugins\ShoutBox\Controllers;

use App\Plugins\ShoutBox\Repositories\ShoutBoxRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Components\Theme;

class ShoutBoxController extends Controller
{
     

    public function __construct(ShoutBoxRepository $shoutBoxRepo)
    {
        $this->shoutBoxRepo = $shoutBoxRepo;
    }


    public function shout()
    {
        return Theme::view('plugin.ShoutBox.shout', [
            'feed_credits_required' => $this->shoutBoxRepo->feedCreditRequired(),
            'feed_credits' => $this->shoutBoxRepo->feedCredits() 
        ]);
    }


    public function addFeed(Request $request)
    {
        $authUser = Auth::user();
        $response = $this->shoutBoxRepo->addFeed($authUser, $request->text);
        return response()->json($response);
    }



    public function getFeeds()
    {
        $response = $this->shoutBoxRepo->getFeeds(Auth::user());
        return response()->json($response);
    }


    public function doLike(Request $request)
    {
        $response = $this->shoutBoxRepo->likeFeed(Auth::user(), $request->feed_id);
        return response()->json($response);
    }



    public function doDislike(Request $request)
    {
        $response = $this->shoutBoxRepo->disLikeFeed(Auth::user(), $request->feed_id);
        return response()->json($response);
    }


    public function deleteFeed(Request $request)
    {
        $response = $this->shoutBoxRepo->deleteFeed(Auth::user(), $request->feed_id);
        return response()->json($response);
    }
    


    public function likes(Request $request)
    {
        $response = $this->shoutBoxRepo->feedLikedOrDislikedUsers($request->feed_id, Auth::user()->id, true, 100);
        return response()->json($response);
    }


    public function dislikes(Request $request)
    {
       $response = $this->shoutBoxRepo->feedLikedOrDislikedUsers($request->feed_id, Auth::user()->id, false, 100);
        return response()->json($response);
    }



    public function showFeed($feed_id)
    {
        return Theme::view('plugin.ShoutBox.single_shout_feed', ['feed_id' => $feed_id]); 
    }



    public function getFeed($feed_id)
    {
        $response = $this->shoutBoxRepo->getFeedByID($feed_id, Auth::user());
        return response()->json($response);
    }


}