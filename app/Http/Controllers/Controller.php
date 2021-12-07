<?php

namespace App\Http\Controllers;

use App\Core\Message;
use Illuminate\Http\Request;
use App\Core\Utils\ControllerUtils;
use App\Core\Utils\ModelUtils;
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
    protected $model;

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
            if (is_null($this->model)) {
                return $this->sendError(Message::BAD_REQUEST, [], 400);
            }

            $query = $request->query();

            $filters = ControllerUtils::getRequestFilters($query);

            $relationships = ControllerUtils::getRequestRelationships($query);

            $orderBy = ControllerUtils::getRequestOrderBy($query);

            $paginate = ControllerUtils::getPaginate($query);

            $entities = $this->model::with($relationships)
                ->where($filters);

            foreach ($orderBy as $order) {
                $entities->orderBy($order[0], $order[1]);
            }

            $entities = $paginate
                ? $entities->paginate($this->perPage)->withQueryString()->toArray()
                : $entities->get()->toArray();

            return $this->sendResponse($entities, Message::INDEX_OK);
        } catch (\Illuminate\Database\QueryException $ex) {
            // TODO Add log
            // Log::error(Message::FILTER_KO, __METHOD__, new $this->model(), $request, $ex);

            return $this->sendError(Message::FILTER_KO);
        } catch (\Illuminate\Database\Eloquent\RelationNotFoundException $ex) {
            // TODO Add log
            // Log::error(Message::RELATION_KO, __METHOD__, new $this->model(), $request, $ex);

            return $this->sendError(Message::RELATION_KO);
        } catch (\Exception $ex) {
            // TODO Add log
            // Log::error(Message::INDEX_KO, __METHOD__, new $this->model(), $request, $ex);

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
            if (is_null($this->model)) {
                return $this->sendError(Message::BAD_REQUEST, [], 400);
            }

            $query = $request->query();

            $relationships = ControllerUtils::getRequestRelationships($query);

            $entity = $this->model::with($relationships)
                ->find($id);

            if (is_null($entity)) {
                return $this->sendNotFound();
            }

            return $this->sendResponse($entity->toArray(), Message::SHOW_OK);
        } catch (\Illuminate\Database\Eloquent\RelationNotFoundException $ex) {
            // TODO Add log
            // Log::error(Message::RELATION_KO, __METHOD__, new $this->model(), $request, $ex);

            return $this->sendError(Message::RELATION_KO);
        } catch (\Exception $ex) {
            // TODO Add log
            // Log::error(Message::SHOW_KO, __METHOD__, new $this->model(), $request, $ex);

            return $this->sendError(Message::SHOW_KO);
        }
    }

    /**
     * Base method to create a new entity
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            if (is_null($this->model)) {
                return $this->sendError(Message::BAD_REQUEST, [], 400);
            }

            $entityUsesStoreValidation = ModelUtils::usesTrait(
                \App\Traits\StoreValidation::class,
                get_class($this->model())
            );
            if ($entityUsesStoreValidation) {
                $storeValidationRules = $this->model->getStoreValidationRules();
                $validated = $request->validate($storeValidationRules);

                // TODO to be implement
                dd($validated);
            }

            $data = $request->all();

            $entity = new $this->model;
            $entity->fill($data);

            $entityUsesBelongsToUser = ModelUtils::usesTrait(
                \App\Traits\BelongsToUser::class,
                get_class($this->model)
            );
            if ($entityUsesBelongsToUser && !isset($request->user_id)) {
                $entity->fillUser();

                // TODO to be implement
                dd($entity);
            }

            // Saves entity with data
            $entity->save();

            // TODO add log
            // Log::info(Message::CREATE_OK, __METHOD__, $entity, $request);

            return $this->sendResponse($entity->fresh()->toArray(), Message::CREATE_OK, 201);
        } catch (\Exception $ex) {
            // TODO Add log
            // Log::error(Message::CREATE_KO, __METHOD__, new $this->model(), $request, $ex);

            return $this->sendError(Message::CREATE_KO);
        }
    }

    /**
     * Base method to update an entity
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            if (is_null($this->model)) {
                return $this->sendError(Message::BAD_REQUEST, [], 400);
            }

            $data = $request->all();

            $entity = $this->model::find($id);

            if (is_null($entity)) {
                // TODO Add log
                // Log::error(Message::UPDATE_KO, __METHOD__, new $this->model(), $request);

                return $this->sendNotFound();
            }

            $entityUsesUpdateValidation = ModelUtils::usesTrait(
                App\Traits\UpdateValidation::class,
                get_class($this->model)
            );
            if ($entityUsesUpdateValidation) {
                $updateValidationRules = $this->model->getUpdateValidationRules();
                $validated = $request->validate($updateValidationRules);

                // TODO to be implement
                dd($validated);
            }

            $entity->fill($data);
            $entity->save();

            // TODO Add log
            // Log::info(Message::UPDATE_OK, __METHOD__, $entity, $request);

            return $this->sendResponse($entity->fresh()->toArray(), Message::UPDATE_OK);
        } catch (\Exception $ex) {
            // TODO Add log
            // Log::error(Message::UPDATE_KO, __METHOD__, new $this->model(), $request, $ex);

            return $this->sendError(Message::UPDATE_KO);
        }
    }

    /**
     * Base method to delete an entity
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            if (is_null($this->model)) {
                return $this->sendError(Message::BAD_REQUEST, [], 400);
            }

            $entity = $this->model::find($id);

            if (is_null($entity)) {
                return $this->sendNotFound();
            }

            $canDelete = true;
            $relations = ModelUtils::relations($this->model);
            foreach ($relations as $relation) {
                if (!isset($entity->{$relation})) {
                    continue;
                }

                if ($entity->{$relation}->count() > 0) {
                    $canDelete = false;
                }
            }

            if (!$canDelete) {
                // TODO Add log
                // Log::error(Message::DELETE_KO_RELATIONSHIP, __METHOD__, new $this->model(), $request);

                return $this->sendError(Message::DELETE_KO_RELATIONSHIP);
            }

            $entity->delete();

            // TODO Add log
            // Log::info(Message::DELETE_OK, __METHOD__, $entity, $request);

            return $this->sendResponse([], Message::DELETE_OK);
        } catch (\Exception $ex) {
            // TODO Add log
            // Log::error(Message::DELETE_KO, __METHOD__, new $this->model(), $request, $ex);

            return $this->sendError(Message::DELETE_KO);
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
