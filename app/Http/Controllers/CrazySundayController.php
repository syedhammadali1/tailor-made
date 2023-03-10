<?php

namespace App\Http\Controllers;

use App\Models\CrazySunday;
use Illuminate\Http\Request;

class CrazySundayController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $crazy_sundays = CrazySunday::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $crazy_sundays = $crazy_sundays->where('title', 'like', '%'.$sort_search.'%');
        }
        $crazy_sundays = $crazy_sundays->paginate(15);
        return view('backend.marketing.crazy_sunday.index', compact('crazy_sundays', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrazySunday  $crazySunday
     * @return \Illuminate\Http\Response
     */
    public function show(CrazySunday $crazySunday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CrazySunday  $crazySunday
     * @return \Illuminate\Http\Response
     */
    public function edit(CrazySunday $crazySunday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CrazySunday  $crazySunday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrazySunday $crazySunday)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrazySunday  $crazySunday
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrazySunday $crazySunday)
    {
        //
    }


    /**
     * @param Request $request
     * @return int
     */
    public function update_status(Request $request)
    {
        $item = CrazySunday::findOrFail($request->id);
        $item->status = $request->status;
        if($item->save()){
            flash(translate('Crazy Sunday status updated successfully'))->success();
            return 1;
        }
        return 0;
    }


}
