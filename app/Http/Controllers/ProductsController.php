<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  //Validate submitted data
  $request->validate([
    //Post Validation Rules
   // 'title' => 'required',
    //Media Validation Rules
    'media' => 'array|max:10', //max:10 means maximum array size of 10, so max 10 uploads
    "media.*" => "required|string|max:255|min:1", //filenames must have a length between 1 and 255
    'media_original_name' => 'array|max:10',
    "media_original_name.*" => "required|required_with:media.*|string|max:255|min:1", //required_with here /should/ make it validate that both media and media_original_name arrays be the same length.  I think.
]);
$post = new Product;
$data = $request->all(); //The request also contains media attachments, so only get the data required for the post
$post->fill($data);
$post->save();

//Handle media
//Items in media and media_original_name arrays from the request must be in the correct order in each array so the media and it's original name can be matched together by their array index
foreach ($request->input('media', []) as $index => $file) {
    //Media Library should now attach file previously uploaded by Dropzone (prior to the post form being submitted) to the post
    $post->addMedia(storage_path("app/" . $file))
        ->usingName($request->input('media_original_name', [])[$index]) //get the media original name at the same index as the media item
        ->toMediaCollection();
}

return redirect()->route('products.index')->with('success', 'Post created successfully.');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load('media'); //Make sure media is included
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product->load('media'); //Make sure media is included
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
          //Validate submitted data
          $request->validate([
            //Post Validation Rules
            //'title' => 'required',
            //Media Validation Rules
            'media' => 'array|max:10', //max:10 means maximum array size of 10, so max 10 uploads
            "media.*" => "required|string|max:255|min:1", //filenames must have a length between 1 and 255
            'media_original_name' => 'array|max:10',
            "media_original_name.*" => "required|required_with:media.*|string|max:255|min:1", //required_with here /should/ make it validate that both media and media_original_name arrays be the same length.  I think.
        ]);
        $data = $request->all(); //The request also contains media attachments, so only get the data required for the post
        $product->fill($data);
        $product->save();

        //Handle media
        //Items in media and media_original_name arrays from the request must be in the correct order in each array so the media and it's original name can be matched together by their array index

        //Load existing media for post
        $product->load('media');

        //Delete existing media which is not included in the updated post
        if (count($product->media) > 0) {
            foreach ($product->media as $media) {
                if (!in_array($media->file_name, $request->input('media', []))) {
                    $media->delete();
                }
            }
        }

        //Attach only media which isint in the existing media
        $media = $product->media->pluck('file_name')->toArray();
        foreach ($request->input('media', []) as $index => $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                //Media Library should now attach file previously uploaded by Dropzone (prior to the post form being submitted) to the post
                $product->addMedia(storage_path("app/" . $file))
                    ->usingName($request->input('media_original_name', [])[$index]) //Get the media original name at the same index as the media item
                    ->toMediaCollection();
            }
        }

        return redirect()->route('products.index')->with('success', 'Post updated successfully');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
