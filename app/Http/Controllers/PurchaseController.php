<?php

namespace App\Http\Controllers;

use App\ChequeDetail;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\PaymentDetail;
use App\Purchase;
use App\Service;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use JavaScript;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $purchases = Purchase::indexQuery(
            $request->sort_field,
            $request->sort_direction,
            $request->product_id,
            $request->drp_start,
            $request->drp_end,
            $request->input('search')
        )->paginate(100);

        $purchaseTotal = Purchase::indexQuery(
            null,
            null,
            $request->product_id,
            $request->drp_start,
            $request->drp_end,
            $request->input('search')
        )->get();

        // dd([
        //     "purchases" => $purchases,
        //     "purchaseTotal" => $purchaseTotal
        // ]);
        $count = $purchaseTotal->count();

        if (!$request->has('drp_start') or !$request->has('drp_end')) {
            $drp_placeholder = 'Select daterange filter';
        } else {
            $drp_placeholder = $request->drp_start . ' - ' . $request->drp_end;
        }

        $request->flash();

        return view('purchases.index', compact('purchases', 'count', 'drp_placeholder'));
    }
    public function belt(Request $request)
    {
        // dd($request->all());
        $purchases_query = Purchase::indexQuery($request->sort_field, $request->sort_direction, [13, 14, 15, 16, 18], $request->drp_start, $request->drp_end, $request->product_name)->search('"' . $request->input('search') . '"');
        $purchases = $purchases_query->paginate(100);
        $purchaseTotal = $purchases_query->get();
        // dd([
        //     "purchases" => $purchases,
        //     "purchaseTotal" => $purchaseTotal
        // ]);
        $count = $purchaseTotal->count();

        if (!$request->has('drp_start') or !$request->has('drp_end')) {
            $drp_placeholder = 'Select daterange filter';
        } else {
            $drp_placeholder = $request->drp_start . ' - ' . $request->drp_end;
        }

        $request->flash();

        return view('purchases.index', compact('purchases', 'count', 'drp_placeholder'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        JavaScript::put([
            'taxes' => \Utilities::getSetting('taxes'),
            'gymieToday' => Carbon::today()->format('Y-m-d'),
            'servicesCount' => Service::count(),
        ]);
        list($invoice_number_mode, $invoiceCounter, $invoice_number) = $this->generateInvoiceNumber();

        return view('purchases.create', compact('invoice_number', 'invoiceCounter', 'invoice_number_mode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            //Helper function to set Payment status
            $invoice_total = $request->admission_amount + $request->purchase_amount + $request->taxes_amount - $request->discount_amount;
            $paymentStatus = \constPaymentStatus::Unpaid;
            $pending = $invoice_total - $request->payment_amount;

            if ($request->mode == 1) {
                if ($request->payment_amount == $invoice_total) {
                    $paymentStatus = \constPaymentStatus::Paid;
                } elseif ($request->payment_amount > 0 && $request->payment_amount < $invoice_total) {
                    $paymentStatus = \constPaymentStatus::Partial;
                } elseif ($request->payment_amount == 0) {
                    $paymentStatus = \constPaymentStatus::Unpaid;
                } else {
                    $paymentStatus = \constPaymentStatus::Overpaid;
                }
            }
            // Storing Invoice
            $invoiceData = [
                'invoice_number' => $request->invoice_number,
                'member_id' => $request->member_id,
                'total' => $invoice_total,
                'status' => $paymentStatus,
                'pending_amount' => $pending,
                'discount_amount' => $request->discount_amount,
                'discount_percent' => $request->discount_percent,
                'discount_note' => $request->discount_note,
                'tax' => $request->taxes_amount,
                'additional_fees' => $request->additional_fees,
                'note' => ' ',
            ];

            $invoice = new Invoice($invoiceData);
            $invoice->createdBy()->associate(Auth::user());
            $invoice->updatedBy()->associate(Auth::user());
            $invoice->save();

            // Storing purchases
            foreach ($request->product as $product) {
                $purchases = [
                    'member_id' => $request->member_id,
                    'invoice_id' => $invoice->id,
                    'product_id' => $product['id'],
                ];

                $purchases = new Purchase($purchases);
                $purchases->createdBy()->associate(Auth::user());
                $purchases->updatedBy()->associate(Auth::user());
                $purchases->save();
            }

            //Payment Details
            $paymentData = [
                'invoice_id' => $invoice->id,
                'payment_amount' => $request->payment_amount,
                'mode' => $request->mode,
                'note' => ' ',
            ];

            $payment_details = new PaymentDetail($paymentData);
            $payment_details->createdBy()->associate(Auth::user());
            $payment_details->updatedBy()->associate(Auth::user());
            $payment_details->save();

            if ($request->mode == 0) {
                // Store Cheque Details
                $chequeData = [
                    'payment_id' => $payment_details->id,
                    'number' => $request->number,
                    'date' => $request->date,
                    'status' => \constChequeStatus::Recieved,
                ];

                $cheque_details = new ChequeDetail($chequeData);
                $cheque_details->createdBy()->associate(Auth::user());
                $cheque_details->updatedBy()->associate(Auth::user());
                $cheque_details->save();
            }


            //Updating Numbering Counters
            Setting::where('key', '=', 'invoice_last_number')->update(['value' => $request->invoiceCounter]);

            DB::commit();
            flash()->success('purchases was successfully created');

            return redirect(action('PurchaseController@index'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Error while creating the Purchases');

            return redirect(action('PurchaseController@index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::findOrFail($id);
        dd($purchase);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('purchases.edit', compact('purchase'));
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
        $purchase = Purchase::findOrFail($id);

        $purchase->update($request->all());
        $purchase->updatedBy()->associate(Auth::user());
        $purchase->save();
        flash()->success('Subscription details were successfully updated');

        return redirect('purchases/all');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change($id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $purchase = Purchase::findOrFail($id);
            $invoice = Invoice::where('id', $purchase->invoice_id)->first();
            $payment_details = PaymentDetail::where('invoice_id', $invoice->id)->get();

            foreach ($payment_details as $payment_detail) {
                ChequeDetail::where('payment_id', $payment_detail->id)->delete();
                $payment_detail->delete();
            }

            $purchase->delete();
            $invoice->delete();

            DB::commit();

            return back();
        } catch (\Exception $e) {
            DB::rollback();

            return back();
        }
    }

    private function generateInvoiceNumber()
    {
        //Get Numbering mode
        $invoiceNumberMode = \Utilities::getSetting('invoice_number_mode');

        //Generating Invoice number
        if ($invoiceNumberMode == \constNumberingMode::Auto) {
            $invoiceCounter = \Utilities::getSetting('invoice_last_number') + 1;
            $invoiceNumber = \Utilities::getSetting('invoice_prefix') . $invoiceCounter;
        } else {
            $invoiceNumber = '';
            $invoiceCounter = '';
        }

        return [$invoiceNumberMode, $invoiceCounter, $invoiceNumber];
    }
}
