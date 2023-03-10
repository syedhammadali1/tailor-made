<?php

namespace App\Http\Controllers;

use App\Models\RequestAppointment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Artisan;
use Cache;
use CoreComponentRepository;
use Illuminate\Database\Eloquent\Model;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard(Request $request)
    {
//        CoreComponentRepository::initializeCache();
        $root_categories = Category::where('level', 0)->get();

        $cached_graph_data = Cache::remember('cached_graph_data', 86400, function() use ($root_categories){
            $num_of_sale_data = null;
            $qty_data = null;
            foreach ($root_categories as $key => $category){
                $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                $category_ids[] = $category->id;

                $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
                $qty = 0;
                $sale = 0;
                foreach ($products as $key => $product) {
                    $sale += $product->num_of_sale;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                }
                $qty_data .= $qty.',';
                $num_of_sale_data .= $sale.',';
            }
            $item['num_of_sale_data'] = $num_of_sale_data;
            $item['qty_data'] = $qty_data;

            return $item;
        });

        return view('backend.dashboard', compact('root_categories', 'cached_graph_data'));
    }

    function clearCache(Request $request)
    {
        Artisan::call('cache:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }

    public function approvedByAdmin(Request $request)
    {

        if($request->Approved == 1){

            $user = User::findOrFail($request->user_id);
            $user->is_approved_by_admin = 1;
            $user->update();

            flash('User Approved')->success();

            return redirect()->back();
        }else{
            $user = User::findOrFail($request->user_id);
            $user->is_approved_by_admin = 2;
            $user->update();

            flash('User Rejected')->success();

            return redirect()->back();
        }
        // return response()->json([
        //     'Approved' => false
        // ]);

    }


    public function getAllMeasurments()
    {
        $appointments  = RequestAppointment::with('measurement','request')
        ->where([
            'appointment_status' =>  6
            ])
        ->paginate(10);

        return view('backend.completed_measurments.completed_measurments', compact('appointments'));

    }

    public function allModels()
    {
        $users = User::with('defaultModelCommission')->where('user_type' , '=','model')->get();
       
        return view('backend.model.index', compact('users'));

    }

    
}
