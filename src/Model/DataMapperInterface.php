<?php

namespace Model;

interface DataMapperInterface
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
