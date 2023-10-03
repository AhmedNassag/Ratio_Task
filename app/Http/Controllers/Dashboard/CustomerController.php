<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Sale;
use App\Notifications\CustomerAdded;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Models\Notification as NotificationModel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $data = Customer::
        when($request->company != null,function ($q) use($request){
            return $q->where('company','like','%'.$request->company.'%');
        })
        ->when($request->from_date != null,function ($q) use($request){
            return $q->whereDate('created_at','>=',$request->from_date);
        })
        ->when($request->to_date != null,function ($q) use($request){
            return $q->whereDate('created_at','<=',$request->to_date);
        })
        ->paginate(10);
        $trashed = false;
        return view('dashboard.customer.index')
        ->with([
            'data'      => $data,
            'trashed'   => $trashed,
            'company'   => $request->company,
            'from_date' => $request->from_date,
            'to_date'   => $request->to_date,
        ]);
    }



    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'company'        => 'required|string|max:191|unique:customers,company',
                'contact_person' => 'required|string',
                'email'          => 'required|email|unique:customers,email',
                'phone'          => 'required|numeric|unique:customers,phone',
                'address'        => 'required|string',
                'city'           => 'required|string',
                'state'          => 'required|string',
                'postal_code'    => 'required|numeric',
                'country'        => 'required|string',
            ]);
            if($validator->fails())
            {
                return response()->json([
                    'status'   => false,
                    'messages' => $validator->messages(),
                ]);
            }
            //insert data
            $customer = Customer::create([
                'company'        => $request->company,
                'contact_person' => $request->contact_person,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'city'           => $request->city,
                'state'          => $request->state,
                'postal_code'    => $request->postal_code,
                'country'        => $request->country,
            ]);
            if (!$customer) {
                session()->flash('error');
                return response()->json([
                    'status'   => false,
                    'messages' => 'لقد حدث خطأ ما برجاء المحاولة مجدداً',
                ]);
            }
            //send notification
            // $users = User::/*where('id', '!=', Auth::user()->id)->select('id','name')->*/get();
            // Notification::send($users, new CuctomerAdded($customer->id));

            session()->flash('success');
            return response()->json([
                'status'   => true,
                'messages' => 'تم الحفظ بنجاح',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function edit($id)
    {
        try {
            $data = Customer::find($id);
            if(!$data)
            {
                return response()->json([
                    'status'   => false,
                    'messages' => 'لقد حدث خطأ ما برجاء المحاولة مجدداً',
                ]);
            }
            return response()->json([
                'status' => true,
                'data'   => $data,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'company'  => 'required|max:191|unique:customers,company,'.$request->id.'',
            ]);
            if($validator->fails())
            {
                return response()->json([
                    'status'   => false,
                    'messages' => $validator->messages(),
                ]);
            }
            $customer  = Customer::findOrFail($request->id);
            if (!$customer) {
                session()->flash('error');
                return response()->json([
                    'status'   => false,
                    'messages' => 'لقد حدث خطأ ما برجاء المحاولة مجدداً',
                ]);
            }
            $customer->update([
                'company'  => $request->company,
            ]);
            session()->flash('success');
            return response()->json([
                'status'   => true,
                'messages' => 'تم الحفظ بنجاح',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function destroy(Request $request)
    {
        try {
            $related_table = Sale::where('customer_id', $request->id)->pluck('customer_id');
            if($related_table->count() == 0) { 
                $customer = Customer::findOrFail($request->id);
                if (!$customer) {
                    session()->flash('error');
                    return redirect()->back();
                }
                $customer->delete();
                session()->flash('success');
                return redirect()->back();
            } else {
                session()->flash('canNotDeleted');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function deleteSelected(Request $request)
    {
        try {
            $delete_selected_id = explode(",", $request->delete_selected_id);
            foreach($delete_selected_id as $selected_id) {
                $related_table = Sale::where('customer_id', $selected_id)->pluck('customer_id');
                if($related_table->count() == 0) {
                    $customers = Customer::whereIn('id', $delete_selected_id)->delete();
                    if(!$customers) {
                        session()->flash('error');
                        return redirect()->back();
                    }
                    session()->flash('success');
                    return redirect()->back();
                } else {
                    session()->flash('canNotDeleted');
                    return redirect()->back();
                }
            }
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
        
        $data = Customer::paginate(10);
        $trashed = false;
        return view('dashboard.customer.index')
        ->with([
            'data'      => $data,
            'trashed'   => $trashed,
            'company'   => null,
            'from_date' => null,
            'to_date'   => null,
        ]);
    }
}
