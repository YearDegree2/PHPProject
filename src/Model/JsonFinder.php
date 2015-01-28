<?php

namespace Model;

use Exception\HttpException;

class JsonFinder implements FinderInterface
{

    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function findAll()
    {
        $statusesJson = json_decode(file_get_contents($this->file), FILE_USE_INCLUDE_PATH);
        if (null === $statusesJson) {
            throw new HttpException(404, 'No statuses');
        }
        $statuses = array();
        foreach ($statusesJson['statuses'] as $status) {
            array_push($statuses, $this->createStatus($status));
        }

        return $statuses;
    }

    public function findOneById($id)
    {
        $statuses = self::findAll();

        return $this->searchStatusInSimpleArray($statuses, $id);
    }

    public function addStatus(Status $status)
    {
        $statusesJson = json_decode(file_get_contents($this->file), FILE_USE_INCLUDE_PATH);
        if (null !== $this->searchStatusInArray($statusesJson, $status->getId())) {
            throw new HttpException(404, 'Status ' . $status->getId() . ' already exists');
        }
        array_push($statusesJson['statuses'], $this->createStatusArray($status));
        file_put_contents($this->file, json_encode($statusesJson));
    }

    public function deleteStatus(Status $status)
    {
        $arrayStatuses = json_decode(file_get_contents($this->file), FILE_USE_INCLUDE_PATH);
        foreach ($arrayStatuses['statuses'] as $key => $statusInArray) {
            if ($statusInArray['id'] == $status->getId()) {
                unset($arrayStatuses['statuses'][$key]);
                file_put_contents($this->file, json_encode($arrayStatuses));
            }
        }
    }

    private function createStatus(array $statusArray)
    {
        return new Status(
            $statusArray['message'],
            $statusArray['id'],
            $statusArray['username'],
            new \DateTime($statusArray['date']),
            $statusArray['clientUsed']
        );
    }

    private function createStatusArray(Status $status)
    {
        return array(
            'message' => $status->getMessage(),
            'id' => $status->getId(),
            'username' => $status->getUsername(),
            'date' => $status->getDate(),
            'clientUsed' => $status->getClientUsed(),
        );
    }

    private function searchStatusInArray(array $array, $id)
    {
        foreach ($array['statuses'] as $status) {
            if ($id == $status['id']) {
                return $status;
            }
        }

        return null;
    }

    private function searchStatusInSimpleArray(array $statuses, $id)
    {
        foreach ($statuses as $status) {
            if ($id == $status->getId()) {
                return $status;
            }
        }

        return null;
    }

    public function findNextStatusId()
    {
        $arrayStatuses = self::findAll();

        return (end($arrayStatuses) !== false) ? end($arrayStatuses)->getId() + 1 : 0;
    }
}
