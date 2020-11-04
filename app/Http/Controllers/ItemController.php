<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

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
    public function create()
    {
        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()) {
                $validated = $request->validate([
                    'title' => 'string|max:40',
                    'description' => 'string|max:100',
                    'image' => 'mimes:jpeg,png|max:1014',
                ]);

                $extension = $request->photo->extension();
                $request->image->storeAs('/public', $validated['title'].".".$extension);
                $url = Storage::url($validated['title'].".".$extension);
                $item = Item::create([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'photo' => $url,
                ]);
                Session::flash('success', "Success!");
                return response()->json([
                    "message" => $item->title . " created"
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
                'photo' => $url,
            ]);

            Session::flash('success', "Success!");

            return response()->json([
                "message" => $item->title ." created"
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
    public function update(int $id, Request $request, Item $item)
    {
        if(Item::where('id', $id)->exists()) {
            $item = Item::find($id);


            if ($request->hasFile('photo')) {
                if ($request->file('photo')->isValid()) {
                    $validated = $request->validate([
                        'title' => 'string|max:40',
                        'description' => 'string|max:100',
                        'image' => 'mimes:jpeg,png|max:1014',
                    ]);
    
                    $extension = $request->photo->extension();
                    $request->image->storeAs('/public', $validated['title'].".".$extension);
                    $url = Storage::url($validated['title'].".".$extension);
                    $item->title = $validated['title'];
                    $item->description = $validated['description'];
                    $item->photo = $url;

                    $item->save();

                    Session::flash('success', "Success!");
                    return response()->json([
                        "message" => $item->title . " updated"
                    ], 201);
                } else {
                    return  response()->json([
                        "message" => 'Could not update item :('
                    ], 500);
                }
            } else {
                $validated = $request->validate([
                    'title' => 'string|max:40',
                    'description' => 'string|max:100',
                ]);

                $item->title = $validated['title'];
                $item->description = $validated['description'];
                $item->save();
                
                Session::flash('success', "Success!");
                
                return response()->json([
                    "message" => $item->title . " updated"
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
