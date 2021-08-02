<?php


namespace App\Http\Services\Api;


use App\Models\Article;
use App\Models\Author;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthorService
{
    public function fileHandle(Request $request, Author $author)
    {
        if (!$request->test){
            $image = $request->file('image');
            $newName = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(storage_path('app\public\images\author-images'), $newName);
            $imageModel = new Image();
            $imageModel->path = '\images\author-images\\' . $newName;
//            $imageModel->addMedia($request->file('image'))->toMediaCollection(storage_path('images/author-images'));
            $author->save();
            $author->avatar()->save($imageModel);

        }

    }

    public function createAuthor(Request $request)
    {
        $author = Author::make([
            'name' => $request->name,
            'surname' => $request->surname,
            'biography' => $request->biography,
            'user_id' => Auth::id()
        ]);
        $this->fileHandle($request, $author);
    }

    public function updateAuthor(Request $request)
    {
        $author = Author::where('user_id', Auth::id())->first();
        $author->update(
            [
                'name' => $request->name,
                'surname' => $request->surname,
                'biography' => $request->biography
            ]
        );
        if ($request->image) {
            $this->fileHandle($request, $author);
        }
    }

    public function validateAuthor(Request $request)
    {
        return Validator::make($request->all(), self::authorArray());
    }

    public function validateAuthorUpdate(Request $request)
    {
        $array = self::authorArray();
        unset($array['user_id']);
        return Validator::make($request->all(), $array);
    }

    public static function authorArray():array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'surname' => ['required', 'string', 'min:3'],
            'biography' => ['required', 'string', 'min:50'],
            'user_id' => ['unique:authors'],
            'image' => ['image','mimes:jpeg, jpg, png','max:2048']
        ];
    }
}
