<?php

namespace Core\Interfaces;

interface DatabaseModelInterface {

    public function insertQuery($query);

    public function getRowQuery($query);
}