<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chatbot;

class ChatbotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $chatbot = Chatbot::create($request->all());
        return response()->json($chatbot, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $shop_id, $type)
    {
        $chatbot = Chatbot::join('message_chatbot', 'chatbot.id', '=', 'message_chatbot.chatbot_id')
            ->where('shop_id', $shop_id)
            ->where('message_chatbot.type', $type);

        if ($type == 'auto_chat') {
            $chatbot = $chatbot->select('shop_id', 'auto_chat', 'quick_message', 'content', 'type', 'chatbot.id as chatbot_id')
                ->first();
        } else if ($type == 'quick_message') {
            $chatbot = $chatbot->select('shop_id', 'auto_chat', 'quick_message', 'content', 'type', 'chatbot.id as chatbot_id')
                ->get();
        }

        return $chatbot;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $chatbot = Chatbot::find($id);
        $chatbot->update($request->all());
        return response()->json($chatbot, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
