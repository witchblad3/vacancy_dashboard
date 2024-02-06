<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVacancyRequest;
use App\Models\FavVacs;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VacancyController extends Controller
{
    public function addVacancyView()
    {
        $creator = Auth::user();
        $vacs = $creator->vacancies()->withTrashed()->orderBy('created_at', 'desc');
        $vacsCount = $vacs->count();
        $vacs = $vacs->paginate(8);
        return view('vacancies.create_vacancy', [
            'formTitle' => 'Добавить вакансию',
            'creator' => $creator,
            'vacs' => $vacs,
            'vacCount' => $vacsCount
        ]);
    }

    public function addVacancyToTable(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedFields = Validator::make($request->all(), [
            '*' => 'required',
        ]);

        if ($request->get('expirience') == "0") {
            return redirect()->back()->withErrors('Некорректно заполнено поле "Требуемый опыт"')
                ->withInput();
        }

        if ($validatedFields->fails()) {
            return redirect()->back()->withErrors($validatedFields)
                ->withInput();
        }

        $vacancy = new Vacancy();
        $author_id = Auth::id();
        $vacancy->author_id = $author_id;
        $vacancy->company_name = $request->company_name;
        $vacancy->salary = $request->salary;
        $vacancy->name = $request->name;
        $vacancy->expirience = $request->expirience;
        $vacancy->description = $request->description;
//        Vacancy::create($vacancy->toArray());
        $vacancy->save();

        return redirect()->back()->with('success', 'Вакансия успешно добавлена в каталог!');
    }

    public function deleteVacancy($id): \Illuminate\Http\JsonResponse
    {
        // todo: отрефакторить

        Vacancy::query()
            ->find($id)
            ->delete();

        return response()->json(['status' => 'success']);
    }

    public function getVacById($id): \Illuminate\Http\JsonResponse
    {
        $vac = Vacancy::query()
            ->where('id', '=', $id)
            ->first();
        $isThisAFavoriteVacancie = FavVacs::query()
            ->where('vac_id', '=', $id)
            ->where('user_id', '=', Auth::id())
            ->count();

        $isCreator = $vac->author_id == Auth::id();
        $vac->is_creator = $isCreator;

        return response()->json([
            'vacancy' => $vac,
            'is_favorite' => $isThisAFavoriteVacancie
        ]);
    }

    public function showVacancies()
    {
        $vacs = Vacancy::query()
            ->where('id', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        $favvacs = Auth::user()
            ->favvacs()
            ->get()
            ->toArray();

        return view('vacancies.catalog',
            [
                'vacancies' => $vacs,
                'favs' => $favvacs
            ]);
    }

    public function makeFav(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->get('id');
        if (is_null($id)) {
            return response()->json([
                'status' => 'error'
            ]);
        }
        $userId = Auth::id();
        $vac = FavVacs::query()
            ->where('vac_id', '=', $id)
            ->where('user_id', '=', $userId)
            ->count();

        if ($vac != 0) {
            return response()->json([
                'status' => 'exists'
            ]);
        }

        FavVacs::query()
            ->insert([
                'user_id' => $userId,
                'vac_id' => $id
            ]);

        return response()->json([
            'status' => 200
        ]);
    }

    public function makeUnfav(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->get('id');
        if (is_null($id)) {
            return response()->json([
                'status' => 'error'
            ]);
        }
        $userId = Auth::id();
        $vac = FavVacs::query()
            ->where('vac_id', '=', $id)
            ->where('user_id', '=', $userId)
            ->count();

        if ($vac == 0) {
            return response()->json([
                'status' => 'deleted'
            ]);
        }

        FavVacs::query()
            ->where('user_id', '=', $userId)
            ->where('vac_id', '=', $id)
            ->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    public function updateVacancy(UpdateVacancyRequest $request): \Illuminate\Http\JsonResponse
    {
        $authorId = Auth::id();
        $attributes = $request->validated();

        $updateVac = Vacancy::query()
            ->where('author_id', '=', $authorId)
            ->where('id', '=', $request->get('vacancyId'))
            ->update($attributes);
        return response()->json(['status' => 200]);
    }

    public function favList()
    {
        $vacancies = Auth::user()->favvacs()->orderBy('created_at', 'desc');
        $vacsCount = $vacancies->count();
        $vacancies = $vacancies->paginate(5);
        return view('vacancies.favorites',
            [
                'vacancies' => $vacancies,
                'vacsCount' => $vacsCount
            ]);
    }
}
