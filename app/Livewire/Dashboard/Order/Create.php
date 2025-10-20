<?php

namespace App\Livewire\Dashboard\Order;

use App\Models\Admin;
use App\Models\Agent;
use App\Models\City;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderType;
use App\Models\SubDistrict;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Events\NewOrderCreated;

class Create extends Component
{
    public $agent_id;
    public $type_id;
    public $selectedCity;
    public $selectedDistrict;
    public $selectedSubdistrict;
    public $amount;
    public $currency_id;
    public $custom_id;
    public $url;
    public $customer_name;
    public $customer_lastname;
    public $customer_phone;
    public $comment;

    public $agent;
    public $cities = [];
    public $districts = [];
    public $subdistricts = [];

    protected $agentCityIds = [];
    protected $agentDistrictIds = [];
    protected $agentSubdistrictIds = [];

    protected $listeners = ['openOrderModal' => 'resetForm'];

    public function updatedAgentId($admin_id)
    {
        if (!$admin_id) {
            $this->resetAgentDependent();
            return;
        }

        $agent = Agent::where('admin_id', $admin_id)->first();

        if (!$agent) {
            $this->resetAgentDependent();
            return;
        }

        $this->agent = $agent;

        $this->agentCityIds = $agent->cities ?? [];
        $this->agentDistrictIds = $agent->districts ?? [];
        $this->agentSubdistrictIds = $agent->subdistricts ?? [];

        $this->cities = City::whereIn('id', $this->agentCityIds)
            ->orderBy('name')
            ->get();

        $this->districts = [];
        $this->subdistricts = [];
        $this->selectedCity = null;
        $this->selectedDistrict = null;
        $this->selectedSubdistrict = null;
    }

    public function updatedSelectedCity($cityId)
    {
        if (!$cityId) {
            $this->districts = [];
            $this->selectedDistrict = null;
            $this->subdistricts = [];
            $this->selectedSubdistrict = null;
            return;
        }

        $this->districts = District::whereIn('id', $this->agent->districts)
            ->where('city_id', $cityId)
            ->orderBy('name')
            ->get();

        $this->selectedDistrict = null;
        $this->subdistricts = [];
        $this->selectedSubdistrict = null;
    }

    public function updatedSelectedDistrict($districtId)
    {
        if (!$districtId) {
            $this->subdistricts = [];
            $this->selectedSubdistrict = null;
            return;
        }

        $this->subdistricts = Subdistrict::whereIn('id', $this->agent->subdistricts)
            ->where('district_id', $districtId)
            ->orderBy('name')
            ->get();

        $this->selectedSubdistrict = null;
    }

    /**
     * Reset only agent-dependent fields
     */
    protected function resetAgentDependent()
    {
        $this->agent = null;
        $this->agentCityIds = [];
        $this->agentDistrictIds = [];
        $this->agentSubdistrictIds = [];
        $this->cities = [];
        $this->districts = [];
        $this->subdistricts = [];
        $this->selectedCity = null;
        $this->selectedDistrict = null;
        $this->selectedSubdistrict = null;
    }

    public function resetForm()
    {
        $this->reset([
            'agent_id', 'agent',
            'cities', 'selectedCity',
            'districts', 'selectedDistrict',
            'subdistricts', 'selectedSubdistrict',
        ]);
        $this->agentCityIds = [];
        $this->agentDistrictIds = [];
        $this->agentSubdistrictIds = [];
    }

    public function save()
    {
        $this->validate([
            'agent_id' => 'required|exists:db_admins,id',
            'type_id' => 'required|exists:db_order_types,id',
            'selectedCity' => 'required|exists:db_cities,id',
            'amount' => 'required',
            'currency_id' => 'required',
            'customer_name' => 'required',
            'customer_lastname' => 'required',
            'customer_phone' => 'required',
        ],[
            'required' => 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი!',
        ]);

        $check_customer = Customer::where('phone', $this->customer_phone)->first();

        if($check_customer) {
            $customer_id = $check_customer->id;
        } else {
            $customer = Customer::create([
                'name' => $this->customer_name,
                'lastname' => $this->customer_lastname,
                'phone' => $this->customer_phone,
            ]);
            $customer_id = $customer->id;
        }

        $order = Order::create([
            'agent_id' => $this->agent_id,
            'created_by' => Auth::user()->id,
            'type_id' => $this->type_id,
            'city_id' => $this->selectedCity,
            'district_id' => $this->selectedDistrict,
            'subdistrict_id' => $this->selectedSubdistrict,
            'amount' => $this->amount * 100,
            'currency_id' => $this->currency_id,
            'custom_id' => $this->custom_id,
            'url' => $this->url,
            'customer_id' => $customer_id,
            'comment' => $this->comment,
        ]);
        OrderLog::create([
            'order_id' => $order->id,
            'type' => 'შეკვეთის შექმნა',
            'new_value' => json_encode($order, JSON_UNESCAPED_UNICODE),
            'created_by' => Auth::user()->id,
        ]);
        event(new NewOrderCreated($order));
        $this->reset();
        $this->dispatch('order-created', message: 'შეკვეთა წარმატებით დაემატა!');
        $this->dispatch('order-refresh');
    }

    public function render()
    {
        $agents = Admin::whereIn('role_id', [2,3])
            ->where('active', 1)
            ->get();

        $order_types = OrderType::where('active', 1)->get();
        $currencies = Currency::all();
        return view('livewire.dashboard.order.create', compact('agents', 'order_types', 'currencies'));
    }
}
