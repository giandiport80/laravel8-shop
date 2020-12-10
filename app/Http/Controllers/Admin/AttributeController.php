<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeOptionRequest;
use App\Http\Requests\AttributeRequest;
use App\Models\Attribute;
use App\Models\AttributeOption;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        parent::__construct();

        $this->data['currentAdminMenu'] = 'catalog';
        $this->data['currentAdminSubMenu'] = 'attribute';

        $this->data['types'] = Attribute::types();
        $this->data['booleanOptions'] = Attribute::booleanOptions();
        $this->data['validations'] = Attribute::validations();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['attributes'] = Attribute::orderBy('name')->paginate(10);

        return view('admin.attributes.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['attribute'] = null;

        return view('admin.attributes.form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeRequest $request)
    {
        $params = $request->except('_token');

        $params['is_required'] = (bool) $params['is_required']; // .. 1
        $params['is_unique'] = (bool) $params['is_unique'];
        $params['is_configurable'] = (bool) $params['is_configurable'];
        $params['is_filterable'] = (bool) $params['is_filterable'];

        if(Attribute::create($params)){
            session()->flash('success', 'Attribute has been saved!');
        }

        return redirect()->route('attributes.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {
        $this->data['attribute'] = $attribute;

        return view('admin.attributes.form', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $params  = $request->except('_token');

        $params['is_required'] = (bool) $params['is_required'];
        $params['is_unique'] = (bool) $params['is_unique'];
        $params['is_configurable'] = (bool) $params['is_configurable'];
        $params['is_filterable'] = (bool) $params['is_filterable'];

        unset($params['code']); // .. 2
        unset($params['type']);

        if($attribute->update($params)){
            session()->flash('success', 'Attribute has been updated!');
        }

        return redirect()->route('attributes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        if($attribute->delete()){
            request()->session()->flash('success', 'Attribute has been deleted!');
        }

        return redirect()->route('attributes.index');
    }

    // k: method2 attributeOptions
    // ============================================================================

    public function options($attributeID)
    {
        if(empty($attributeID)){
            return redirect()->route('attributes.index');
        }

        $attribute = Attribute::findOrFail($attributeID);

        $this->data['attribute'] = $attribute;

        return view('admin.attributes.options', $this->data);
    }

    public function store_option(AttributeOptionRequest $request, $attributeID)
    {
        if(empty($attributeID)){
            return redirect()->route('attributes.index');
        }

        $params = [
            'attribute_id' => $attributeID,
            'name' => $request->name
        ];

        if(AttributeOption::create($params)){
            session()->flash('success', 'Option has been saved!');
        }

        return redirect('admin/attributes/' . $attributeID . '/options');
    }

    public function edit_option($optionID)
    {
        $option = AttributeOption::findOrFail($optionID);

        $this->data['attributeOption'] = $option;
        $this->data['attribute'] = $option->attribute; // .. 3

        return view('admin.attributes.options', $this->data);
    }

    public function update_option(AttributeOptionRequest $request, $optionID)
    {
        $option = AttributeOption::findOrFail($optionID);
        $params = $request->except('_token');

        if($option->update($params)){
            session()->flash('success', 'Option has been updated!');
        }

        return redirect()->route('attributes.options', $option->attribute->id);
    }

    public function remove_option($optionID)
    {
        if(empty($optionID)){
            return redirect()->route('attributes.index');
        }

        $option = AttributeOption::findOrFail($optionID);

        if($option->delete()){
            session()->flash('success', 'Option has been deleted!');
        }

        return redirect()->route('attributes.options', $option->attribute->id);
    }

    // ============================================================================
}










// h: DOKUMENTASI

// p: clue 1
// kita convert requrest nya menjadi boolean
// jika false, akan bernilai 0
// jika true, akan bernilai 1

// p: clue 2
// menghapus request dari code dan type

// p: clue 3
// merupakan relasi belongsToMany dari attribute
// N attributeOption dimiliki oleh 1 attribute

