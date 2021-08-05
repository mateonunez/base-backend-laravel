<?php

namespace App\Http\Controllers;

use App\Core\Message;
use Illuminate\Http\Request;
use App\Core\ControllerUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var mixed
     */
    protected $entityClass;

    /**
     * @var int
     */
    protected $perPage = 33;

    /**
     * Base method to get all entities
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            if (is_null($this->entityClass)) {
                return $this->sendError(Message::BAD_REQUEST, [], 400);
            }

            $query = $request->query();

            // Getting filters
            $filters = ControllerUtils::getRequestFilters($query);

            // Getting relationships
            $relationships = ControllerUtils::getRequestRelationships($query);

            // Getting order
            $orderBy = ControllerUtils::getRequestOrderBy($query);

            // Getting pagination
            $paginate = ControllerUtils::getPaginate($query);

            // Retrieving entities
            $entities = $this->entityClass::with($relationships)
                ->where($filters);

            // Ordering entities
            foreach ($orderBy as $order) {
                $entities->orderBy($order[0], $order[1]);
            }

            $entities = $paginate
                ? $entities->paginate($this->perPage)->withQueryString()->toArray()
                : $entities->get()->toArray();

            return $this->sendResponse(
                $entities,
                Message::INDEX_OK
            );
        } catch (\Illuminate\Database\QueryException $ex) {
            // TODO Add log
            // Log::error(Message::FILTER_KO, __METHOD__, new $this->entityClass(), $request, $ex);

            return $this->sendError(Message::FILTER_KO);
        } catch (\Illuminate\Database\Eloquent\RelationNotFoundException $ex) {
            // TODO Add log
            // Log::error(Message::RELATION_KO, __METHOD__, new $this->entityClass(), $request, $ex);

            return $this->sendError(Message::RELATION_KO);
        } catch (\Exception $ex) {
            // TODO Add log
            // Log::error(Message::INDEX_KO, __METHOD__, new $this->entityClass(), $request, $ex);

            return $this->sendError(Message::INDEX_KO  . $ex->getMessage());
        }
    }

    /**
     * Base method to show single entity
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        try {
            if (is_null($this->entityClass)) {
                return $this->sendError(Message::BAD_REQUEST, [], 400);
            }

            $query = $request->query();

            // Geting relationships
            $relationships = ControllerUtils::getRequestRelationships($query);

            $entity = $this->entityClass::with($relationships)
                ->find($id);

            if (is_null($entity)) {
                return $this->sendNotFound();
            }

            return $this->sendResponse(
                $entity->toArray(),
                Message::SHOW_OK
            );
        } catch (\Illuminate\Database\Eloquent\RelationNotFoundException $ex) {
            // TODO Add log
            // Log::error(Message::RELATION_KO, __METHOD__, new $this->entityClass(), $request, $ex);

            return $this->sendError(Message::RELATION_KO);
        } catch (\Exception $ex) {
            // TODO Add log
            // Log::error(Message::SHOW_KO, __METHOD__, new $this->entityClass(), $request, $ex);

            return $this->sendError(Message::SHOW_KO);
        }
    }

    /**
     * Method to send response
     *
     * @param array $payload
     * @param string $message
     * @param int $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendResponse(
        array $payload,
        string $message = null,
        int $statusCode = 200
    ): JsonResponse {
        $response = [
            'success' => true,
            'data' => $payload,
            'message' => $message
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Method to send response error
     *
     * @param string $error
     * @param array $errorMessages
     * @param int $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendError(
        string $error,
        array $errorMessages = [],
        int $statusCode = 405
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $error
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Method to send not found error
     *
     * @param string $error
     * @param array $errorMessages
     * @param int $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendNotFound(
        string $error = Message::NOT_FOUND,
        array $errorMessages = []
    ): JsonResponse {
        return self::sendError($error, $errorMessages, 404);
    }
}
