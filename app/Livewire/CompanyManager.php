<?php

namespace App\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyManager extends Component
{
    use WithFileUploads;

    protected $listeners = ['delete'];
    public $companies, $name, $email, $website, $logo, $companyId;
    public $isEditMode = false;
    public $showForm = false;

    protected $rules = [
        'name' => 'required|unique:companies,name',
        'email' => 'nullable|email|unique:companies,email',
        'website' => 'nullable|url|unique:companies,website',
        'logo' => 'nullable|image|dimensions:min_width=100,min_height=100',
    ];

    public function render()
    {
        $this->companies = Company::all();
        return view('livewire.company-manager');
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'website', 'logo', 'companyId', 'isEditMode']);
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['name', 'email', 'website', 'logo', 'isEditMode', 'showForm']);
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'website' => $this->website,
        ];

        if ($this->logo) {
            $data['logo'] = $this->logo->store('logos', 'public');
        }

        Company::create($data);
        session()->flash('message', 'Company added successfully.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $this->showForm = true;
        $company = Company::findOrFail($id);
        $this->companyId = $company->id;
        $this->name = $company->name;
        $this->email = $company->email;
        $this->website = $company->website;
        $this->isEditMode = true;
    }

    public function update()
    {
        $company = Company::findOrFail($this->companyId);

        $this->validate([
            'name' => 'required|unique:companies,name,' . $company->id,
            'email' => 'nullable|email|unique:companies,email,' . $company->id,
            'website' => 'nullable|url|unique:companies,website,' . $company->id,
            'logo' => 'nullable|image|dimensions:min_width=100,min_height=100',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'website' => $this->website,
        ];

        if ($this->logo) {
            $data['logo'] = $this->logo->store('logos', 'public');
        }

        $company->update($data);
        session()->flash('message', 'Company updated successfully.');
        $this->resetForm();
    }

    public function confirmDelete($companyId)
    {
        $this->dispatch('confirm-delete', ['companyId' => $companyId]);
    }

    public function delete($companyId)
    {
        Company::findOrFail($companyId)->delete();
        session()->flash('message', 'Company deleted successfully.');
    }
}
