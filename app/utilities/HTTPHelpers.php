<?php

namespace App\Utilities;

use Throwable;

class HTTPHelpers
{

    /**
     * Respuesta predeterminada
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseJson($message, $query = null, $bindings = null, $headers = [], $options = JSON_UNESCAPED_UNICODE)
    {
        if (!app()->environment('production')) {
            return response()->json(["status" => true, 'message' => $message, 'query' => $query, 'bindings' => $bindings], 200, $headers, $options);
        }
        return response()->json(["status" => true, 'message' => $message], 200, $headers, $options);
    }

    /**
     * Respuesta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseJsonR($message, $query = null, $bindings = null, $headers = [], $options = JSON_INVALID_UTF8_SUBSTITUTE)
    {
        $response = is_array($message) ? ($message[count($message) - 1] ?? null) : $message;

        if (!app()->environment('production')) {
            return response()->json(["status" => true, 'message' => $response, 'log' => $message, 'query' => $query, 'bindings' => $bindings], 200, $headers, $options);
        }
        return response()->json(["status" => true, 'message' => $response], 200, $headers, $options);
    }

    /**
     * Respuesta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseJsonGetPage($message, $limit, $page, $headers = [], $options = JSON_UNESCAPED_UNICODE)
    {
        $records = DBHelpers::count($message);
        $pages = ceil($records / $limit);
        $message->limit($limit)->offset(($page - 1) * $limit);
        $bindings = $message->getBindings();
        $sql = $message->toSql();
        $message = $message->get();

        if (!app()->environment('production')) {
            return response()->json(["status" => true, 'records' => $records, 'pages' => $pages, 'message' => $message, 'query' => $sql, 'bindings' => $bindings], 200, $headers, $options);
        }
        return response()->json(["status" => true, 'records' => $records, 'pages' => $pages, 'message' => $message], 200, $headers, $options);
    }

    /**
     * Respuesta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseJsonGet($message, $headers = [], $options = JSON_UNESCAPED_UNICODE)
    {
        if (!app()->environment('production')) {
            return response()->json(["status" => true, 'message' => $message->get(), 'query' => $message->toSql(), 'bindings' => $message->getBindings()], 200, $headers, $options);
        }
        return response()->json(["status" => true, 'message' => $message->get()], 200, $headers, $options);
    }

    /**
     * Respuesta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseJsonFirst($message, $headers = [], $options = JSON_UNESCAPED_UNICODE)
    {
        if (!app()->environment('production')) {
            return response()->json(["status" => true, 'message' => $message->first(), 'query' => $message->toSql(), 'bindings' => $message->getBindings()], 200, $headers, $options);
        }
        return response()->json(["status" => true, 'message' => $message->first()], 200, $headers, $options);
    }

    /**
     * Respuesta para errores de php
     * @param \Illuminate\Support\MessageBag $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function validatorError($message, $status = 200, $headers = [], $options = JSON_UNESCAPED_UNICODE)
    {
        $errors = array_values($message->messages());
        $errorMessages = array_map(function ($error) {return implode("<br>", $error);}, $errors);

        return response()->json(["status" => false, 'message' => implode("<br>", $errorMessages)], $status, $headers, $options);
    }

    /**
     * Respuesta para errores de php
     * @param Throwable $th
     * @return \Illuminate\Http\JsonResponse
     */
    public static function throwError(Throwable $th, $file, $status = 200, $headers = [], $options = JSON_UNESCAPED_UNICODE)
    {
        $errorMsg = "{$th->getMessage()} - {$th->getLine()} ({$th->getFile()}) [{$file}]";
        if (!app()->environment('production')) {
            return response()->json(["status" => false, 'message' => $errorMsg], $status, $headers, $options);
        }
        // $ticket = LogAccionesSistemaController::save(7, $errorMsg);
        // return response()->json(["status" => false, 'message' => "Error # {$ticket}, comuníquese con un asesor"], $status, $headers, $options);
        return response()->json(["status" => false, 'message' => $errorMsg], $status, $headers, $options);
    }

    /**
     * Respuesta para errores personalizados
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseError($message, $status = 200, $headers = [], $options = JSON_UNESCAPED_UNICODE)
    {
        return response()->json(["status" => false, 'message' => $message], $status, $headers, $options);
    }

}
