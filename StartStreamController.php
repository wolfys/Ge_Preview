<?php

namespace App\Http\Controllers\Api\Stream;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StartStreamController extends Controller
{
    // View Blade Laravel Stream
    // Start
    public function index()
    {
        $sql = DB::table('stream_w_start')->where('active', '=', 1)->first();
        $time = DB::table('stream_w_data')->select('startTime')->first();

        return view('stream.start', [
            'name' => $sql->name,
            'video' => $sql->url_video,
            'time' => $time->startTime
        ]);
    }
    // More
    public function getMore() {
        $time_id = DB::table('stream_w_data')->select('nextGameTime')->first();
        $sql = DB::table('stream_w_start')->where('active', '=', 1)->first();
        return view('stream.more', [
            'name' => $sql->name,
            'video' => $sql->url_video,
            'time' => $time_id->nextGameTime
        ]);

    }
    // Afk
    public function getAfk() {
        $time_id = DB::table('stream_w_data')->select('afkTime')->first();
        $sql = DB::table('stream_w_start')->where('active', '=', 1)->first();

        return view('stream.afk', [
            'name' => $sql->name,
            'video' => $sql->url_video,
            'time' => $time_id->afkTime
        ]);
    }
    /* Admin panel Start */
    public function adminStartList()
    {
        return DB::table('stream_w_start')->get();
    }

    public function getDefaultStart(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->messages()
            ]);
        }

        DB::table('stream_w_start')
            ->where('active', '=', 1)
            ->update([
                'active' => 0,
                'updated_at' => now()
            ]);

        DB::table('stream_w_start')
            ->where('id', '=', $request->get('id'))
            ->update([
                'active' => 1,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Успешно изменен ролик по умолчанию'
        ]);
    }

    public function getCreateStart(Request $request) {

        if($request->get('active') === 1) {
            DB::table('stream_w_start')
                ->where('active', '=', 1)
                ->update([
                    'active' => 0,
                    'updated_at' => now()
                ]);
        }

        DB::table('stream_w_start')->insert([
           [
               'name' => $request->get('name'),
               'url_video' => $request->get('video'),
               'active' => $request->get('active'),
               'created_at' => now()
           ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Успешно добавлен новый Start Стрима'
        ]);
    }

    public function getEditStart(Request $request) {

        if($request->get('active') === 1) {
            DB::table('stream_w_start')
                ->where('active', '=', 1)
                ->update([
                    'active' => 0,
                    'updated_at' => now()
                ]);
        }

        DB::table('stream_w_start')
            ->where('id','=',$request->get('id'))
            ->update([
                'name' => $request->get('name'),
                'url_video' => $request->get('video'),
                'active' => $request->get('active'),
                'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Успешно изменен Start Стрима'
        ]);
    }

    public function getDeleteStart(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->messages()
            ]);
        }

        $sql = DB::table('stream_w_start')
            ->where('id','=',$request->get('id'))
            ->first();

        if($sql->active === 1) {
            return response()->json([
                'success' => false,
                'message' => 'Нельзя удалять активный Start Стрима'
            ]);
        }

        DB::table('stream_w_start')
            ->where('id','=',$request->get('id'))
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Успешно удален лишний Start Стрима'
        ]);
    }
}
