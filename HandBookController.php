<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function saveLogs;

class HandBookController extends Controller
{
    /**
     * Функция для вывода различных справочников.
     * @param $name
     * @return JsonResponse
     */
    public function index($name): JsonResponse
    {
        // Проверяем что справочник с таким именем у нас имеется. (заносится в ручную)
        // TODO: Подумать как это можно реализовать на автоматическом уровне
        $output = $this->check($name);
        // Если возвращается true - то значит такой справочник у нас есть выводим его данные.
        if ($output->getData()->data) {
            $output = DB::table('sys__' . $name)
                ->orderBy('id')
                ->get();

            return response()->json($output);
        }
        // Если не 1, то отправляем на вывод ошибку.
        return $output;
    }

    /**
     * Функция для добавления данных в таблицу справочника.
     * @param $name
     * @param Request $request
     * @return JsonResponse
     */
    public function create($name, Request $request) :JsonResponse
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|unique:sys__' . $name,
            'engName' => 'string',
            'file' => 'mimes:jpg,bmp,png,gif,webp|file|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->messages()
            ]);
        }
        // Проверяем что справочник с таким именем у нас имеется. (заносится в ручную)
        // TODO: Подумать как это можно реализовать на автоматическом уровне
        $output = $this->check($name);

        if ($output->getData()->data) {

            $array = [];
            $array['name'] = $request->get('name');
            $array['engName'] = $request->get('engName');

            if (!empty($request->file('file'))) {
                $image = $request->file('file');
                $fileName = time() . '-' . $image->getClientOriginalName();
                $request->file('file')->storeAs('sys/' . $name, $fileName, 'img');
                $array['img'] = $fileName;
            }

            $array['created_at'] = now();

            DB::table('sys__' . $name)
                ->insert($array);

            $user = auth()->user()->id;

            saveLogs(
                $user,
                'POST',
                'sys/' . $name . '/create',
                [
                    'name' => $request->get('name'),
                    'engName' => $request->get('engName')
                ],
                'INSERT',
                'HandBookController->create',
                31
            );

            return response()->json([
                'success' => true,
                'message' => 'Справочник успешно добавлен'
            ]);
        }

        return $output;
    }

    /**
     * Поиск информации в справочнике для вывода.
     * @param $name
     * @param Request $request
     * @return JsonResponse
     */
    public function find($name, Request $request) :JsonResponse
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'id' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->messages()
            ]);
        }

        $output = DB::table('sys__' . $name)
            ->where('id', '=', $request->get('id'))
            ->first();

        return response()->json($output);
    }

    /**
     * Функция редактирования справочников.
     * @param $name
     * @param Request $request
     * @return JsonResponse
     */
    public function edit($name, Request $request) :JsonResponse
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'name' => 'required|string',
            'nameEng' => 'required|string',
            'active' => 'required|integer',
            'img' => 'string',
            'file' => 'mimes:jpg,bmp,png,gif,webp|file|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->messages()
            ]);
        }

        $output = $this->check($name);

        if ($output->getData()->data) {
            $array = [];
            $array['name'] = $request->get('name');
            $array['engName'] = $request->get('nameEng');
            $array['active'] = $request->get('active');

            if (!empty($request->file('file'))) {
                // Так получаем текущий файл его сохраняем, но нужно удалить предыдущий для экономии места.
                $image = $request->file('file');
                $fileName = time() . '-' . $image->getClientOriginalName();
                $request->file('file')->storeAs('sys/' . $name, $fileName, 'img');
                $array['img'] = $fileName;

                // Теперь ищем файл который мы хотим удалить. (чтобы не делать лишний запрос пусть фронт отправит)
                Storage::disk('img')->delete('sys/' . $name . '/' . $request->get('img'));
            }

            $array['updated_at'] = now();


            DB::table('sys__' . $name)
                ->where('id', '=', $request->get('id'))
                ->update($array);


            saveLogs(
                auth()->user()->id,
                'POST',
                'sys/' . $name . '/edit',
                [
                    'name' => $request->get('name'),
                    'engName' => $request->get('engName'),
                    'active' => $request->get('active'),

                ],
                'UPDATE',
                'HandBookController->edit',
                31
            );

            return response()->json([
                'success' => true,
                'message' => 'Справочник успешно обновлен'
            ]);


        }

        return $output;
    }

    /**
     * @param $name
     * @return JsonResponse
     */
    private function check($name) :JsonResponse
    {

        if (!in_array($name, $this->spsList())) {
            return response()->json([
                'data' => 0,
                'error' => 'Ошибка данного справочника не существует'
            ], 404);
        }
        return response()
            ->json([
                'data' => 1,
                'message' => 'Данный справочник существует'
            ]);

    }

    /**
     * @param $name
     * @return JsonResponse
     */
    public function all($name) :JsonResponse
    {
        $output = $this->check($name);

        if ($output->getData()->data) {
            $output = DB::table('sys__' . $name)
                ->where('active', '=', 0)
                ->orderBy('id')
                ->get();

            return response()->json($output);
        }

        return $output;
    }

    /**
     * @return string[]
     */
    private function spsList()
    {
        return [
            // Minecraft
            'category',
            'icon',
            'rating',
            'type_email',
            'type_server',
            'category_mods',
            // Stream
            'interactive_game',
            // Game
            // Money
            'category_income_money',
            'category_expend_money'
        ];
    }
}
