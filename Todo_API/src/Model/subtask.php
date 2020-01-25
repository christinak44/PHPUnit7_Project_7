<?php
 namespace APP\Model;
// ---> **~mirrored 'Review Class Model' from Unit 7 CRUD API in Slim Instruction~** <----
 class Subtask
 {
      protected $database;
      public function __construct(\PDO $database)
      {
          $this->database = $database;
      }
      public function getSubtasksByTaskId($task_id)
    {
        if (empty($task_id)) {
            throw new ApiException(ApiException::INFO_REQUIRED);
        }
        $statement = $this->database->prepare('SELECT * FROM subtasks WHERE task_id=:task_id');
        $statement->bindParam('task_id', $task_id);
        $statement->execute();
        $subtasks = $statement->fetchAll();
        if (empty($subtasks)) {
            throw new ApiException(ApiException::NOT_FOUND, 404);
        }
        return $subtasks;
    }
    public function getsubtask($subtask_id)
    {
        if (empty($subtask_id)) {
            throw new ApiException(ApiException::INFO_REQUIRED);
        }
        $statement = $this->database->prepare('SELECT * FROM subtasks WHERE id=:id');
        $statement->bindParam('id', $subtask_id);
        $statement->execute();
        $subtask = $statement->fetch();
        if (empty($subtask)) {
            throw new ApiException(ApiException::NOT_FOUND, 404);
        }
        return $subtask;
    }
    public function createsubtask($data)
    {
        if (empty($data['name']) || empty($data['status']) || empty($data['task_id'])) {
            throw new ApiException(ApiException::INFO_REQUIRED);
        }
        $statement = $this->database->prepare('INSERT INTO subtasks (name, status, task_id) VALUES ( :name, :status, :task_id)');
        $statement->bindParam('name', $data['name']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('task_id', $data['task_id']);
        $statement->execute();
        if ($statement->rowCount()>0) {
            return $this->getSubtask($this->database->lastInsertId());
        } else {
            throw new ApiException(ApiException::CREATION_FAILED);
        }
    }

    /**
     * Update a subtask.
     *
     * @param array $data
     * @param int $subtask_id
     * @return object
     */
    public function updatesubtask($data)
    {
        if (empty($data['subtask_id']) || empty($data['name']) || empty($data['status'])|| empty($data['task_id'])) {
            throw new ApiException(ApiException::INFO_REQUIRED);
        }
        $this->getsubtask($data['subtask_id']);
        $statement = $this->database->prepare('UPDATE subtasks SET name=:name, status=:status, task_id=:task_id WHERE id=:id');
        $statement->bindParam('id', $data['subtask_id']);
        $statement->bindParam('name', $data['name']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('task_id', $data['task_id']);
        $statement->execute();
        if ($statement->rowCount()>0) {
            return $this->getsubtask($data['subtask_id']);
        } else {
            throw new ApiException(ApiException::UPDATE_FAILED);
        }
    }

    /**
     * Delete a subtask.
     *
     * @param int $subtask_id
     * @return string
     */
    public function deletesubtask($subtask_id)
    {
        if (empty($subtask_id)) {
            throw new ApiException(ApiException::INFO_REQUIRED);
        }
        $this->getsubtask($subtask_id);
        $statement = $this->database->prepare('DELETE FROM subtasks WHERE id=:id');
        $statement->bindParam('id', $subtask_id);
        $statement->execute();
        if ($statement->rowCount()>0) {
            return ['message' => 'The subtask was deleted.'];
        } else {
            throw new ApiException(ApiException::DELETE_FAILED);
        }
    }
}
