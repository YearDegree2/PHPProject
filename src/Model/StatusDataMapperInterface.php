<?php

namespace Model;

interface StatusDataMapperInterface
{
    /**
     * Save a status.
     *
     * @param  Status $status
     * @return mixed  null|boolean
     */
    public function persist(Status $status);

    /**
     * Delete a status.
     * @param Status $status
     */
    public function remove(Status $status);
}
