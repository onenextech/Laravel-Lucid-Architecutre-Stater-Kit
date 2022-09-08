<?php

namespace App\Traits;

use App\Data\Models\File as FileModel;

trait HasAttachable
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getAttachOne()
    {
        return isset($this->attachOne) ? $this->attachOne : [];
    }

    public function getAttachMany()
    {
        return isset($this->attachMany) ? $this->attachMany : [];
    }

    public function attachables()
    {
        return $this->morphMany(FileModel::class, 'attachable');
    }

    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->getAttachOne())) {
            return $this->getAttachable($key);
        } elseif (array_key_exists($key, $this->getAttachMany())) {
            return $this->getAttachables($key);
        }

        return parent::getAttribute($key);
    }

    public function getAttachable($key)
    {
        return $this->attachables()->where('field', $key)->first();
    }

    public function getAttachables($key)
    {
        return $this->attachables()->where('field', $key)->get();
    }

    public function setAttribute($key, $value)
    {
        if (array_key_exists($key, $this->getAttachOne())) {
            return $this->setAttachable($key, $value);
        } elseif (array_key_exists($key, $this->getAttachMany())) {
            return $this->setAttachables($key, $value);
        }
        parent::setAttribute($key, $value);
    }

    public function setAttachable($key, $value)
    {
        $file = $this->getAttachable($key);
        if ($file) {
            $file->delete();
        }
        if ($value) {
            $file = $this->createAttachOne($key, $value);
        }

        return $file;
    }

    public function setAttachables($key, $value)
    {
        if ($value && is_array($value)) {
            return $this->createAttachMany($key, $value);
        } elseif ($value && get_class($value) == FileModel::class) {
            return $this->createAttachMany($key, [$value]);
        }
    }

    public function createAttachMany($key, $files = [])
    {
        $attachables = [];
        foreach ($files as $file) {
            $attachables[] = $this->createAttachOne($key, $file);
        }

        return $attachables;
    }

    public function createAttachOne($key, $file)
    {
        $file->attachable_type = get_class($this);
        $file->attachable_id = $this->id;
        $file->field = $key;
        $file->sort_order = 0;
        $file->save();

        return $file;
    }
}
