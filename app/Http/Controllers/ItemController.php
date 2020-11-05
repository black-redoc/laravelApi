<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;
use Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allItems()
    {
        $items = Item::get()->toJson(JSON_PRETTY_PRINT);
        return response($items, 200);
    }

    /**
     * Display a unique resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function itemById(int $id)
    {
        if (Item::where('id', $id)->exists()) {
            $item = Item::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($item, 200);
        } else {
            return response()->json([
              "message" => "Item not found"
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()) {
                $validated = $request->validate([
                    'title' => 'string|max:40',
                    'description' => 'string|max:100',
                    'photo' => 'mimes:jpeg,png|max:1014',
                ]);

                $extension = $request->photo->extension();
                $request->photo->storeAs('/public', $validated['title'].".".$extension);
                $url = Storage::url($validated['title'].".".$extension);
                $item = Item::create([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'photo' => $url,
                ]);
                Session::flash('success', "Success!");
                return response()->json([
                    "item" => $item
                ], 201);
            } else {
                return  response()->json([
                    "message" => 'Could not create item :('
                ], 500);
            }
        } else {
            $validated = $request->validate([
                'title' => 'string|max:40',
                'description' => 'string|max:100',
            ]);

            $item = Item::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            Session::flash('success', "Success!");

            return response()->json([
                "item" => $item
            ], 201);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if(Item::where('id', $id)->exists()) {
            $item = Item::find($id);
            /*
             * $request is no returning the json data?
             * fix it!
             */
            if ($request->hasFile('photo')) {
                if ($request->file('photo')->isValid()) {
                    $validated = $request->validated();
    
                    $extension = $request->photo->extension();
                    $request->photo->storeAs('/public', $validated['title'].".".$extension);
                    $url = Storage::url($validated['title'].".".$extension);

                    $item = Item::where('id', $id)->update([
                        'title' => $validated['title'],
                        'description' => $validated['description'],
                        'photo' => $url
                    ]);

                    Session::flash('success', "Success!");
                    return response()->json([
                        "item" => $item
                    ], 201);
                } else {
                    return  response()->json([
                        "message" => 'Could not update item :('
                    ], 500);
                }
            } else {
                $validator = Validator::make($request->all(), [
                    'title' => 'string|max:40',
                    'description' => 'string|max:100',
                ]);

                if($validator->fails()) abort(400);

                $valodated = $request->validate([
                    'title' => 'string|max:40',
                    'description' => 'string|max:100',
                ]);

                $item = Item::where('id', $id)->update([
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                ]);

                Session::flash('success', "Success!");
                
                return response()->json([
                    "item" => $item
                ], 201);
            }
        } else {
            return response()->json([
                "message" => "Item not found"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroyById(int $id)
    {
        if(Item::where('id', $id)->exists()) {
            $item = Item::find($id);
            $item->delete();
    
            return response()->json([
              "message" => "record deleted"
            ], 202);
          } else {
            return response()->json([
              "message" => "Item not found"
            ], 404);
          }
    }
}
