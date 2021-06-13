<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::orderBy('time', 'DESC')->get();

        $response = [
            'messages' => 'List Transaction order by time',
            'data' => $transaction
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => ['required'],
                'amount' => ['required', 'numeric'],
                'type' => ['required', 'in:expense, revenue']
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            
            $transaction = Transaction::create($request->all());
            $response = [
                'messages' => 'Transaction Created Successfully!',
                'data' => $transaction

            ];

            return response()->json($response, Response::HTTP_CREATED);
            
        } catch (QueryException $e) {
            
            return response()->json(
                [
                    'messages' => "Failed " . $e->errorInfo
                ]
            );
            
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        $response = [
            'messages' => 'Detail of Transaction Resource.',
            'data' => $transaction

        ];

        return response()->json($response, Response::HTTP_OK);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $validator = Validator::make($request->all(),
            [
                'title' => ['required'],
                'amount' => ['required', 'numeric'],
                'type' => ['required', 'in:expense, revenue']
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            
            $transaction->update($request->all());
            $response = [
                'messages' => 'Transaction Updated Successfully!',
                'data' => $transaction

            ];

            return response()->json($response, Response::HTTP_OK);
            
        } catch (QueryException $e) {
            
            return response()->json(
                [
                    'messages' => "Failed " . $e->errorInfo
                ]
            );
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        try {
            
            $transaction->delete();
            $response = [
                'messages' => 'Transaction Deleted Successfully!'

            ];

            return response()->json($response, Response::HTTP_OK);
            
        } catch (QueryException $e) {
            
            return response()->json(
                [
                    'messages' => "Failed " . $e->errorInfo
                ]
            );
            
        }
    }
}
