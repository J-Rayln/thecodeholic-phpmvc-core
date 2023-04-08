<?php

namespace app\core;

use app\core\db\DbModel;

/**
 * Class UserModel.
 * 
 * Abstract class implements common core user methods.
 * 
 * @package app\core
 */
abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
    abstract public function getAttribute(mixed $key): string;
}
