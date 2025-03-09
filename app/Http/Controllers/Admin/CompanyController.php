<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

use App\Services\UserRoleService;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return Company::paginate(25);
        } else {
            return response()->json(['message' => 'You are not authorized to access this resource'], 403);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'fantasy_name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:companies,cnpj',
            'cpf' => 'required|string|max:14|unique:companies,cpf',
            'email' => 'required|email|unique:companies,email',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:10',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:9',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'whatsapp' => 'required|string|max:15',
            'website' => 'required|string|max:255',
        ]);

        $company = Company::create($validatedData);

        return response()->json($company, 201);
    }

    public function show(Company $company)
    {
        $company = Company::find($company->id);

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return response()->json($company);
    }

    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'cnpj' => 'sometimes|string|max:18|unique:companies,cnpj,' . $company->id,
            'email' => 'sometimes|email|unique:companies,email,' . $company->id,
            'address' => 'sometimes|string|max:255',
            'number' => 'sometimes|string|max:10',
            'neighborhood' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'state' => 'sometimes|string|max:255',
            'zip_code' => 'sometimes|string|max:9',
            'country' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:15',
            'whatsapp' => 'sometimes|string|max:15',
            'website' => 'sometimes|string|max:255',
        ]);

        $company->update($validatedData);

        return response()->json($company);
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json(['message' => 'Company deleted successfully']);
    }
}
