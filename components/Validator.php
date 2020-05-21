<?php

namespace components;

class Validator
{
    protected array $data;

    protected array $messages;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->messages = [];
    }

    public function validate(): bool
    {
        foreach ($this->data as $key => $value) {
            if (! $this->{$key}($value)) {
                $this->messages[$key] = "Attribute $key is invalid";
            }
        }

        if (! $this->messages) {
            return true;
        }

        Session::flash('messages', $this->messages);

        return false;
    }

    public function email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}