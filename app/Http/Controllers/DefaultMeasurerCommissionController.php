<?php

namespace App\Http\Controllers;

use App\Models\DefaultMeasurerCommission;
// use App\Http\Requests\StoreDefaultMeasurerCommissionRequest;
// use App\Http\Requests\UpdateDefaultMeasurerCommissionRequest;
use App\Models\TemporaryMeasurerCommission;
use Illuminate\Http\Request;

class DefaultMeasurerCommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreDefaultMeasurerCommissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $defaultMeasurerCommission  = DefaultMeasurerCommission::updateOrCreate([
            'measurer_id'  => auth()->id(),
        ],[
            'default_commission' => $request->default_commission
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DefaultMeasurerCommission  $defaultMeasurerCommission
     * @return \Illuminate\Http\Response
     */
    public function show(DefaultMeasurerCommission $defaultMeasurerCommission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DefaultMeasurerCommission  $defaultMeasurerCommission
     * @return \Illuminate\Http\Response
     */
    public function edit(DefaultMeasurerCommission $defaultMeasurerCommission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDefaultMeasurerCommissionRequest  $request
     * @param  \App\Models\DefaultMeasurerCommission  $defaultMeasurerCommission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DefaultMeasurerCommission $defaultMeasurerCommission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DefaultMeasurerCommission  $defaultMeasurerCommission
     * @return \Illuminate\Http\Response
     */
    public function destroy(DefaultMeasurerCommission $defaultMeasurerCommission)
    {
        //
    }

    public function storeTempCommission(Request $request)
    {
        $TemporaryMeasurerCommission  = TemporaryMeasurerCommission::updateOrCreate([
            'consumer_id' => $request->consumer_id,
            'measurer_id' => $request->measurer_id,
        ],[
            'commission' => $request->commission,

        ]);

        return redirect()->back();
    }
}
