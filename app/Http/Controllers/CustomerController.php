<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerEmail;
use App\Models\CustomerPhoneNo;
use Illuminate\Http\Request;
use Helper;

class CustomerController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Customer Permissions
        $this->middleware('RolePermissionCheck:customers.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:customers.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:customers.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:customers.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Customers', 'View all customers list');

        $customers = Customer::get();
        return view('customers.index', ['customers' => $customers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Customers', 'Open create customer form');

        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'company_name' => 'string|max:255',
            'country_name' => 'string|max:255',
            'country_code' => 'string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $customer = new Customer;
        $customer->customer_name = $request->customer_name;
        $customer->company_name = $request->company_name;
        $customer->country_name = $request->country_name;
        $customer->country_code = $request->country_code;
        $customer->save();

        $customerId = $customer->id;

        if(!empty($request->emails)) {
            $emailIds = $request->emails;
            foreach($emailIds as $email) {
                if(empty($email)) {
                    continue;
                }

                $customerEmail = new CustomerEmail;
                $customerEmail->customer_id = $customerId;
                $customerEmail->email_id = $email;
                $customerEmail->save();
            }
        }

        if (!empty($request->phoneNos)) {
            $phoneNos = $request->phoneNos;
            foreach ($phoneNos as $phoneNo) {
                if (empty($phoneNo)) {
                    continue;
                }

                $customerPhoneNo = new CustomerPhoneNo;
                $customerPhoneNo->customer_id = $customerId;
                $customerPhoneNo->phone_number = $phoneNo;
                $customerPhoneNo->save();
            }
        }

        Helper::logSystemActivity('Customers', 'Added customer successfully');

        // If Ajax Request
        if ($request->ajax()) {
            return $customer;
        }

        // Back to index with success
        return redirect()->route('customers.index')->with('custom_success', 'Customer has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Customers', 'View customer details id: ' . $id);

        $customer = Customer::with(['emailIds', 'phoneNos'])->find($id);
        return view('customers.show', ['customer' => $customer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Customers', 'Edit customer form opened id: ' . $id);

        $customer = Customer::find($id);
        return view('customers.edit', ['customer' => $customer]);
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
        // Validations
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'company_name' => 'string|max:255',
            'country_name' => 'string|max:255',
            'country_code' => 'string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $customer = Customer::findOrFail($id);

        // Update the item
        $customer->customer_name = $request->customer_name;
        $customer->company_name = $request->company_name;
        $customer->country_name = $request->country_name;
        $customer->country_code = $request->country_code;
        $customer->save();

        $customerId = $customer->id;

        if (!empty($request->emails)) {
            $emailIds = $request->emails;

            // Delete Old Email Ids
            CustomerEmail::where('customer_id', $id)->delete();

            foreach ($emailIds as $email) {
                if (empty($email)) {
                    continue;
                }

                $customerEmail = new CustomerEmail;
                $customerEmail->customer_id = $customerId;
                $customerEmail->email_id = $email;
                $customerEmail->save();
            }
        }

        if (!empty($request->phoneNos)) {
            $phoneNos = $request->phoneNos;

            // Delete Old Phone Nos
            CustomerPhoneNo::where('customer_id', $id)->delete();

            foreach ($phoneNos as $phoneNo) {
                if (empty($phoneNo)) {
                    continue;
                }

                $customerPhoneNo = new CustomerPhoneNo;
                $customerPhoneNo->customer_id = $customerId;
                $customerPhoneNo->phone_number = $phoneNo;
                $customerPhoneNo->save();
            }
        }

        Helper::logSystemActivity('Customers', 'Edit customer successfull id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Customer has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the customer by $id
        $customer = Customer::findOrFail($id);

        // Delete Childs
        CustomerEmail::where('customer_id', $id)->delete();
        CustomerPhoneNo::where('customer_id', $id)->delete();

        // delete Customer
        $status = $customer->delete();

        if ($status) {
            Helper::logSystemActivity('Customers', 'Delete customer successfull id: ' . $id);

            // If success
            return back()->with('custom_success', 'Customer has been deleted');
        } else {
            Helper::logSystemActivity('Customers', 'Delete customer failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Customer was not deleted. Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomerEmail($id)
    {
        // Find the customer email by $id
        $email = CustomerEmail::findOrFail($id);

        // delete Customer
        $status = $email->delete();

        if ($status) {
            Helper::logSystemActivity('Customers', 'Delete customer email successfull id: ' . $id);
            return true;
        } else {
            Helper::logSystemActivity('Customers', 'Delete customer email failed id: ' . $id);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomerPhone($id)
    {
        // Find the customer email by $id
        $phoneNo = CustomerPhoneNo::findOrFail($id);

        // delete Customer
        $status = $phoneNo->delete();

        if ($status) {
            Helper::logSystemActivity('Customers', 'Delete customer phone number successfull id: ' . $id);
            return true;
        } else {
            Helper::logSystemActivity('Customers', 'Delete customer phone number failed id: ' . $id);
            return false;
        }
    }

    /**
     * Ajax Search the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxSearch(Request $request)
    {
        $search = $request->q;

        if(empty($search)) {
            return;
        }

        // Find the customers
        $customers = Customer::where('customer_name', 'like', '%'. $search . '%')
            ->orWhere('company_name', 'like', '%' . $search . '%')
            ->orWhere('country_name', 'like', '%' . $search . '%')
            ->orWhere('country_code', 'like', '%' . $search . '%')
            ->limit(100)->get();

        $response = [];
        foreach ($customers as $customer) {
            $response[] = array(
                "id" => $customer->id,
                "text" => $customer->customer_name. " / " . $customer->country_code
            );
        }

        return $response;
    }
}
