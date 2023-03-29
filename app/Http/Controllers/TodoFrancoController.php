<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TodoFranco;
use Illuminate\Support\Facades\Validator;


class TodoFrancoController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse 
     */
    public function index()
    {
        $todo = TodoFranco::all();
        return response()->json([
            'status' => 'success',
            'todo' => $todo,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response 
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse 
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:60',
            'email' => 'required|email|max:60|unique:users',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' =>  $validator->errors(),
                'message' => 'There are errors on required fields',
            ], 422);
        }

        $todo = TodoFranco::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'age'  => $request->age,
            ]
        );

        return response()->json(
            [
                'status' => 'success',
                '_wellow_is_awesome_' => true,
                'message' => 'Data created successfully',
                'todo' => $todo,

            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse 
     */
    public function show($id)
    {
        $todo = TodoFranco::find($id);
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data created successfully',
                'todo' => $todo,

            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse 
     */
    public function edit(Request $request, $id)
    {

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse 
     */
    public function update(Request $request, $id)
    {
        // on postman show be set to 'x-www-form-url-encoded'

        $rules = [
            'name'  => 'required|string|max:60',
            'email'  => 'required|email|unique:users',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation Fails',
            ], 422);
        }

        if (TodoFranco::where('id', $id)->exists()) {
            $todo = TodoFranco::where('id', $id)->update(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'age'  => $request->age,
                ]
            );
        } else {
            return response()->json([
                "message" => "Todo not found"
            ], 404);
        }


        return response()->json([
            'status' => 'success',
            "message" => 'Data updated successfully',
            'todo' => $todo,
            'id' => $id,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse 
     */
    public function destroy($id)
    {
        if (TodoFranco::where('id', $id)->exists()) {
            $todo =   TodoFranco::find($id);
            if ($todo->delete()) {
                return response()->json([
                    'message' => 'Record deleted successfully',
                    'record_id' => $id
                ], 200);
            }else{

                return response()->json(['message' => 'I was not possible to delete this record Id:'.$id],404);
            }
        }else{
            return response()->json(['message' => 'Operation not allowed for record Id:'.$id],402);
        }
    }
}
