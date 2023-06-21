<?php

namespace App\Http\Controllers;

use App\Models\Entrie;
use App\Models\Padlet;
use App\Models\Userright;
use Dotenv\Parser\Entry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PadletController extends Controller
{
    //returns all padlets
    public function index(): JsonResponse
    {
        $padlets = Padlet::with(['user', 'entries', 'userrights'])->get();
        return response()->json($padlets, 200);
    }

    //finds padlet by id
    public function findById(int $id): JsonResponse
    {
        $padlets = Padlet::where('id', $id)->with(['user', 'entries', 'userrights'])->first();
        return response()->json($padlets, 200);
    }

    // returns true if padlet with id exists
    public function checkID(string $id): JsonResponse
    {
        $padlet = Padlet::where('id', $id)->first();
        return $padlet != null ? response()->json(true, 200) : response()->json(false, 200);
    }

    //finds padley by search term
    public function findBySearchTerm(string $searchTerm): JsonResponse
    {
        $padlets = Padlet::with(['user', 'entries', 'userrights', 'entries.comments', 'entries.ratings'])
            ->where('name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhereHas('user', function ($query) use ($searchTerm) {
                $query->where('firstName', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('lastName', 'LIKE', '%' . $searchTerm . '%');
            })->get();
        return response()->json($padlets, 200);
    }

    //creates new padlet
    //IN THIS PROJECT, PADLETS CAN ONLY BE CREATED IF USER IS LOGGED IN
    public function save(Request $request): JsonResponse
    {
        $request = $this->parseRequest($request);
        DB::beginTransaction();

        try {
            $padlet = Padlet::create($request->all());

            DB::commit();
            return response()->json($padlet, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving padlet failed: " . $e->getMessage(), 420);
        }
    }

    // convert values if needed
    private function parseRequest(Request $request): Request
    {
        //convert date
        $date = new \DateTime($request->created_at);
        $request['published'] = $date;
        return $request;
    }

    //updates padlet
    public function update(Request $request, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $padlet = Padlet::with(['user', 'entries', 'userrights'])
                ->where('id', $id)->first();
            if ($padlet != null) {
                $request = $this->parseRequest($request);
                $padlet->update($request->all());

                $padlet->userrights()->delete();
                //updates userrights
                if (isset($request['userrights']) && is_array($request['userrights'])) {
                    foreach ($request['userrights'] as $userrights) {

                        $userrights = Userright::firstOrNew(
                            ['padlet_id' => $userrights['padlet_id'],
                                'user_id' => $userrights['user_id'],
                                'read' => $userrights['read'],
                                'edit' => $userrights['edit']]);
                        $padlet->userrights()->save($userrights);
                    }
                }
                $padlet->save();
            }
            DB::commit();
            $padlet1 = Padlet::with(['user', 'entries', 'userrights'])
                ->where('id', $id)->first(); // return a vaild http response
            return response()->json($padlet1, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating padlet failed: " . $e->getMessage(), 420);
        }
    }

    //deletes padlet
    public function delete(string $id): JsonResponse
    {
        $padlet = Padlet::where('id', $id)->first();
        if ($padlet != null) {
            $padlet->delete();
            return response()->json('padlet (' . $id . ') successfully deleted', 200);
        } else
            return response()->json('padlet could not be deleted - it does not exist', 422);
    }
}
