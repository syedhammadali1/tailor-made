<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductForum;
use Illuminate\Support\Facades\Auth;

class ProductForumController extends Controller
{
    public function add_forum_user(Request $request)
    {

      // dd($request->seller_id);

      $productforum = new ProductForum;
      $productforum->user_id = Auth::user()->id;
      $productforum->seller_id = $request->seller_id;
      $productforum->product_id = $request->product_id;
      $productforum->question = $request->question;
      $productforum->save();

      flash(translate('Your Question has Been Send Successfully'))->success();

      return redirect()->back();


    }


    public function all_forum_seller()
    {

      $productforum = ProductForum::with('user')->where('seller_id',Auth::user()->id)->orderBy('id','desc')->get();

      // dd($productforum);
   

      return view('seller.product-forum',compact('productforum'));


    }

    public function add_forum_seller(Request $request)
    {

      $productforum = ProductForum::find($request->forum_id);

    
      if (isset($productforum)) {
         $productforum->answer = $request->answer; 
         $productforum->save();
      }

     

      flash(translate('Your Answer has Been Send Successfully'))->success();

      return redirect()->back();



    }


    
}
