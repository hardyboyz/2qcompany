<div class="container mx-auto p-6 bg-white shadow rounded-lg">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Companies</h1>
        <button
            wire:click="resetForm"
            class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600"
        >
            Add New Company
        </button>
    </div>

    <!-- Form Section -->
    @if ($showForm)
        <div class="bg-gray-50 p-4 rounded-lg shadow mb-6">
            <h2 class="text-lg font-medium text-gray-700 mb-4">
                {{ $isEditMode ? 'Edit Company' : 'Create Company' }}
            </h2>
            <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                        @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                        @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                        <input type="file" id="logo" wire:model="logo" class="mt-1 block w-full text-gray-700">
                        @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="Logo Preview" class="mt-2 w-16 h-16 rounded-full object-cover">
                        @endif
                        @error('logo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                        <input type="text" id="website" wire:model="website" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                        @error('website') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mt-10 flex justify-end">
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                        {{ $isEditMode ? 'Update' : 'Save' }}
                    </button>
                    <button type="button" wire:click="closeForm" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 ml-2">
                        Close
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Website</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $company)
                    <tr class="border-t border-gray-300 hover:bg-gray-50">
                        <td class="px-4 py-2 flex items-center space-x-4">
                            @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="w-10 h-10 rounded-full object-cover">
                            @endif
                            <span class="text-gray-700">{{ $company->name }}</span>
                        </td>
                        <td class="px-4 py-2 text-gray-700">{{ $company->email }}</td>
                        <td class="px-4 py-2 text-gray-700">
                            <a href="{{ $company->website }}" target="_blank" class="text-blue-500 hover:underline">
                                {{ $company->website }}
                            </a>
                        </td>
                        <td class="px-4 py-2">
                            <button
                                wire:click="edit({{ $company->id }})"
                                class="text-sm text-blue-500 hover:underline"
                            >
                                Edit
                            </button>
                            |
                            <button
                                wire:click="confirmDelete({{ $company->id }})"
                                class="text-red-500 hover:underline"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.addEventListener('confirm-delete', (event, detail) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will delete this company!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = parseInt(event.detail[0].companyId);
                    console.log(id);
                    Livewire.dispatch('delete', {companyId: id});
                    Swal.fire(
                        'Deleted!',
                        'The company has been deleted.',
                        'success'
                    );
                }
            });
        });

    </script>
</div>
