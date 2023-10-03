<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Sale;
use App\Models\Sale_Detail;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Notifications\SaleAdded;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Models\Notification as NotificationModel;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales_details = Sale_Detail::where('sale_id',null)->get();
        foreach($sales_details as $sale_detail)
        {
            $sale_detail->delete();
        }

        $data = Sale::
        when($request->customer_id != null,function ($q) use($request){
            return $q->where('customer_id','like','%'.$request->customer_id.'%');
        })
        ->when($request->from_date != null,function ($q) use($request){
            return $q->whereDate('date','>=',$request->from_date);
        })
        ->when($request->to_date != null,function ($q) use($request){
            return $q->whereDate('date','<=',$request->to_date);
        })
        ->paginate(10);

        $customers = Customer::get(['id','company']);
        $trashed = false;
        return view('dashboard.sale.index')
        ->with([
            'data'        => $data,
            'customers'   => $customers,
            'trashed'     => $trashed,
            'customer_id' => $request->customer_id,
            'from_date'   => $request->from_date,
            'to_date'     => $request->to_date,
        ]);
    }



    public function create()
    {
        $sales_details = Sale_Detail::where('sale_id',null)->get();
        foreach($sales_details as $sale_detail)
        {
            $sale_detail->delete();
        }
        $customers = Customer::get(['id','company']);
        $products  = Product::get(['id','name','price']);
        return view('dashboard.sale.create')
        ->with([
            'customers' => $customers,
            'products'  => $products,
        ]);
    }



    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'date'        => 'required',
                'customer_id' => 'required|integer|exists:customers,id',
                'notes'       => 'nullable|string',
            ]);
            if($validator->fails())
            {
                session()->flash('error');
                return redirect()->back();
            }
            //insert data
            $sale = Sale::create([
                'date'        => $request->date,
                'discount'    => $request->discount,
                'tax'         => $request->tax,
                'grandTotal'  => $request->grand_total,
                'notes'       => $request->notes,
                'customer_id' => $request->customer_id,
                'notes'       => $request->notes,
            ]);
            $sales_details = Sale_Detail::where('sale_id',null)->get();
            foreach($sales_details as $sale_detail)
            {
                $sale_detail->update(['sale_id' => $sale->id]);
            }
            if (!$sale) {
                session()->flash('error');
                return redirect()->back();
            }
            //send notification
            $users = User::/*where('id', '!=', Auth::user()->id)->select('id','name')->*/get();
            Notification::send($users, new SaleAdded($sale->id));

            session()->flash('success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function destroy(Request $request)
    {
        try {
            $sale = Sale::findOrFail($request->id);
            if (!$sale) {
                session()->flash('error');
                return redirect()->back();
            }
            $sale->delete();
            $sales_details = Sale_Detail::where('sale_id',$request->id)->get();
            foreach($sales_details as $sale_detail)
            {
                $sale_detail->delete();
            }
            session()->flash('success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function showNotification($route_id,$notification_id)
    {
        $notification = NotificationModel::findOrFail($notification_id);
        $notification->update([
            'read_at' => now(),
        ]);
        
        $sales_details = Sale_Detail::where('sale_id',null)->get();
        foreach($sales_details as $sale_detail)
        {
            $sale_detail->delete();
        }
        $data      = Sale::paginate(10);
        $customers = Customer::get(['id','company']);
        $trashed   = false;
        return view('dashboard.sale.index')
        ->with([
            'data'        => $data,
            'customers'   => $customers,
            'trashed'     => $trashed,
            'customer_id' => null,
            'from_date'   => null,
            'to_date'     => null,
        ]);
    }



    public function fetchDetails()
    {
        $grand_total = 0;
        $sale_details = [];
        $sales_details = Sale_Detail::where('sale_id',null)->get();
        foreach($sales_details as $sale_detail)
        {
            $grand_total += ($sale_detail->unit_price) * ($sale_detail->quantity);
            
            $item['id']         = $sale_detail->id;
            $item['quantity']   = $sale_detail->quantity;
            $item['unit_price'] = $sale_detail->unit_price;
            $item['product']    = $sale_detail->product->name;
            $item['sub_total']  = ($sale_detail->unit_price) * ($sale_detail->quantity);
            $sale_details[] = $item;
        }
        return response()->json([
            'sale_details' => $sale_details,
            'grand_total'  => $grand_total,
        ]);
    }



    public function fetchLastDetails()
    {
        $grand_total = 0;
        $sale_details = [];
        $sales_details = Sale_Detail::where('sale_id',null)->OrderBy('id','Desc')->get();
        foreach($sales_details as $sale_detail)
        {
            $grand_total += ($sale_detail->unit_price) * ($sale_detail->quantity);

            $item['id']         = $sale_detail->id;
            $item['product']    = $sale_detail->product->name;
            $item['quantity']   = $sale_detail->quantity;
            $item['unit_price'] = $sale_detail->unit_price;
            $item['sub_total']  = ($sale_detail->unit_price) * ($sale_detail->quantity);
            $sale_details[] = $item;
        }
        return response()->json([
            'sale_details' => $sale_details[0],
            'grand_total'  => $grand_total,
        ]);
    }



    public function storeDetails(Request $request)
    {
        try {
            //insert data
            $sale_detail = Sale_Detail::create([
                'quantity'    => $request->quantity,
                'unit_price'  => $request->unit_price,
                'product_id'  => $request->product_id,
            ]);
            if (!$sale_detail) {
                return response()->json([
                    'status'   => false,
                    'messages' => 'لقد حدث خطأ ما برجاء المحاولة مجدداً',
                ]);
            }
            return response()->json([
                'status'   => true,
                'messages' => 'تم الحفظ بنجاح',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function destroyDetails($id)
    {
        $sale_detail = Sale_Detail::find($id);
        $sale_detail->delete();
        return response()->json([
            'status'   => true,
            'messages' => 'تم الحفظ بنجاح',
        ]);
    }
}
