<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    $endpoints = [
        'all tasks' => $this->api['api_url'].'/todos',
        'single task' => $this->api['api_url'].'/todos/{task_id}',
        'subtasks by task' => '/todos/{task_id}/subtasks', //<---- subtask placeholder
        'single subtask' => '/todos/{task_id}/subtasks/{subtask_id}', //<---- subtask placeholder
        'help' => $this->api['base_url'].'/',
    ];
    $result = [
       'endpoints' => $endpoints,
       'version' => $this->api['version'],
       'timestamp' => time(),
    ];
    return $response->withJson($result, 200, JSON_PRETTY_PRINT);
});
$app->group('/api/v1/todos', function() use($app) {
  // List Tasks
    $app->get('', function ($request, $response, $args) {
        $result = $this->task->getTasks();
        return $response->withJson($result, 200, JSON_PRETTY_PRINT);
    });
      // Get 1 Task- pulls single task by ID
    $app->get('/{task_id}', function ($request, $response, $args) {
        $result = $this->task->getTask($args['task_id']);
        return $response->withJson($result, 200, JSON_PRETTY_PRINT);
    });
    // subtask routes
    $app->group('/{task_id}/subtasks', function () use ($app) {
        $app->get('', function ($request, $response, $args) {
            $result = $this->subtask->getSubtasksByTaskId($args['task_id']);
            return $response->withJson($result, 200, JSON_PRETTY_PRINT);
        });
        $app->get('/{subtask_id}', function ($request, $response, $args) {
            $result = $this->subtask->getSubtask($args['subtask_id']);
            return $response->withJson($result, 200, JSON_PRETTY_PRINT);
        });
        $app->post('', function ($request, $response, $args) {
            $data = $request->getParsedBody();
            $data['task_id'] = $args['task_id'];
            $result = $this->subtask->createSubtask($data);
            return $response->withJson($result, 201, JSON_PRETTY_PRINT);
        });
        $app->put('/{subtask_id}', function ($request, $response, $args) {
            $data = $request->getParsedBody();
            $data['task_id'] = $args['task_id'];
            $data['subtask_id'] = $args['subtask_id'];
            $result = $this->subtask->updateSubtask($data);
            return $response->withJson($result, 201, JSON_PRETTY_PRINT);
        });
        $app->delete('/{subtask_id}', function ($request, $response, $args) {
            $result = $this->subtask->deleteSubtask($args['subtask_id']);
            return $response->withJson($result, 200, JSON_PRETTY_PRINT);
        });
    });
      // Create Task
    $app->post('', function ($request, $response, $args) {
        $result = $this->task->createTask($request->getParsedBody());
        return $response->withJson($result, 201, JSON_PRETTY_PRINT);
    });
      // Update Task
    $app->put('/{task_id}', function ($request, $response, $args) {
        $data = $request->getParsedBody();
        $data['task_id'] = $args['task_id'];
        $result = $this->task->updateTask($data);
        return $response->withJson($result, 201, JSON_PRETTY_PRINT);
    });
      // Delete Task
    $app->delete('/{task_id}', function ($request, $response, $args) {
        $result = $this->task->deleteTask($args['task_id']);
        return $response->withJson($result, 200, JSON_PRETTY_PRINT);
    });
});
