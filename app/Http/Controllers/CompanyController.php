<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:companies',
            'email' => 'nullable|email|unique:companies',
            'website' => 'nullable|url|unique:companies',
            'logo' => 'nullable|image|dimensions:min_width=100,min_height=100',
        ]);

        $data = $request->all();
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Company::create($data);
        return redirect()->route('companies.index')->with('success', 'Company added successfully.');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|unique:companies,name,' . $company->id,
            'email' => 'nullable|email|unique:companies,email,' . $company->id,
            'website' => 'nullable|url|unique:companies,website,' . $company->id,
            'logo' => 'nullable|image|dimensions:min_width=100,min_height=100',
        ]);

        $data = $request->all();
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);
        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
