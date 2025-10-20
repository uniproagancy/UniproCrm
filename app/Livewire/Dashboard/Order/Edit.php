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
use App\Models\Subdistrict;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public $order_id;

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

    protected $listeners = ['openEditModal' => 'loadOrder'];

    public function loadOrder($order_id)
    {
        $order = Order::findOrFail($order_id);
        $this->order_id = $order->id;

        $this->agent_id = $order->agent_id;
        $this->type_id = $order->type_id;
        $this->amount = $order->amount / 100;
        $this->currency_id = $order->currency_id;
        $this->custom_id = $order->custom_id;
        $this->url = $order->url;
        $this->comment = $order->comment;

        if ($order->customer) {
            $this->customer_name = $order->customer->name;
            $this->customer_lastname = $order->customer->lastname;
            $this->customer_phone = $order->customer->phone;
        }

        $this->updatedAgentId($this->agent_id);

        if ($this->selectedCity) {
            $this->updatedSelectedCity($this->selectedCity);
        }

        if ($this->selectedDistrict) {
            $this->updatedSelectedDistrict($this->selectedDistrict);
        }

        $this->selectedCity = $order->city_id;

        $this->dispatch('show-edit-modal');
    }

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
    }

    public function updatedSelectedCity($cityId)
    {
        if (!$cityId || !$this->agent) {
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
    }

    public function updatedSelectedDistrict($districtId)
    {
        if (!$districtId || !$this->agent) {
            $this->subdistricts = [];
            $this->selectedSubdistrict = null;
            return;
        }

        $this->subdistricts = Subdistrict::whereIn('id', $this->agent->subdistricts)
            ->where('district_id', $districtId)
            ->orderBy('name')
            ->get();
    }

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

    public function update()
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
        ], [
            'required' => 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი!',
        ]);

        $order = Order::findOrFail($this->order_id);

        $customer = Customer::updateOrCreate(
            ['phone' => $this->customer_phone],
            [
                'name' => $this->customer_name,
                'lastname' => $this->customer_lastname,
                'phone' => $this->customer_phone,
            ]
        );

        $oldData = $order->toArray();

        $order->update([
            'agent_id' => $this->agent_id,
            'type_id' => $this->type_id,
            'city_id' => $this->selectedCity,
            'district_id' => $this->selectedDistrict,
            'subdistrict_id' => $this->selectedSubdistrict,
            'amount' => $this->amount * 100,
            'currency_id' => $this->currency_id,
            'custom_id' => $this->custom_id,
            'url' => $this->url,
            'customer_id' => $customer->id,
            'comment' => $this->comment,
        ]);

        OrderLog::create([
            'order_id' => $order->id,
            'type' => 'შეკვეთის რედაქტირება',
            'old_value' => json_encode($oldData, JSON_UNESCAPED_UNICODE),
            'new_value' => json_encode($order->toArray(), JSON_UNESCAPED_UNICODE),
            'created_by' => Auth::user()->id,
        ]);

        $this->dispatch('order-updated', message: 'შეკვეთა წარმატებით განახლდა!');
        $this->dispatch('order-refresh');
        $this->dispatch('hide-edit-modal');
    }

    public function render()
    {
        $agents = Admin::whereIn('role_id', [2, 3])
            ->where('active', 1)
            ->get();

        $order_types = OrderType::where('active', 1)->get();
        $currencies = Currency::all();

        return view('livewire.dashboard.order.edit', compact('agents', 'order_types', 'currencies'));
    }
}
