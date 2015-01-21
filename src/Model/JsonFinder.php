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
        $statusesJson = json_decode(file_get_contents($this->file), FILE_USE_INCLUDE_PATH);
        if (null === $statusesJson) {
            throw new HttpException(404, 'No statuses');
        }
        $status = $this->searchStatusInArray($statusesJson, $id);
        if (null === $status) {
            throw new HttpException(404, 'Status ' . $id . ' not exists');
        }

        return $this->createStatus($status);
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

    private function createStatus($statusArray)
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

    private function searchStatusInArray($array, $id)
    {
        foreach ($array['statuses'] as $status) {
            if ($id == $status['id']) {
                return $status;
            }
        }

        return null;
    }
}
