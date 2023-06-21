<?php

namespace App\Http\Controllers;

use App\Models\Entrie;
use App\Models\Padlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntrieController extends Controller
{
    public function index(): JsonResponse
    {
        $entrie = Entrie::with(['comments', 'user', 'ratings'])->get();
        return response()->json($entrie, 200);
    }

    //find entry by padlet id
    public function findByPadletID(int $id): JsonResponse
    {
        $entrie = Entrie::where('padlet_id', $id)
            ->with(['comments', 'user', 'ratings'])->get();
        return $entrie != null ? response()->json($entrie, 200) : response()->json(null, 200);
    }

    public function getEntryByID(string $id):JsonResponse{
        $entry = Entrie::where('id', $id)
            ->with(['comments','user', 'ratings'])->first();
        return $entry != null ? response()->json($entry, 200) : response()->json(null, 200);
    }

    // create new entrie within padlet
    public function save(Request $request, string $padletID): JsonResponse
    {
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            if (isset($request['user_id']) && isset($request['title']) && isset($request['content'])) ;
            {
                $entry = Entrie::create(
                    [
                        'user_id' => $request['user_id'],
                        'title' => $request['title'],
                        'content' => $request['content'],
                        'padlet_id' => $padletID
                    ]
                );
            }
            DB::commit();
            return response()->json($entry, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving entrie failed: " . $e->getMessage(), 420);
        }
    }

    private function parseRequest(Request $request): Request
    {
        //convert date
        $date = new \DateTime($request->created_at);
        $request['published'] = $date;
        return $request;
    }

    //update entry
    public function update(Request $request, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $entry = Entrie::with(['comments','user', 'ratings'])
                ->where('id', $id)->first();
            if ($entry != null) {
                $request = $this->parseRequest($request);
                $entry->update($request->all());

                //delete all old entries
                // $entry->entries()->delete();

                if (isset($request['entries']) && is_array($request['entries'])) {
                    foreach ($request['entries'] as $e) {
                        $entrie = Entrie::firstOrNew(['title' => $e['title'], 'content' => $e['content'], 'user_id' => $e['user_id'], 'padlet_id'=>$id]);
                        $entry->entries()->save($entrie);
                    }
                }
                $entry->save();
            }
            DB::commit();
            $entry1 = Entrie::with(['comments','user', 'ratings'])
                ->where('id', $id)->first(); // return a vaild http response
            return response()->json($entry1, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating Entry failed: " . $e->getMessage(), 420);
        }
    }

    //delete entry
    public function delete(int $id): JsonResponse
    {
        $entrie = Entrie::where('id', $id)->first();
        if ($entrie != null) {
            $entrie->delete();
            return response()->json('entry (' . $id . ') successfully deleted', 200);
        } else
            return response()->json('entry could not be deleted - it does not exist', 422);
    }


}
