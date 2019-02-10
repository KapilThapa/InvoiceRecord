<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Carbon\Carbon;
use App\Invoice;
use App\InvoicePayment;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // select 
        //     i.bill_no,
        //     i.bill_total,
        //     sum(ip.payment_amount) as total_paid,
        //     i.bill_total - IFNULL(sum(ip.payment_amount), 0) as due
        //         from invoice as i 
        //             left join invoice_payments as ip 
        //                 on i.id = ip.invoice_id 
        //     group by i.bill_no;
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
        $data = $request->all();
        $validator = Validator::make($data, [
            'bill_no' => 'required|unique:invoice,bill_no',
            'delivery_date' => 'required',
            'total' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()],422);
        }

        $data['status_id'] = 1;
        $data['bill_total'] =  $data['total'];

        DB::beginTransaction();

        try {
            $invoice = Invoice::create([
                "bill_no" => $data['bill_no'],
                "customer_name" => $data['customer_name'],
                "contact" => $data['contact'],
                "status_id" => 1,
                "notes" => $data['note'],
                "bill_total" => $data['total'],
                "full_payment_date" => $data['total'] == $data['advance'] ? Carbon::now() : null,
                "delivery_date" => $data['delivery_date']
            ]);
            if ($data['advance']>0) {
                InvoicePayment::create([
                    'invoice_id'=>$invoice->id,
                    'payment_amount'=>$data['advance']
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
        return response()->json([
            'msg' => 'Invoice #'.$data['bill_no'].' saved successfully'
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bill = DB::select("select 
                                i.bill_no,
                                i.bill_total,
                                sum(ip.payment_amount) as total_paid,
                                i.bill_total - IFNULL(sum(ip.payment_amount), 0) as due
                                    from invoice as i 
                                        left join invoice_payments as ip 
                                            on i.id = ip.invoice_id 
                                        where i.bill_no = $id
                                group by i.bill_total,i.bill_no;");
        if (count($bill) > 0) {
            return response()->json($bill,200);
        }else{
            return response()->json([
                'msg' => 'There is no bill recorded with the provided bill number.'
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkInvoiceNo($billno)
    {
        $bill = Invoice::where('bill_no',$billno)->first();
        if(isset($bill)){
            return 0;
        }
        return 1;
    }
}
