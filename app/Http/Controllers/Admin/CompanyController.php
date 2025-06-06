<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


use App\Services\UserRoleService;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user);

        if ($roleId == 1) {
            return CompanyResource::collection(Company::paginate(25)->get());
        } else {
            return CompanyResource::collection(Company::where('id', $user->company_id)->with(['plans.plan.features'])->get());
            //return Company::where('id', $user->company_id)->with(['plans.plan.features'])->firstOrFail();
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

        // Gerar slug a partir do nome
        $validatedData['slug'] = Str::slug($validatedData['name']);

        // Garantir unicidade do slug (se necessário)
        $originalSlug = $validatedData['slug'];
        $count = 1;
        while (Company::where('slug', $validatedData['slug'])->exists()) {
            $validatedData['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

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
