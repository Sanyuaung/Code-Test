<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyLikedPostException;
use App\Exceptions\UserLikeOwnPostException;
use App\Http\Requests\PostToggleReactionRequest;
use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function list()
    {
        $posts = Post::select('id', 'title', 'description', 'author_id')
            ->withCount('likes')
            ->with('tags:id,value')
            ->paginate();
        return new PostCollection($posts);
    }

    public function toggleReaction(PostToggleReactionRequest $request)
    {
        $validated = $request->validated();

        try {
            $post = Post::with(['likes' => function (HasMany $query) {
                $query->where('user_id', Auth::id());
            }])
                ->findOrFail($validated['post_id']);

            if (Gate::denies('like-post', $post)) {
                throw new UserLikeOwnPostException('You cannot like your post.');
            }

            $likeExists = $post->likes->isNotEmpty();

            if ($likeExists && !$validated['like']) {
                $post->likes()->where('user_id', Auth::id())->delete();

                return response()->json([
                    'status'  => Response::HTTP_OK,
                    'message' => 'You unliked this post successfully.',
                ]);
            } elseif (!$likeExists && $validated['like']) {
                $post->likes()->create(['user_id' => Auth::id()]);

                return response()->json([
                    'status'  => Response::HTTP_OK,
                    'message' => 'You liked this post successfully.',
                ]);
            }

            if ($likeExists && $validated['like']) {
                throw new UserAlreadyLikedPostException('You already liked this post.');
            }

            throw new \Exception('Invalid operation.');
        } catch (UserLikeOwnPostException | UserAlreadyLikedPostException $e) {
            return response()->json([
                'status'  => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => Response::HTTP_NOT_FOUND,
                'message' => 'Post not found.',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}