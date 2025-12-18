<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like for a user.
     */
    public function toggleLike(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        abort_unless($user->is_active, 404);
        abort_if($user->id === $currentUser->id, 403, 'You cannot like yourself.');

        $existingLike = UserLike::where('user_id', $currentUser->id)
            ->where('liked_user_id', $user->id)
            ->first();

        if ($existingLike) {
            if ($existingLike->type === 'like') {
                // If already liked, remove the like (toggle off)
                $existingLike->delete();
                $isLiked = false;
            } else {
                // If disliked, change to like
                $existingLike->update(['type' => 'like']);
                $isLiked = true;
            }
        } else {
            // Create new like
            UserLike::create([
                'user_id' => $currentUser->id,
                'liked_user_id' => $user->id,
                'type' => 'like',
            ]);
            $isLiked = true;
        }

        // Get updated likes count
        $likesCount = $user->likesReceived()->where('type', 'like')->count();

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $likesCount,
        ]);
    }

    /**
     * Toggle dislike for a user.
     */
    public function toggleDislike(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        abort_unless($user->is_active, 404);
        abort_if($user->id === $currentUser->id, 403, 'You cannot dislike yourself.');

        $existingLike = UserLike::where('user_id', $currentUser->id)
            ->where('liked_user_id', $user->id)
            ->first();

        if ($existingLike) {
            if ($existingLike->type === 'dislike') {
                // If already disliked, remove the dislike (toggle off)
                $existingLike->delete();
                $isDisliked = false;
            } else {
                // If liked, change to dislike
                $existingLike->update(['type' => 'dislike']);
                $isDisliked = true;
            }
        } else {
            // Create new dislike
            UserLike::create([
                'user_id' => $currentUser->id,
                'liked_user_id' => $user->id,
                'type' => 'dislike',
            ]);
            $isDisliked = true;
        }

        // Get updated likes count (dislikes don't affect likes count)
        $likesCount = $user->likesReceived()->where('type', 'like')->count();

        return response()->json([
            'success' => true,
            'is_disliked' => $isDisliked,
            'likes_count' => $likesCount,
        ]);
    }
}
